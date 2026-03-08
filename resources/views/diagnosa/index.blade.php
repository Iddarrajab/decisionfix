<x-app-layout title="{{ $page_meta['title'] }}">
    <x-slot name="heading">{{ $page_meta['title'] }}</x-slot>

    <x-slot name="body">
        <h1 class="text-xl font-semibold mb-4">{{ $page_meta['title'] }}</h1>

        @if(session('success'))
        <p class="text-green-600 mb-4">{{ session('success') }}</p>
        @endif

        <a href="{{ route('diagnosa.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Diagnosa Baru
        </a>

        <table class="w-full border border-gray-300 rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nama Pengguna</th>
                    <th class="px-4 py-2 border">Penyakit Hasil Diagnosa</th>
                    <th class="px-4 py-2 border">Hasil CF (%)</th>
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diagnosas as $diagnosa)
                <tr>
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $diagnosa->nama_user }}</td>
                    <td class="px-4 py-2 border">{{ $diagnosa->penyakit->penyakit ?? '-' }}</td>
                    <td class="px-4 py-2 border">
                        @if($diagnosa->hasil_cf)
                            {{ number_format($diagnosa->hasil_cf * 100, 2) }}%
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-2 border">{{ $diagnosa->created_at->format('d-m-Y H:i') }}</td>
                    <td class="px-4 py-2 border flex space-x-2">
                        <a href="{{ route('diagnosa.show', $diagnosa->id) }}"
                            class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">Lihat</a>
                            @auth
                        <form action="{{ route('diagnosa.destroy', $diagnosa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                        </form>
                        @endauth
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center border">Belum ada data diagnosa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot>
</x-app-layout>