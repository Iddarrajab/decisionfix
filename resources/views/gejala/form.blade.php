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

            {{-- Hanya tambahkan @method('PUT') jika edit --}}
            @if(strtoupper($page_meta['method']) === 'PUT')
            @method('PUT')
            @endif

            {{-- Gejala --}}
            <div class="mb-4">
                <label for="gejala" class="block font-medium mb-1">Nama Gejala:</label>
                <input
                    type="text"
                    name="gejala"
                    id="gejala"
                    value="{{ old('gejala', $gejala->gejala ?? '') }}"
                    class="border rounded px-3 py-2 w-full">
                @error('gejala')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Code --}}
            <div class="mb-4">
                <label for="code" class="block font-medium mb-1">Kode Gejala:</label>
                <input
                    type="text"
                    name="code"
                    id="code"
                    value="{{ old('code', $gejala->code ?? '') }}"
                    class="border rounded px-3 py-2 w-full">
                @error('code')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <x-button class="mt-6">
                {{ $page_meta['button'] }}
            </x-button>
        </form>
    </x-slot>
</x-app-layout>