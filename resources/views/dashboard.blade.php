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
                                    @foreach ($houses as $house)
                                        @foreach ($house->kriteria as $kriteria)
                                            <th class="py-2 px-4 border">{{ $kriteria->nama }}</th>
                                        @endforeach
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($houses as $house)
                                    <tr class="hover:bg-gray-100">
                                        <td class="py-2 px-4 border">{{ $house->nama }}</td>
                                        @foreach ($house->kriteriaScores as $kriteriaScore)
                                            <td class="py-2 px-4 border">{{ $kriteriaScore->nilai }}</td>
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
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Rumah --</option>
                                    @foreach ($houses as $house)
                                        <option value="{{ $house->id }}">{{ $house->nama }}
                                            ({{ $house->lokasi }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('house1')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="house2" :value="__('Pilihan Rumah 2')" />
                                <select id="house2" name="house2"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Rumah --</option>
                                    @foreach ($houses as $house)
                                        <option value="{{ $house->id }}">{{ $house->nama }}
                                            ({{ $house->lokasi }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('house2')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="house3" :value="__('Pilihan Rumah 3')" />
                                <select id="house3" name="house3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Rumah --</option>
                                    @foreach ($houses as $house)
                                        <option value="{{ $house->id }}">{{ $house->nama }}
                                            ({{ $house->lokasi }})
                                        </option>
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
