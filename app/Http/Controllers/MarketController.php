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
        $params = [
            'start' => 1,
            'limit' => 100,
            'convert' => $currency, // 選択した通貨を使用
        ];
        $response = $client->get($url, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api_key'),
            ],
            'query' => $params,
        ]);
        // レスポンスをJSONとして取得し、配列に変換
        $marketData = json_decode($response->getBody()->getContents(), true);
        
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
            'data' => $marketData,
            'logos' => collect($logoData['data']),
        ]);
    }
  
}
