<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Http\Request;

class AturanController extends Controller
{
    /**
     * Tampilkan daftar aturan
     */
    public function index()
    {
        $data = Aturan::with(['penyakit', 'gejala'])->paginate(10);

        $page_meta = [
            'title' => 'Daftar Aturan',
            'button' => 'Tambah Aturan',
            'url' => route('aturan.create'),
        ];

        return view('aturan.index', compact('data', 'page_meta'));
    }


    /**
     * Form tambah aturan
     */
    public function create()
    {
        $penyakitList = Penyakit::all();
        $gejalaList = Gejala::all();

        $page_meta = [
            'title' => 'Tambah Aturan',
            'url' => route('aturan.store'),
            'method' => 'POST',
            'button' => 'Simpan',
        ];

        return view('aturan.form', compact('penyakitList', 'gejalaList', 'page_meta'));
    }

    /**
     * Simpan aturan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:aturan,code',
            'penyakit_id' => 'required|exists:penyakit,id',
            'gejala_ids' => 'required|array|min:1',
            'cf' => 'required|array',
        ]);

        $aturan = Aturan::create([
            'code' => $request->code,
            'penyakit_id' => $request->penyakit_id,
        ]);

        foreach ($request->gejala_ids as $gid) {
            $aturan->gejala()->attach($gid, ['cf' => $request->cf[$gid] ?? 0]);
        }

        return redirect()->route('aturan.index')->with('success', 'Aturan berhasil disimpan.');
    }

    /**
     * Form edit aturan
     */
    public function edit($id)
    {
        $aturan = Aturan::with('gejala')->findOrFail($id);
        $penyakitList = Penyakit::all();
        $gejalaList = Gejala::all();

        $page_meta = [
            'title' => 'Edit Aturan',
            'url' => route('aturan.update', $aturan->id),
            'method' => 'PUT',
            'button' => 'Perbarui',
        ];

        return view('aturan.form', compact('aturan', 'penyakitList', 'gejalaList', 'page_meta'));
    }

    /**
     * Update aturan
     */
    public function update(Request $request, $id)
    {
        $aturan = Aturan::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:aturan,code,' . $id,
            'penyakit_id' => 'required|exists:penyakit,id',
            'gejala_ids' => 'required|array|min:1',
            'cf' => 'required|array',
        ]);

        $aturan->update([
            'code' => $request->code,
            'penyakit_id' => $request->penyakit_id,
        ]);

        $syncData = [];
        foreach ($request->gejala_ids as $gid) {
            $syncData[$gid] = ['cf' => $request->cf[$gid] ?? 0];
        }

        $aturan->gejala()->sync($syncData);

        return redirect()->route('aturan.index')->with('success', 'Aturan berhasil diperbarui.');
    }

    /**
     * Hapus aturan
     */
    public function destroy($id)
    {
        $aturan = Aturan::findOrFail($id);
        $aturan->gejala()->detach();
        $aturan->delete();

        return redirect()->route('aturan.index')->with('success', 'Aturan berhasil dihapus.');
    }
}
