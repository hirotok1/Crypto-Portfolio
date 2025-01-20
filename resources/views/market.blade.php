<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crypto時価総額 Top100') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-4">
                    <table class="border-collapse border border-gray-200 w-2/4 text-center">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">#</th>
                            <th class="border border-gray-300 px-4 py-2">Logo</th>
                            <th class="border border-gray-300 px-4 py-2">Name</th>
                            <th class="border border-gray-300 px-4 py-2">Ticker</th>
                            <th class="border border-gray-300 px-4 py-2">Price({{session('currency', 'USD')}})</th>
                            <th class="border border-gray-300 px-4 py-2">Market Cap({{session('currency', 'USD')}})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // 通貨記号を基準通貨に応じて設定
                            $currencySymbol = match(session('currency', 'USD')) {
                                'USD' => '$',
                                'JPY' => '¥',
                                'BTC' => '₿',
                                default => '',
                            };
                        @endphp
                        @foreach ($data['data'] as $index => $crypto)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <img src="{{ $logos[$crypto['id']]['logo'] ?? '' }}" alt="{{ $crypto['name'] }}" width="24" height="24">
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $crypto['name'] }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $crypto['symbol'] }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $currencySymbol }}{{ number_format($crypto['quote'][session('currency', 'USD')]['price'], 2) }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $currencySymbol }}{{ number_format($crypto['quote'][session('currency', 'USD')]['market_cap'], 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>  
    </div>  
</x-app-layout>
