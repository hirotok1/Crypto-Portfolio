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
        $apiKey=config('services.coinmarketcap.api_key');
        $user = Auth::user();
        // コインの価格データを取得
        $client = new Client();
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $currency = session('currency', 'JPY'); // デフォルト通貨は JPY
        $response = $client->get($url, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $apiKey,
            ],
            'query' => [
                'start' => 1,
                'limit' => 1000,
                'convert' => $currency,
            ],
        ]);
        // レスポンスをJSONとして取得し、配列に変換
        $marketData = json_decode($response->getBody()->getContents(), true);
        //dd($marketData);
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
        // JPYのデータを追加
        $coinData['JPY'] = [
            'price' => 1,
            'percent_change_1h' => 0,
            'percent_change_24h' => 0,
        ];
        
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
        // 各コインが存在する場所を準備
        $coinPlaces = $changes->groupBy('coin')->map(function ($group) {
            return $group->pluck('place')->unique();
        });
        // 総資産を計算
        $totalAssets = $coinBalance->reduce(function ($carry, $balance, $coin) use ($coinData) {
            $price = $coinData[$coin]['price'] ?? 0;
            return $carry + ($price * $balance);
        }, 0);
        // 円グラフに渡すデータを用意
        $chartData = [];
        foreach ($coinBalance as $coin => $balance) {
            $totalValue = $balance * ($coinData[$coin]['price'] ?? 0);
            $chartData[] = [
                'label' => $coin,
                'value' => $totalValue,
            ];
        }
        //dd($apiKey);//きてる
        //dd($marketData);//きてる
        // ロゴ表示
        // ロゴ情報を取得するための通貨IDのリストを作成。BTC:1,LTC:2,etc.時価総額順位とは異なる！のでわざわざidリスト作んないといけない。
        $coinIdMap = collect($marketData['data'])->mapWithKeys(function ($item) {
            return [$item['symbol'] => $item['id']];
        });
        //dd($coinIdMap);
        $ids = $coinBalance->keys()->map(function ($coin) use ($coinIdMap) {
            return $coinIdMap[$coin] ?? null;
        })->filter()->implode(',');
        //dd($ids);//きてる
        // ロゴ取得のためのリクエスト
        if (empty($ids)) {
            $logos=[];
        } else {
            $client = new Client();
            $infoUrl = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/info';
            $infoResponse = $client->get($infoUrl, [
                'headers' => [
                    'X-CMC_PRO_API_KEY' => $apiKey,
                ],
                'query' => ['id' => $ids],
            ]);
            //dd($apiKey);
            //dd($infoResponse->getStatusCode());
            //dd($infoResponse);//ない
            $logoData = json_decode($infoResponse->getBody()->getContents(), true);
            //dd($logoData);//ない
            $logos = collect($logoData['data'])->mapWithKeys(function ($item) {
                return [$item['id'] => ['logo' => $item['logo']]];
            });
        }
        //dd($logos);
        // ビューにデータを渡す
        return view('portfolio', [
            'coinData' => $coinData,
            'changes' => $changes,
            'coinBalance' => $coinBalance,
            'placeBalance' => $placeBalance,
            'coinPlaces' => $coinPlaces,
            'totalAssets' => $totalAssets,
            'chartData' => $chartData,
            'logos' => $logos,
            'data' => $marketData,
            'coinIdMap' => $coinIdMap,
        ]);
    
    }
}
