<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crypto時価総額 Top100') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-4">
                    <table class="w-2/4 text-center">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">No.</th>
                            <th class="px-4 py-2">コイン</th>
                            <th class="px-4 py-2">ティッカー</th>
                            <th class="px-4 py-2">1h%</th>
                            <th class="px-4 py-2">24h%</th>
                            <th class="px-4 py-2">価格</th>
                            <th class="px-4 py-2">時価総額</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // 通貨記号を基準通貨に応じて設定
                            $currencySymbol = match(session('currency', 'JPY')) {
                                'JPY' => '¥',
                                'USD' => '$',
                                'BTC' => '₿',
                                default => '',
                            };
                        
                        @endphp
                        @foreach ($data['data'] as $index => $crypto)
                            @php
                                $percent_change_1h = $coinData[$crypto['symbol']]['percent_change_1h'] ?? 0;
                                $percent_change_24h = $coinData[$crypto['symbol']]['percent_change_24h'] ?? 0;
                                $coin = $crypto['symbol'];
                                $price = $crypto['quote'][session('currency', 'JPY')]['price'];
                                $marketcap = $crypto['quote'][session('currency', 'JPY')]['market_cap'];
                            @endphp
                            <tr>
                                <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="flex px-4 py-2">
                                    <img src="{{ $logos[$crypto['id']]['logo'] ?? '' }}" alt="{{ $crypto['name'] }}" width="24" height="24">
                                    <span class="ml-2">{{ $crypto['name'] }}</span>
                                </td>
                                <td class="px-4 py-2 text-center">{{ $coin }}</td>
                                <td class="px-4 py-2 text-center" style="color: {{ $percent_change_1h >= 0 ? 'green' : 'red' }};">{{ number_format($percent_change_1h, 2) }}%</td>
                                <td class="px-4 py-2 text-center" style="color: {{ $percent_change_24h >= 0 ? 'green' : 'red' }};">{{ number_format($percent_change_24h, 2) }}%</td>
                                <td class="px-4 py-2 text-center">{{ $currencySymbol }}{{ number_format($price, 2) }}</td>
                                <td class="px-4 py-2 text-center">{{ $currencySymbol }}{{ number_format($marketcap, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>  
    </div>  
</x-app-layout>
