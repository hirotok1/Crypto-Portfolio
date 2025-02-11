<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MarketController extends Controller
{
    public function index()
    {
        $client = new Client();
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $currency = session('currency', 'JPY'); // デフォルトは JPY
        $response = $client->get($url, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api_key'),
            ],
            'query' => [
                'start' => 1,
                'limit' => 200,
                'convert' => $currency, // 選択した通貨を使用
            ],
        ]);
        // レスポンスをJSONとして取得し、配列に変換
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

        // ロゴ情報を取得するための通貨IDのリストを作成。BTC:1,LTC:2,etc.時価総額順位とは異なる！のでわざわざidリスト作んないといけない。
        $ids = collect($marketData['data'])->pluck('id')->implode(',');
        // ロゴ取得のためのリクエスト
        $infoUrl = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/info';
        $infoResponse = $client->get($infoUrl, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api_key'),
            ],
            'query' => ['id' => $ids],
        ]);
        //dd(config('services.coinmarketcap.api_key'));//"245b4ca3-0551-4981-9e6d-351454194f4b"
        $logoData = json_decode($infoResponse->getBody()->getContents(), true);
        // ロゴデータをビューに渡す
        return view('market', [
            'coinData' => $coinData,
            'data' => $marketData,
            'logos' => collect($logoData['data']),
        ]);
    }
  
}
