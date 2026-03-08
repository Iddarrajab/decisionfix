<x-app-layout title="Data Gejala">

    <x-slot name="heading">
        Data Gejala
    </x-slot>

    <x-slot name="body">
        @if(session('success'))
        <p class="text-green-600 mb-4">{{ session('success') }}</p>
        @endif

        <div class="sm:flex sm:items-center justify-between">
            <x-section-title>
                <x-slot name="title">Daftar Gejala</x-slot>
                <x-slot name="description">Tabel daftar gejala penyakit hewan</x-slot>
            </x-section-title>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <x-button as="a" href="{{ route('gejala.create') }}">
                    Tambah Gejala
                </x-button>
            </div>
        </div>

        <div class="mt-8 flow-root">
            <x-table class="min-w-full divide-y divide-gray-300 table-fixed">

                {{-- 🔥 PAKSA LEBAR KOLOM --}}
                <colgroup>
                    <col class="w-[100px]">
                    <col>
                    <col class="w-[180px]">
                    <col class="w-[140px]">
                </colgroup>

                <x-table.thead>
                    <tr>
                        <x-table.th>Kode</x-table.th>
                        <x-table.th>Nama Gejala</x-table.th>
                        <x-table.th>Tanggal Dibuat</x-table.th>
                        <x-table.th class="text-center">
                            Aksi
                        </x-table.th>
                    </tr>
                </x-table.thead>

                <x-table.tbody>
                    @forelse ($gejala as $item)
                    <tr class="hover:bg-gray-50">
                        <x-table.td>
                            {{ $item->code ?? '-' }}
                        </x-table.td>

                        <x-table.td>
                            {{ $item->gejala }}
                        </x-table.td>

                        <x-table.td>
                            {{ $item->created_at
                                    ? \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i')
                                    : '-' }}
                        </x-table.td>

                        {{-- AKSI BENAR-BENAR TENGAH --}}
                        <x-table.td>
                            <div class="flex justify-center gap-x-4">
                                <a href="{{ route('gejala.edit', $item) }}"
                                    class="text-yellow-600 hover:underline">
                                    Edit
                                </a>

                                <form action="{{ route('gejala.destroy', $item) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </x-table.td>
                    </tr>
                    @empty
                    <tr>
                        <x-table.td colspan="4"
                            class="text-center text-gray-500 py-4">
                            Belum ada data gejala.
                        </x-table.td>
                    </tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        </div>
    </x-slot>

</x-app-layout>