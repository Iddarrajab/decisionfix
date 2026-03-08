# Panduan Pengisian Decision Nodes

## Overview

Decision Nodes adalah struktur pohon keputusan yang digunakan untuk mendiagnosa penyakit berdasarkan gejala yang dipilih. Setiap node dapat berupa:

-   **Node Gejala**: Node yang berisi pertanyaan tentang gejala
-   **Node Penyakit**: Node akhir (leaf) yang berisi hasil diagnosa

## Struktur Database

### Tabel Decision Nodes

```sql
CREATE TABLE decision_nodes (
    id BIGINT PRIMARY KEY,
    gejala_id BIGINT NULL,           -- ID gejala (untuk node gejala)
    penyakit_id BIGINT NULL,         -- ID penyakit (untuk node penyakit/leaf)
    parent_id BIGINT NULL,           -- ID parent node
    jawaban ENUM('ya', 'tidak') NULL, -- Jawaban terhadap parent
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Cara Mengisi Decision Nodes

### 1. Melalui Seeder (Otomatis)

Jalankan seeder untuk membuat data sample:

```bash
php artisan migrate:fresh --seed
```

Seeder akan membuat struktur pohon keputusan sebagai berikut:

```
                    Demam (G001)
                   /            \
                  /              \
                Ya              Tidak
                /                  \
               /                    \
           Batuk (G002)        Gastroenteritis (P003)
           /        \
          /          \
        Ya          Tidak
        /              \
       /                \
  Pneumonia (P002)   Flu (P001)
```

### 2. Melalui Tinker (Manual)

```bash
php artisan tinker
```

```php
// 1. Buat gejala
$gejala1 = App\Models\Gejala::create(['code' => 'G001', 'gejala' => 'Demam']);
$gejala2 = App\Models\Gejala::create(['code' => 'G002', 'gejala' => 'Batuk']);

// 2. Buat penyakit
$penyakit1 = App\Models\Penyakit::create([
    'code' => 'P001',
    'penyakit' => 'Flu',
    'solusi' => 'Istirahat dan minum air putih',
    'obat' => 'Paracetamol'
]);

// 3. Buat root node
$rootNode = App\Models\DecisionNode::create([
    'gejala_id' => $gejala1->id,
    'parent_id' => null
]);

// 4. Buat child nodes
$childNodeYa = App\Models\DecisionNode::create([
    'gejala_id' => $gejala2->id,
    'parent_id' => $rootNode->id,
    'jawaban' => 'ya'
]);

$childNodeTidak = App\Models\DecisionNode::create([
    'penyakit_id' => $penyakit1->id,
    'parent_id' => $rootNode->id,
    'jawaban' => 'tidak'
]);
```

### 3. Melalui Interface Admin

1. Buka halaman admin
2. Pilih menu "Decision Nodes"
3. Klik "Create New"
4. Isi form sesuai struktur yang diinginkan

## Struktur Pohon Keputusan

### Contoh Struktur Sederhana

```
                    Gejala A
                   /        \
                  /          \
                Ya          Tidak
                /              \
               /                \
           Gejala B        Penyakit X
           /        \
          /          \
        Ya          Tidak
        /              \
       /                \
  Penyakit Y        Penyakit Z
```

### Contoh Struktur Kompleks

```
                    Demam
                   /      \
                  /        \
                Ya        Tidak
                /            \
               /              \
           Batuk          Nafsu Makan
           /    \           /        \
          /      \         /          \
        Ya      Tidak    Ya          Tidak
        /        \       /              \
       /          \     /                \
  Sesak Nafas   Flu  Muntah          Sehat
       /              \
      /                \
   Ya                Tidak
    /                  \
   /                    \
