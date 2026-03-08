<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // ← kalau kamu masih butuh ini
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Admin::latest()->get();
        return view('admin.index', ['admin' => $admin]);
    }

    public function create()
    {
        return view('admin.form', [
            'admin' => new Admin(),
            'page_meta' => [
                'title' => 'Create New Admin',
                'method' => 'POST',
                'url' => route('admin.store',),
                'button' => 'create'
            ],
        ]);
    }

    public function store(AdminRequest $request)
    {
        // Simpan ke database
        Admin::create($request->validated());

        return redirect()->route('admin.index');
    }

    public function show(Admin $admin)
    {

        return view('index.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admins.form', [
            'admin' => $admin,
            'page_meta' => [
                'title' => 'Update New Admin ' . $admin->name,
                'method' => 'PUT',
                'url' => route('admin.update', ['admin' => $admin->id]),
                'button' => 'Update'
            ],
        ]);
    }

    public function update(AdminRequest $request, Admin $admin)
    {
        $admin->update($request->validated());
        return redirect()->route('admin.index');
    }

    public function destroy(Admin $admin)

    {
        $admin->delete();

        return redirect()->route('admin.index');
    }
}
