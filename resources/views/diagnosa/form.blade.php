<x-app-layout title="{{ $page_meta['title'] }}">
    <x-slot name="heading">{{ $page_meta['title'] }}</x-slot>

    <x-slot name="body">
        <h1 class="text-xl font-semibold mb-4">{{ $page_meta['title'] }}</h1>

        {{-- Pesan Error --}}
        @if (session('error'))
        <p class="text-red-600 mb-4">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('diagnosa.store') }}">
            @csrf
            @method($page_meta['method'] ?? 'POST')

            {{-- Nama Pengguna --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">
                    Nama Pengguna / Hewan
                </label>
                <input type="text" name="nama_user"
                    value="{{ old('nama_user', $diagnosa->nama_user ?? '') }}"
                    class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500">
                @error('nama_user')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cari Gejala --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">
                    Cari Gejala
                </label>
                <input type="text" id="searchGejala"
                    placeholder="Ketik nama gejala..."
                    class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- TABEL GEJALA --}}
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2 text-center w-12">No</th>
                            <th class="border px-3 py-2">Gejala</th>
                            <th class="border px-3 py-2 text-center w-20">Pilih</th>
                            <th class="border px-3 py-2 text-center w-40">
                                Tingkat Keyakinan
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gejalaList as $index => $gejala)
                        <tr class="gejala-row hover:bg-gray-50">
                            {{-- No --}}
                            <td class="border px-3 py-2 text-center">
                                {{ $index + 1 }}
                            </td>

                            {{-- Nama Gejala --}}
                            <td class="border px-3 py-2 gejala-text">
                                {{ $gejala->nama_gejala ?? $gejala->gejala }}
                            </td>

                            {{-- Checkbox --}}
                            <td class="border px-3 py-2 text-center">
                                <input type="checkbox"
                                    name="gejala_dipilih[]"
                                    value="{{ $gejala->id }}"
                                    class="form-checkbox text-blue-600">
                            </td>

                            {{-- Dropdown CF (TANPA ANGKA) --}}
                            <td class="border px-3 py-2 text-center">
                                <select name="cf_gejala[{{ $gejala->id }}]"
                                    class="border rounded px-2 py-1 text-sm">
                                    <option value="">Pilih Keyakinan</option>
                                    <option value="0.4">Mungkin</option>
                                    <option value="0.6">Kemungkinan Besar</option>
                                    <option value="0.8">Hampir Pasti</option>
                                    <option value="1.0">Pasti</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @error('gejala_dipilih')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <x-button type="submit">
                {{ $page_meta['button'] ?? 'Diagnosa Sekarang' }}
            </x-button>
        </form>

        {{-- SCRIPT SEARCH --}}
        <script>
            document.getElementById('searchGejala').addEventListener('keyup', function() {
                const keyword = this.value.toLowerCase();
                const rows = document.querySelectorAll('.gejala-row');

                rows.forEach(row => {
                    const text = row.querySelector('.gejala-text').textContent.toLowerCase();
                    row.style.display = text.includes(keyword) ? '' : 'none';
                });
            });
        </script>
    </x-slot>
</x-app-layout>