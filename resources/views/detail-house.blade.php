<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Perumahan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @isset($selectedHouses)
                    <div class="p-6 border-t">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Perumahan Yang Dipilih</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">Kriteria</th>
                                        @foreach ($selectedHouses as $house)
                                            <th class="py-2 px-4 border-b">{{ $house->nama }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-2 px-4 border-b font-medium">Harga</td>
                                        @foreach ($selectedHouses as $house)
                                            <td class="py-2 px-4 border-b">
                                                Rp {{ number_format($house->harga, 0, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b font-medium">Luas Tanah</td>
                                        @foreach ($selectedHouses as $house)
                                            <td class="py-2 px-4 border-b">{{ $house->luas_tanah }} m²</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b font-medium">Luas Bangunan</td>
                                        @foreach ($selectedHouses as $house)
                                            <td class="py-2 px-4 border-b">{{ $house->luas_bangunan }} m²</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b font-medium">Jumlah Fasilitas</td>
                                        @foreach ($selectedHouses as $house)
                                            <td class="py-2 px-4 border-b">{{ $house->jumlah_fasilitas }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4 border-b font-medium">Jarak Tempuh</td>
                                        @foreach ($selectedHouses as $house)
                                            <td class="py-2 px-4 border-b">{{ $house->jarak_tempuh }} km</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endisset

                @isset($wpResults)
                    <!-- Matriks Normalisasi -->
                    <div class="p-6 border-t">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Matriks Normalisasi</h3>
                        <p class="mb-4 text-gray-600">Tabel berikut menunjukkan nilai kriteria sebelum dilakukan
                            perhitungan.
                        </p>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">Kriteria</th>
                                        <th class="py-2 px-4 border-b">Bobot</th>
                                        <th class="py-2 px-4 border-b">Jenis</th>
                                        @foreach ($selectedHouses as $house)
                                            <th class="py-2 px-4 border-b">{{ $house->nama }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Daftar kriteria yang digunakan dalam perhitungan
                                        $criteriaList = [
                                            ['field' => 'harga', 'label' => 'Harga', 'kode' => 'C', 'type' => 'cost'],
                                            [
                                                'field' => 'luas_tanah',
                                                'label' => 'Luas Tanah',
                                                'kode' => 'LT',
                                                'type' => 'benefit',
                                            ],
                                            [
                                                'field' => 'luas_bangunan',
                                                'label' => 'Luas Bangunan',
                                                'kode' => 'LB',
                                                'type' => 'benefit',
                                            ],
                                            [
                                                'field' => 'jarak_tempuh',
                                                'label' => 'Jarak Tempuh',
                                                'kode' => 'JTK',
                                                'type' => 'cost',
                                            ],
                                            [
                                                'field' => 'jumlah_fasilitas',
                                                'label' => 'Jumlah Fasilitas',
                                                'kode' => 'F',
                                                'type' => 'benefit',
                                            ],
                                        ];

                                        // Ambil kriteria dari database
                                        $kriterias = \App\Models\Kriteria::all()->keyBy('kode');
                                    @endphp

                                    @foreach ($criteriaList as $criterion)
                                        <tr>
                                            <td class="py-2 px-4 border-b font-medium">{{ $criterion['label'] }}</td>
                                            <td class="py-2 px-4 border-b">
                                                @if (isset($kriterias[$criterion['kode']]))
                                                    {{ number_format($kriterias[$criterion['kode']]->bobot, 2) }}
                                                @else
                                                    @if ($criterion['type'] == 'cost')
                                                        {{ $criterion['kode'] == 'C' ? '0.30' : '0.05' }}
                                                    @else
                                                        {{ $criterion['kode'] == 'LT' || $criterion['kode'] == 'LB' ? '0.15' : '0.10' }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full {{ $criterion['type'] == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $criterion['type'] == 'benefit' ? 'Benefit' : 'Cost' }}
                                                </span>
                                            </td>
                                            @foreach ($selectedHouses as $house)
                                                <td class="py-2 px-4 border-b">
                                                    @if ($criterion['field'] == 'harga')
                                                        {{ number_format($house->{$criterion['field']}, 0, ',', '.') }}
                                                    @else
                                                        {{ $house->{$criterion['field']} }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Matriks Perhitungan Vector S -->
                    <div class="p-6 border-t">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Matriks Perhitungan Vector S</h3>
                        <p class="mb-4 text-gray-600">Tabel berikut menunjukkan perhitungan nilai Vector S untuk setiap
                            kriteria
                            dan alternatif rumah.</p>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">Alternatif</th>
                                        @foreach ($criteriaList as $criterion)
                                            <th class="py-2 px-4 border-b">{{ $criterion['label'] }}</th>
                                        @endforeach
                                        <th class="py-2 px-4 border-b">Vector S</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedHouses as $index => $house)
                                        <tr>
                                            <td class="py-2 px-4 border-b font-medium">{{ $house->nama }}</td>
                                            @foreach ($criteriaList as $criterion)
                                                <td class="py-2 px-4 border-b">
                                                    @php
                                                        $weight = isset($kriterias[$criterion['kode']])
                                                            ? $kriterias[$criterion['kode']]->bobot
                                                            : ($criterion['type'] == 'cost'
                                                                ? ($criterion['kode'] == 'C'
                                                                    ? 0.3
                                                                    : 0.05)
                                                                : ($criterion['kode'] == 'LT' ||
                                                                $criterion['kode'] == 'LB'
                                                                    ? 0.15
                                                                    : 0.1));

                                                        $value = $house->{$criterion['field']};

                                                        if ($criterion['type'] == 'cost') {
                                                            $calculation = pow(1 / max(0.001, $value), $weight);
                                                            echo "(1/{$value})<sup>{$weight}</sup> = " .
                                                                number_format($calculation, 4);
                                                        } else {
                                                            $calculation = pow($value, $weight);
                                                            echo "({$value})<sup>{$weight}</sup> = " .
                                                                number_format($calculation, 4);
                                                        }
                                                    @endphp
                                                </td>
                                            @endforeach
                                            <td class="py-2 px-4 border-b font-bold">
                                                {{ number_format($wpResults[$index]['vector_s'], 4) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-100">
                                        <td class="py-2 px-4 border-b font-medium"
                                            colspan="{{ count($criteriaList) + 1 }}">Jumlah
                                            Total Vector S</td>
                                        <td class="py-2 px-4 border-b font-bold">
                                            @php
                                                $totalVectorS = array_sum(array_column($wpResults, 'vector_s'));
                                                echo number_format($totalVectorS, 4);
                                            @endphp
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Matriks Perhitungan Vector V -->
                    <div class="p-6 border-t">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Matriks Perhitungan Vector V</h3>
                        <p class="mb-4 text-gray-600">Tabel berikut menunjukkan perhitungan nilai Vector V untuk setiap
                            alternatif
                            rumah.</p>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">Alternatif</th>
                                        <th class="py-2 px-4 border-b">Vector S</th>
                                        <th class="py-2 px-4 border-b">Total Vector S</th>
                                        <th class="py-2 px-4 border-b">Perhitungan</th>
                                        <th class="py-2 px-4 border-b">Vector V</th>
                                        <th class="py-2 px-4 border-b">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalVectorS = array_sum(array_column($wpResults, 'vector_s'));
                                    @endphp

                                    @foreach ($selectedHouses as $index => $house)
                                        <tr>
                                            <td class="py-2 px-4 border-b font-medium">{{ $house->nama }}</td>
                                            <td class="py-2 px-4 border-b">
                                                {{ number_format($wpResults[$index]['vector_s'], 4) }}</td>
                                            <td class="py-2 px-4 border-b">{{ number_format($totalVectorS, 4) }}</td>
                                            <td class="py-2 px-4 border-b">
                                                {{ number_format($wpResults[$index]['vector_s'], 4) }} /
                                                {{ number_format($totalVectorS, 4) }}
                                            </td>
                                            <td class="py-2 px-4 border-b font-bold">
                                                {{ number_format($wpResults[$index]['vector_v'], 4) }}
                                            </td>
                                            <td class="py-2 px-4 border-b font-bold">
                                                {{ number_format($wpResults[$index]['percentage'], 2) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Hasil Akhir Perankingan -->
                    <div class="p-6 border-t">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Hasil Perhitungan Metode Weight Product (WP)</h3>

                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="font-bold text-blue-700">Rekomendasi Terbaik:</h4>
                            <p class="text-blue-700">Berdasarkan perhitungan, <span
                                    class="font-bold">{{ $wpResults[0]['house_name'] }}</span> adalah rumah yang paling
                                direkomendasikan dengan nilai {{ number_format($wpResults[0]['percentage'], 2) }}%.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">Peringkat</th>
                                        <th class="py-2 px-4 border-b">Nama Rumah</th>
                                        <th class="py-2 px-4 border-b">Vector S</th>
                                        <th class="py-2 px-4 border-b">Vector V</th>
                                        <th class="py-2 px-4 border-b">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wpResults as $index => $result)
                                        <tr class="{{ $index === 0 ? 'bg-green-50' : '' }}">
                                            <td class="py-2 px-4 border-b font-medium">{{ $index + 1 }}</td>
                                            <td class="py-2 px-4 border-b">{{ $result['house_name'] }}</td>
                                            <td class="py-2 px-4 border-b">{{ number_format($result['vector_s'], 4) }}</td>
                                            <td class="py-2 px-4 border-b">{{ number_format($result['vector_v'], 4) }}</td>
                                            <td class="py-2 px-4 border-b">{{ number_format($result['percentage'], 2) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="font-medium text-gray-700 mb-2">Keterangan:</h4>
                            <ul class="list-disc ml-5 text-sm text-gray-600">
                                <li>Vector S: nilai vektor S dari perhitungan WP untuk setiap alternatif</li>
                                <li>Vector V: nilai preferensi akhir yang digunakan untuk peringkat alternatif</li>
                                <li>Persentase: nilai Vector V dalam bentuk persentase</li>
                            </ul>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('dashboard') }}"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>
</x-app-layout>
