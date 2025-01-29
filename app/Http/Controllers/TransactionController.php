<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\Swap;
use App\Models\Send;
use App\Models\Deposit;
use App\Models\Change;
use App\Http\Requests\SendRequest;
use App\Http\Requests\DepositRequest;

class TransactionController extends Controller
{
    public function index()
    {
        // 現在のユーザーを取得
        $user = Auth::user();
        // Swaps テーブルのデータを取得
        $swaps = DB::table('swaps')->where('user_id', $user->id)->orderBy('customtime', 'desc')->get();
        // Sends テーブルのデータを取得
        $sends = DB::table('sends')->where('user_id', $user->id)->orderBy('customtime', 'desc')->get();        
        // Deposits テーブルのデータを取得
        $deposits = DB::table('deposits')->where('user_id', $user->id)->orderBy('customtime', 'desc')->get();
        // ユーザーの全changesを取得
        $changes = DB::table('changes')->where('user_id', $user->id)->get();
        /*コインのロゴいらないからコメントアウト
        // changesが空の場合、logosを空にして処理をスキップ
        if ($changes->isEmpty()) {
            $logos = collect();
        } else {
            // CoinMarketCap API クライアント
            $client = new Client();
            // スワップに登場する全てのコイン名を取得
            //$coinSymbols = $swaps->pluck('coina')->merge($swaps->pluck('coinb'))->unique();
            // changesに登場する全てのコイン名を取得
            $coinSymbols = $changes->where('related_type', 'swaps')->pluck('coin')
                ->merge($changes->where('related_type', 'sends')->pluck('coin'))
                ->merge($changes->where('related_type', 'deposits')->pluck('coin'))
                ->unique();
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
            // ここで $coinIds が空の場合、API にリクエストしないようにする
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
        }
        */
        return view('transaction.index', [
            'swaps' => $swaps,
            'sends' => $sends,
            'deposits' => $deposits,
            //'logos' => $logos,
        ]);
    }

