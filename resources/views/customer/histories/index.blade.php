<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Rekomendasi Rumah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Rekomendasi Rumah Anda</h3>

                    @if ($recommendations->isEmpty())
                        <div class="text-center p-4 bg-gray-50 rounded-md">
                            <p class="text-gray-500">Anda belum memiliki riwayat rekomendasi rumah.</p>
                            <a href="{{ route('dashboard') }}"
                                class="mt-2 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Mulai Bandingkan Rumah
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">No</th>
                                        <th class="py-2 px-4 border-b">Nama Rumah</th>
                                        <th class="py-2 px-4 border-b">Skor Rekomendasi</th>
                                        <th class="py-2 px-4 border-b">Persentase</th>
                                        <th class="py-2 px-4 border-b">Tanggal Penilaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recommendations as $index => $recommendation)
                                        <tr class="hover:bg-gray-200" style="cursor: pointer;"
                                            data-href="{{ route('recommendations.show', $recommendation) }}"
                                            onclick="window.location.href=this.dataset.href">
                                            <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                                            <td class="py-2 px-4 border-b">{{ $recommendation->house->nama }}</td>
                                            <td class="py-2 px-4 border-b">
                                                {{ number_format($recommendation->nilai, 4) }}
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                {{ number_format($recommendation->nilai * 100, 2) }}%
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                {{ $recommendation->created_at->format('d M Y, H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
