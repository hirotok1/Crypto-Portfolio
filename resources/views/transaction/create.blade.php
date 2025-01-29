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
                <button id="send-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-none bg-gray-200">送金</button>
                <button id="deposit-tab" class="flex-1 py-2 px-4 border border-gray-300 rounded-r-lg bg-gray-200">振込</button>
            </div>

            <!-- スワップフォーム -->
            <form id="swap-form" method="POST" action="{{ route('transaction.storeSwap') }}" class="block">
                @csrf
                <!-- 取引所 -->
                <div class="mb-6">
                    <label for="place" class="block text-sm font-medium text-gray-700">取引所</label>
                    <select id="place" name="place" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="">取引所を選択</option>
                        @foreach($places as $place)
                            <option value="{{ $place }}">{{ $place }}</option>
                        @endforeach
                        <option value="other">新しい場所を追加</option>
                        <input type="text" id="place_other" name="place_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しい場所名を入力" style="display: none;">
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- スワップ元 -->
                    <div>
                        <label for="coina" class="block text-sm font-medium text-gray-700">スワップ元コイン</label>
                        <select id="coina" name="coina" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">コインを選択</option>
                            @foreach($coins as $coin)
                                <option value="{{ $coin }}">{{ $coin }}</option>
                            @endforeach
                            <option value="other">新しいコインを追加</option>
                        </select>
                        <input type="text" id="coina_other" name="coina_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しいコイン名を入力" style="display: none;">
                        <input type="number" name="amounta" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="数量" step=any>
                    </div>
                    <!-- スワップ先 -->
                    <div>
                        <label for="coinb" class="block text-sm font-medium text-gray-700">スワップ先コイン</label>
                        <select id="coinb" name="coinb" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">コインを選択</option>
                            @foreach($coins as $coin)
                                <option value="{{ $coin }}">{{ $coin }}</option>
                            @endforeach
                            <option value="other">新しいコインを追加</option>        
                        </select>
                        <input type="text" id="coinb_other" name="coinb_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しいコイン名を入力" style="display: none;">
                        <input type="number" name="amountb" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="数量" step=any>
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
                            <input type="number" name="customfee" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="手数料" step=any>
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
            
            <!-- 送金フォーム -->
            <form id="send-form" method="POST" action="{{ route('transaction.storeSend') }}" class="hidden">
                @csrf
                <!-- コイン -->
                <div class="mb-6">
                    <label for="coin" class="block text-sm font-medium text-gray-700">コイン</label>
                        <select id="coin" name="coin" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">コインを選択</option>
                            @foreach($coins as $coin)
                                <option value="{{ $coin }}">{{ $coin }}</option>
                            @endforeach
                            <option value="other">新しいコインを追加</option>
                            <input type="text" id="coin_other" name="coin_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しいコイン名を入力" style="display: none;">
                        </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- 送金元 -->
                    <div>
                        <label for="placea" class="block text-sm font-medium text-gray-700">送金元</label>
                        <select id="placea" name="placea" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">場所を選択</option>
                            @foreach($places as $place)
                                <option value="{{ $place }}">{{ $place }}</option>
                            @endforeach
                            <option value="other">新しい場所を追加</option>
                        </select>
                        <input type="text" id="placea_other" name="placea_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しい場所名を入力" style="display: none;">
                        <input type="number" name="amounta" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="数量" step=any>
                    </div>
                    <!-- 送金先 -->
                    <div>
                        <label for="placeb" class="block text-sm font-medium text-gray-700">送金先</label>
                        <select id="placeb" name="placeb" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">場所を選択</option>
                            @foreach($places as $place)
                                <option value="{{ $place }}">{{ $place }}</option>
                            @endforeach
                            <option value="other">新しい場所を追加</option>        
                        </select>
                        <input type="text" id="placeb_other" name="placeb_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しい場所名を入力" style="display: none;">
                        <input type="number" name="amountb" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="数量" step=any>
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
                            <input type="number" name="customfee" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="手数料" step=any>
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

            <!-- 振込フォーム -->
            <form id="deposit-form" method="POST" action="{{ route('transaction.storeDeposit') }}" class="hidden">
                @csrf
                <!-- 振込場所 -->
                <div class="mb-6">
                    <label for="place" class="block text-sm font-medium text-gray-700">振込場所</label>
                        <select id="deposit_place" name="place" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">場所を選択</option>
                            @foreach($places as $place)
                                <option value="{{ $place }}">{{ $place }}</option>
                            @endforeach
                            <option value="other">新しい振込場所を追加</option>
                            <input type="text" id="deposit_place_other" name="place_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しい振込場所を入力" style="display: none;">
                        </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- 振込コイン -->
                    <div>
                        <label for="coin" class="block text-sm font-medium text-gray-700">振込コイン</label>
                        <select id="deposit_coin" name="coin" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">コインを選択</option>
                            @foreach($coins as $coin)
                                <option value="{{ $coin }}">{{ $coin }}</option>
                            @endforeach
                            <option value="other">新しいコインを追加</option>        
                        </select>
                        <input type="text" id="deposit_coin_other" name="coin_other" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="新しいコイン名を入力" style="display: none;">
                        <input type="number" name="amount" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" placeholder="数量" step=any>
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
        const depositTab = document.getElementById('deposit-tab');
        const swapForm = document.getElementById('swap-form');
        const sendForm = document.getElementById('send-form');
        const depositForm = document.getElementById('deposit-form');

        swapTab.addEventListener('click', () => {
            swapForm.classList.remove('hidden');
            sendForm.classList.add('hidden');
            depositForm.classList.add('hidden');
            //swapタブのbg色のみ明るく
            swapTab.classList.add('bg-gray-100');
            swapTab.classList.remove('bg-gray-200');
            sendTab.classList.remove('bg-gray-100');
            sendTab.classList.add('bg-gray-200');
            depositTab.classList.remove('bg-gray-100');
            depositTab.classList.add('bg-gray-200');
        });

        sendTab.addEventListener('click', () => {
            sendForm.classList.remove('hidden');
            swapForm.classList.add('hidden');
            depositForm.classList.add('hidden');
            //sendタブのbg色のみ明るく
            swapTab.classList.add('bg-gray-200');
            swapTab.classList.remove('bg-gray-100');
            sendTab.classList.add('bg-gray-100');
            sendTab.classList.remove('bg-gray-200');
            depositTab.classList.add('bg-gray-200');
            depositTab.classList.remove('bg-gray-100');            
        });
       
        depositTab.addEventListener('click', () => {
            depositForm.classList.remove('hidden');
            swapForm.classList.add('hidden');
            sendForm.classList.add('hidden');
            //depositタブのbg色のみ明るく
            depositTab.classList.add('bg-gray-100');
            depositTab.classList.remove('bg-gray-200');
            swapTab.classList.remove('bg-gray-100');
            swapTab.classList.add('bg-gray-200');
            sendTab.classList.remove('bg-gray-100');
            sendTab.classList.add('bg-gray-200');
        });

        document.getElementById('coina').addEventListener('change', function() {
            var coinaOther = document.getElementById('coina_other');
            if (this.value === 'other') {
                coinaOther.style.display = 'block';
            } else {
                coinaOther.style.display = 'none';
            }
        });
        document.getElementById('coinb').addEventListener('change', function() {
            var coinbOther = document.getElementById('coinb_other');
            if (this.value === 'other') {
                coinbOther.style.display = 'block';
            } else {
                coinbOther.style.display = 'none';
            }
        });

        
        document.getElementById('place').addEventListener('change', function() {
            var placeOther = document.getElementById('place_other');
            if (this.value === 'other') {
                placeOther.style.display = 'block';
            } else {
                placeOther.style.display = 'none';
            }
        });

        document.getElementById('coin').addEventListener('change', function() {
            var placeOther = document.getElementById('coin_other');
            if (this.value === 'other') {
                placeOther.style.display = 'block';
            } else {
                placeOther.style.display = 'none';
            }
        });

        document.getElementById('placea').addEventListener('change', function() {
            var placeOther = document.getElementById('placea_other');
            if (this.value === 'other') {
                placeOther.style.display = 'block';
            } else {
                placeOther.style.display = 'none';
            }
        });

        document.getElementById('placeb').addEventListener('change', function() {
            var placeOther = document.getElementById('placeb_other');
            if (this.value === 'other') {
                placeOther.style.display = 'block';
            } else {
                placeOther.style.display = 'none';
            }
        });

        document.getElementById('deposit_place').addEventListener('change', function() {
            var placeOther = document.getElementById('deposit_place_other');
            if (this.value === 'other') {
                placeOther.style.display = 'block';
            } else {
                placeOther.style.display = 'none';
            }
        });

        document.getElementById('deposit_coin').addEventListener('change', function() {
            var placeOther = document.getElementById('deposit_coin_other');
            if (this.value === 'other') {
                placeOther.style.display = 'block';
            } else {
                placeOther.style.display = 'none';
            }
        });

    </script>
</x-app-layout>
