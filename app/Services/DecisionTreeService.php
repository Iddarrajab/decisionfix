<?php

namespace App\Services;

use App\Models\DecisionNode;
use App\Models\Penyakit;
use App\Models\Aturan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DecisionTreeService
{
    /**
     * Traverse decision tree and return candidate diseases
     *
     * @param DecisionNode $rootNode
     * @param array $jawaban
     * @return Collection
     */
    public function traverseDecisionTree(DecisionNode $rootNode, array $jawaban): Collection
    {
        $node = $rootNode;
        $path = [];

        while ($node && !$node->isLeafNode()) {
            $path[] = $node;

            // Get the answer for current symptom
            $currentJawaban = $jawaban[$node->gejala_id] ?? null;

            if (!$currentJawaban) {
                Log::warning('No answer found for gejala_id: ' . $node->gejala_id);
                return collect([]);
            }

            // Find next node based on answer
            $nextNode = $node->children()
                ->where('jawaban', $currentJawaban)
                ->first();

            if (!$nextNode) {
                Log::warning('No child node found for jawaban: ' . $currentJawaban . ' at node: ' . $node->id);
                return collect([]);
            }

            $node = $nextNode;
        }

        // If we reached a leaf node, return the disease
        if ($node && $node->isLeafNode() && $node->penyakit) {
            return collect([$node->penyakit]);
        }

        return collect([]);
    }

    /**
     * Calculate certainty factor for diseases based on symptoms and user CF values
     *
     * @param array $gejalaDipilih
     * @param array $cfUser
     * @param Collection $kandidatPenyakit
     * @return Collection
     */
    public function calculateCertaintyFactor(array $gejalaDipilih, array $cfUser, Collection $kandidatPenyakit): Collection
    {
        $hasil = collect();

        foreach ($kandidatPenyakit as $penyakit) {
            $cfCombine = null;
            $aturanList = $this->getAturanForPenyakit($penyakit->id);

            // If no aturan found, use default CF calculation
            if ($aturanList->isEmpty()) {
                $cfCombine = $this->calculateDefaultCf($gejalaDipilih, $cfUser);
            } else {
                foreach ($aturanList as $aturan) {
                    $cfPenyakit = $this->calculateCfForAturan($aturan, $gejalaDipilih, $cfUser);

                    if ($cfPenyakit > 0) {
                        $cfCombine = $this->combineCf($cfCombine, $cfPenyakit);
                    }
                }
            }

            $hasil->push([
                'id' => $penyakit->id,
                'nama' => $penyakit->penyakit,
                'cf' => round($cfCombine ?? 0, 4),
                'solusi' => $penyakit->solusi,
                'obat' => $penyakit->obat
            ]);
        }

        return $hasil->sortByDesc('cf');
    }

    /**
     * Calculate default CF when no aturan is found
     *
     * @param array $gejalaDipilih
     * @param array $cfUser
     * @return float
     */
    private function calculateDefaultCf(array $gejalaDipilih, array $cfUser): float
    {
        $totalCf = 0;
        $count = 0;

        foreach ($gejalaDipilih as $gejalaId) {
            if (isset($cfUser[$gejalaId])) {
                $totalCf += $cfUser[$gejalaId];
                $count++;
            }
        }

        return $count > 0 ? $totalCf / $count : 0;
    }

    /**
     * Get aturan (rules) for a specific disease
     *
     * @param int $penyakitId
     * @return Collection
     */
    private function getAturanForPenyakit(int $penyakitId): Collection
    {
        return Aturan::where('penyakit_id', $penyakitId)
            ->with(['gejalas' => function ($query) {
                $query->select('gejala.id', 'gejala.gejala');
            }])
            ->get();
    }

    /**
     * Calculate CF for a specific aturan (rule)
     *
     * @param Aturan $aturan
     * @param array $gejalaDipilih
     * @param array $cfUser
     * @return float
     */
    private function calculateCfForAturan(Aturan $aturan, array $gejalaDipilih, array $cfUser): float
    {
        $cfAturan = 1.0;
        $gejalaCount = 0;

        foreach ($aturan->gejalas as $gejala) {
            if (in_array($gejala->id, $gejalaDipilih)) {
                $cfPakar = $gejala->pivot->cf ?? 1.0;
                $cfInput = floatval($cfUser[$gejala->id] ?? 0);

                if ($cfInput > 0) {
                    $cfGejala = $cfPakar * $cfInput;
                    $cfAturan = $this->combineCf($cfAturan, $cfGejala);
                    $gejalaCount++;
                }
            }
        }

        // If no symptoms match, return 0
        if ($gejalaCount === 0) {
            return 0.0;
        }

        return $cfAturan;
    }

    /**
     * Combine two certainty factors using the CF combination formula
     *
     * @param float|null $cf1
     * @param float $cf2
     * @return float
     */
    private function combineCf(?float $cf1, float $cf2): float
    {
        if ($cf1 === null) {
            return $cf2;
        }

        // CF combination formula: CF = CF1 + CF2 * (1 - CF1)
        return $cf1 + $cf2 * (1 - $cf1);
    }

    /**
     * Map CF values to boolean answers for decision tree traversal
     *
     * @param array $cfUser
     * @return array
     */
    public function mapCfToBoolean(array $cfUser): array
    {
        $result = [];
        foreach ($cfUser as $gejalaId => $cf) {
            $cfValue = floatval($cf);
            $result[$gejalaId] = $cfValue > 0.5 ? 'ya' : 'tidak';
        }
        return $result;
    }

    /**
     * Get decision tree path for debugging
     *
     * @param DecisionNode $rootNode
     * @param array $jawaban
     * @return array
     */
    public function getDecisionPath(DecisionNode $rootNode, array $jawaban): array
    {
        $path = [];
        $node = $rootNode;

        while ($node && !$node->isLeafNode()) {
            $currentJawaban = $jawaban[$node->gejala_id] ?? null;

            $path[] = [
                'gejala_id' => $node->gejala_id,
                'gejala_nama' => $node->gejala->gejala ?? 'Unknown',
                'jawaban' => $currentJawaban,
                'node_id' => $node->id
            ];

            $nextNode = $node->children()
                ->where('jawaban', $currentJawaban)
                ->first();

            if (!$nextNode) {
                break;
            }

            $node = $nextNode;
        }

        if ($node && $node->isLeafNode()) {
            $path[] = [
                'penyakit_id' => $node->penyakit_id,
                'penyakit_nama' => $node->penyakit->penyakit ?? 'Unknown',
                'node_id' => $node->id
            ];
        }

        return $path;
    }

    /**
     * Validate decision tree structure
     *
     * @param DecisionNode $rootNode
     * @return array
     */
    public function validateDecisionTree(DecisionNode $rootNode): array
    {
        $errors = [];
        $this->validateNode($rootNode, $errors);
        return $errors;
    }

    /**
     * Recursively validate decision tree nodes
     *
     * @param DecisionNode $node
     * @param array $errors
     * @param int $level
     */
    private function validateNode(DecisionNode $node, array &$errors, int $level = 0): void
    {
        if ($level > 10) {
            $errors[] = "Maximum tree depth exceeded at node {$node->id}";
            return;
        }

        if ($node->isLeafNode()) {
            if (!$node->penyakit) {
                $errors[] = "Leaf node {$node->id} has no associated disease";
            }
        } else {
            if (!$node->gejala) {
                $errors[] = "Non-leaf node {$node->id} has no associated symptom";
            }

            $children = $node->children;
            if ($children->isEmpty()) {
                $errors[] = "Non-leaf node {$node->id} has no children";
            }

            foreach ($children as $child) {
                $this->validateNode($child, $errors, $level + 1);
            }
        }
    }
}
