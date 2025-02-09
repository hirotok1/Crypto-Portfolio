<x-app-layout>
    <!-- ヘッダーのスタイルを非表示にする -->
    <style>
        header.bg-white.shadow {
            display: none; /* ヘッダー全体を非表示 */
        }
    </style>
    
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- 総資産の表示と円グラフのコンテナ -->
                <div class="flex items-start mb-4">
                    <!-- 総資産の表示状態 -->
                    <h2 id="total-assets-uncovered" class="text-lg font-semibold">
                        総資産 {{ session('currency', 'JPY') === 'JPY' ? '¥' : '$' }}{{ number_format($totalAssets, 2) }}
                    </h2>
                    <!-- 総資産の非表示状態 -->
                    <h2 id="total-assets-covered" class="text-lg font-semibold hidden">
                        総資産 {{ session('currency', 'JPY') === 'JPY' ? '¥' : '$' }} *************
                    </h2>               
                    <!-- 総資産の非表示ボタン -->
                    <button id="cover-assets-button" class="bg-blue-500 px-3 py-1 rounded hover:bg-blue-600 transition">
                        <svg class="h-6 w-6 text-zinc-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                    <!-- 総資産の表示ボタン -->
                    <button id="uncover-assets-button" class="bg-blue-500 px-3 py-1 rounded hover:bg-blue-600 transition hidden">
                        <svg class="h-6 w-6 text-zinc-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    <!-- 資産円グラフ -->
                    <div class="w-1/2 ml-10 flex justify-center">
                        <canvas id="portfolioChart" width="250" height="250"></canvas>
                    </div>
                </div>
                <!-- 総資産表 -->
                <table class="border-collapse border border-gray-200 w-11/12 text-center">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">ロゴ</th>
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
                                $coinId = $coinIdMap[$coin] ?? null;
                                $logoUrl = $coinId ? ($logos[$coinId]['logo'] ?? '') : '';
                            @endphp
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    @if ($logoUrl)
                                        <img src="{{ $logoUrl }}" alt="{{ $coin }}" width="24" height="24">
                                    @endif
                                </td>
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
    
    <!--Chart.js-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById("portfolioChart").getContext("2d");

            // Blade から PHP のデータを JavaScript に変換
            const chartData = @json($chartData);

            const labels = chartData.map(data => data.label);
            const values = chartData.map(data => data.value);

            new Chart(ctx, {
                type: 'doughnut',  // 円グラフ
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        });
    </script>

    <!-- 総資産表示非表示切り替え -->
    <script>
        document.getElementById('cover-assets-button').addEventListener('click', function() {
            document.getElementById('total-assets-uncovered').classList.add('hidden');
            document.getElementById('total-assets-covered').classList.remove('hidden');   
            document.getElementById('cover-assets-button').classList.add('hidden');  
            document.getElementById('uncover-assets-button').classList.remove('hidden');  
        });
        document.getElementById('uncover-assets-button').addEventListener('click', function() {
            document.getElementById('total-assets-uncovered').classList.remove('hidden');
            document.getElementById('total-assets-covered').classList.add('hidden');  
            document.getElementById('uncover-assets-button').classList.add('hidden');  
            document.getElementById('cover-assets-button').classList.remove('hidden');     
        });
    </script>



</x-app-layout>
