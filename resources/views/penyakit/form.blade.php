<x-app-layout title="{{ $page_meta['title'] }}">
    <x-slot name="heading">
        {{ $page_meta['title'] }}
    </x-slot>

    <x-slot name="body">
        <h1 class="text-xl font-semibold mb-4">{{ $page_meta['title'] }}</h1>

        @if(session('success'))
        <p class="text-green-600 mb-4">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ $page_meta['url'] }}">
            @csrf
            @method($page_meta['method'])

            {{-- Kode Penyakit --}}
            <div class="mb-4">
                <label for="code" class="block font-medium mb-1">Kode Penyakit:</label>
                <input type="text" value="{{ old('code', $penyakit->code ?? '') }}" name="code" id="code"
                    class="border rounded px-3 py-2 w-full">
                @error('code')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Penyakit --}}
            <div class="mb-4">
                <label for="penyakit" class="block font-medium mb-1">Nama Penyakit:</label>
                <input type="text" value="{{ old('penyakit', $penyakit->penyakit ?? '') }}" name="penyakit" id="penyakit"
                    class="border rounded px-3 py-2 w-full">
                @error('penyakit')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Solusi --}}
            <div class="mb-4">
                <label for="solusi" class="block font-medium mb-1">Solusi:</label>
                <textarea name="solusi" id="solusi" rows="4"
                    class="border rounded px-3 py-2 w-full">{{ old('solusi', $penyakit->solusi ?? '') }}</textarea>
                @error('solusi')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Obat --}}
            <div class="mb-6">
                <label for="obat" class="block font-medium mb-1">Obat:</label>
                <textarea name="obat" id="obat" rows="3"
                    class="border rounded px-3 py-2 w-full">{{ old('obat', $penyakit->obat ?? '') }}</textarea>
                @error('obat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <x-button>
                {{ $page_meta['button'] }}
            </x-button>
        </form>
    </x-slot>
</x-app-layout>