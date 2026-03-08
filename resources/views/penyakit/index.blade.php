<x-app-layout title="Penyakit Kucing">

    <x-slot name="heading">
        Penyakit Kucing
    </x-slot>

    <x-slot name="body">
        <div class="sm:flex sm:items-center justify-between">
            <x-section-title>
                <x-slot name="title">Data Penyakit</x-slot>
                <x-slot name="description">Tabel daftar penyakit kucing beserta solusi dan obat</x-slot>
            </x-section-title>

            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <x-button as="a" href="{{ route('penyakit.create') }}">
                    Tambah Penyakit
                </x-button>
            </div>
        </div>

        <div class="mt-8 flow-root">
            <x-table class="min-w-full divide-y divide-gray-300">
                <x-table.thead>
                    <tr>
                        <x-table.th>Kode</x-table.th>
                        <x-table.th>Nama Penyakit</x-table.th>
                        <x-table.th>Solusi</x-table.th>
                        <x-table.th>Obat</x-table.th>
                        <x-table.th>Tanggal Dibuat</x-table.th>
                        <x-table.th>Aksi</x-table.th>
                    </tr>
                </x-table.thead>

                <x-table.tbody>
                    @forelse ($penyakit as $item)
                    <tr class="hover:bg-gray-50">
                        <x-table.td class="px-3 py-2 font-semibold">{{ $item->code ?? '-' }}</x-table.td>
                        <x-table.td class="px-3 py-2">{{ $item->penyakit }}</x-table.td>
                        <x-table.td class="px-3 py-2 text-sm">{{ $item->solusi ? Str::limit($item->solusi, 100) : '-' }}</x-table.td>
                        <x-table.td class="px-3 py-2 text-sm">{{ $item->obat ? Str::limit($item->obat, 100) : '-' }}</x-table.td>
                        <x-table.td class="px-3 py-2">{{ $item->created_at->format('d-m-Y H:i') }}</x-table.td>
                        <x-table.td class="px-3 py-2">
                            <div class="flex justify-center gap-x-2">
                                <a href="{{ route('penyakit.show', $item) }}" class="text-blue-600 hover:underline">Lihat</a>
                                <a href="{{ route('penyakit.edit', $item) }}" class="text-yellow-600 hover:underline">Edit</a>
                                <form action="{{ route('penyakit.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </x-table.td>
                    </tr>
                    @empty
                    <tr>
                        <x-table.td colspan="6" class="text-center text-gray-500 py-4">
                            Belum ada data penyakit.
                        </x-table.td>
                    </tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        </div>
    </x-slot>
</x-app-layout>