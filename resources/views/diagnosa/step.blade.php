<x-app-layout title="Langkah Diagnosa">
    <x-slot name="heading">Langkah Diagnosa</x-slot>

    <x-slot name="body">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-xl font-bold text-gray-800 mb-4">Langkah Diagnosa: {{ $decisionNode->nama_node }}</h1>
            <form method="POST" action="{{ route('diagnosa.processStep') }}">
                @csrf
                <input type="hidden" name="current_node" value="{{ $decisionNode->code }}">

                <div class="space-y-4">
                    @foreach($gejala as $item)
                    <div class="flex items-center justify-between border rounded p-3">
                        <label class="font-medium">{{ $item->gejala }}</label>
                        <select name="cf_gejala[{{ $item->id }}]" class="border rounded px-2 py-1">
                            <option value="0.2">Sangat Tidak Yakin</option>
                            <option value="0.4">Tidak Yakin</option>
                            <option value="0.6">Cukup Yakin</option>
                            <option value="0.8">Yakin</option>
                            <option value="1.0">Sangat Yakin</option>
                        </select>
                        <input type="checkbox" name="gejala_terpilih[]" value="{{ $item->id }}">
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-end mt-6">
                    <x-button type="submit">Lanjut</x-button>
                </div>
            </form>
        </div>
    </x-slot>
</x-app-layout>