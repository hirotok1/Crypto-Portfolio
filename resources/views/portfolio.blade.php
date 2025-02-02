<x-app-layout>
    <!-- ヘッダーのスタイルを非表示にする -->
    <style>
        header.bg-white.shadow {
            display: none; /* ヘッダー全体を非表示 */
        }
    </style>
    
    <div class="flex justify-center mt-6">
    <div class="py-4>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- 総資産の表示 -->
                <div class="mb-4">
                    <h2 class="text-lg font-semibold">総資産 {{ session('currency', 'JPY') === 'JPY' ? '¥' : '$' }}{{ number_format($totalAssets, 2) }}</h2>
                </div>
                <table class="border-collapse border border-gray-200 w-11/12 text-center">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">コイン</th>
                            <th class="border border-gray-300 px-4 py-2">コインの価格</th>
                            <th class="border border-gray-300 px-4 py-2">1h%</th>
                            <th class="border border-gray-300 px-4 py-2">24h%</th>
                            <th class="border border-gray-300 px-4 py-2">保有枚数</th>
                            <th class="border border-gray-300 px-4 py-2">保有額</th>
                            <th class="border border-gray-300 px-4 py-2">内訳</th>
                        </tr>
                    </thead>
                    <!-- coinBalanceはポートフォリオの保有枚数 -->
                    <tbody>
                        @foreach ($coinBalance as $coin => $coinBalance)
                            @php
                                $price = $coinData[$coin]['price'] ?? 0;
                                $percentChange1h = $coinData[$coin]['percent_change_1h'] ?? 0;
                                $percentChange24h = $coinData[$coin]['percent_change_24h'] ?? 0;
                            @endphp
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $coin }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ session('currency', 'JPY') === 'JPY' ? '¥' : '$' }}
                                    {{ number_format($price, 2) }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2" style="color: {{ $percentChange1h >= 0 ? 'green' : 'red' }};">
                                    {{ number_format($percentChange1h, 2) }}%
                                </td>
                                <td class="border border-gray-300 px-4 py-2" style="color: {{ $percentChange24h >= 0 ? 'green' : 'red' }};">
                                    {{ number_format($percentChange24h, 2) }}%
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ rtrim(rtrim(number_format($coinBalance, 8), '0'), '.') }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ session('currency', 'JPY') === 'JPY' ? '¥' : '$' }}    
                                    {{ number_format($price * $coinBalance, 2) }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    @foreach ($coinPlaces[$coin] as $place)
                                        {{ $place }}:{{ $placeBalance[$place][$coin] }}{{ $coin }}<br>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- 場所ごとのコインと枚数 -->
                <div class="mt-6">
                    <h2 class="text-lg font-semibold mb-4">場所ごとのコインと枚数</h2>
                    @foreach ($placeBalance as $place => $coins)
                        <div class="mb-4">
                            <h3 class="text-md font-semibold">{{ $place }}</h3>
                            <ul class="list-disc list-inside">
                                @foreach ($coins as $coin => $amount)
                                    <li>{{ $coin }}: {{ rtrim(rtrim(number_format($amount, 8), '0'), '.') }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
