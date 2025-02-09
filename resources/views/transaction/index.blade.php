<x-app-layout>
    <!-- ヘッダーのスタイルを非表示にする -->
    <style>
        header.bg-white.shadow {
            display: none; /* ヘッダー全体を非表示 */
        }
    </style>
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
                                    <th>日時</th>
                                    <th>取引所</th>
                                    <th>スワップ</th>
                                    <th>手数料</th>
                                    <th class="w-1/4">メモ</th>
                                    <th></th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($swaps as $index => $swap)
                                    <tr>
                                        <td>{{ $swap->customtime }}</td>
                                        <td>{{ $swap->place }}</td>
                                        <td>
                                            {{ rtrim(rtrim(number_format($swap->amounta, 8), '0'), '.') }}
                                            {{ $swap->coina }}
                                            →
                                            {{ rtrim(rtrim(number_format($swap->amountb, 8), '0'), '.') }}
                                            {{ $swap->coinb }}
                                        </td>
                                        <td>{{ rtrim(rtrim(number_format($swap->customfee, 8), '0'), '.') }}{{ $swap->customfeecoin }}</td>
                                        <td class="break-words">{{ $swap->memo }}</td>
                                        <td>
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
                                    <th>日時</th>
                                    <th>コイン</th>
                                    <th>送金元</th><!--送金元場所と枚数-->
                                    <th>送金先</th><!--送金先場所と枚数-->
                                    <th>その他手数料</th>
                                    <th class="w-1/4">メモ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sends as $send)
                                    <tr>
                                        <td>{{ $send->customtime }}</td>
                                        <td>{{ $send->coin }}</td>
                                        <td>
                                            {{ $send->placea }}
                                            {{ rtrim(rtrim(number_format($send->amounta, 8), '0'), '.') }}{{ $send->coin }}
                                        </td>
                                        <td>
                                            {{ $send->placeb }}
                                            {{ rtrim(rtrim(number_format($send->amountb, 8), '0'), '.') }}{{ $send->coin }}
                                        </td>
                                        <td>{{ rtrim(rtrim(number_format($send->customfee, 8), '0'), '.') }}{{ $send->customfeecoin }}
                                        </td>
                                        <td class="break-words">{{ $send->memo }}</td>
                                        <td>
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
                                    <th>日時</th>
                                    <th>場所</th>
                                    <th>コイン</th>
                                    <th class="w-1/4">メモ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deposits as $deposit)
                                    <tr>
                                        <td>{{ $deposit->customtime }}</td>
                                        <td>{{ $deposit->place }}</td>
                                        <td>{{ rtrim(rtrim(number_format($deposit->amount, 8), '0'), '.') }}{{ $deposit->coin }}</td>
                                        <td class="break-words">{{ $deposit->memo }}</td>
                                        <td>
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
</x-app-layout>
