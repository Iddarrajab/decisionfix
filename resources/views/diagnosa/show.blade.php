<x-app-layout title="Detail Diagnosa">
    <x-slot name="heading">
        Detail Diagnosa
    </x-slot>

    <x-slot name="body">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Detail Diagnosa</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-700">Informasi Pengguna</h3>
                        <p class="text-gray-600">Nama: {{ $diagnosa->nama_user ?? 'Tidak disebutkan' }}</p>
                        <p class="text-gray-600">Kode Diagnosa: <span class="font-mono text-sm">{{ $diagnosa->code ?? '-' }}</span></p>
                        <p class="text-gray-600">Tanggal: {{ $diagnosa->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700">Hasil Diagnosa</h3>
                        <p class="text-gray-600">Penyakit: <span class="font-semibold text-blue-600">{{ $diagnosa->penyakit->penyakit ?? 'Tidak ditemukan' }}</span></p>
                        <p class="text-gray-600">Nilai CF: 
                            <span class="font-semibold {{ $diagnosa->hasil_cf > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $diagnosa->hasil_cf ? number_format($diagnosa->hasil_cf * 100, 2) : 0 }}%
                            </span>
                        </p>
                        @if($diagnosa->hasil_keputusan)
                        <div class="mt-2 text-sm p-3 rounded {{ str_contains($diagnosa->hasil_keputusan, 'PERINGATAN') ? 'bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800' : 'bg-blue-50 text-gray-700' }}">
                            {!! nl2br(e($diagnosa->hasil_keputusan)) !!}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Decision Tree Path -->
            @if(session('diagnosa_path'))
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Jalur Pohon Keputusan</h2>

                <div class="space-y-3">
                    @foreach(session('diagnosa_path') as $index => $step)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">
                            {{ $index + 1 }}
                        </div>

                        <div class="flex-1">
                            @if(isset($step['gejala_nama']))
                            <p class="font-medium text-gray-800">{{ $step['gejala_nama'] }}</p>
                            <p class="text-sm text-gray-600">Jawaban: <span class="font-semibold text-blue-600">{{ ucfirst($step['jawaban']) }}</span></p>
                            @elseif(isset($step['penyakit_nama']))
                            <p class="font-medium text-green-600">{{ $step['penyakit_nama'] }}</p>
                            <p class="text-sm text-gray-600">Penyakit terdiagnosa</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Gejala yang Dipilih -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Gejala yang Dipilih</h2>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">No</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Gejala</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Nilai CF</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gejalaList as $index => $gejala)
                            @php
                            $cfValue = $cfGejala[$gejala->id] ?? 0;
                            $cfPercentage = $cfValue * 100;

                            $keterangan = match(true) {
                            $cfValue >= 0.8 => 'Sangat Yakin',
                            $cfValue >= 0.6 => 'Yakin',
                            $cfValue >= 0.4 => 'Cukup Yakin',
                            $cfValue >= 0.2 => 'Tidak Yakin',
                            default => 'Sangat Tidak Yakin'
                            };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $gejala->gejala ?? $gejala->nama_gejala ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center font-semibold text-blue-600">
                                    {{ number_format($cfPercentage, 1) }}%
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $cfValue >= 0.8 ? 'bg-green-100 text-green-800' : 
                                               ($cfValue >= 0.6 ? 'bg-blue-100 text-blue-800' : 
                                               ($cfValue >= 0.4 ? 'bg-yellow-100 text-yellow-800' : 
                                               ($cfValue >= 0.2 ? 'bg-orange-100 text-orange-800' : 
                                               'bg-red-100 text-red-800'))) }}">
                                        {{ $keterangan }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Hasil Perhitungan CF -->
            @if(session('diagnosa_results'))
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Hasil Perhitungan Certainty Factor</h2>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">Ranking</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Penyakit</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Nilai CF</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Persentase</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('diagnosa_results') as $index => $result)
                            @php
                            // CF sudah dalam bentuk decimal (0-1)
                            $cfValue = is_numeric($result['cf']) ? floatval($result['cf']) : 0;
                            $cfPercentage = $cfValue * 100;
                            $isTopResult = $index === 0;
                            $statusClass = $isTopResult ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                            $statusText = $isTopResult ? 'Terpilih' : 'Alternatif';
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $isTopResult ? 'bg-green-50' : '' }}">
                                <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $result['nama'] ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center font-semibold text-blue-600">
                                    {{ number_format($cfValue, 4) }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-center font-semibold">
                                    {{ number_format($cfPercentage, 2) }}%
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Solusi dan Obat -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Solusi dan Pengobatan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Solusi</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $diagnosa->penyakit->solusi ?? 'Tidak ada solusi yang tersedia.' }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Obat</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $diagnosa->penyakit->obat ?? 'Tidak ada obat yang tersedia.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <a href="{{ route('diagnosa.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    Kembali ke Daftar
                </a>

                <a href="{{ route('diagnosa.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                    Diagnosa Baru
                </a>
            </div>
        </div>
    </x-slot>
</x-app-layout>