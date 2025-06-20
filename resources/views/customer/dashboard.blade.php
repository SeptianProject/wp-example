<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @php
                    $upcomingMeetings = Auth::user()->meetings()->where('date', '>', now())->orderBy('date')->get();
                @endphp

                @if ($upcomingMeetings->count() > 0)
                    <div id="meeting-notification" class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-blue-800">Jadwal Pertemuan</h3>
                                <div class="mt-1 text-sm text-blue-700">
                                    @foreach ($upcomingMeetings as $meeting)
                                        <p class="mb-1">
                                            <span
                                                class="font-semibold">{{ \Carbon\Carbon::parse($meeting->date)->format('d M Y H:i') }}</span>
                                            @if ($meeting->description)
                                                - {{ $meeting->description }}
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button"
                                        onclick="document.getElementById('meeting-notification').remove()"
                                        class="inline-flex rounded-md p-1.5 text-blue-500 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <span class="sr-only">Dismiss</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="p-6 text-gray-900">
                    {{ __('Hai, ') . Auth::user()->name . '!' }}
                </div>

                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Daftar Rumah Tersedia</h3>

                    <div class="space-y-6">
                        @forelse ($houses as $house)
                            <div class="flex flex-col md:flex-row border rounded-lg overflow-hidden shadow-md">
                                <div class="w-full md:w-1/3 h-64 md:h-auto">
                                    @if ($house->image)
                                        <img src="{{ asset('storage/' . $house->image) }}" alt="{{ $house->nama }}"
                                            class="w-full h-full object-cover object-left">
                                    @else
                                        <img src="https://via.placeholder.com/600x400" alt="{{ $house->nama }}"
                                            class="w-full h-full object-cover object-center">
                                    @endif
                                </div>

                                <div class="w-full md:w-2/3 p-4">
                                    <h4 class="text-xl font-bold mb-2">{{ $house->nama }}</h4>

                                    @php
                                        $hargaScore = $house
                                            ->kriteriaScores()
                                            ->whereHas('kriteria', function ($q) {
                                                $q->where('kode', 'C');
                                            })
                                            ->first();
                                    @endphp

                                    <div class="mb-4">
                                        @if ($hargaScore)
                                            <span
                                                class="inline-block bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded">
                                                Rp {{ number_format($hargaScore->nilai, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mb-6">
                                        <h5 class="text-md font-semibold text-gray-700 mb-2 border-b pb-1">Spesifikasi
                                            Rumah
                                        </h5>

                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                                            @php
                                                $kriterias = \App\Models\Kriteria::where('kode', '!=', 'C')
                                                    ->orderBy('id')
                                                    ->get();
                                            @endphp

                                            @foreach ($kriterias as $kriteria)
                                                @php
                                                    $nilaiKriteria = $house->kriteriaScores
                                                        ->where('kriteria_id', $kriteria->id)
                                                        ->first();

                                                    $value = '-';
                                                    if ($nilaiKriteria) {
                                                        $value = $nilaiKriteria->nilai;
                                                        if ($kriteria->kode == 'JTK') {
                                                            $value .= ' km';
                                                        } elseif ($kriteria->kode == 'LT' || $kriteria->kode == 'LB') {
                                                            $value .= ' m²';
                                                        }
                                                    }
                                                @endphp

                                                <div class="bg-gray-50 p-2 rounded">
                                                    <p class="text-xs text-gray-500">{{ $kriteria->nama }}</p>
                                                    <p class="font-semibold">{{ $value }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if ($house->description)
                                        <div class="text-gray-700 mb-4 text-sm">
                                            <h5 class="text-md font-semibold text-gray-700 mb-1 border-b pb-1">Deskripsi
                                            </h5>
                                            <div class="description-container" data-house-id="{{ $house->id }}">
                                                <p class="description-short">
                                                    {{ \Illuminate\Support\Str::limit($house->description, 150) }}</p>
                                                <p class="description-full hidden">{{ $house->description }}</p>
                                                @if (strlen($house->description) > 150)
                                                    <button type="button"
                                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-1 description-toggle"
                                                        data-house-id="{{ $house->id }}">
                                                        Lihat lebih banyak
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex justify-between items-center mt-4">
                                        <div>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox"
                                                    class="house-checkbox form-checkbox h-5 w-5 text-blue-600"
                                                    value="{{ $house->id }}" data-house-name="{{ $house->nama }}">
                                                <span class="ml-2 text-gray-700">Pilih untuk perbandingan</span>
                                            </label>
                                        </div>
                                        <a href="{{ route('dashboard.house.detail', ['id' => $house->id]) }}"
                                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p>Tidak ada rumah yang tersedia saat ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <form id="compare-form" method="POST" action="{{ route('compare.houses') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="house1" id="house1-input">
                    <input type="hidden" name="house2" id="house2-input">
                    <input type="hidden" name="house3" id="house3-input">
                </form>

                <div id="compare-button-container" class="fixed bottom-6 right-6 hidden">
                    <button id="compare-button" type="button"
                        class="px-6 py-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <span>Bandingkan <span id="selected-count">0</span> Rumah</span>
                    </button>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const houseCheckboxes = document.querySelectorAll('.house-checkbox');
                        const compareForm = document.getElementById('compare-form');
                        const compareButtonContainer = document.getElementById('compare-button-container');
                        const compareButton = document.getElementById('compare-button');
                        const selectedCountEl = document.getElementById('selected-count');
                        const houseInputs = [
                            document.getElementById('house1-input'),
                            document.getElementById('house2-input'),
                            document.getElementById('house3-input')
                        ];

                        // Add description toggle functionality
                        const descriptionToggles = document.querySelectorAll('.description-toggle');
                        descriptionToggles.forEach(function(toggle) {
                            toggle.addEventListener('click', function() {
                                const houseId = this.getAttribute('data-house-id');
                                const container = document.querySelector(
                                    `.description-container[data-house-id="${houseId}"]`);
                                const shortDesc = container.querySelector('.description-short');
                                const fullDesc = container.querySelector('.description-full');

                                shortDesc.classList.toggle('hidden');
                                fullDesc.classList.toggle('hidden');

                                if (this.textContent.trim() === 'Lihat lebih banyak') {
                                    this.textContent = 'Lihat lebih sedikit';
                                } else {
                                    this.textContent = 'Lihat lebih banyak';
                                }
                            });
                        });

                        function updateCompareButton() {
                            const checkedBoxes = Array.from(houseCheckboxes).filter(cb => cb.checked);
                            const checkedCount = checkedBoxes.length;

                            selectedCountEl.textContent = checkedCount;

                            if (checkedCount > 0) {
                                compareButtonContainer.classList.remove('hidden');
                            } else {
                                compareButtonContainer.classList.add('hidden');
                            }

                            if (checkedCount < 2 || checkedCount < 3) {
                                compareButton.disabled = true;
                                compareButton.classList.add('opacity-60', 'cursor-not-allowed');
                            } else {
                                compareButton.disabled = false;
                                compareButton.classList.remove('opacity-60', 'cursor-not-allowed');
                            }
                        }

                        function updateCheckboxState() {
                            const checkedCount = document.querySelectorAll('.house-checkbox:checked').length;

                            houseCheckboxes.forEach(function(checkbox) {
                                if (!checkbox.checked && checkedCount >= 3) {
                                    checkbox.disabled = true;
                                    checkbox.parentNode.classList.add('opacity-50', 'cursor-not-allowed');
                                } else {
                                    checkbox.disabled = false;
                                    checkbox.parentNode.classList.remove('opacity-50', 'cursor-not-allowed');
                                }
                            });
                        }

                        compareButton.addEventListener('click', function() {
                            const checkedBoxes = Array.from(houseCheckboxes)
                                .filter(cb => cb.checked)
                                .map(cb => cb.value);

                            checkedBoxes.forEach((houseId, index) => {
                                if (index < 3 && houseInputs[index]) {
                                    houseInputs[index].value = houseId;
                                }
                            });

                            compareForm.submit();
                        });

                        houseCheckboxes.forEach(function(checkbox) {
                            checkbox.addEventListener('change', function() {
                                const checkedCount = document.querySelectorAll('.house-checkbox:checked')
                                    .length;
                                if (checkedCount > 3 && this.checked) {
                                    this.checked = false;
                                    return;
                                }

                                updateCheckboxState();
                                updateCompareButton();
                            });
                        });

                        updateCheckboxState();
                        updateCompareButton();
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
