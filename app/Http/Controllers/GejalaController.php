<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GejalaRequest;
use App\Models\Gejala;

class GejalaController extends Controller
{
    public function index()
    {
        $gejala = Gejala::latest()->get();
        return view('gejala.index', compact('gejala'));
    }

    public function create()
    {
        return view('gejala.form', [
            'gejala' => new Gejala(), // supaya form tidak error
            'page_meta' => [
                'title' => 'Tambah Gejala Baru',
                'method' => 'POST',
                'url' => route('gejala.store'),
                'button' => 'Simpan'
            ],
        ]);
    }

    public function store(GejalaRequest $request)
    {
        Gejala::create($request->validated());

        return redirect()->route('gejala.index')
            ->with('success', 'Gejala berhasil ditambahkan.');
    }

    public function show(Gejala $gejala)
    {
        return view('gejala.show', compact('gejala'));
    }

    public function edit(Gejala $gejala)
    {
        return view('gejala.form', [
            'gejala' => $gejala,
            'page_meta' => [
                'title' => 'Edit Gejala: ' . $gejala->gejala,
                'method' => 'PUT',
                'url' => route('gejala.update', $gejala),
                'button' => 'Update'
            ],
        ]);
    }

    public function update(GejalaRequest $request, Gejala $gejala)
    {
        $gejala->update($request->validated());

        return redirect()->route('gejala.index')
            ->with('success', 'Gejala berhasil diperbarui.');
    }

    public function destroy(Gejala $gejala)
    {
        $gejala->delete();

        return redirect()->route('gejala.index')
            ->with('success', 'Gejala berhasil dihapus.');
    }
}
