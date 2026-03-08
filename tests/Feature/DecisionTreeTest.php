<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\DecisionNode;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Aturan;
use App\Services\DecisionTreeService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DecisionTreeTest extends TestCase
{
    use RefreshDatabase;

    protected $decisionTreeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->decisionTreeService = new DecisionTreeService();
    }

    /** @test */
    public function it_can_traverse_decision_tree()
    {
        // Create test data
        $gejala1 = Gejala::create(['code' => 'G001', 'gejala' => 'Demam']);
        $gejala2 = Gejala::create(['code' => 'G002', 'gejala' => 'Batuk']);
        $penyakit = Penyakit::create([
            'code' => 'P001',
            'penyakit' => 'Flu',
            'solusi' => 'Istirahat dan minum air putih',
            'obat' => 'Paracetamol'
        ]);

        // Create decision tree structure
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
            'penyakit_id' => $penyakit->id,
            'parent_id' => $rootNode->id,
            'jawaban' => 'tidak'
        ]);

        $leafNode = DecisionNode::create([
            'penyakit_id' => $penyakit->id,
            'parent_id' => $childNodeYa->id,
            'jawaban' => 'ya'
        ]);

        // Test traversal
        $jawaban = [
            $gejala1->id => 'ya',
            $gejala2->id => 'ya'
        ];

        $result = $this->decisionTreeService->traverseDecisionTree($rootNode, $jawaban);

        $this->assertCount(1, $result);
        $this->assertEquals($penyakit->id, $result->first()->id);
    }

    /** @test */
    public function it_can_calculate_certainty_factor()
    {
        // Create test data
        $gejala1 = Gejala::create(['code' => 'G001', 'gejala' => 'Demam']);
        $gejala2 = Gejala::create(['code' => 'G002', 'gejala' => 'Batuk']);
        $penyakit = Penyakit::create([
            'code' => 'P001',
            'penyakit' => 'Flu',
            'solusi' => 'Istirahat',
            'obat' => 'Paracetamol'
        ]);

        // Create aturan with CF values
        $aturan = Aturan::create([
            'code' => 'A001',
            'penyakit_id' => $penyakit->id
        ]);

        // Attach gejala to aturan with CF values
        $aturan->gejalas()->attach($gejala1->id, ['cf' => 0.8]);
        $aturan->gejalas()->attach($gejala2->id, ['cf' => 0.6]);

        $gejalaDipilih = [$gejala1->id, $gejala2->id];
        $cfUser = [
            $gejala1->id => 0.9,
            $gejala2->id => 0.7
        ];

        $kandidatPenyakit = collect([$penyakit]);

        $result = $this->decisionTreeService->calculateCertaintyFactor($gejalaDipilih, $cfUser, $kandidatPenyakit);

        $this->assertCount(1, $result);
        $this->assertEquals($penyakit->id, $result->first()['id']);
        $this->assertGreaterThan(0, $result->first()['cf']);
    }

    /** @test */
    public function it_can_map_cf_to_boolean()
    {
        $cfUser = [
            1 => 0.8,
            2 => 0.3,
            3 => 0.6,
            4 => 0.1
        ];

        $result = $this->decisionTreeService->mapCfToBoolean($cfUser);

        $expected = [
            1 => 'ya',
            2 => 'tidak',
            3 => 'ya',
            4 => 'tidak'
        ];

        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function it_can_get_decision_path()
    {
        // Create test data
        $gejala1 = Gejala::create(['code' => 'G001', 'gejala' => 'Demam']);
        $gejala2 = Gejala::create(['code' => 'G002', 'gejala' => 'Batuk']);
        $penyakit = Penyakit::create([
            'code' => 'P001',
            'penyakit' => 'Flu',
            'solusi' => 'Istirahat',
            'obat' => 'Paracetamol'
        ]);

        // Create decision tree structure
        $rootNode = DecisionNode::create([
            'gejala_id' => $gejala1->id,
            'parent_id' => null
        ]);

        $childNode = DecisionNode::create([
            'gejala_id' => $gejala2->id,
            'parent_id' => $rootNode->id,
            'jawaban' => 'ya'
        ]);

        $leafNode = DecisionNode::create([
            'penyakit_id' => $penyakit->id,
            'parent_id' => $childNode->id,
            'jawaban' => 'ya'
        ]);

        $jawaban = [
            $gejala1->id => 'ya',
            $gejala2->id => 'ya'
        ];

        $result = $this->decisionTreeService->getDecisionPath($rootNode, $jawaban);

        $this->assertCount(3, $result);
        $this->assertEquals($gejala1->id, $result[0]['gejala_id']);
        $this->assertEquals($gejala2->id, $result[1]['gejala_id']);
        $this->assertEquals($penyakit->id, $result[2]['penyakit_id']);
    }

    /** @test */
    public function it_handles_empty_traversal_result()
    {
        $gejala = Gejala::create(['code' => 'G001', 'gejala' => 'Demam']);

        $rootNode = DecisionNode::create([
            'gejala_id' => $gejala->id,
            'parent_id' => null
        ]);

        $jawaban = [
            $gejala->id => 'ya'
        ];

        $result = $this->decisionTreeService->traverseDecisionTree($rootNode, $jawaban);

        $this->assertCount(0, $result);
    }

    /** @test */
    public function it_combines_cf_values_correctly()
    {
        // Test CF combination formula: CF = CF1 + CF2 * (1 - CF1)

        // Test with null first CF
        $result1 = $this->invokePrivateMethod($this->decisionTreeService, 'combineCf', [null, 0.8]);
        $this->assertEquals(0.8, $result1);

        // Test with two CF values
        $result2 = $this->invokePrivateMethod($this->decisionTreeService, 'combineCf', [0.6, 0.4]);
        $expected2 = 0.6 + 0.4 * (1 - 0.6); // 0.6 + 0.4 * 0.4 = 0.6 + 0.16 = 0.76
        $this->assertEquals($expected2, $result2);
    }

    /**
     * Helper method to invoke private methods for testing
     */
    private function invokePrivateMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
