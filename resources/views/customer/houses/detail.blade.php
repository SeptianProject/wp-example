<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $house->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('dashboard') }}"
                            class="bg-blue-600 px-2 py-1 text-white rounded hover:bg-blue-700">
                            &lArr; Kembali
                        </a>
                    </div>
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="w-full md:w-1/2">
                            @if ($house->image)
                                <img src="{{ asset('storage/' . $house->image) }}" alt="{{ $house->nama }}"
                                    class="w-full h-auto rounded-lg shadow-md">
                            @else
                                <img src="https://via.placeholder.com/800x600" alt="{{ $house->nama }}"
                                    class="w-full h-auto rounded-lg shadow-md">
                            @endif
                        </div>

                        <div class="w-full md:w-1/2">
                            <h3 class="text-2xl font-bold mb-4">{{ $house->nama }}</h3>

                            @php
                                $hargaScore = $house
                                    ->kriteriaScores()
                                    ->whereHas('kriteria', function ($q) {
                                        $q->where('kode', 'C');
                                    })
                                    ->first();
                            @endphp

                            @if ($hargaScore)
                                <div class="mb-6">
                                    <span
                                        class="inline-block bg-blue-100 text-blue-800 text-lg font-medium px-3 py-1 rounded">
                                        Rp {{ number_format($hargaScore->nilai, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif

                            <div class="prose max-w-none mb-6">
                                <h4 class="text-lg font-semibold mb-2">Deskripsi</h4>
                                <p>{{ $house->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-lg font-semibold mb-2">Spesifikasi</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach ($house->kriteriaScores as $score)
                                        <div class="border rounded p-3">
                                            <p class="text-sm text-gray-600">{{ $score->kriteria->nama }}</p>
                                            <p class="font-medium">
                                                @if ($score->kriteria->kode == 'LT' || $score->kriteria->kode == 'LB')
                                                    {{ $score->nilai }} m²
                                                @elseif($score->kriteria->kode == 'C')
                                                    Rp {{ number_format($score->nilai, 0, ',', '.') }}
                                                @else
                                                    {{ $score->nilai }}
                                                @endif
                                            </p>
                                            @if ($score->keterangan)
                                                <p class="text-xs text-gray-500 mt-1">{{ $score->keterangan }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
