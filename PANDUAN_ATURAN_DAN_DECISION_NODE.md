# 📘 Panduan Lengkap: Cara Mengisi Aturan dan Decision Node

## 🎯 Tujuan

Sistem diagnosa menggunakan 2 komponen utama:
1. **Aturan (Rules)** - Menghitung Certainty Factor (CF) untuk menentukan keyakinan diagnosa
2. **Decision Node** - Pohon keputusan untuk menemukan kandidat penyakit berdasarkan gejala

---

## 📋 BAGIAN 1: MENGISI ATURAN

### Langkah-langkah:

1. **Login sebagai Admin** → Buka menu **"Aturan"**

2. **Klik "Tambah Aturan"** atau **Edit** aturan yang sudah ada

3. **Isi Form Aturan:**

   ```
   ┌─────────────────────────────────────────┐
   │ Kode Aturan: [R01, R02, dst]           │
   │ Nama Penyakit: [Pilih dari dropdown]    │
   │                                          │
   │ Pilih Gejala dan Nilai CF:              │
   │ ┌─────────────────────────────────────┐ │
   │ │ # │ Nama Gejala │ Pilih │ CF        │ │
   │ ├───┼─────────────┼───────┼───────────┤ │
   │ │ 1 │ Demam       │  ☑   │ [0.8]     │ │
   │ │ 2 │ Batuk       │  ☑   │ [0.7]     │ │
   │ │ 3 │ Sesak Nafas │  ☐   │ [disabled]│ │
   │ └─────────────────────────────────────┘ │
   │                                          │
   │ [Simpan]                                 │
   └─────────────────────────────────────────┘
   ```

4. **Penjelasan Field:**

   - **Kode Aturan**: Kode unik untuk aturan (contoh: R01, R02, RABIES-01)
   - **Nama Penyakit**: Pilih penyakit yang ingin dibuat aturannya
   - **Pilih Gejala**: Centang checkbox gejala yang terkait dengan penyakit tersebut
   - **CF (Certainty Factor)**: Nilai keyakinan pakar (0.0 - 1.0)
     - **0.1 - 0.3**: Keyakinan rendah
     - **0.4 - 0.6**: Keyakinan sedang
     - **0.7 - 0.9**: Keyakinan tinggi
     - **1.0**: Keyakinan sangat tinggi

5. **Quick Set CF**: Gunakan dropdown "Quick Set CF" untuk mengisi semua CF sekaligus

### ⚠️ PENTING - Aturan yang Benar:

✅ **Benar:**
- Setiap penyakit memiliki minimal 1 aturan
- Setiap aturan memiliki minimal 1 gejala
- Setiap gejala memiliki CF Pakar > 0
- Gejala yang dipilih user saat diagnosa HARUS ada di aturan

❌ **Salah:**
- Aturan tanpa gejala (CF akan = 0%)
- CF Pakar = 0 atau null
- Gejala di aturan tidak cocok dengan gejala yang dipilih user

### 📝 Contoh: Aturan untuk Penyakit "Rabies"

```
Kode Aturan: RABIES-01
Nama Penyakit: Rabies

Gejala yang Dipilih:
  ☑ Gejala 1: Air liur berlebihan        CF: 0.9
  ☑ Gejala 2: Agresif/takut air          CF: 0.8
  ☑ Gejala 3: Kejang-kejang               CF: 0.7
  ☑ Gejala 4: Lumpuh                      CF: 0.6
```

---

## 🌳 BAGIAN 2: MENGISI DECISION NODE

### Konsep Decision Tree:

Decision Node adalah struktur pohon yang digunakan untuk **menemukan kandidat penyakit** berdasarkan gejala yang dipilih user.

```
                    [Root Node: Gejala 1]
                           |
            ┌───────────────┴───────────────┐
            |                               |
      [Ya: Gejala 2]                [Tidak: Gejala 3]
            |                               |
    ┌───────┴───────┐               ┌───────┴───────┐
    |               |               |               |
[Ya: Penyakit A] [Tidak: Penyakit B] [Ya: Penyakit C] [Tidak: Penyakit D]
```

