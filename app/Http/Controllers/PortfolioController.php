<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\Portfolio;

class PortfolioController extends Controller
{
    public function index()
    { 
        $user = Auth::user();
        // portfolios テーブルのデータを取得
        $portfolios = Portfolio::where('user_id', $user->id)->get();
        
        // コインの価格データを取得
        $client = new Client();
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';

        $currency = session('currency', 'USD'); // デフォルト通貨は USD
        $response = $client->get($url, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api_key'),
            ],
            'query' => [
                'start' => 1,
                'limit' => 1000,
                'convert' => $currency,
            ],
        ]);

        $marketData = json_decode($response->getBody()->getContents(), true);
        // 必要なデータ（価格と変動率）を収集
        $coinData = collect($marketData['data'])->mapWithKeys(function ($item) use ($currency) {
            return [
                $item['symbol'] => [
                    'price' => $item['quote'][$currency]['price'],
                    'percent_change_1h' => $item['quote'][$currency]['percent_change_1h'],
                    'percent_change_24h' => $item['quote'][$currency]['percent_change_24h'],
                ]
            ];
        });

        // ユーザーの全changesを取得
        $changes = DB::table('changes')->where('user_id', $user->id)->get();
        // ユーザーの全changesのうちコインごとに集計
        $coinBalance = $changes->groupBy('coin')->map(function ($group) {
            return $group->sum('change');
        });
        // ユーザーの全changesのうち各場所の全コインを集計
        $placeBalance = $changes->groupBy('place')->map(function ($group) {
            return $group->groupBy('coin')->map(function ($group) {
                return $group->sum('change');
            });
        });
        // ビューにデータを渡す
        return view('portfolio', [
            'portfolios' => $portfolios,
            'coinData' => $coinData,
            'coinBalance' => $coinBalance,
            'placeBalance' => $placeBalance,
        ]);
    
    }
}
