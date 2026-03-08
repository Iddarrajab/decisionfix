<x-app-layout title="{{ $page_meta['title'] }}">
    <x-slot name="heading">
        {{ $page_meta['title'] }}
    </x-slot>

    <x-slot name="body">
        <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">

            <h2 class="text-xl font-semibold mb-6">
                Detail Penyakit
            </h2>

            <div class="space-y-4">

                {{-- Kode Penyakit --}}
                <div>
                    <label class="font-medium text-gray-700">
                        Kode Penyakit
                    </label>
                    <p class="text-gray-900 mt-1 font-semibold">
                        {{ $penyakit->kode_penyakit ?? $penyakit->kode ?? '-' }}
                    </p>
                </div>

                {{-- Nama Penyakit --}}
                <div>
                    <label class="font-medium text-gray-700">
                        Nama Penyakit
                    </label>
                    <p class="text-gray-900 mt-1 font-semibold">
                        {{ $penyakit->nama_penyakit ?? $penyakit->penyakit ?? '-' }}
                    </p>
                </div>

                {{-- Solusi --}}
                <div>
                    <label class="font-medium text-gray-700">
                        Solusi
                    </label>
                    <p class="text-gray-900 mt-1 whitespace-pre-line">
                        {{ $penyakit->solusi ?? '-' }}
                    </p>
                </div>

                {{-- Obat --}}
                <div>
                    <label class="font-medium text-gray-700">
                        Obat
                    </label>
                    <p class="text-gray-900 mt-1 whitespace-pre-line">
                        {{ $penyakit->obat ?? '-' }}
                    </p>
                </div>

            </div>

            <div class="mt-6 flex gap-3">
                <x-button as="a" href="{{ route('penyakit.index') }}">
                    Kembali
                </x-button>

                <x-button as="a" href="{{ route('penyakit.edit', $penyakit) }}" variant="secondary">
                    Edit
                </x-button>
            </div>

        </div>
    </x-slot>
</x-app-layout>