<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DecisionNode;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Aturan;
use App\Services\DecisionTreeService;
use Illuminate\Support\Facades\DB;

class TestDecisionTree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:decision-tree {--create-sample-data : Create sample data for testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the decision tree and certainty factor system';

    protected $decisionTreeService;

    /**
     * Create a new command instance.
     */
    public function __construct(DecisionTreeService $decisionTreeService)
    {
        parent::__construct();
        $this->decisionTreeService = $decisionTreeService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Decision Tree and Certainty Factor System');
        $this->newLine();

        if ($this->option('create-sample-data')) {
            $this->createSampleData();
        }

        // Test decision tree traversal
        $this->testDecisionTreeTraversal();

        // Test certainty factor calculation
        $this->testCertaintyFactor();

        $this->info('Testing completed successfully!');
    }

    private function createSampleData()
    {
        $this->info('Creating sample data...');

        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DecisionNode::truncate();
        Gejala::truncate();
        Penyakit::truncate();
        Aturan::truncate();
        DB::table('aturan_gejala')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create gejala
        $gejala1 = Gejala::create(['code' => 'G001', 'gejala' => 'Demam']);
        $gejala2 = Gejala::create(['code' => 'G002', 'gejala' => 'Batuk']);
        $gejala3 = Gejala::create(['code' => 'G003', 'gejala' => 'Sesak Nafas']);
        $gejala4 = Gejala::create(['code' => 'G004', 'gejala' => 'Nafsu Makan Berkurang']);

        // Create penyakit
        $penyakit1 = Penyakit::create([
            'code' => 'P001',
            'penyakit' => 'Flu',
            'solusi' => 'Istirahat dan minum air putih yang cukup',
            'obat' => 'Paracetamol, Vitamin C'
        ]);

        $penyakit2 = Penyakit::create([
            'code' => 'P002',
            'penyakit' => 'Pneumonia',
            'solusi' => 'Segera ke dokter untuk pemeriksaan lebih lanjut',
            'obat' => 'Antibiotik (resep dokter)'
        ]);

        // Create decision tree
        $rootNode = DecisionNode::create([
            'gejala_id' => $gejala1->id,
            'parent_id' => null
        ]);

        $childNodeYa = DecisionNode::create([
            'gejala_id' => $gejala2->id,
            'parent_id' => $rootNode->id,
            'jawaban' => 'ya'
        ]);

        $childNodeTidak = DecisionNode::create([
            'penyakit_id' => $penyakit1->id,
            'parent_id' => $rootNode->id,
            'jawaban' => 'tidak'
        ]);

        $leafNode = DecisionNode::create([
            'penyakit_id' => $penyakit2->id,
            'parent_id' => $childNodeYa->id,
            'jawaban' => 'ya'
        ]);

        // Create aturan
        $aturan1 = Aturan::create(['code' => 'A001', 'penyakit_id' => $penyakit1->id]);
        $aturan2 = Aturan::create(['code' => 'A002', 'penyakit_id' => $penyakit2->id]);

        // Attach gejala to aturan with CF values
        $aturan1->gejalas()->attach($gejala1->id, ['cf' => 0.8]);
        $aturan1->gejalas()->attach($gejala2->id, ['cf' => 0.6]);

        $aturan2->gejalas()->attach($gejala1->id, ['cf' => 0.9]);
        $aturan2->gejalas()->attach($gejala2->id, ['cf' => 0.7]);
        $aturan2->gejalas()->attach($gejala3->id, ['cf' => 0.8]);

        $this->info('Sample data created successfully!');
        $this->newLine();
    }

    private function testDecisionTreeTraversal()
    {
        $this->info('Testing Decision Tree Traversal...');

        $rootNode = DecisionNode::with(['gejala', 'penyakit', 'children'])->find(1);

        if (!$rootNode) {
            $this->error('Root node not found! Please create sample data first.');
            return;
        }

        // Test case 1: Ya -> Ya (should lead to Pneumonia)
        $jawaban1 = [
            1 => 'ya', // Demam
            2 => 'ya'  // Batuk
        ];

        $result1 = $this->decisionTreeService->traverseDecisionTree($rootNode, $jawaban1);

        if ($result1->isNotEmpty()) {
            $this->info('✓ Test 1 passed: Found disease - ' . $result1->first()->penyakit);
        } else {
            $this->error('✗ Test 1 failed: No disease found');
        }

        // Test case 2: Ya -> Tidak (should lead to Flu)
        $jawaban2 = [
            1 => 'ya',   // Demam
            2 => 'tidak' // Batuk
        ];

        $result2 = $this->decisionTreeService->traverseDecisionTree($rootNode, $jawaban2);

        if ($result2->isNotEmpty()) {
            $this->info('✓ Test 2 passed: Found disease - ' . $result2->first()->penyakit);
        } else {
            $this->error('✗ Test 2 failed: No disease found');
        }

        $this->newLine();
    }

    private function testCertaintyFactor()
    {
        $this->info('Testing Certainty Factor Calculation...');

        $gejalaDipilih = [1, 2]; // Demam, Batuk
        $cfUser = [
            1 => 0.8, // CF pengguna untuk Demam
            2 => 0.6  // CF pengguna untuk Batuk
        ];

        $kandidatPenyakit = Penyakit::all();

        $result = $this->decisionTreeService->calculateCertaintyFactor($gejalaDipilih, $cfUser, $kandidatPenyakit);

        if ($result->isNotEmpty()) {
            $this->info('✓ CF calculation successful!');
            $this->info('Results:');

            foreach ($result as $index => $item) {
                $this->line("  " . ($index + 1) . ". {$item['nama']} - CF: " . number_format($item['cf'], 4));
            }
        } else {
            $this->error('✗ CF calculation failed!');
        }

        $this->newLine();
    }
}
