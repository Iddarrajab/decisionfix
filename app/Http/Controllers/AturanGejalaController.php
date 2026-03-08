<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AturanGejalaRequest;

class AturanGejalaController extends Controller
{
    /**
     * Tampilkan semua relasi aturan ↔ gejala
     */
    public function index()
    {
        $aturanList = Aturan::with(['gejalas' => fn($q) => $q->orderBy('gejala', 'asc')])
            ->orderBy('code', 'asc')
            ->get();

        $page_meta = [
            'title' => 'Relasi Aturan & Gejala',
        ];

        return view('aturan_gejala.index', compact('aturanList', 'page_meta'));
    }

    /**
     * Form tambah relasi baru
     */
    public function create()
    {
        $aturanList = Aturan::select('id', 'code')->orderBy('code')->get();
        $gejalaList = Gejala::select('id', 'gejala')->orderBy('gejala')->get();

        $page_meta = [
            'title' => 'Tambah Relasi Aturan-Gejala',
            'url' => route('aturan-gejala.store'),
            'method' => 'POST',
            'button' => 'Simpan',
        ];

        return view('aturan_gejala.form', compact('aturanList', 'gejalaList', 'page_meta'));
    }

    /**
     * Simpan relasi baru ke tabel pivot
     */
    public function store(AturanGejalaRequest $request)
    {
        DB::beginTransaction();

        try {
            $aturan = Aturan::findOrFail($request->aturan_id);

            // Cegah duplikasi gejala untuk aturan yang sama
            if ($aturan->gejalas()->where('gejala_id', $request->gejala_id)->exists()) {
                return back()->withErrors([
                    'gejala_id' => 'Gejala ini sudah terhubung dengan aturan tersebut.'
                ])->withInput();
            }

            // Tambahkan gejala beserta CF
            $aturan->gejalas()->attach($request->gejala_id, ['cf' => $request->cf]);

            DB::commit();
            return redirect()->route('aturan-gejala.index')
                ->with('success', 'Relasi aturan-gejala berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menambahkan relasi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form edit nilai CF pada pivot
     */
    public function edit($aturan_id, $gejala_id)
    {
        $aturan = Aturan::findOrFail($aturan_id);
        $gejala = Gejala::findOrFail($gejala_id);
        $pivot = $aturan->gejalas()->where('gejala_id', $gejala_id)->first();

        if (!$pivot) {
            return redirect()->route('aturan-gejala.index')->withErrors('Relasi tidak ditemukan.');
        }

        $page_meta = [
            'title' => 'Edit Nilai CF Aturan-Gejala',
            'url' => route('aturan-gejala.update', [$aturan_id, $gejala_id]),
            'method' => 'PUT',
            'button' => 'Update',
        ];

        return view('aturan_gejala.form', [
            'aturan' => $aturan,
            'gejala' => $gejala,
            'cf' => $pivot->pivot->cf,
            'page_meta' => $page_meta,
        ]);
    }

    /**
     * Update nilai CF di tabel pivot
     */
    public function update(AturanGejalaRequest $request, $aturan_id, $gejala_id)
    {
        DB::beginTransaction();

        try {
            $aturan = Aturan::findOrFail($aturan_id);
            $aturan->gejalas()->updateExistingPivot($gejala_id, ['cf' => $request->cf]);

            DB::commit();
            return redirect()->route('aturan-gejala.index')
                ->with('success', 'Nilai CF berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal memperbarui nilai CF: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus relasi aturan-gejala
     */
    public function destroy($aturan_id, $gejala_id)
    {
        try {
            $aturan = Aturan::findOrFail($aturan_id);
            $aturan->gejalas()->detach($gejala_id);

            return redirect()->route('aturan-gejala.index')
                ->with('success', 'Relasi aturan-gejala berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus relasi: ' . $e->getMessage());
        }
    }
}
