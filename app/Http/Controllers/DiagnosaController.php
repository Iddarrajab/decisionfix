<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Aturan;
use App\Models\DecisionNode;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DiagnosaController extends Controller
{
    public function index()
    {
        $diagnosas = Diagnosa::with('penyakit')->latest()->get();

        return view('diagnosa.index', [
            'diagnosas' => $diagnosas,
            'page_meta' => ['title' => 'Hasil Diagnosa Penyakit Hewan'],
        ]);
    }

    public function create()
    {
        $gejalaList = Gejala::orderBy('id')->get();

        if ($gejalaList->isEmpty()) {
            return back()->with('error', 'Belum ada data gejala di sistem.');
        }

        return $this->formView(
            new Diagnosa(),
            'Form Diagnosa Penyakit Hewan',
            'POST',
            route('diagnosa.store'),
            'Diagnosa Sekarang'
        );
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_user'       => 'required|string|max:100',
                'gejala_dipilih'  => 'required|array|min:1',
                'cf_gejala'      => 'required|array',
            ]);

            $gejalaDipilih = array_map('intval', $validated['gejala_dipilih']);
            $cfUserInput   = $validated['cf_gejala'];
            $namaUser      = $validated['nama_user'];

            // 🔹 ROOT DIPILIH OTOMATIS (tidak ditampilkan ke user)
            $rootNode = DecisionNode::whereNull('parent_id')->first();

            if (!$rootNode) {
                return back()->with('error', 'Decision Tree belum dibuat oleh admin.');
            }

            // 🔹 Traversal fleksibel (tanpa ya/tidak)
            $decisionPath = [];
            $kandidatPenyakit = $this->collectLeafPenyakit($rootNode, $decisionPath);

            if ($kandidatPenyakit->isEmpty()) {
                return back()->with('error', 'Tidak ditemukan penyakit di pohon keputusan.');
            }

            // 🔹 CF FINAL (adil & akurat)
            $hasil = $this->calculateCertaintyFactor($gejalaDipilih, $cfUserInput, $kandidatPenyakit);

            if ($hasil->isEmpty()) {
                return back()->with('error', 'Tidak ada penyakit yang cocok dengan gejala yang dipilih.');
            }

            $tertinggi = $hasil->first();

            $cfPersentase = round($tertinggi['cf'] * 100, 2);
            $hasilKeputusan = "Kemungkinan terbesar adalah penyakit {$tertinggi['nama']} dengan tingkat keyakinan {$cfPersentase}%";

            $diagnosa = Diagnosa::create([
                'code'            => 'D' . now()->format('ymdHis'),
                'nama_user'       => $namaUser,
                'penyakit_id'     => $tertinggi['id'],
                'hasil_cf'        => $tertinggi['cf'],
                'hasil_keputusan' => $hasilKeputusan,
                'gejala_dipilih'  => $gejalaDipilih,
                'cf_gejala'      => $cfUserInput,
            ]);

            session()->flash('diagnosa_results', $hasil->toArray());
            session()->flash('decision_path', $decisionPath);

            return redirect()->route('diagnosa.show', $diagnosa->id)
                ->with('success', 'Diagnosa berhasil dilakukan.');
        } catch (\Exception $e) {
            Log::error('Error in diagnosa.store: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat proses diagnosa: ' . $e->getMessage());
        }
    }

    /**
     * 🔥 Ambil semua penyakit leaf dari pohon (root dipilih sistem)
     */
    private function collectLeafPenyakit(DecisionNode $node, array &$path): Collection
    {
        $path[] = [
            'node_id'   => $node->id,
            'node_code' => $node->node_code,
            'gejala_id' => $node->gejala_id,
        ];

        if ($node->is_leaf && $node->penyakit_id) {
            return collect([$node->penyakit]);
        }

        $children = DecisionNode::where('parent_id', $node->id)->get();

        if ($children->isEmpty()) {
            return collect();
        }

        $kandidat = collect();

        foreach ($children as $child) {
            $kandidat = $kandidat->merge(
                $this->collectLeafPenyakit($child, $path)
            );
        }

        return $kandidat->unique('id')->values();
    }

    /**
     * 🧮 CF FINAL – adil & tidak 0 palsu
     */
    private function calculateCertaintyFactor(array $gejalaDipilih, array $cfUserInput, Collection $kandidatPenyakit): Collection
    {
        $hasil = collect();

        // 🔥 Mapping CF user berdasarkan ID gejala
        $cfUserMap = [];
        foreach ($cfUserInput as $gejalaId => $cfVal) {
            $cfUserMap[(int)$gejalaId] = (float)$cfVal;
        }

        foreach ($kandidatPenyakit as $penyakit) {
            $aturan = Aturan::with('gejala')->where('penyakit_id', $penyakit->id)->first();

            if (!$aturan || $aturan->gejala->isEmpty()) {
                continue;
            }

            $cfCombine = null;
            $jumlahGejalaCocok = 0;
            $totalGejalaPenyakit = $aturan->gejala->count();

            foreach ($aturan->gejala as $gejala) {
                $gejalaId = (int)$gejala->id;
                $cfPakar = (float)($gejala->pivot->cf ?? 0);
                $cfUserVal = (float)($cfUserMap[$gejalaId] ?? 0);

                if (in_array($gejalaId, $gejalaDipilih, true)) {
                    $jumlahGejalaCocok++;

                    $cfGejala = $cfPakar * $cfUserVal;

                    if ($cfCombine === null) {
                        $cfCombine = $cfGejala;
                    } else {
                        $cfCombine = $cfCombine + ($cfGejala * (1 - $cfCombine));
                    }
                }
            }

            // 🔥 Buang penyakit tanpa gejala cocok
            if ($jumlahGejalaCocok === 0) {
                continue;
            }

            // 🔥 Coverage factor (adil)
            $coverage = $jumlahGejalaCocok / max($totalGejalaPenyakit, 1);
            $cfFinal = $cfCombine * $coverage;

            $hasil->push([
                'id'   => $penyakit->id,
                'nama' => $penyakit->penyakit,
                'cf'   => round($cfFinal, 4),
            ]);
        }

        return $hasil->sortByDesc('cf')->values();
    }

    public function show(Diagnosa $diagnosa)
    {
        $gejalaTerpilih = $diagnosa->gejala_dipilih ?? [];
        $cfGejala = $diagnosa->cf_gejala ?? [];
        $gejalaList = Gejala::whereIn('id', $gejalaTerpilih)->get();
        $decisionPath = session('decision_path', []);
        $diagnosaResults = session('diagnosa_results', []);

        return view('diagnosa.show', [
            'diagnosa'        => $diagnosa,
            'gejalaList'     => $gejalaList,
            'cfGejala'       => $cfGejala,
            'decisionPath'   => $decisionPath,
            'diagnosaResults' => $diagnosaResults,
            'page_meta'      => ['title' => 'Hasil Diagnosa Penyakit Hewan'],
        ]);
    }

    public function destroy(Diagnosa $diagnosa)
    {
        $diagnosa->delete();
        return redirect()->route('diagnosa.index')
            ->with('success', 'Data diagnosa berhasil dihapus.');
    }

    private function formView(Diagnosa $diagnosa, $title, $method, $url, $button)
    {
        $gejalaList = Gejala::orderBy('id')->get();

        return view('diagnosa.form', [
            'diagnosa'  => $diagnosa,
            'gejalaList' => $gejalaList,
            'page_meta' => compact('title', 'method', 'url', 'button'),
        ]);
    }
}
