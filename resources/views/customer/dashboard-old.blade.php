<x-app-layout>
     <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Dashboard') }}
         </h2>
     </x-slot>
 
     <div class="py-12">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900">
                     {{ __('Hai, ') . Auth::user()->name . '!' }}
                 </div>
 
                 <div class="p-6">
                     <h3 class="text-lg font-medium text-gray-700 mb-4">Daftar Rumah Tersedia</h3>
                     <div class="overflow-x-auto">
                         <table class="min-w-full bg-white border border-gray-200">
                             <thead>
                                 <tr>
                                     <th class="py-2 px-4 border">Nama</th>
                                     @php
                                         $kriterias = \App\Models\Kriteria::orderBy('id')->get();
                                     @endphp
                                     @foreach ($kriterias as $kriteria)
                                         <th class="py-2 px-4 border">{{ $kriteria->nama }}</th>
                                     @endforeach
                                 </tr>
                             </thead>
                             <tbody>
                                 @foreach ($houses as $house)
                                     <tr class="hover:bg-gray-100">
                                         <td class="py-2 px-4 border">{{ $house->nama }}</td>
                                         @foreach ($kriterias as $kriteria)
                                             <td class="py-2 px-4 border">
                                                 @php
                                                     $nilaiKriteria = $house->kriteriaScores
                                                         ->where('kriteria_id', $kriteria->id)
                                                         ->first();
                                                 @endphp
 
                                                 @if ($nilaiKriteria)
                                                     @if ($kriteria->kode == 'C')
                                                         Rp {{ number_format($nilaiKriteria->nilai, 0, ',', '.') }}
                                                     @elseif ($kriteria->kode == 'LT' || $kriteria->kode == 'LB')
                                                         {{ $nilaiKriteria->nilai }} m²
                                                     @elseif ($kriteria->kode == 'JTK')
                                                         {{ $nilaiKriteria->nilai }} km
                                                     @else
                                                         {{ $nilaiKriteria->nilai }}
                                                     @endif
                                                 @else
                                                     <span class="text-gray-400">-</span>
                                                 @endif
                                             </td>
                                         @endforeach
                                     </tr>
                                 @endforeach
                             </tbody>
                         </table>
                     </div>
                 </div>
 
                 <div class="p-6">
                     <h3 class="text-lg font-medium text-gray-700 mb-4">Pilih Rumah untuk Dibandingkan</h3>
                     <form method="POST" action="{{ route('compare.houses') }}" class="space-y-4">
                         @csrf
 
                         <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                             <div>
                                 <x-input-label for="house1" :value="__('Pilihan Rumah 1')" />
                                 <select id="house1" name="house1"
                                     class="house-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                     <option value="">-- Pilih Rumah --</option>
                                     @foreach ($houses as $house)
                                         <option value="{{ $house->id }}">{{ $house->nama }}</option>
                                     @endforeach
                                 </select>
                                 <x-input-error :messages="$errors->get('house1')" class="mt-2" />
                             </div>
 
                             <div>
                                 <x-input-label for="house2" :value="__('Pilihan Rumah 2')" />
                                 <select id="house2" name="house2"
                                     class="house-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                     <option value="">-- Pilih Rumah --</option>
                                     @foreach ($houses as $house)
                                         <option value="{{ $house->id }}">{{ $house->nama }}</option>
                                     @endforeach
                                 </select>
                                 <x-input-error :messages="$errors->get('house2')" class="mt-2" />
                             </div>
 
                             <div>
                                 <x-input-label for="house3" :value="__('Pilihan Rumah 3')" />
                                 <select id="house3" name="house3"
                                     class="house-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                     <option value="">-- Pilih Rumah --</option>
                                     @foreach ($houses as $house)
                                         <option value="{{ $house->id }}">{{ $house->nama }}</option>
                                     @endforeach
                                 </select>
                                 <x-input-error :messages="$errors->get('house3')" class="mt-2" />
                             </div>
                         </div>
 
                         <div class="flex items-center justify-end mt-4">
                             <x-primary-button type="submit" class="ml-4">
                                 {{ __('Bandingkan Rumah') }}
                             </x-primary-button>
                         </div>
                     </form>
 
                     <script>
                         document.addEventListener('DOMContentLoaded', function() {
                             const houseSelects = document.querySelectorAll('.house-select');
 
                             function updateAvailableOptions() {
                                 const selectedValues = Array.from(houseSelects)
                                     .map(select => select.value)
                                     .filter(value => value !== "");
 
                                 houseSelects.forEach(function(select) {
                                     const currentValue = select.value;
 
                                     Array.from(select.options).forEach(function(option) {
                                         if (option.value === "") return;
  
                                         if (option.value !== currentValue &&
                                             selectedValues.includes(option.value)) {
                                             option.disabled = true;
                                             option.style.color = "#999";
                                         } else {
                                             option.disabled = false;
                                             option.style.color = "";
                                         }
                                     });
                                 });
                             }
                             houseSelects.forEach(function(select) {
                                 select.addEventListener('change', updateAvailableOptions);
                             });
 
                             updateAvailableOptions();
                         });
                     </script>
                 </div>
             </div>
         </div>
     </div>
 </x-app-layout>
 