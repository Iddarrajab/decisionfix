<x-app-layout title="{{ $page_meta['title'] }}">
    <x-slot name="heading">
        {{ $page_meta['title'] }}
    </x-slot>

    <x-slot name="body">
        <h1 class="text-xl font-semibold mb-4">{{ $page_meta['title'] }}</h1>

        {{-- Notifikasi sukses --}}
        @if(session('success'))
        <p class="text-green-600 mb-4">{{ session('success') }}</p>
        @endif

        {{-- Form Aturan --}}
        <form method="POST" action="{{ $page_meta['url'] }}">
            @csrf
            @method($page_meta['method'])

            {{-- Input: Kode Aturan --}}
            <div class="mb-4">
                <label for="code" class="block font-medium mb-1">Kode Aturan:</label>
                <input type="text" name="code" id="code"
                    value="{{ old('code', $aturan->code ?? '') }}"
                    class="border rounded px-3 py-2 w-full">
                @error('code')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dropdown: Penyakit --}}
            <div class="mb-4">
                <label for="penyakit_id" class="block font-medium mb-1">Nama Penyakit:</label>
                <select name="penyakit_id" id="penyakit_id" class="border rounded px-3 py-2 w-full">
                    <option value="">-- Pilih Penyakit --</option>
                    @foreach($penyakitList as $p)
                    <option value="{{ $p->id }}"
                        {{ old('penyakit_id', $aturan->penyakit_id ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->penyakit }}
                    </option>
                    @endforeach
                </select>
                @error('penyakit_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Daftar Gejala + Nilai CF --}}
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="font-medium">Pilih Gejala dan Nilai CF:</label>

                    {{-- Tombol Quick Set --}}
                    <select id="quick-set" class="border rounded px-2 py-1 text-sm">
                        <option value="">Quick Set CF</option>
                        <option value="0.1">0.1</option>
                        <option value="0.2">0.2</option>
                        <option value="0.3">0.3</option>
                        <option value="0.4">0.4</option>
                        <option value="0.5">0.5</option>
                        <option value="0.6">0.6</option>
                        <option value="0.7">0.7</option>
                        <option value="0.8">0.8</option>
                        <option value="0.9">0.9</option>
                        <option value="1.0">1.0</option>
                    </select>
                </div>

                <table class="w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 w-12 text-center">#</th>
                            <th class="border px-2 py-1 text-left">Nama Gejala</th>
                            <th class="border px-2 py-1 text-center w-32">Pilih</th>
                            <th class="border px-2 py-1 text-center w-24">CF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gejalaList as $index => $g)
                        @php
                        $isSelected = isset($aturan) && $aturan->gejala->contains($g->id);
                        $cf_value = $isSelected ? $aturan->gejala->find($g->id)->pivot->cf : '';
                        @endphp
                        <tr>
                            <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                            <td class="border px-2 py-1">{{ $g->gejala }}</td>
                            <td class="border px-2 py-1 text-center">
                                <input type="checkbox" name="gejala_ids[]" value="{{ $g->id }}"
                                    class="gejala-checkbox"
                                    {{ $isSelected ? 'checked' : '' }}>
                            </td>
                            <td class="border px-2 py-1 text-center">
                                <input type="number" step="0.1" min="0" max="1"
                                    name="cf[{{ $g->id }}]" id="cf-{{ $index }}"
                                    value="{{ $cf_value }}"
                                    class="border rounded w-20 text-center cf-input"
                                    {{ $isSelected ? '' : 'disabled' }}>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @error('gejala_ids')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <x-button>
                {{ $page_meta['button'] }}
            </x-button>
        </form>

        {{-- Script untuk Quick Set dan enable input CF --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.gejala-checkbox');
                const cfInputs = document.querySelectorAll('.cf-input');
                const quickSet = document.getElementById('quick-set');

                // Aktif/nonaktifkan input CF sesuai checkbox
                checkboxes.forEach((checkbox, index) => {
                    checkbox.addEventListener('change', function() {
                        cfInputs[index].disabled = !this.checked;
                        if (!this.checked) cfInputs[index].value = '';
                    });
                });

                // Quick Set: isi semua CF aktif
                quickSet.addEventListener('change', function() {
                    if (this.value) {
                        cfInputs.forEach((input, i) => {
                            if (!input.disabled) {
                                input.value = this.value;
                            }
                        });
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>