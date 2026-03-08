<x-app-layout title="{{ $page_meta['title'] }}">
    <x-slot name="heading">{{ $page_meta['title'] }}</x-slot>

    <x-slot name="body">
        <form method="POST" action="{{ $page_meta['url'] }}">
            @csrf
            @method($page_meta['method'])

            {{-- Node Code --}}
            <div class="mb-4">
                <label for="node_code" class="block font-medium mb-1">Kode Node:</label>
                <input type="text" name="node_code" id="node_code"
                    value="{{ old('node_code', $node->node_code ?? '') }}"
                    class="border rounded px-3 py-2 w-full">
                @error('node_code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Parent --}}
            <div class="mb-4">
                <label for="parent_id" class="block font-medium mb-1">Parent Node:</label>
                <select name="parent_id" id="parent_id" class="border rounded px-3 py-2 w-full">
                    <option value="">-- Pilih Parent --</option>
                    @foreach($parentList as $parent)
                    <option value="{{ $parent->id }}"
                        {{ old('parent_id', $node->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->node_code }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Gejala --}}
            <div class="mb-4">
                <label for="gejala_id" class="block font-medium mb-1">Gejala:</label>
                <select name="gejala_id" id="gejala_id" class="border rounded px-3 py-2 w-full">
                    <option value="">-- Pilih Gejala --</option>
                    @foreach($gejalaList as $gejala)
                    <option value="{{ $gejala->id }}"
                        {{ old('gejala_id', $node->gejala_id ?? '') == $gejala->id ? 'selected' : '' }}>
                        {{ $gejala->gejala }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Penyakit --}}
            <div class="mb-4">
                <label for="penyakit_id" class="block font-medium mb-1">Penyakit:</label>
                <select name="penyakit_id" id="penyakit_id" class="border rounded px-3 py-2 w-full">
                    <option value="">-- Pilih Penyakit --</option>
                    @foreach($penyakitList as $penyakit)
                    <option value="{{ $penyakit->id }}"
                        {{ old('penyakit_id', $node->penyakit_id ?? '') == $penyakit->id ? 'selected' : '' }}>
                        {{ $penyakit->penyakit }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Yes Branch --}}
            <div class="mb-4">
                <label for="yes_branch" class="block font-medium mb-1">Jawaban "Ya":</label>
                <input type="text" name="yes_branch" id="yes_branch"
                    value="{{ old('yes_branch', $node->yes_branch ?? '') }}"
                    class="border rounded px-3 py-2 w-full">
            </div>

            {{-- No Branch --}}
            <div class="mb-4">
                <label for="no_branch" class="block font-medium mb-1">Jawaban "Tidak":</label>
                <input type="text" name="no_branch" id="no_branch"
                    value="{{ old('no_branch', $node->no_branch ?? '') }}"
                    class="border rounded px-3 py-2 w-full">
            </div>

            {{-- Is Leaf --}}
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_leaf" value="1"
                        {{ old('is_leaf', $node->is_leaf ?? false) ? 'checked' : '' }}
                        class="form-checkbox">
                    <span class="ml-2">Node Akhir (Leaf)</span>
                </label>
            </div>

            <x-button>{{ $page_meta['button'] }}</x-button>
        </form>
    </x-slot>
</x-app-layout>