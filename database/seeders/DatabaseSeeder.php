<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\DecisionNode;
use App\Models\Aturan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample gejala
        $gejala1 = Gejala::create(['code' => 'G001', 'gejala' => 'Demam']);
        $gejala2 = Gejala::create(['code' => 'G002', 'gejala' => 'Batuk']);
        $gejala3 = Gejala::create(['code' => 'G003', 'gejala' => 'Sesak Nafas']);
        $gejala4 = Gejala::create(['code' => 'G004', 'gejala' => 'Nafsu Makan Berkurang']);
        $gejala5 = Gejala::create(['code' => 'G005', 'gejala' => 'Muntah']);
        $gejala6 = Gejala::create(['code' => 'G006', 'gejala' => 'Diare']);

        // Create sample penyakit
        $penyakit1 = Penyakit::create([
            'code' => 'P001',
            'penyakit' => 'Flu',
            'solusi' => 'Istirahat dan minum air putih yang cukup, berikan makanan bergizi',
            'obat' => 'Paracetamol, Vitamin C'
        ]);

        $penyakit2 = Penyakit::create([
            'code' => 'P002',
            'penyakit' => 'Pneumonia',
            'solusi' => 'Segera ke dokter untuk pemeriksaan lebih lanjut, berikan antibiotik sesuai resep',
            'obat' => 'Antibiotik (resep dokter)'
        ]);

        $penyakit3 = Penyakit::create([
            'code' => 'P003',
            'penyakit' => 'Gastroenteritis',
            'solusi' => 'Berikan makanan lunak, jaga kebersihan, berikan oralit',
            'obat' => 'Oralit, Probiotik'
        ]);

        // Create decision tree structure
        $rootNode = DecisionNode::create([
            'gejala_id' => $gejala1->id, // Demam
            'parent_id' => null
        ]);

        // Child nodes for Demam = Ya
        $childNodeYa = DecisionNode::create([
            'gejala_id' => $gejala2->id, // Batuk
            'parent_id' => $rootNode->id,
            'jawaban' => 'ya'
        ]);

        // Child nodes for Demam = Tidak
        $childNodeTidak = DecisionNode::create([
            'penyakit_id' => $penyakit3->id, // Gastroenteritis
            'parent_id' => $rootNode->id,
            'jawaban' => 'tidak'
        ]);

        // Leaf nodes for Batuk = Ya
        $leafNodeYa = DecisionNode::create([
            'penyakit_id' => $penyakit2->id, // Pneumonia
            'parent_id' => $childNodeYa->id,
            'jawaban' => 'ya'
        ]);

        // Leaf nodes for Batuk = Tidak
        $leafNodeTidak = DecisionNode::create([
            'penyakit_id' => $penyakit1->id, // Flu
            'parent_id' => $childNodeYa->id,
            'jawaban' => 'tidak'
        ]);

        // Create aturan for each penyakit
        $aturan1 = Aturan::create(['code' => 'A001', 'penyakit_id' => $penyakit1->id]);
        $aturan2 = Aturan::create(['code' => 'A002', 'penyakit_id' => $penyakit2->id]);
        $aturan3 = Aturan::create(['code' => 'A003', 'penyakit_id' => $penyakit3->id]);

        // Attach gejala to aturan with CF values
        $aturan1->gejalas()->attach($gejala1->id, ['cf' => 0.8]);
        $aturan1->gejalas()->attach($gejala2->id, ['cf' => 0.6]);

        $aturan2->gejalas()->attach($gejala1->id, ['cf' => 0.9]);
        $aturan2->gejalas()->attach($gejala2->id, ['cf' => 0.7]);
        $aturan2->gejalas()->attach($gejala3->id, ['cf' => 0.8]);

        $aturan3->gejalas()->attach($gejala4->id, ['cf' => 0.7]);
        $aturan3->gejalas()->attach($gejala5->id, ['cf' => 0.6]);
        $aturan3->gejalas()->attach($gejala6->id, ['cf' => 0.8]);
    }
}
