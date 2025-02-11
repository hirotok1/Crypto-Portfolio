<x-app-layout>
    <!-- ヘッダーのスタイルを非表示にする -->
    <style>
        header.bg-white.shadow {
            display: none; /* ヘッダー全体を非表示 */
        }
    </style>

    <!-- フラッシュメッセージ -->
    @if (session('success'))
        <div class="mb-4 text-green-600 bg-green-100 border border-green-500 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 text-red-600 bg-red-100 border border-red-500 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 bg-white border-b border-gray-200">
                    <!-- タブ -->
                    <div class="flex justify-between mb-4">
                        <button id="swap-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-l-lg bg-gray-100">スワップ</button>
                        <button id="send-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-none bg-gray-200">送金</button>
                        <button id="deposit-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-r-lg bg-gray-200">振込 / 引出</button>
                    </div>
                    <!-- スワップテーブル -->
                    <div id="swap-table" class="block">
                        <table>
                            <thead>
                                <tr>
                                    <th class="w-1/5 px-4 py-2">日時</th>
                                    <th class="w-1/5 px-4 py-2">取引所</th>
                                    <th class="w-1/5 px-4 py-2">スワップ</th>
                                    <th class="w-1/5 px-4 py-2">手数料</th>
                                    <th class="w-1/5 px-4 py-2">メモ</th>
                                    <th class="w-1/5 px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($swaps as $index => $swap)
                                    <tr>
                                        <td class="w-1/5 px-4 py-2">{{ $swap->customtime }}</td>
                                        <td class="w-1/5 px-4 py-2">{{ $swap->place }}</td>
                                        <td class="w-1/5 px-4 py-2">
                                            {{ rtrim(rtrim(number_format($swap->amounta, 8), '0'), '.') }}
                                            {{ $swap->coina }}
                                            →
                                            {{ rtrim(rtrim(number_format($swap->amountb, 8), '0'), '.') }}
                                            {{ $swap->coinb }}
                                        </td>
                                        <td class="w-1/5 px-4 py-2">{{ rtrim(rtrim(number_format($swap->customfee, 8), '0'), '.') }}{{ $swap->customfeecoin }}</td>
                                        <td class="w-1/5 px-4 py-2 break-words">{{ $swap->memo }}</td>
                                        <td class="w-1/5 px-4 py-2">
                                            <!-- 編集ボタン -->
                                            <button type="button" onclick="showEditSwapModal(
                                                '{{ $swap->id }}',
                                                '{{ $swap->place }}',
                                                '{{ $swap->coina }}',
                                                '{{ $swap->amounta }}',
                                                '{{ $swap->coinb }}',
                                                '{{ $swap->amountb }}',
                                                '{{ $swap->customfeecoin }}',
                                                '{{ $swap->customfee }}',
                                                '{{ $swap->customtime }}',
                                                '{{ $swap->memo }}',
                                            )">
                                                <svg class="h-5 w-5 text-zinc-400"  viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />  <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />  <line x1="16" y1="5" x2="19" y2="8" />
                                                </svg> 
                                            </button>
                                        </td>
                                        <td class="w-1/5 px-4 py-2">
                                            <button type="button" class="text-red-600" onclick="showDeleteSwapModal({{ $swap->id }}, '{{ $swap->customtime }}', '{{ $swap->place }}', '{{ rtrim(rtrim(number_format($swap->amounta, 8), '0'), '.') }}', '{{ $swap->coina }}', '{{ rtrim(rtrim(number_format($swap->amountb, 8), '0'), '.') }}', '{{ $swap->coinb }}')">
                                                <svg class="h-5 w-5 text-zinc-400"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="4" y1="7" x2="20" y2="7" />  <line x1="10" y1="11" x2="10" y2="17" />  <line x1="14" y1="11" x2="14" y2="17" />  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- 送金テーブル -->
                    <div id="send-table" class="hidden">                        
                        <table>
                            <thead>
                                <tr>
                                    <th class="w-1/5 px-4 py-2">日時</th>
                                    <th class="w-1/5 px-4 py-2">コイン</th>
                                    <th class="w-1/5 px-4 py-2">送金元</th><!--送金元場所と枚数-->
                                    <th class="w-1/5 px-4 py-2">送金先</th><!--送金先場所と枚数-->
                                    <th class="w-1/5 px-4 py-2">その他手数料</th>
                                    <th class="w-1/5 px-4 py-2">メモ</th>
                                    <th class="w-1/5 px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sends as $send)
                                    <tr>
                                        <td class="w-1/5 px-4 py-2">{{ $send->customtime }}</td>
                                        <td class="w-1/5 px-4 py-2">{{ $send->coin }}</td>
                                        <td class="w-1/5 px-4 py-2">
                                            {{ $send->placea }}
                                            {{ rtrim(rtrim(number_format($send->amounta, 8), '0'), '.') }}{{ $send->coin }}
                                        </td>
                                        <td class="w-1/5 px-4 py-2">
                                            {{ $send->placeb }}
                                            {{ rtrim(rtrim(number_format($send->amountb, 8), '0'), '.') }}{{ $send->coin }}
                                        </td>
                                        <td class="w-1/5 px-4 py-2">{{ rtrim(rtrim(number_format($send->customfee, 8), '0'), '.') }}{{ $send->customfeecoin }}
                                        </td>
                                        <td class="w-1/5 px-4 py-2 break-words">{{ $send->memo }}</td>
                                        <td class="w-1/5 px-4 py-2">
                                            <!-- 編集ボタン -->
                                            <button type="button" onclick="showEditSendModal(
                                                '{{ $send->id }}',
                                                '{{ $send->coin }}',
                                                '{{ $send->placea }}',
                                                '{{ $send->amounta }}',
                                                '{{ $send->placeb }}',
                                                '{{ $send->amountb }}',
                                                '{{ $send->customfeecoin }}',
                                                '{{ $send->customfee }}',
                                                '{{ $send->customtime }}',
                                                '{{ $send->memo }}',
                                            )">
                                                <svg class="h-5 w-5 text-zinc-400"  viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />  <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />  <line x1="16" y1="5" x2="19" y2="8" />
                                                </svg> 
                                            </button>
                                        </td>
                                        <td class="w-1/5 px-4 py-2">
                                            <button type="button" class="text-red-600" onclick="showDeleteSendModal({{ $send->id }}, '{{ $send->customtime }}', '{{ $send->placea }}', '{{ $send->placeb }}', '{{ rtrim(rtrim(number_format($send->amounta, 8), '0'), '.') }}', '{{ rtrim(rtrim(number_format($send->amountb, 8), '0'), '.') }}', '{{ $send->coin }}')">
                                               <svg class="h-5 w-5 text-zinc-400"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="4" y1="7" x2="20" y2="7" />  <line x1="10" y1="11" x2="10" y2="17" />  <line x1="14" y1="11" x2="14" y2="17" />  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- 振込テーブル -->
                    <div id="deposit-table" class="hidden">
                        <table>
                            <thead>
                                <tr>
                                    <th class="w-1/5 px-4 py-2">日時</th>
                                    <th class="w-1/5 px-4 py-2">場所</th>
                                    <th class="w-1/5 px-4 py-2">コイン</th>
                                    <th class="w-1/5 px-4 py-2">メモ</th>
                                    <th class="w-1/5 px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deposits as $deposit)
                                    <tr>
                                        <td class="w-1/5 px-4 py-2">{{ $deposit->customtime }}</td>
                                        <td class="w-1/5 px-4 py-2">{{ $deposit->place }}</td>
                                        <td class="w-1/5 px-4 py-2">{{ rtrim(rtrim(number_format($deposit->amount, 8), '0'), '.') }}{{ $deposit->coin }}</td>
                                        <td class="w-1/5 px-4 py-2 break-words">{{ $deposit->memo }}</td>
                                        <td class="w-1/5 px-4 py-2">
                                            <!-- 編集ボタン -->
                                            <button type="button" onclick="showEditDepositModal(
                                                '{{ $deposit->id }}',
                                                '{{ $deposit->coin }}',
                                                '{{ $deposit->place }}',
                                                '{{ $deposit->amount }}',                                                
                                                '{{ $deposit->customtime }}',
                                                '{{ $deposit->memo }}',
                                            )">
                                                <svg class="h-5 w-5 text-zinc-400"  viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />  <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />  <line x1="16" y1="5" x2="19" y2="8" />
                                                </svg> 
                                            </button>
                                        </td>
                                        <td class="w-1/5 px-4 py-2">
                                            <button type="button" class="text-red-600" onclick="showDeleteDepositModal({{ $deposit->id }}, '{{ $deposit->customtime }}', '{{ $deposit->place }}', '{{ rtrim(rtrim(number_format($deposit->amount, 8), '0'), '.') }}', '{{ $deposit->coin }}')">
                                                <svg class="h-5 w-5 text-zinc-400"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="4" y1="7" x2="20" y2="7" />  <line x1="10" y1="11" x2="10" y2="17" />  <line x1="14" y1="11" x2="14" y2="17" />  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- スワップ削除確認モーダル -->
    <div id="delete-swap-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white w-1/3 mx-auto mt-24 p-4 rounded-lg border border-gray-300">
            <p class="text-center font-bold">本当に削除しますか？</p>
        
            <!--削除する送金の情報を表示-->
            <p id="swap-info" class="text-center"></p>
            <div class="flex justify-center mt-4">
                <button id="cancel-delete-swap-button" class="bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400 transition" onclick="hideDeleteSwapModal()">キャンセル</button>
                <form id="delete-swap-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                        削除
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- 送金削除確認モーダル -->
    <div id="delete-send-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white w-1/3 mx-auto mt-24 p-4 rounded-lg border border-gray-300">
            <p class="text-center font-bold">本当に削除しますか？</p>
            <!--削除する送金の情報を表示-->
            <p id="send-info" class="text-center"></p>
            <div class="flex justify-center mt-4">
                <button id="cancel-delete-send-button" class="bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400 transition" onclick="hideDeleteSendModal()">キャンセル</button>
                <form id="delete-send-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                        削除
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- 振込削除確認モーダル -->
    <div id="delete-deposit-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white w-1/3 mx-auto mt-24 p-4 rounded-lg border border-gray-300">
            <p class="text-center font-bold">本当に削除しますか？</p>
            <!--削除する送金の情報を表示-->
            <p id="deposit-info" class="text-center"></p>
            <div class="flex justify-center mt-4">
                <button id="cancel-delete-deposit-button" class="bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400 transition" onclick="hideDeleteDepositModal()">キャンセル</button>
                <form id="delete-deposit-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                        削除
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- スワップ編集モーダル -->
    <div id="edit-swap-modal" class="fixed inset-0 flex items-start justify-center mt-3 bg-black bg-opacity-50 overflow-y-auto hidden" style="padding-top: 7.5vh;">
        <div class="border border-gray-300 p-6 w-3/4 rounded-lg bg-white shadow-lg">
            <h2 class="text-lg font-semibold">スワップを編集</h2>
            <form id="edit-swap-form" method="POST" action="">
                @csrf
                @method('PUT')
                <!-- 場所 -->
                <div class="mt-4">
                    <label for="edit-place" class="block text-sm font-medium text-gray-700">場所</label>
                    <select id="edit-place" name="edit-place" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="">取引所を選択</option>
                        @foreach($places as $place)
                            <option value="{{ $place }}">{{ $place }}</option>
                        @endforeach
                        <option value="other">新しい場所を追加</option>
                        <input type="text" id="edit-place_other" name="edit-place_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しい場所名を入力" style="display: none;">
                    </select>
                </div>
                <!-- スワップ元、先コイン -->
                <div class="mt-2 grid grid-cols-2 gap-4">
                    <!-- スワップ元コイン -->
                    <div>
                        <!-- スワップ元コイン -->
                        <label for="edit-coina" class="block text-sm font-medium text-gray-700">スワップ元コイン</label>
                        <select id="edit-coina" name="edit-coina" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">コインを選択</option>
                            @foreach($coins as $coin)
                                <option value="{{ $coin }}">{{ $coin }}</option>
                            @endforeach
                            <option value="other">新しいコインを追加</option>
                        </select>
                        <input type="text" id="edit-coina_other" name="edit-coina_other" class="mt-2 block w-full mt-1 border-gray-300 rounded-md shadow-sm" placeholder="新しいコイン名を入力" style="display: none;">
                        <!-- スワップ元の数量 -->
                        <label for="edit-amounta" class="mt-2 block text-sm font-medium text-gray-700">スワップ元の数量</label>
                        <input type="number" id="edit-amounta" name="edit-amounta" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" step=any>
                    </div>
                    <!-- スワップ先コイン -->
                    <div>
                        <!-- スワップ先コイン -->
                        <label for="edit-coinb" class="block text-sm font-medium text-gray-700">スワップ先コイン</label>
                        <select id="edit-coinb" name="edit-coinb" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">コインを選択</option>
                            @foreach($coins as $coin)
                                <option value="{{ $coin }}">{{ $coin }}</option>
                            @endforeach
                            <option value="other">新しいコインを追加</option>
                        </select>
                        <input type="text" id="edit-coinb_other" name="edit-coinb_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しいコイン名を入力" style="display: none;">
                        <!-- スワップ先の数量 -->
                        <label for="edit-amountb" class="mt-2 block text-sm font-medium text-gray-700">スワップ先の数量</label>
                        <input type="number" id="edit-amountb" name="edit-amountb" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" step=any>
                    </div>
                </div>
                <!-- 手数料のコイン -->
                <div class="mt-2">
                    <label for="edit-customfeecoin" class="block text-sm font-medium text-gray-700">手数料のコイン</label>
                    <input type="text" id="edit-customfeecoin" name="edit-customfeecoin" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                </div>
                <!-- 手数料 -->
                <div class="mt-2">
                    <label for="edit-customfee" class="block text-sm font-medium text-gray-700">手数料</label>
                    <input type="number" id="edit-customfee" name="edit-customfee" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm" step=any>
                </div>
                <!-- 日時 -->
                <div class="mt-2">
                    <label for="edit-customtime" class="block text-sm font-medium text-gray-700">日時</label>
                    <input type="datetime-local" id="edit-customtime" name="edit-customtime" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                </div>
                <!-- メモ -->
                <div class="mt-2">
                    <label for="edit-memo" class="block text-sm font-medium text-gray-700">メモ</label>
                    <textarea id="edit-memo" name="edit-memo" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <!-- ボタン -->
                <div class="mt-4 flex justify-center">
                <button type="button" onclick="closeEditSwapModal()" class="bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400 transition">キャンセル</button>
                <button type="submit" class="ml-5 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">更新</button>
                </div>
            </form>
        </div>
    </div>
    <!-- 送金編集モーダル -->
    <div id="edit-send-modal" class="fixed inset-0 flex items-start justify-center mt-3 bg-black bg-opacity-50 overflow-y-auto hidden" style="padding-top: 7.5vh;">
        <div class="border border-gray-300 p-6 w-3/4 rounded-lg bg-white shadow-lg">
            <h2 class="text-lg font-semibold">送金を編集</h2>
            <form id="edit-send-form" method="POST" action="">
                @csrf
                @method('PUT')
                <!-- 送金コイン -->
                <div class="mt-4">
                    <label for="edit-coin" class="block text-sm font-medium text-gray-700">コイン</label>
                    <select id="edit-coin" name="edit-coin" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="">コインを選択</option>
                        @foreach($coins as $coin)
                            <option value="{{ $coin }}">{{ $coin }}</option>
                        @endforeach
                        <option value="other">新しいコインを追加</option>
                        <input type="text" id="edit-coin_other" name="coin_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しい場所名を入力" style="display: none;">
                    </select>
                </div>
                <!-- 送金元、先 -->
                <div class="mt-2 grid grid-cols-2 gap-4">
                    <!-- 送金元 -->
                    <div>
                        <!-- 送金元場所 -->
                        <label for="edit-placea" class="block text-sm font-medium text-gray-700">送金元場所</label>
                        <select id="edit-placea" name="edit-placea" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">場所を選択</option>
                            @foreach($places as $place)
                                <option value="{{ $place }}">{{ $place }}</option>
                            @endforeach
                            <option value="other">新しい場所を追加</option>
                        </select>
                        <input type="text" id="edit-placea_other" name="edit-placea_other" class="mt-2 block w-full mt-1 border-gray-300 rounded-md shadow-sm" placeholder="新しい場所名を入力" style="display: none;">
                        <!-- 送金元の数量 -->
                        <label for="edit-send-amounta" class="mt-2 block text-sm font-medium text-gray-700">送金元の数量</label>
                        <input type="number" id="edit-send-amounta" name="edit-amounta" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" step=any>
                    </div>
                    <!-- 送金先 -->
                    <div>
                        <!-- 送金先場所 -->
                        <label for="edit-placeb" class="block text-sm font-medium text-gray-700">送金先場所</label>
                        <select id="edit-placeb" name="edit-placeb" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">場所を選択</option>
                            @foreach($places as $place)
                                <option value="{{ $place }}">{{ $place }}</option>
                            @endforeach
                            <option value="other">新しい場所を追加</option>
                        </select>
                        <input type="text" id="edit-placeb_other" name="edit-placeb_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しいコイン名を入力" style="display: none;">
                        <!-- 送金先の数量 -->
                        <label for="edit-send-amountb" class="mt-2 block text-sm font-medium text-gray-700">送金先の数量</label>
                        <input type="number" id="edit-send-amountb" name="edit-amountb" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" step=any>
                    </div>
                </div>
                <!-- 手数料のコイン -->
                <div class="mt-2">
                    <label for="edit-send-customfeecoin" class="block text-sm font-medium text-gray-700">手数料のコイン</label>
                    <input type="text" id="edit-send-customfeecoin" name="edit-customfeecoin" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                </div>
                <!-- 手数料 -->
                <div class="mt-2">
                    <label for="edit-send-customfee" class="block text-sm font-medium text-gray-700">手数料</label>
                    <input type="number" id="edit-send-customfee" name="edit-customfee" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm" step=any>
                </div>
                <!-- 日時 -->
                <div class="mt-2">
                    <label for="edit-send-customtime" class="block text-sm font-medium text-gray-700">日時</label>
                    <input type="datetime-local" id="edit-send-customtime" name="edit-customtime" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                </div>
                <!-- メモ -->
                <div class="mt-2">
                    <label for="edit-send-memo" class="block text-sm font-medium text-gray-700">メモ</label>
                    <textarea id="edit-send-memo" name="edit-memo" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <!-- ボタン -->
                <div class="mt-4 flex justify-center">
                    <button type="button" onclick="closeEditSendModal()" class="bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400 transition">キャンセル</button>
                    <button type="submit" class="ml-5 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">更新</button>
                </div>
            </form>
        </div>
    </div>
    <!-- 振込編集モーダル -->
    <div id="edit-deposit-modal" class="fixed inset-0 flex items-start justify-center mt-3 bg-black bg-opacity-50 overflow-y-auto hidden" style="padding-top: 7.5vh;">
        <div class="border border-gray-300 p-6 w-3/4 rounded-lg bg-white shadow-lg">
            <h2 class="text-lg font-semibold">振込を編集</h2>
            <form id="edit-deposit-form" method="POST" action="">
                @csrf
                @method('PUT')
                <!-- 振込場所 -->
                <div class="mt-4">
                    <label for="edit-deposit-place" class="block text-sm font-medium text-gray-700">振込元場所</label>
                    <select id="edit-deposit-place" name="edit-place" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="">場所を選択</option>
                        @foreach($places as $place)
                            <option value="{{ $place }}">{{ $place }}</option>
                        @endforeach
                        <option value="other">新しい場所を追加</option>
                    </select>
                    <input type="text" id="edit-deposit-place_other" name="edit-place_other" class="mt-2 block w-full mt-1 border-gray-300 rounded-md shadow-sm" placeholder="新しい場所名を入力" style="display: none;">
                </div>
                <!-- 振込コイン -->
                <div class="mt-4">
                    <!-- 振込コイン -->
                    <label for="edit-deposit-coin" class="block text-sm font-medium text-gray-700">コイン</label>
                    <select id="edit-deposit-coin" name="edit-coin" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="">コインを選択</option>
                        @foreach($coins as $coin)
                            <option value="{{ $coin }}">{{ $coin }}</option>
                        @endforeach
                        <option value="other">新しいコインを追加</option>
                        <input type="text" id="edit-deposit-coin_other" name="edit-coin_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しい場所名を入力" style="display: none;">
                    </select>
                    <!-- 振込コインの数量 -->
                    <label for="edit-deposit-amount" class="mt-2 block text-sm font-medium text-gray-700">振込の数量</label>
                    <input type="number" id="edit-deposit-amount" name="edit-amount" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" step=any>
                </div>
                <!-- 日時 -->
                <div class="mt-2">
                    <label for="edit-deposit-customtime" class="block text-sm font-medium text-gray-700">日時</label>
                    <input type="datetime-local" id="edit-deposit-customtime" name="edit-customtime" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                </div>
                <!-- メモ -->
                <div class="mt-2">
                    <label for="edit-deposit-memo" class="block text-sm font-medium text-gray-700">メモ</label>
                    <textarea id="edit-deposit-memo" name="edit-memo" class="mt-1 block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <!-- ボタン -->
                <div class="mt-4 flex justify-center">
                    <button type="button" onclick="closeEditSendModal()" class="bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400 transition">キャンセル</button>
                    <button type="submit" class="ml-5 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">更新</button>
                </div>
            </form>
        </div>
    </div>

    
    <!-------------------------- Javascript ------------------------------>
    <!-- タブ切り替えスクリプト -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('JavaScript is loaded correctly in index.blade.php');
        });

        document.getElementById('swap-tab').addEventListener('click', function() {
            document.getElementById('swap-tab').classList.remove('bg-gray-200');
            document.getElementById('swap-tab').classList.add('bg-gray-100');
            document.getElementById('send-tab').classList.remove('bg-gray-100');
            document.getElementById('send-tab').classList.add('bg-gray-200');
            document.getElementById('deposit-tab').classList.remove('bg-gray-100');
            document.getElementById('deposit-tab').classList.add('bg-gray-200');
            document.getElementById('swap-table').classList.remove('hidden');
            document.getElementById('send-table').classList.add('hidden');
            document.getElementById('deposit-table').classList.add('hidden');
        });
        document.getElementById('send-tab').addEventListener('click', function() {
            document.getElementById('swap-tab').classList.remove('bg-gray-100');
            document.getElementById('swap-tab').classList.add('bg-gray-200');
            document.getElementById('send-tab').classList.remove('bg-gray-200');
            document.getElementById('send-tab').classList.add('bg-gray-100');
            document.getElementById('deposit-tab').classList.remove('bg-gray-100');
            document.getElementById('deposit-tab').classList.add('bg-gray-200');
            document.getElementById('swap-table').classList.add('hidden');
            document.getElementById('send-table').classList.remove('hidden');
            document.getElementById('deposit-table').classList.add('hidden');
            // クリック時にコンソールにメッセージを表示
            console.log('Send tab clicked');
        });
        document.getElementById('deposit-tab').addEventListener('click', function() {
            document.getElementById('swap-tab').classList.remove('bg-gray-100');
            document.getElementById('swap-tab').classList.add('bg-gray-200');
            document.getElementById('send-tab').classList.remove('bg-gray-100');
            document.getElementById('send-tab').classList.add('bg-gray-200');
            document.getElementById('deposit-tab').classList.remove('bg-gray-200');
            document.getElementById('deposit-tab').classList.add('bg-gray-100');
            document.getElementById('swap-table').classList.add('hidden');
            document.getElementById('send-table').classList.add('hidden');
            document.getElementById('deposit-table').classList.remove('hidden');
        });
    </script>
    <!-- 削除確認モーダル -->
    <script>
        <!-- スワップ削除モーダル表示用 -->
        function showDeleteSwapModal(id, customtime, place, amounta, coina, amountb, coinb) {
            document.getElementById('delete-swap-form').action = '/transaction/delete-swap/' + id;
            document.getElementById('swap-info').innerHTML = customtime + '<br>' + place + 'でスワップ<br>' + amounta + coina + '→' + amountb + coinb;
            document.getElementById('delete-swap-modal').classList.remove('hidden');
        }
        <!-- スワップ削除モーダル非表示用 -->
        function hideDeleteSwapModal() {
            document.getElementById('delete-swap-modal').classList.add('hidden');
        }
        <!-- 送金削除モーダル表示用 -->
        function showDeleteSendModal(id, customtime, placea, placeb, amounta, amountb, coin) {
            document.getElementById('delete-send-form').action = '/transaction/delete-send/' + id;
            document.getElementById('send-info').innerHTML = customtime + '<br>' + placea + '→' + placeb + '<br>' + amounta + ' ' + coin;
            document.getElementById('delete-send-modal').classList.remove('hidden');
        }
        <!-- 送金削除モーダル非表示用 -->
        function hideDeleteSendModal() {
            let modal=document.getElementById('delete-send-modal');
            modal.classList.add('hidden');
        }
        <!-- 振込削除モーダル表示用 -->
        function showDeleteDepositModal(id, customtime, place, amount, coin) {
            document.getElementById('delete-deposit-form').action = '/transaction/delete-deposit/' + id;
            document.getElementById('deposit-info').innerHTML = customtime + '<br>' + place + '<br>' + amount + ' ' + coin;
            document.getElementById('delete-deposit-modal').classList.remove('hidden');
        }
        <!-- 振込削除モーダル非表示用 -->
        function hideDeleteDepositModal() {
            document.getElementById('delete-deposit-modal').classList.add('hidden');
        }
    </script>
    <!-- スワップ編集モーダル -->
    <script>
        //スワップ編集モーダル表示
        function showEditSwapModal(id, place, coina, amounta, coinb, amountb, customfeecoin, customfee, customtime, memo) {
            //フォームのアクションURLを更新
            document.getElementById('edit-swap-form').action = '/transaction/edit-swap/' + id;
            // 各入力フィールドに値をセット
            document.getElementById('edit-place').value = place;
            document.getElementById('edit-coina').value = coina;
            document.getElementById('edit-amounta').value = amounta;
            document.getElementById('edit-coinb').value = coinb;
            document.getElementById('edit-amountb').value = amountb;
            document.getElementById('edit-customfeecoin').value = customfeecoin;
            document.getElementById('edit-customfee').value = customfee;
            document.getElementById('edit-customtime').value = customtime;
            document.getElementById('edit-memo').value = memo;
            // モーダルを表示
            document.getElementById('edit-swap-modal').classList.remove('hidden');
        }
        //スワップ編集モーダルを閉じる
        function closeEditSwapModal() {
            document.getElementById('edit-swap-modal').classList.add('hidden');
        }
        //スワップ元の新しいコインを入力するinputを表示
        document.getElementById('edit-coina').addEventListener('change', function() {
            var coinaOther = document.getElementById('edit-coina_other');
            if (this.value === 'other') {
                coinaOther.style.display = 'block';
            } else {
                coinaOther.style.display = 'none';
            }
        });
        //スワップ先の新しいコインを入力するinputを表示
        document.getElementById('edit-coinb').addEventListener('change', function() {
            var coinbOther = document.getElementById('edit-coinb_other');
            if (this.value === 'other') {
                coinbOther.style.display = 'block';
            } else {
                coinbOther.style.display = 'none';
            }
        });
        //スワップ場所の新しい場所を入力するinputを表示
        document.getElementById('edit-place').addEventListener('change', function() {
            var placeOther = document.getElementById('edit-place_other');
            if (this.value === 'other') {
                placeOther.style.display = 'block';
            } else {
                placeOther.style.display = 'none';
            }
        });
    </script>
    <!-- 送金編集モーダル -->
    <script>
        //送金編集モーダル表示
        function showEditSendModal(id, coin, placea, amounta, placeb, amountb, customfeecoin, customfee, customtime, memo) {
            console.log('hi');
            //フォームのアクションURLを更新
            document.getElementById('edit-send-form').action = '/transaction/edit-send/' + id;
            // 各入力フィールドに値をセット
            document.getElementById('edit-coin').value = coin;
            document.getElementById('edit-placea').value = placea;
            document.getElementById('edit-send-amounta').value = amounta;
            document.getElementById('edit-placeb').value = placeb;
            document.getElementById('edit-send-amountb').value = amountb;
            document.getElementById('edit-send-customfeecoin').value = customfeecoin;
            document.getElementById('edit-send-customfee').value = customfee;
            document.getElementById('edit-send-customtime').value = customtime;
            document.getElementById('edit-send-memo').value = memo;
            // モーダルを表示
            document.getElementById('edit-send-modal').classList.remove('hidden');
        }
        //送金編集モーダルを閉じる
        function closeEditSendModal() {
            document.getElementById('edit-send-modal').classList.add('hidden');
        }
        //送金元の新しい場所を入力するinputを表示
        document.getElementById('edit-placea').addEventListener('change', function() {
            var coinaOther = document.getElementById('edit-placea_other');
            if (this.value === 'other') {
                coinaOther.style.display = 'block';
            } else {
                coinaOther.style.display = 'none';
            }
        });
        //送金先の新しい場所を入力するinputを表示
        document.getElementById('edit-placeb').addEventListener('change', function() {
            var coinbOther = document.getElementById('edit-placeb_other');
            if (this.value === 'other') {
                coinbOther.style.display = 'block';
            } else {
                coinbOther.style.display = 'none';
            }
        });
        //送金の新しいコインを入力するinputを表示
        document.getElementById('edit-coin').addEventListener('change', function() {
            var placeOther = document.getElementById('edit-coin_other');
            if (this.value === 'other') {
                placeOther.style.display = 'block';
            } else {
                placeOther.style.display = 'none';
            }
        });
    </script>
    <!-- 振込編集モーダル -->
    <script>
        //振込編集モーダル表示
        function showEditDepositModal(id, coin, place, amount, customtime, memo) {
            //フォームのアクションURLを更新
            document.getElementById('edit-deposit-form').action = '/transaction/edit-deposit/' + id;
            // 各入力フィールドに値をセット
            document.getElementById('edit-deposit-coin').value = coin;
            document.getElementById('edit-deposit-place').value = place;
            document.getElementById('edit-deposit-amount').value = amount;
            console.log('amount set');
            document.getElementById('edit-deposit-customtime').value = customtime;
            document.getElementById('edit-deposit-memo').value = memo;
            // モーダルを表示
            document.getElementById('edit-deposit-modal').classList.remove('hidden');
        }
        //振込編集モーダルを閉じる
        function closeEditSendModal() {
            document.getElementById('edit-deposit-modal').classList.add('hidden');
        }
        //振込の新しい場所を入力するinputを表示
        document.getElementById('edit-deposit-place').addEventListener('change', function() {
            var coinaOther = document.getElementById('edit-deposit-place_other');
            if (this.value === 'other') {
                coinaOther.style.display = 'block';
            } else {
                coinaOther.style.display = 'none';
            }
        });
        //振込の新しいコインを入力するinputを表示
        document.getElementById('edit-deposit-coin').addEventListener('change', function() {
            var placeOther = document.getElementById('edit-deposit-coin_other');
            if (this.value === 'other') {
                placeOther.style.display = 'block';
            } else {
                placeOther.style.display = 'none';
            }
        });
    </script>        
    <!-- モーダルの外をクリックするとモーダルを閉じる -->
    <script>
        window.addEventListener('click', function(event) {
            const modals = ['edit-swap-modal', 'edit-send-modal', 'edit-deposit-modal', 'delete-swap-modal', 'delete-send-modal', 'delete-deposit-modal'];
            modals.forEach(function(modalId) {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden') && event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>

</x-app-layout>
