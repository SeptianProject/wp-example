<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Rekomendasi Rumah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-700">Detail Rekomendasi: {{ $house->nama }}</h3>
                        <span class="text-sm text-gray-500">Tanggal pencarian:
                            {{ $recommendation->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <!-- House Details Box -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="font-bold text-blue-700">Rekomendasi Perumahan:</h4>
                        <p class="text-blue-700 mt-2"><span class="font-bold">{{ $house->nama }}</span> memiliki nilai
                            rekomendasi {{ number_format($result['percentage'], 2) }}%.</p>
                    </div>

                    <!-- Criteria Values Table -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-700 mb-2">Nilai Kriteria</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">Kriteria</th>
                                        <th class="py-2 px-4 border-b">Nilai</th>
                                        <th class="py-2 px-4 border-b">Bobot</th>
                                        <th class="py-2 px-4 border-b">Jenis</th>
                                        <th class="py-2 px-4 border-b">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kriteriaScores as $score)
                                        <tr class="hover:bg-gray-100">
                                            <td class="py-2 px-4 border-b">{{ $score->kriteria->nama }}</td>
                                            <td class="py-2 px-4 border-b">
                                                @if ($score->kriteria->kode == 'C')
                                                    Rp. {{ number_format($score->nilai, 0, ',', '.') }}
                                                @elseif (in_array($score->kriteria->kode, ['LT', 'LB']))
                                                    {{ $score->nilai }} m²
                                                @elseif ($score->kriteria->kode == 'JTK')
                                                    {{ $score->nilai }} km
                                                @else
                                                    {{ $score->nilai }}
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b">{{ $score->kriteria->bobot }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full {{ $score->kriteria->type == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($score->kriteria->type) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b text-sm text-gray-600">
                                                {{ $score->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Calculation Summary -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="font-medium text-gray-700 mb-2">Ringkasan Perhitungan:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                            <div>
                                <p class="text-sm text-gray-600"><span class="font-medium">Vector V:</span>
                                    {{ number_format($result['vector_v'], 4) }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Persentase:</span>
                                    {{ number_format($result['percentage'], 2) }}%</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600"><span class="font-medium">ID Rumah:</span>
                                    {{ $house->id }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Tanggal Rekomendasi:</span>
                                    {{ $recommendation->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('recommendations.history') }}"
                            class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                            ← Kembali ke Riwayat
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Bandingkan Rumah Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
