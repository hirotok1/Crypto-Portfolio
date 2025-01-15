<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('トランザクションを追加') }}
        </h2>
    </x-slot>

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

    <div class="flex justify-center mt-3">
        <div class="border border-gray-300 p-6 w-3/4 rounded-lg bg-white shadow-lg">
            <!-- タブ -->
            <div class="flex justify-between mb-6">
                <button id="swap-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-l-lg bg-gray-100">スワップ</button>
                <button id="send-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-r-lg bg-gray-200">送金</button>
            </div>

            <!-- スワップフォーム -->
            <form id="swap-form" method="POST" action="{{ route('transaction.storeSwap') }}" class="block">
                @csrf
                <!-- 取引所 -->
                <div class="mb-6">
                    <label for="place" class="block text-sm font-medium text-gray-700">取引所</label>
                    <select id="place" name="place" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option>Bitbank</option>
                        <option>Binance</option>
                    </select>
                </div>

                <!-- スワップ元 -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="coina" class="block text-sm font-medium text-gray-700">スワップ元コイン</label>
                        <select id="coina" name="coina" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option>BTC</option>
                            <option>ETH</option>
                        </select>
                        <input type="number" name="amounta" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="数量">
                    </div>
                    <div>
                        <label for="coinb" class="block text-sm font-medium text-gray-700">スワップ先コイン</label>
                        <select id="coinb" name="coinb" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option>JPY</option>
                            <option>USD</option>
                        </select>
                        <input type="number" name="amountb" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="数量">
                    </div>
                </div>

                <!-- 手数料 -->
                <div class="mb-6">
                    <label for="customfee" class="block text-sm font-medium text-gray-700">手数料</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 bg-gray-100 p-2 rounded-md">
                                自動計算：<span id="auto-fee">--</span>
                            </p>
                        </div>
                        <div>
                            <input type="text" name="customfeecoin" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" placeholder="コイン名 (例: JPY)">
                            <input type="number" name="customfee" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="手数料">
                        </div>
                    </div>
                </div>

                <!-- 日時 -->
                <div class="mb-6">
                    <label for="customtime" class="block text-sm font-medium text-gray-700">日時</label>
                    <input type="datetime-local" name="customtime" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                </div>

                <!-- メモ -->
                <div class="mb-6">
                    <label for="memo" class="block text-sm font-medium text-gray-700">メモ</label>
                    <textarea name="memo" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                </div>

                <!-- 追加ボタン -->
                <div class="flex justify-center mt-6">
                    <button
                        type="submit"
                        style="background-color: #2563eb; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); cursor: pointer;"
                    >
                        トランザクションを追加
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- タブ切り替えスクリプト -->
    <script>
        const swapTab = document.getElementById('swap-tab');
        const sendTab = document.getElementById('send-tab');
        const swapForm = document.getElementById('swap-form');
        const sendForm = document.getElementById('send-form');

        swapTab.addEventListener('click', () => {
            swapForm.classList.remove('hidden');
            sendForm.classList.add('hidden');
            swapTab.classList.add('bg-gray-100');
            sendTab.classList.remove('bg-gray-100');
        });

        sendTab.addEventListener('click', () => {
            sendForm.classList.remove('hidden');
            swapForm.classList.add('hidden');
            sendTab.classList.add('bg-gray-100');
            swapTab.classList.remove('bg-gray-100');
        });
    </script>
</x-app-layout>
