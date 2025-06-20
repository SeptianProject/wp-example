<div class="p-4 mt-4 bg-gray-50 rounded-lg border border-gray-200">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Bobot Kriteria</h3>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $kriterias = \App\Models\Kriteria::all();
                    $totalWeight = $kriterias->sum('bobot');
                @endphp

                @foreach ($kriterias as $kriteria)
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ $kriteria->nama }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $kriteria->kode }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($kriteria->bobot, 2) }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $kriteria->type === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $kriteria->type }}
                            </span>
                        </td>
                    </tr>
                @endforeach

                <tr class="bg-gray-50">
                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900" colspan="2">Total
                        Bobot:</td>
                    <td
                        class="px-3 py-2 whitespace-nowrap text-sm font-medium 
                        {{ abs($totalWeight - 1) < 0.0001 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($totalWeight) }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-3 text-sm text-gray-600">
        <p>Catatan: Total bobot semua kriteria harus berjumlah 1. Bobot akan otomatis disesuaikan saat menyimpan.</p>
    </div>
</div>
