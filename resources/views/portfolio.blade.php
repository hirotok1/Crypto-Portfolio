<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ポートフォリオ') }}
        </h2>
    </x-slot>
    <div class="flex justify-center mt-6">
    <div class="py-4>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="border-collapse border border-gray-200 w-11/12 text-center">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">コイン</th>
                            <th class="border border-gray-300 px-4 py-2">コインの価格</th>
                            <th class="border border-gray-300 px-4 py-2">1h%</th>
                            <th class="border border-gray-300 px-4 py-2">24h%</th>
                            <th class="border border-gray-300 px-4 py-2">保有枚数</th>
                            <th class="border border-gray-300 px-4 py-2">保有額</th>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
