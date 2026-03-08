<?php

namespace App\Http\Controllers;

use App\Models\DecisionNode;
use App\Models\Gejala;
use App\Models\Penyakit;
use Illuminate\Http\Request;

class DecisionNodeController extends Controller
{
    public function index()
    {
        $nodes = DecisionNode::with(['parent', 'gejala', 'penyakit', 'children'])
            ->orderBy('id')
            ->paginate(10); // pagination agar bisa pakai links()

        return view('decision_nodes.index', [
            'nodes' => $nodes,
            'page_meta' => [
                'title' => 'Manajemen Decision Tree',
            ],
        ]);
    }

    public function create()
    {
        $gejalaList = Gejala::all();
        $penyakitList = Penyakit::all();
        $parentList = DecisionNode::all();

        return view('decision_nodes.form', [
            'node' => new DecisionNode(),
            'gejalaList' => $gejalaList,
            'penyakitList' => $penyakitList,
            'parentList' => $parentList,
            'page_meta' => [
                'title' => 'Tambah Node Decision Tree',
                'method' => 'POST',
                'url' => route('decision-nodes.store'),
                'button' => 'Simpan Node',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'node_code' => 'required|string|unique:decision_nodes,node_code|max:20',
            'parent_id' => 'nullable|exists:decision_nodes,id',
            'gejala_id' => 'nullable|exists:gejala,id',
            'penyakit_id' => 'nullable|exists:penyakit,id',
            'yes_branch' => 'nullable|string|max:255',
            'no_branch' => 'nullable|string|max:255',
            'is_leaf' => 'nullable|boolean',
        ]);

        DecisionNode::create($validated);

        return redirect()->route('decision-nodes.index')
            ->with('success', 'Node decision tree berhasil ditambahkan.');
    }

    public function edit(DecisionNode $decision_node)
    {
        $gejalaList = Gejala::all();
        $penyakitList = Penyakit::all();
        $parentList = DecisionNode::where('id', '!=', $decision_node->id)->get();

        return view('decision_nodes.form', [
            'node' => $decision_node,
            'gejalaList' => $gejalaList,
            'penyakitList' => $penyakitList,
            'parentList' => $parentList,
            'page_meta' => [
                'title' => 'Edit Node Decision Tree',
                'method' => 'PUT',
                'url' => route('decision-nodes.update', $decision_node->id),
                'button' => 'Update Node',
            ],
        ]);
    }

    public function update(Request $request, DecisionNode $decision_node)
    {
        $validated = $request->validate([
            'node_code' => 'required|string|max:20|unique:decision_nodes,node_code,' . $decision_node->id,
            'parent_id' => 'nullable|exists:decision_nodes,id',
            'gejala_id' => 'nullable|exists:gejala,id',
            'penyakit_id' => 'nullable|exists:penyakit,id',
            'yes_branch' => 'nullable|string|max:255',
            'no_branch' => 'nullable|string|max:255',
            'is_leaf' => 'nullable|boolean',
        ]);

        $decision_node->update($validated);

        return redirect()->route('decision-nodes.index')
            ->with('success', 'Node decision tree berhasil diperbarui.');
    }

    public function destroy(DecisionNode $decision_node)
    {
        $decision_node->delete();
        return redirect()->route('decision-nodes.index')
            ->with('success', 'Node decision tree berhasil dihapus.');
    }
}
