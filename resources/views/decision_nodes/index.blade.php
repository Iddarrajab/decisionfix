<x-app-layout title="Decision Tree Diagnosis">
    <x-slot name="heading">
        Decision Tree Diagnosis
    </x-slot>

    <x-slot name="body">
        {{-- Header Section --}}
        <div class="sm:flex sm:items-center justify-between mb-6">
            <x-section-title>
                <x-slot name="title">Struktur Decision Tree</x-slot>
                <x-slot name="description">
                    Menampilkan seluruh node dari <b>pohon keputusan</b> diagnosis penyakit
                    berbasis metode <b>Decision Tree</b> dan <b>Certainty Factor</b>.
                </x-slot>
            </x-section-title>

            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <x-button as="a" href="{{ route('decision-nodes.create') }}">
                    + Tambah Node
                </x-button>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="max-w-full flow-root bg-white shadow rounded-2xl p-4">
            <x-table class="min-w-full divide-y divide-gray-300 table-fixed text-sm">
                <x-table.thead>
                    <tr>
                        <x-table.th class="px-2 py-2 w-12 text-center">ID</x-table.th>
                        <x-table.th class="px-2 py-2 w-32">Parent Node</x-table.th>
                        <x-table.th class="px-2 py-2 w-20 text-center">Branch</x-table.th>
                        <x-table.th class="px-2 py-2 w-64">Pertanyaan (Gejala)</x-table.th>
                        <x-table.th class="px-2 py-2 w-64">Penyakit (Node Akhir)</x-table.th>
                        <x-table.th class="px-2 py-2 w-20 text-center">Leaf</x-table.th>
                        <x-table.th class="px-2 py-2 w-28 text-center">Aksi</x-table.th>
                    </tr>
                </x-table.thead>

                <x-table.tbody>
                    @forelse ($nodes as $node)
                    <tr class="border-t hover:bg-gray-50 transition">
                        {{-- ID --}}
                        <x-table.td class="px-2 py-2 text-center font-semibold text-gray-700">
                            {{ $node->id }}
                        </x-table.td>

                        {{-- Parent Node --}}
                        <x-table.td class="px-2 py-2 text-center">
                            @if ($node->parent)
                            <div>
                                <span class="font-medium text-gray-700">#{{ $node->parent->id }}</span>
                                <div class="text-xs text-gray-500 italic">
                                    {{ $node->parent->node_code ?? 'N/A' }}
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400 italic font-semibold">[Root Node]</span>
                            @endif
                        </x-table.td>

                        {{-- Branch Info --}}
                        <x-table.td class="px-2 py-2 text-center">
                            @if ($node->parent)
                            <div class="text-xs space-y-1">
                                @if ($node->yes_branch)
                                <div class="text-blue-600 font-medium">Ya: {{ $node->yes_branch }}</div>
                                @endif
                                @if ($node->no_branch)
                                <div class="text-orange-600 font-medium">Tidak: {{ $node->no_branch }}</div>
                                @endif
                                @if (!$node->yes_branch && !$node->no_branch)
                                <span class="text-gray-400">-</span>
                                @endif
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </x-table.td>

                        {{-- Gejala --}}
                        <x-table.td class="px-2 py-2">
                            @if ($node->gejala)
                            <div class="font-medium text-gray-800">
                                {{ $node->gejala->gejala }}
                            </div>
                            <div class="text-xs text-gray-500">
                                (Kode: {{ $node->gejala->code }})
                            </div>
                            @else
                            <span class="text-gray-400 italic">-</span>
                            @endif
                        </x-table.td>

                        {{-- Penyakit --}}
                        <x-table.td class="px-2 py-2">
                            @if ($node->penyakit)
                            <div class="inline-flex items-center gap-1 px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-medium">
                                <span class="font-semibold">{{ $node->penyakit->code ?? 'P-' . $node->penyakit->id }}</span>
                                <span>—</span>
                                <span>{{ $node->penyakit->penyakit }}</span>
                            </div>
                            @if ($node->is_leaf)
                            <div class="text-xs text-green-600 mt-1">(Leaf Node)</div>
                            @endif
                            @else
                            <span class="text-gray-400 italic">-</span>
                            @endif
                        </x-table.td>

                        {{-- Is Leaf --}}
                        <x-table.td class="px-2 py-2 text-center">
                            @if ($node->is_leaf)
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Ya</span>
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">Tidak</span>
                            @endif
                        </x-table.td>

                        {{-- Aksi --}}
                        <x-table.td class="px-2 py-2 text-center">
                            <div class="flex justify-center gap-x-3">
                                <a href="{{ route('decision-nodes.edit', $node) }}"
                                    class="text-yellow-600 hover:text-yellow-800 font-medium transition">
                                    Edit
                                </a>

                                <form method="POST"
                                    action="{{ route('decision-nodes.destroy', $node) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus node ini?')"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 font-medium transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </x-table.td>
                    </tr>
                    @empty
                    <tr>
                        <x-table.td colspan="7" class="text-center text-gray-500 py-4 italic">
                            Belum ada node pada Decision Tree. Tambahkan node pertama untuk memulai.
                        </x-table.td>
                    </tr>
                    @endforelse
                </x-table.tbody>
            </x-table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $nodes->links() }}
            </div>
        </div>
    </x-slot>
</x-app-layout>