Pneumonia          Diare
```

## Aturan Pengisian

### 1. Root Node

-   `gejala_id`: ID gejala pertama yang akan ditanyakan
-   `parent_id`: `null`
-   `jawaban`: `null`

### 2. Node Gejala (Non-Leaf)

-   `gejala_id`: ID gejala yang akan ditanyakan
-   `penyakit_id`: `null`
-   `parent_id`: ID node parent
-   `jawaban`: 'ya' atau 'tidak' (jawaban terhadap parent)

### 3. Node Penyakit (Leaf)

-   `gejala_id`: `null`
-   `penyakit_id`: ID penyakit hasil diagnosa
-   `parent_id`: ID node parent
-   `jawaban`: 'ya' atau 'tidak' (jawaban terhadap parent)

## Contoh Implementasi Lengkap

### 1. Buat Gejala

```php
$gejala = [
    'demam' => Gejala::create(['code' => 'G001', 'gejala' => 'Demam']),
    'batuk' => Gejala::create(['code' => 'G002', 'gejala' => 'Batuk']),
    'sesak' => Gejala::create(['code' => 'G003', 'gejala' => 'Sesak Nafas']),
    'muntah' => Gejala::create(['code' => 'G004', 'gejala' => 'Muntah']),
];
```

### 2. Buat Penyakit

```php
$penyakit = [
    'flu' => Penyakit::create([
        'code' => 'P001',
        'penyakit' => 'Flu',
        'solusi' => 'Istirahat dan minum air putih',
        'obat' => 'Paracetamol'
    ]),
    'pneumonia' => Penyakit::create([
        'code' => 'P002',
        'penyakit' => 'Pneumonia',
        'solusi' => 'Segera ke dokter',
        'obat' => 'Antibiotik'
    ]),
    'gastro' => Penyakit::create([
        'code' => 'P003',
        'penyakit' => 'Gastroenteritis',
        'solusi' => 'Berikan makanan lunak',
        'obat' => 'Oralit'
    ]),
];
```

### 3. Buat Decision Tree

```php
// Root node
$root = DecisionNode::create([
    'gejala_id' => $gejala['demam']->id,
    'parent_id' => null
]);

// Child nodes untuk Demam = Ya
$demamYa = DecisionNode::create([
    'gejala_id' => $gejala['batuk']->id,
    'parent_id' => $root->id,
    'jawaban' => 'ya'
]);

// Child nodes untuk Demam = Tidak
$demamTidak = DecisionNode::create([
    'penyakit_id' => $penyakit['gastro']->id,
    'parent_id' => $root->id,
    'jawaban' => 'tidak'
]);

// Leaf nodes untuk Batuk = Ya
$batukYa = DecisionNode::create([
    'penyakit_id' => $penyakit['pneumonia']->id,
    'parent_id' => $demamYa->id,
    'jawaban' => 'ya'
]);

// Leaf nodes untuk Batuk = Tidak
$batukTidak = DecisionNode::create([
    'penyakit_id' => $penyakit['flu']->id,
    'parent_id' => $demamYa->id,
    'jawaban' => 'tidak'
]);
```

## Validasi dan Testing

### 1. Validasi Struktur

```php
// Cek apakah root node ada
$rootNode = DecisionNode::whereNull('parent_id')->first();
if (!$rootNode) {
    throw new Exception('Root node tidak ditemukan');
}

// Cek apakah semua leaf node memiliki penyakit
$leafNodes = DecisionNode::whereNotNull('penyakit_id')->get();
foreach ($leafNodes as $node) {
    if (!$node->penyakit) {
        throw new Exception("Leaf node {$node->id} tidak memiliki penyakit");
    }
}
```

### 2. Testing Traversal

```php
// Test traversal
$jawaban = [
    $gejala['demam']->id => 'ya',
    $gejala['batuk']->id => 'ya'
];

$result = $decisionTreeService->traverseDecisionTree($rootNode, $jawaban);
echo "Hasil: " . $result->first()->penyakit;
```

## Troubleshooting

### 1. Node Tidak Ditemukan

-   Pastikan `parent_id` sudah benar
-   Cek apakah node parent sudah ada

### 2. Traversal Error

-   Pastikan struktur pohon sudah benar
-   Cek apakah semua jawaban ('ya'/'tidak') sudah sesuai

### 3. Leaf Node Error

-   Pastikan `penyakit_id` sudah diisi untuk leaf node
-   Cek apakah penyakit sudah ada di database

## Tips dan Best Practices

1. **Gunakan Seeder**: Gunakan seeder untuk data awal yang konsisten
2. **Validasi Struktur**: Selalu validasi struktur pohon sebelum digunakan
3. **Testing**: Test traversal dengan berbagai kombinasi jawaban
4. **Backup**: Backup data sebelum melakukan perubahan besar
5. **Dokumentasi**: Dokumentasikan struktur pohon untuk referensi

## Contoh Penggunaan

Setelah decision nodes terisi, sistem akan:

1. **Menampilkan pertanyaan gejala** berdasarkan traversal pohon
2. **Mengumpulkan jawaban** dari pengguna (ya/tidak)
3. **Menelusuri pohon** berdasarkan jawaban
4. **Menghasilkan diagnosa** dengan nilai CF
5. **Menampilkan hasil** beserta solusi dan obat
