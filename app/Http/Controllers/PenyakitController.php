<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use Illuminate\Http\Request;
use App\Http\Requests\PenyakitRequest;

class PenyakitController extends Controller
{
    /**
     * Menampilkan daftar penyakit
     */
    public function index()
    {
        $penyakit = Penyakit::latest()->get();
        return view('penyakit.index', compact('penyakit'));
    }

    /**
     * Menampilkan form tambah penyakit
     */
    public function create()
    {
        $page_meta = [
            'title' => 'Tambah Penyakit',
            'url' => route('penyakit.store'),
            'method' => 'POST',
            'button' => 'Simpan'
        ];

        return view('penyakit.form', compact('page_meta'));
    }

    /**
     * Menyimpan data penyakit baru
     */
    public function store(PenyakitRequest $request)
    {
        Penyakit::create($request->validated());

        return redirect()
            ->route('penyakit.index')
            ->with('success', 'Penyakit berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail penyakit
     */
    public function show(Penyakit $penyakit)
    {
        $page_meta = [
            'title' => 'Detail Penyakit'
        ];

        return view('penyakit.show', compact('penyakit', 'page_meta'));
    }

    /**
     * Menampilkan form edit penyakit
     */
    public function edit(Penyakit $penyakit)
    {
        $page_meta = [
            'title' => 'Edit Penyakit',
            'url' => route('penyakit.update', $penyakit),
            'method' => 'PUT',
            'button' => 'Update'
        ];

        return view('penyakit.form', compact('penyakit', 'page_meta'));
    }

    /**
     * Mengupdate data penyakit
     */
    public function update(PenyakitRequest $request, Penyakit $penyakit)
    {
        $penyakit->update($request->validated());

        return redirect()
            ->route('penyakit.index')
            ->with('success', 'Penyakit berhasil diperbarui.');
    }

    /**
     * Menghapus data penyakit
     */
    public function destroy(Penyakit $penyakit)
    {
        $penyakit->delete();

        return redirect()
            ->route('penyakit.index')
            ->with('success', 'Penyakit berhasil dihapus.');
    }
}
