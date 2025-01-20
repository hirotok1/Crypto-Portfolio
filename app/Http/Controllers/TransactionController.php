<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\Swap;

class TransactionController extends Controller
{
    public function index()
    {
        // 現在のユーザーを取得
        $user = Auth::user();
        
        // Swaps テーブルのデータを取得
        $swaps = DB::table('swaps')->where('user_id', $user->id)->get();

        // CoinMarketCap API クライアント
        $client = new Client();

        // スワップに登場する全てのコイン名を取得
        $coinSymbols = $swaps->pluck('coina')->merge($swaps->pluck('coinb'))->unique();

        // CoinMarketCap API からコインIDを取得
        $mapUrl = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/map';
        $mapResponse = $client->get($mapUrl, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api_key'),
            ],
        ]);

        $mapData = json_decode($mapResponse->getBody()->getContents(), true);

        // CoinMarketCapのデータからIDを抽出
        $coinIds = collect($mapData['data'])
            ->whereIn('symbol', $coinSymbols)
            ->pluck('id')
            ->implode(',');

        // コイン情報の取得 (ロゴ取得)
        $infoUrl = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/info';
        $infoResponse = $client->get($infoUrl, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api_key'),
            ],
            'query' => ['id' => $coinIds],
        ]);

        $logoData = json_decode($infoResponse->getBody()->getContents(), true);

        // ロゴをキー:ID、値:ロゴURLの形に変換
        $logos = collect($logoData['data'])->mapWithKeys(function ($item) {
            return [$item['symbol'] => $item['logo']];
        });

        return view('transaction.index', [
            'swaps' => $swaps,
            'logos' => $logos,
        ]);
    }

    public function create()
    {
        // 現在のユーザーを取得
        $user = Auth::user();

        // 場所を取得
        $places = DB::table('places')->where('user_id', $user->id)->pluck('place');

        // ポートフォリオのコインを取得
        $portfolioCoins = DB::table('portfolios')->where('user_id', $user->id)->pluck('coin');
       
        // ビューにデータを渡す
        return view('transaction.create', compact('places', 'portfolioCoins'));
    }

    // スワップデータの保存
    public function storeSwap(Request $request)
    {
        $validated = $request->validate([
            'place' => 'required|string|max:255',
            'coina' => 'required|string|max:255', // スワップ元コイン
            'amounta' => 'required|numeric|min:0', // スワップ元の数量
            'coinb' => 'required|string|max:255', // スワップ先コイン
            'amountb' => 'required|numeric|min:0', // スワップ先の数量
            'customfeecoin' => 'nullable|string|max:255', // 手数料コイン
            'customfee' => 'nullable|numeric|min:0', // 手数料の値
            'customtime' => 'required|date', // 日時
            'memo' => 'nullable|string|max:255', // メモ
        ]);
        
        // coinaの値が「other」の場合、coina_otherの値を使用
        $coina = $request->input('coina') === 'other' ? $request->input('coina_other') : $request->input('coina');
         // coinbの値が「other」の場合、coinb_otherの値を使用
        $coinb = $request->input('coinb') === 'other' ? $request->input('coinb_other') : $request->input('coinb');

        DB::table('swaps')->insert([
            'user_id' => auth()->id(), // 現在ログイン中のユーザーID
            'place' => $validated['place'], // 取引所
            'coina' => $coina, // スワップ元コイン
            'amounta' => $validated['amounta'], // スワップ元の数量
            'coinb' => $validated['coinb'], // スワップ先コイン
            'amountb' => $validated['amountb'], // スワップ先の数量
            'customfeecoin' => $validated['customfeecoin'] ?? null, // 手数料コイン（任意）
            'customfee' => $validated['customfee'] ?? 0, // 手数料（デフォルトは0）
            'customtime' => $validated['customtime'], // 日時
            'memo' => $validated['memo'] ?? '', // メモ（任意）
            'created_at' => now(), // 作成日時
            'updated_at' => now(), // 更新日時
        ]);
        // 成功メッセージを表示してリダイレクト
        return redirect()->route('transaction.create')->with('success', 'スワップが記録されました！');
    }

    // 送金データの保存
    public function storeSend(Request $request)
    {
        $validated = $request->validate([
            'coin' => 'required|string',
            'placea' => 'required|string',
            'amounta' => 'required|numeric',
            'placeb' => 'required|string',
            'amountb' => 'required|numeric',
            'customfeecoin' => 'nullable|string',
            'customfee' => 'nullable|numeric',
            'customtime' => 'required|date',
            'memo' => 'nullable|string',
        ]);
         // coinaの値が「other」の場合、coina_otherの値を使用
        $coina = $request->input('coina') === 'other' ? $request->input('coina_other') : $request->input('coina');

        DB::table('sends')->insert([
            'user_id' => auth()->id(),
            'coin' => $validated['coin'],
            'placea' => $validated['placea'],
            'amounta' => $validated['amounta'],
            'placeb' => $validated['placeb'],
            'amountb' => $validated['amountb'],
            'customfeecoin' => $validated['customfeecoin'] ?? null,
            'customfee' => $validated['customfee'] ?? 0,
            'customtime' => $validated['customtime'],
            'memo' => $validated['memo'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('transaction.create')->with('success', '送金が記録されました！');
    }

   
}