### Langkah-langkah:

1. **Login sebagai Admin** → Buka menu **"Decision Nodes"**

2. **Buat Root Node (Node Pertama):**
   
   ```
   ┌─────────────────────────────────────────┐
   │ Kode Node: ROOT-001                     │
   │ Parent Node: [-- Pilih Parent --] ← KOSONGKAN (ini root)
   │ Gejala: [Pilih Gejala Pertama]          │
   │ Penyakit: [-- Pilih Penyakit --] ← KOSONGKAN
   │ Jawaban "Ya": [optional]                │
   │ Jawaban "Tidak": [optional]              │
   │ ☐ Node Akhir (Leaf) ← JANGAN dicentang │
   │                                          │
   │ [Simpan Node]                            │
   └─────────────────────────────────────────┘
   ```

3. **Buat Child Nodes:**

   Untuk setiap parent node, buat 2 child node (untuk jawaban Ya dan Tidak):
   
   **Child Node 1 (Jawaban "Ya"):**
   ```
   Kode Node: NODE-002
   Parent Node: ROOT-001 ← Pilih parent
   Gejala: [Pilih Gejala Kedua] atau [Kosongkan jika leaf]
   Penyakit: [Kosongkan] atau [Pilih jika ini leaf node]
   ☑ Node Akhir (Leaf) ← Centang jika ini hasil akhir
   ```

   **Child Node 2 (Jawaban "Tidak"):**
   ```
   Kode Node: NODE-003
   Parent Node: ROOT-001 ← Pilih parent yang sama
   Gejala: [Pilih Gejala Alternatif] atau [Kosongkan jika leaf]
   Penyakit: [Kosongkan] atau [Pilih jika ini leaf node]
   ☑ Node Akhir (Leaf) ← Centang jika ini hasil akhir
   ```

### Penjelasan Field:

- **Kode Node**: Kode unik (contoh: ROOT-001, NODE-002, LEAF-001)
- **Parent Node**: 
  - **Root Node**: Kosongkan (tidak punya parent)
  - **Child Node**: Pilih parent node
- **Gejala**: 
  - **Node Gejala**: Pilih gejala yang akan ditanyakan
  - **Node Penyakit (Leaf)**: Kosongkan
- **Penyakit**:
  - **Node Gejala**: Kosongkan
  - **Node Penyakit (Leaf)**: Pilih penyakit hasil diagnosa
- **Node Akhir (Leaf)**: 
  - **Centang** jika node ini berisi penyakit (hasil akhir)
  - **Tidak centang** jika node ini berisi gejala (masih ada cabang)

### ⚠️ PENTING - Decision Node yang Benar:

✅ **Benar:**
- Ada 1 root node (tanpa parent)
- Setiap node gejala memiliki 2 child (Ya dan Tidak)
- Node leaf harus memiliki penyakit
- Node non-leaf harus memiliki gejala

❌ **Salah:**
- Root node memiliki parent
- Node tanpa child (kecuali leaf)
- Node leaf tanpa penyakit
- Node non-leaf tanpa gejala

### 📝 Contoh: Decision Tree untuk 3 Penyakit

**Struktur:**
```
                    Demam (ROOT-001)
                           |
            ┌───────────────┴───────────────┐
            |                               |
        Ya: Batuk                     Tidak: Muntah
        (NODE-002)                    (NODE-003)
            |                               |
    ┌───────┴───────┐               ┌───────┴───────┐
    |               |               |               |
Ya: Pneumonia  Tidak: Flu    Ya: Gastroenteritis Tidak: Rabies
(LEAF-001)     (LEAF-002)    (LEAF-003)         (LEAF-004)
```

**Cara Membuat:**

