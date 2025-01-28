<x-app-layout>
    <!-- ヘッダーのスタイルを非表示にする -->
    <style>
        header.bg-white.shadow {
            display: none; /* ヘッダー全体を非表示 */
        }
    </style>
    <!--<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('トランザクション') }}
        </h2>
    </x-slot>-->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 bg-white border-b border-gray-200">
                    <!-- タブ -->
                    <div class="flex justify-between mb-4">
                        <button id="swap-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-l-lg bg-gray-100">スワップ</button>
                        <button id="send-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-none bg-gray-200">送金</button>
                        <button id="deposit-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-r-lg bg-gray-200">振込</button>
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
                                            <!--<img src="{{ $logos[$swap->coina] ?? '' }}" alt="{{ $swap->coina }}" width="32" height="32">-->
                                            →
                                            {{ rtrim(rtrim(number_format($swap->amountb, 8), '0'), '.') }}
                                            {{ $swap->coinb }}
                                            <!--<img src="{{ $logos[$swap->coinb] ?? '' }}" alt="{{ $swap->coinb }}" width="32" height="32">-->
                                        </td>
                                        <td>{{ rtrim(rtrim(number_format($swap->customfee, 8), '0'), '.') }}{{ $swap->customfeecoin }}</td>
                                        <td class="break-words">{{ $swap->memo }}</td>
                                        
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
                                    <th>振込先</th>
                                    <th>コイン</th>
                                    <th class="w-1/4">メモ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deposits as $deposit)
                                    <tr>
                                        <td>{{ $deposit->customtime }}</td>
                                        <td>{{ $deposit->place }}</td>
                                        <td>{{ rtrim(rtrim(number_format($deposit->amount, 8), '0'), '.') }}{{ $deposit->coin }}</td>
                                        <td class="break-words">{{ $deposit->memo }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
    </script>
</x-app-layout>
