# Sistem Diagnosa dengan Decision Tree dan Certainty Factor

## Overview

Sistem ini mengimplementasikan metode diagnosa penyakit hewan menggunakan kombinasi **Decision Tree** dan **Certainty Factor (CF)** untuk memberikan hasil diagnosa yang akurat dan dapat dipercaya.

## Fitur Utama

### 1. Decision Tree (Pohon Keputusan)

-   **Struktur Hierarkis**: Sistem menggunakan struktur pohon keputusan untuk mengorganisir gejala dan penyakit
-   **Traversal Otomatis**: Sistem secara otomatis menelusuri pohon berdasarkan jawaban pengguna
-   **Fleksibilitas**: Mendukung struktur pohon yang kompleks dengan multiple level

### 2. Certainty Factor (CF)

-   **Perhitungan CF**: Menggunakan formula CF = CF1 + CF2 \* (1 - CF1) untuk menggabungkan nilai CF
-   **Nilai Pakar**: Setiap gejala memiliki nilai CF dari pakar (0.1 - 1.0)
-   **Input Pengguna**: Pengguna dapat memberikan tingkat keyakinan (0.1 - 1.0) untuk setiap gejala
-   **Kombinasi**: Sistem menggabungkan CF pakar dengan CF pengguna

## Cara Kerja

### 1. Proses Diagnosa

```php
// 1. Input gejala dan nilai CF dari pengguna
$gejalaDipilih = [1, 2, 3]; // ID gejala yang dipilih
$cfUser = [
    1 => 0.8, // CF pengguna untuk gejala 1
    2 => 0.6, // CF pengguna untuk gejala 2
    3 => 0.9  // CF pengguna untuk gejala 3
];

// 2. Traverse decision tree
$rootNode = DecisionNode::find(1);
$jawabanBool = $decisionTreeService->mapCfToBoolean($cfUser);
$kandidatPenyakit = $decisionTreeService->traverseDecisionTree($rootNode, $jawabanBool);

// 3. Hitung CF untuk setiap kandidat penyakit
$hasil = $decisionTreeService->calculateCertaintyFactor($gejalaDipilih, $cfUser, $kandidatPenyakit);
```

### 2. Formula Certainty Factor

```php
// Formula kombinasi CF
private function combineCf(?float $cf1, float $cf2): float
{
    if ($cf1 === null) {
        return $cf2;
    }

    // CF = CF1 + CF2 * (1 - CF1)
    return $cf1 + $cf2 * (1 - $cf1);
}
```

### 3. Mapping CF ke Boolean

```php
// CF > 0.5 = 'ya', CF <= 0.5 = 'tidak'
public function mapCfToBoolean(array $cfUser): array
{
    return array_map(function ($cf) {
        return $cf > 0.5 ? 'ya' : 'tidak';
    }, $cfUser);
}
```

## Struktur Database

### Tabel Decision Nodes

```sql
CREATE TABLE decision_nodes (
    id BIGINT PRIMARY KEY,
    gejala_id BIGINT NULL,
    penyakit_id BIGINT NULL,
    parent_id BIGINT NULL,
    jawaban ENUM('ya', 'tidak') NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Tabel Aturan Gejala

```sql
CREATE TABLE aturan_gejala (
    id BIGINT PRIMARY KEY,
    aturan_id BIGINT,
    gejala_id BIGINT,
    cf FLOAT DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Komponen Sistem

### 1. DecisionTreeService

-   **Lokasi**: `app/Services/DecisionTreeService.php`
-   **Fungsi**: Menangani logika decision tree dan certainty factor
-   **Method Utama**:
    -   `traverseDecisionTree()`: Menelusuri pohon keputusan
    -   `calculateCertaintyFactor()`: Menghitung nilai CF
    -   `mapCfToBoolean()`: Mengkonversi CF ke boolean
    -   `getDecisionPath()`: Mendapatkan jalur keputusan

### 2. DecisionNode Model

-   **Lokasi**: `app/Models/DecisionNode.php`
-   **Fungsi**: Model untuk node pohon keputusan
-   **Method Utama**:
    -   `isLeafNode()`: Cek apakah node adalah daun
    -   `isRootNode()`: Cek apakah node adalah root
    -   `getTreeStructure()`: Mendapatkan struktur pohon
    -   `getReachableDiseases()`: Mendapatkan penyakit yang dapat dicapai

### 3. DiagnosaController

-   **Lokasi**: `app/Http/Controllers/DiagnosaController.php`
-   **Fungsi**: Controller untuk menangani proses diagnosa
-   **Method Utama**:
    -   `store()`: Menyimpan hasil diagnosa
    -   `show()`: Menampilkan detail diagnosa

## Interface Pengguna

### 1. Form Diagnosa

-   **Fitur**: Input gejala dengan nilai CF
-   **Validasi**: CF harus antara 0.1 - 1.0
-   **Quick Set**: Pilihan cepat untuk nilai CF (Sangat Tidak Yakin, Tidak Yakin, Cukup Yakin, Yakin, Sangat Yakin)

### 2. Hasil Diagnosa

-   **Jalur Pohon**: Menampilkan jalur keputusan yang diambil
-   **Nilai CF**: Menampilkan nilai CF untuk setiap penyakit
-   **Ranking**: Mengurutkan hasil berdasarkan nilai CF tertinggi
-   **Detail**: Informasi lengkap tentang penyakit, solusi, dan obat

## Testing

### Unit Tests

-   **Lokasi**: `tests/Feature/DecisionTreeTest.php`
-   **Coverage**: Decision tree traversal, CF calculation, path generation
-   **Command**: `php artisan test tests/Feature/DecisionTreeTest.php`

## Keunggulan Sistem

1. **Akurasi Tinggi**: Kombinasi decision tree dan CF memberikan hasil yang lebih akurat
2. **Fleksibilitas**: Mudah menambah gejala dan penyakit baru
3. **Transparansi**: Pengguna dapat melihat jalur keputusan dan perhitungan CF
4. **User-Friendly**: Interface yang mudah digunakan dengan validasi otomatis
5. **Scalable**: Arsitektur yang mendukung pertumbuhan sistem

## Penggunaan

### 1. Setup Awal

```bash
# Install dependencies
composer install

# Setup database
php artisan migrate

# Seed data (jika ada)
php artisan db:seed
```

### 2. Menjalankan Sistem

```bash
# Start server
php artisan serve

# Akses aplikasi
http://localhost:8000
```

### 3. Membuat Diagnosa

1. Buka halaman diagnosa
2. Pilih gejala yang muncul
3. Berikan nilai CF untuk setiap gejala
4. Submit form
5. Lihat hasil diagnosa dengan detail

## Troubleshooting

### 1. Decision Tree Tidak Ditemukan

-   Pastikan ada root node (ID = 1) di tabel `decision_nodes`
-   Cek struktur pohon keputusan

### 2. CF Calculation Error

-   Pastikan nilai CF antara 0.1 - 1.0
-   Cek relasi antara aturan dan gejala

### 3. Database Issues

-   Jalankan `php artisan migrate:fresh` untuk reset database
-   Cek foreign key constraints

## Kontribusi

Untuk berkontribusi pada sistem ini:

1. Fork repository
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

Sistem ini dikembangkan untuk tujuan edukasi dan penelitian.