    public function create()
    {
        // 現在のユーザーを取得
        $user = Auth::user();
        // ユーザーの全changesを取得
        $changes = DB::table('changes')->where('user_id', $user->id)->get();
        // ユーザーの全changesのうち場所を取得し、重複を削除
        $places = DB::table('changes')->where('user_id', $user->id)->pluck('place')->unique();
        // ユーザーの全changesのうちcoinを取得し、重複を削除
        $coins = DB::table('changes')->where('user_id', $user->id)->pluck('coin')->unique();
       
        // ビューにデータを渡す
        return view('transaction.create', compact('places', 'coins'));
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
        // placeの値が「other」の場合、place_otherの値を使用
        $place = $request->input('place') === 'other' ? $request->input('place_other') : $request->input('place');

        //swapsテーブルにスワップデータを保存
        DB::table('swaps')->insert([
            'user_id' => auth()->id(), // 現在ログイン中のユーザーID
            'place' => $place, // 取引所
            'coina' => $coina, // スワップ元コイン
            'amounta' => $validated['amounta'], // スワップ元の数量
            'coinb' => $coinb, // スワップ先コイン
            'amountb' => $validated['amountb'], // スワップ先の数量
            'customfeecoin' => $validated['customfeecoin'] ?? null, // 手数料コイン（任意）
            'customfee' => $validated['customfee'] ?? 0, // 手数料（デフォルトは0）
            'customtime' => $validated['customtime'], // 日時
            'memo' => $validated['memo'] ?? '', // メモ（任意）
            'created_at' => now(), // 作成日時
            'updated_at' => now(), // 更新日時
        ]);

        //changesテーブルに残高変化を記録
        // スワップのIDを取得(最後に挿入されたID)
        $relatedId = DB::getPdo()->lastInsertId();   
        // スワップ元の残高変化を記録
        DB::table('changes')->insert([
            'user_id' => auth()->id(),
            'place' => $place,
            'coin' => $coina,
            'change' => -$validated['amounta'],
            'related_type' => 'swaps',
            'related_id' => $relatedId,
            'customtime' => $validated['customtime'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // スワップ先の残高変化を記録
        DB::table('changes')->insert([
            'user_id' => auth()->id(),
            'place' => $place,
            'coin' => $coinb,
            'change' => $validated['amountb'],
            'related_type' => 'swaps',
            'related_id' => $relatedId,
            'customtime' => $validated['customtime'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 成功メッセージを表示してリダイレクト
        return redirect()->route('transaction.create')->with('success', 'スワップが記録されました！');
    }

    // 送金データの保存
    public function storeSend(SendRequest $request)
    {
        // placeaの値が「other」の場合、placea_otherの値を使用
        $placea = $request->input('placea') === 'other' ? $request->input('placea_other') : $request->input('placea');
        // placebの値が「other」の場合、placeb_otherの値を使用
        $placeb = $request->input('placeb') === 'other' ? $request->input('placeb_other') : $request->input('placeb');
        // coinの値が「other」の場合、coin_otherの値を使用
        $coin = $request->input('coin') === 'other' ? $request->input('coin_other') : $request->input('coin');

        DB::table('sends')->insert([
            'user_id' => auth()->id(),
            'coin' => $coin,
            'placea' => $placea,
            'amounta' => $request['amounta'],
            'placeb' => $placeb,
            'amountb' => $request['amountb'],
            'customfeecoin' => $request['customfeecoin'] ?? null,
            'customfee' => $request['customfee'] ?? 0,
            'customtime' => $request['customtime'],
            'memo' => $request['memo'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 送金のIDを取得(最後に挿入されたID)
        $relatedId = DB::getPdo()->lastInsertId();
        // 送金元の残高変化を記録
        DB::table('changes')->insert([
            'user_id' => auth()->id(),
            'place' => $placea,
            'coin' => $coin,
            'change' => -$request['amounta'],
            'related_type' => 'sends',
            'related_id' => $relatedId,
            'customtime' => $request['customtime'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // 送金先の残高変化を記録
        DB::table('changes')->insert([
            'user_id' => auth()->id(),
            'place' => $placeb,
            'coin' => $coin,
            'change' => $request['amountb'],
            'related_type' => 'sends',
            'related_id' => $relatedId,
            'customtime' => $request['customtime'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('transaction.create')->with('success', '送金が記録されました！');
    }

    // 振込データの保存
    public function storeDeposit(DepositRequest $request)
    {
        //dd($request);
        // coinの値が「other」の場合、coin_otherの値を使用
        $coin = $request->input('coin') === 'other' ? $request->input('coin_other') : $request->input('coin');
        // placeの値が「other」の場合、place_otherの値を使用
        $place = $request->input('place') === 'other' ? $request->input('place_other') : $request->input('place');

        DB::table('deposits')->insert([
            'user_id' => auth()->id(),
            'coin' => $coin,
            'place' => $place,
            'amount' => $request['amount'],
            'customtime' => $request['customtime'],
            'memo' => $request['memo'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 振込のIDを取得(最後に挿入されたID)
        $relatedId = DB::getPdo()->lastInsertId();
        // 振込先の残高変化を記録
        DB::table('changes')->insert([
            'user_id' => auth()->id(),
            'place' => $place,
            'coin' => $coin,
            'change' => $request['amount'],
            'related_type' => 'deposits',
            'related_id' => $relatedId,
            'customtime' => $request['customtime'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('transaction.create')->with('success', '振込が記録されました！');
    }

    // スワップデータの削除
    public function deleteSwap(Swap $swap)
    {
        // 関連するswapを削除
        DB::table('swaps')->where('id', $swap->id)->delete();
        // 関連するchangesを削除
        DB::table('changes')->where('related_type', 'swaps')->where('related_id', $swap->id)->delete();
        
        return redirect()->route('transaction.index')->with('success', 'スワップが削除されました！');
    }
    // 送金データの削除
    public function deleteSend(Send $send)
    {
        // 関連するsendを削除
        DB::table('sends')->where('id', $send->id)->delete();
        // 関連するchangesを削除
        DB::table('changes')->where('related_type', 'sends')->where('related_id', $send->id)->delete();
        
        return redirect()->route('transaction.index')->with('success', '送金が削除されました！');
    }
    // 振込データの削除
    public function deleteDeposit(Deposit $deposit)
    {
        // 関連するdepositを削除
        DB::table('deposits')->where('id', $deposit->id)->delete();
        // 関連するchangesを削除
        DB::table('changes')->where('related_type', 'deposits')->where('related_id', $deposit->id)->delete();
        
        return redirect()->route('transaction.index')->with('success', '振込が削除されました！');
    }
  
}