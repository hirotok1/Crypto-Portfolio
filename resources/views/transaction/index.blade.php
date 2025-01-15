<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('トランザクション') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Swaps Table -->
                    <h3 class="text-lg font-semibold mb-4">Swaps</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>取引所</th>
                                <th>スワップ情報</th>
                                <th>手数料</th>
                                <th class="w-1/4">メモ</th>
                                <th>日時</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($swaps as $index => $swap)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
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
                                    <td>{{ $swap->customtime }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
