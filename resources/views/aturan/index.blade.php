<x-app-layout title="Basis Aturan Keputusan">
    <x-slot name="heading">
        Basis Aturan Keputusan
    </x-slot>

    <x-slot name="body">
        {{-- Header --}}
        <div class="sm:flex sm:items-center justify-between">
            <x-section-title>
                <x-slot name="title">Basis Aturan</x-slot>
                <x-slot name="description">
                    Daftar aturan diagnosis penyakit hewan berdasarkan metode
                    <b>Decision Tree</b> dan <b>Certainty Factor</b>.
                </x-slot>
            </x-section-title>

            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <x-button as="a" href="{{ route('aturan.create') }}">
                    Tambah Aturan
                </x-button>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="mt-8 max-w-full flow-root">
            <x-table class="min-w-full divide-y divide-gray-300 table-fixed">
                <x-table.thead>
                    <tr>
                        <x-table.th class="px-3 py-2 w-20 text-center">Kode</x-table.th>
                        <x-table.th class="px-3 py-2 w-48">Penyakit</x-table.th>
                        <x-table.th class="px-3 py-2 w-80">Node & Gejala (CF)</x-table.th>
                        <x-table.th class="px-3 py-2 w-40">Obat</x-table.th>
                        <x-table.th class="px-3 py-2 w-48">Solusi</x-table.th>
                        <x-table.th class="px-3 py-2 w-24 text-center">Aksi</x-table.th>
                    </tr>
                </x-table.thead>

                <x-table.tbody>
                    @forelse ($data as $aturan)
                    <tr class="hover:bg-gray-50">
                        {{-- Kode Aturan --}}
                        <x-table.td class="px-3 py-2 text-center font-semibold text-gray-800">
                            {{ $aturan->code }}
                        </x-table.td>

                        {{-- Penyakit --}}
                        <x-table.td class="px-3 py-2 font-semibold">
                            {{ $aturan->penyakit->penyakit ?? '-' }}
                        </x-table.td>

                        {{-- Gejala dengan CF --}}
                        <x-table.td class="px-3 py-2 text-sm leading-relaxed">
                            @if ($aturan->gejala && $aturan->gejala->isNotEmpty())
                            @foreach ($aturan->gejala as $gejala)
                            <div class="mb-2 border-b border-dashed border-gray-200 pb-1">
                                <div class="font-medium text-gray-800">
                                    {{ $gejala->gejala ?? $gejala->nama_gejala ?? 'Gejala #' . $gejala->id }}
                                </div>
                                @if ($gejala->pivot && $gejala->pivot->cf)
                                <div class="ml-2 text-gray-600">
                                    <span class="text-gray-500">CF: <strong>{{ number_format($gejala->pivot->cf, 2) }}</strong></span>
                                </div>
                                @else
                                <div class="ml-2 text-gray-400 italic text-xs">CF belum diatur</div>
                                @endif
                            </div>
                            @endforeach
                            @else
                            <div class="text-gray-500 italic">Belum ada gejala terkait.</div>
                            @endif
                        </x-table.td>

                        {{-- Obat --}}
                        <x-table.td class="px-3 py-2 text-sm break-words">
                            {{ $aturan->penyakit->obat ?? '-' }}
                        </x-table.td>

                        {{-- Solusi --}}
                        <x-table.td class="px-3 py-2 text-sm break-words">
                            {{ $aturan->penyakit->solusi ?? '-' }}
                        </x-table.td>

                        {{-- Aksi --}}
                        <x-table.td class="px-3 py-2 text-sm text-center">
                            <div class="flex justify-center gap-x-3">
                                <a href="{{ route('aturan.edit', $aturan) }}"
                                    class="text-yellow-600 hover:text-yellow-800 font-medium">
                                    Edit
                                </a>

                                <form action="{{ route('aturan.destroy', $aturan) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus aturan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </x-table.td>
                    </tr>
                    @empty
                    <tr>
                        <x-table.td colspan="6" class="text-center text-gray-500 py-4">
                            Belum ada data aturan diagnosis.
                        </x-table.td>
                    </tr>
                    @endforelse
                </x-table.tbody>
            </x-table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $data->links() }}
            </div>
        </div>
    </x-slot>
</x-app-layout>