1. **Root Node (ROOT-001):**
   - Kode Node: `ROOT-001`
   - Parent: [Kosong]
   - Gejala: `Demam`
   - Penyakit: [Kosong]
   - Leaf: ☐

2. **Child Node Ya (NODE-002):**
   - Kode Node: `NODE-002`
   - Parent: `ROOT-001`
   - Gejala: `Batuk`
   - Penyakit: [Kosong]
   - Leaf: ☐

3. **Child Node Tidak (NODE-003):**
   - Kode Node: `NODE-003`
   - Parent: `ROOT-001`
   - Gejala: `Muntah`
   - Penyakit: [Kosong]
   - Leaf: ☐

4. **Leaf Nodes:**
   
   **LEAF-001 (Ya → Ya):**
   - Kode Node: `LEAF-001`
   - Parent: `NODE-002`
   - Gejala: [Kosong]
   - Penyakit: `Pneumonia`
   - Leaf: ☑

   **LEAF-002 (Ya → Tidak):**
   - Kode Node: `LEAF-002`
   - Parent: `NODE-002`
   - Gejala: [Kosong]
   - Penyakit: `Flu`
   - Leaf: ☑

   **LEAF-003 (Tidak → Ya):**
   - Kode Node: `LEAF-003`
   - Parent: `NODE-003`
   - Gejala: [Kosong]
   - Penyakit: `Gastroenteritis`
   - Leaf: ☑

   **LEAF-004 (Tidak → Tidak):**
   - Kode Node: `LEAF-004`
   - Parent: `NODE-003`
   - Gejala: [Kosong]
   - Penyakit: `Rabies`
   - Leaf: ☑

---

## 🔄 Alur Kerja Sistem Diagnosa

1. **User memilih gejala** saat diagnosa
2. **Decision Tree** mencari kandidat penyakit berdasarkan gejala yang dipilih
3. **Aturan** menghitung CF untuk setiap kandidat penyakit:
   - CF(Gejala) = CF(Pakar) × CF(User)
   - CF(Kombinasi) = CF(old) + CF(new) × (1 - CF(old))
4. **Hasil**: Penyakit dengan CF tertinggi dipilih

---

## ✅ Checklist Sebelum Diagnosa

### Aturan:
- [ ] Semua penyakit memiliki minimal 1 aturan
- [ ] Setiap aturan memiliki minimal 1 gejala
- [ ] Setiap gejala memiliki CF Pakar > 0
- [ ] Gejala di aturan cocok dengan gejala yang bisa dipilih user

### Decision Node:
- [ ] Ada 1 root node (tanpa parent)
- [ ] Semua node leaf memiliki penyakit
- [ ] Semua node non-leaf memiliki gejala
- [ ] Setiap penyakit yang ada di diagnosa sudah ada di decision tree

---

## 🆘 Troubleshooting

### Masalah: CF = 0%

**Penyebab:**
- Aturan tidak ada untuk penyakit tersebut
- Aturan ada tapi tidak punya gejala
- Gejala yang dipilih user tidak cocok dengan gejala di aturan
- CF Pakar = 0 atau null

**Solusi:**
1. Cek menu Admin → Aturan
2. Edit aturan untuk penyakit tersebut
3. Pastikan ada gejala yang terhubung
4. Pastikan CF Pakar > 0

### Masalah: Tidak ditemukan penyakit berdasarkan gejala

**Penyebab:**
- Decision tree belum dibuat
- Tidak ada root node
- Gejala yang dipilih tidak ada di decision tree

**Solusi:**
1. Buat decision tree di menu Admin → Decision Nodes
2. Pastikan ada root node
3. Pastikan gejala yang dipilih user ada di decision tree

---

## 📚 Referensi

- File: `docs/DECISION_NODES_GUIDE.md` - Dokumentasi teknis decision nodes
- File: `README_DECISION_TREE.md` - Penjelasan decision tree
- Menu Admin → Aturan - Form untuk mengisi aturan
- Menu Admin → Decision Nodes - Form untuk mengisi decision tree

