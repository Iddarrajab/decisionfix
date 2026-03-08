# 📝 Contoh Praktis: Mengisi Decision Node untuk Rabies & Distemper

## 🎯 Tujuan

Membuat Decision Tree untuk:
- **Rabies** dengan gejala: **Gatal**, **Sesak Nafas**
- **Distemper** dengan gejala: **Rontok**, **Demam**, **Lemas**

---

## 📋 LANGKAH 1: Pastikan Data Gejala dan Penyakit Sudah Ada

Sebelum membuat Decision Node, pastikan:

### ✅ Gejala sudah dibuat:
1. Login Admin → Menu **"Gejala"**
2. Pastikan ada gejala berikut (atau buat jika belum ada):
   - Gatal
   - Sesak Nafas
   - Rontok
   - Demam
   - Lemas

### ✅ Penyakit sudah dibuat:
1. Login Admin → Menu **"Penyakit"**
2. Pastikan ada penyakit:
   - Rabies
   - Distemper

---

## 🌳 LANGKAH 2: Membuat Decision Tree

Struktur yang akan dibuat:

```
                    [ROOT: Demam]
                           |
            ┌───────────────┴───────────────┐
            |                               |
        Ya: Rontok                      Tidak: Gatal
     [NODE-DIST-1]                   [NODE-RAB-1]
            |                               |
    ┌───────┴───────┐               ┌───────┴───────┐
    |               |               |               |
Ya: Lemas    Tidak: Distemper  Ya: Sesak Nafas  Tidak: Rabies
[NODE-DIST-2]  [LEAF-DIST]     [NODE-RAB-2]    [LEAF-RAB]
    |                                               |
┌───┴───┐                                       ┌───┴───┐
|       |                                       |       |
Ya  Tidak: Distemper                      Ya  Tidak: Rabies
[LEAF-DIST]                              [LEAF-RAB]
```

---

## 📝 LANGKAH 3: Mengisi Form Decision Node (Step by Step)

### **NODE 1: ROOT NODE (Demam)**

1. Login Admin → Menu **"Decision Nodes"**
2. Klik **"Tambah Node"**
3. Isi form:

```
┌─────────────────────────────────────────────┐
│ Kode Node: ROOT-DEMAM                       │
│ Parent Node: [-- Pilih Parent --] ← KOSONG │
│ Gejala: [Pilih: Demam]                     │
│ Penyakit: [-- Pilih Penyakit --] ← KOSONG │
│ Jawaban "Ya": [Kosongkan atau isi: Rontok] │
│ Jawaban "Tidak": [Kosongkan atau isi: Gatal]│
│ ☐ Node Akhir (Leaf) ← JANGAN dicentang     │
│                                              │
│ [Simpan Node]                                │
└─────────────────────────────────────────────┘
```

**Penjelasan:**
- Ini adalah node pertama (root), jadi tidak punya parent
- Node ini menanyakan gejala "Demam"
- Bukan node akhir, jadi ada cabang selanjutnya

---

### **NODE 2: Child untuk Demam = Ya (Rontok) - NODE-DIST-1**

1. Klik **"Tambah Node"** lagi
2. Isi form:

```
┌─────────────────────────────────────────────┐
│ Kode Node: NODE-DIST-1                      │
│ Parent Node: [Pilih: ROOT-DEMAM] ← PENTING! │
│ Gejala: [Pilih: Rontok]                     │
│ Penyakit: [-- Pilih Penyakit --] ← KOSONG  │
│ Jawaban "Ya": [Kosongkan atau isi: Lemas]   │
│ Jawaban "Tidak": [Kosongkan]                │
│ ☐ Node Akhir (Leaf) ← JANGAN dicentang      │
│                                              │
│ [Simpan Node]                                │
└─────────────────────────────────────────────┘
```

**Penjelasan:**
- Parent-nya adalah ROOT-DEMAM (node yang baru dibuat)
- Ini adalah cabang "Ya" dari pertanyaan Demam
- Jika user jawab Demam = Ya, maka ditanya tentang Rontok

---

### **NODE 3: Child untuk Demam = Ya, Rontok = Tidak → DISTEMPER**

1. Klik **"Tambah Node"** lagi
2. Isi form:

```
┌─────────────────────────────────────────────┐
│ Kode Node: LEAF-DIST-1                      │
│ Parent Node: [Pilih: NODE-DIST-1]           │
│ Gejala: [-- Pilih Gejala --] ← KOSONG      │
│ Penyakit: [Pilih: Distemper] ← PENTING!    │
│ Jawaban "Ya": [Kosongkan]                   │
│ Jawaban "Tidak": [Kosongkan]                │
│ ☑ Node Akhir (Leaf) ← CENTANG!              │
│                                              │
│ [Simpan Node]                                │
└─────────────────────────────────────────────┘
```

**Penjelasan:**
- Parent-nya adalah NODE-DIST-1
- Ini adalah hasil akhir, jadi CENTANG "Node Akhir (Leaf)"
- Pilih penyakit "Distemper"
- Gejala dikosongkan karena ini node akhir

**Alur:** Demam = Ya → Rontok = Tidak → **Distemper**

---

### **NODE 4: Child untuk Demam = Ya, Rontok = Ya → Lemas**

1. Klik **"Tambah Node"** lagi
2. Isi form:

```
┌─────────────────────────────────────────────┐
│ Kode Node: NODE-DIST-2                      │
│ Parent Node: [Pilih: NODE-DIST-1]           │
│ Gejala: [Pilih: Lemas]                      │
│ Penyakit: [-- Pilih Penyakit --] ← KOSONG  │
│ Jawaban "Ya": [Kosongkan]                   │
│ Jawaban "Tidak": [Kosongkan]                │
│ ☐ Node Akhir (Leaf) ← JANGAN dicentang     │
│                                              │
│ [Simpan Node]                                │
└─────────────────────────────────────────────┘
```

**Penjelasan:**
- Parent-nya adalah NODE-DIST-1
- Jika Rontok = Ya, maka ditanya tentang Lemas

---

### **NODE 5: Child untuk Demam = Ya, Rontok = Ya, Lemas = Ya/Tidak → DISTEMPER**

1. Klik **"Tambah Node"** untuk jawaban Ya
2. Isi form:

```
┌─────────────────────────────────────────────┐
│ Kode Node: LEAF-DIST-2                      │
│ Parent Node: [Pilih: NODE-DIST-2]           │
│ Gejala: [-- Pilih Gejala --] ← KOSONG      │
│ Penyakit: [Pilih: Distemper]                │
│ Jawaban "Ya": [Kosongkan]                   │
│ Jawaban "Tidak": [Kosongkan]                │
│ ☑ Node Akhir (Leaf) ← CENTANG!              │
│                                              │
│ [Simpan Node]                                │
└─────────────────────────────────────────────┘
```

**Alur:** Demam = Ya → Rontok = Ya → Lemas = Ya → **Distemper**

---

### **NODE 6: Child untuk Demam = Tidak (Gatal) - NODE-RAB-1**

1. Klik **"Tambah Node"** lagi
2. Isi form:

```
┌─────────────────────────────────────────────┐
│ Kode Node: NODE-RAB-1                       │
│ Parent Node: [Pilih: ROOT-DEMAM]            │
│ Gejala: [Pilih: Gatal]                      │
│ Penyakit: [-- Pilih Penyakit --] ← KOSONG  │
│ Jawaban "Ya": [Kosongkan atau isi: Sesak Nafas]│
│ Jawaban "Tidak": [Kosongkan]                │
│ ☐ Node Akhir (Leaf) ← JANGAN dicentang     │
│                                              │
│ [Simpan Node]                                │
└─────────────────────────────────────────────┘
```

**Penjelasan:**
- Parent-nya adalah ROOT-DEMAM (node yang sama dengan NODE-DIST-1)
- Ini adalah cabang "Tidak" dari pertanyaan Demam
- Jika user jawab Demam = Tidak, maka ditanya tentang Gatal

---

### **NODE 7: Child untuk Demam = Tidak, Gatal = Tidak → RABIES**

1. Klik **"Tambah Node"** lagi
2. Isi form:

```
┌─────────────────────────────────────────────┐
│ Kode Node: LEAF-RAB-1                       │
│ Parent Node: [Pilih: NODE-RAB-1]            │
│ Gejala: [-- Pilih Gejala --] ← KOSONG      │
│ Penyakit: [Pilih: Rabies] ← PENTING!       │
│ Jawaban "Ya": [Kosongkan]                   │
│ Jawaban "Tidak": [Kosongkan]                │
│ ☑ Node Akhir (Leaf) ← CENTANG!             │
│                                              │
│ [Simpan Node]                                │
└─────────────────────────────────────────────┘
```

**Alur:** Demam = Tidak → Gatal = Tidak → **Rabies**

---

### **NODE 8: Child untuk Demam = Tidak, Gatal = Ya → Sesak Nafas**

1. Klik **"Tambah Node"** lagi
2. Isi form:

```
┌─────────────────────────────────────────────┐
│ Kode Node: NODE-RAB-2                       │
│ Parent Node: [Pilih: NODE-RAB-1]            │
│ Gejala: [Pilih: Sesak Nafas]                │
│ Penyakit: [-- Pilih Penyakit --] ← KOSONG  │
│ Jawaban "Ya": [Kosongkan]                   │
│ Jawaban "Tidak": [Kosongkan]                │
│ ☐ Node Akhir (Leaf) ← JANGAN dicentang     │
│                                              │
│ [Simpan Node]                                │
└─────────────────────────────────────────────┘
```

---

### **NODE 9 & 10: Child untuk Sesak Nafas → RABIES**

Buat 2 node leaf (untuk Ya dan Tidak):

**Node 9: Sesak Nafas = Ya → Rabies**

```
Kode Node: LEAF-RAB-2
Parent Node: NODE-RAB-2
Gejala: [KOSONG]
Penyakit: Rabies
☑ Node Akhir (Leaf)
```

**Node 10: Sesak Nafas = Tidak → Rabies**

```
Kode Node: LEAF-RAB-3
Parent Node: NODE-RAB-2
Gejala: [KOSONG]
Penyakit: Rabies
☑ Node Akhir (Leaf)
```

**Alur:** 
- Demam = Tidak → Gatal = Ya → Sesak Nafas = Ya → **Rabies**
- Demam = Tidak → Gatal = Ya → Sesak Nafas = Tidak → **Rabies**

---

## 📊 Ringkasan Node yang Dibuat

| No | Kode Node | Parent | Gejala | Penyakit | Leaf | Jalur |
|----|-----------|--------|--------|----------|------|-------|
| 1 | ROOT-DEMAM | - | Demam | - | ☐ | Root |
| 2 | NODE-DIST-1 | ROOT-DEMAM | Rontok | - | ☐ | Ya |
| 3 | LEAF-DIST-1 | NODE-DIST-1 | - | Distemper | ☑ | Tidak |
| 4 | NODE-DIST-2 | NODE-DIST-1 | Lemas | - | ☐ | Ya |
| 5 | LEAF-DIST-2 | NODE-DIST-2 | - | Distemper | ☑ | Ya |
| 6 | NODE-RAB-1 | ROOT-DEMAM | Gatal | - | ☐ | Tidak |
| 7 | LEAF-RAB-1 | NODE-RAB-1 | - | Rabies | ☑ | Tidak |
| 8 | NODE-RAB-2 | NODE-RAB-1 | Sesak Nafas | - | ☐ | Ya |
| 9 | LEAF-RAB-2 | NODE-RAB-2 | - | Rabies | ☑ | Ya |
| 10 | LEAF-RAB-3 | NODE-RAB-2 | - | Rabies | ☑ | Tidak |

---

## 📋 LANGKAH 4: Membuat Aturan untuk CF Calculation

Setelah Decision Node dibuat, buat **Aturan** untuk perhitungan CF:

### **Aturan untuk Distemper:**

1. Login Admin → Menu **"Aturan"**
2. Klik **"Tambah Aturan"**
3. Isi:

```
Kode Aturan: R-DIST-01
Nama Penyakit: Distemper

Pilih Gejala:
  ☑ Rontok     → CF: 0.8
  ☑ Demam      → CF: 0.9
  ☑ Lemas      → CF: 0.7
```

### **Aturan untuk Rabies:**

1. Klik **"Tambah Aturan"** lagi
2. Isi:

```
Kode Aturan: R-RAB-01
Nama Penyakit: Rabies

Pilih Gejala:
  ☑ Gatal          → CF: 0.8
  ☑ Sesak Nafas    → CF: 0.7
```

---

## ✅ Checklist

Setelah selesai, pastikan:

- [ ] Ada 1 root node (ROOT-DEMAM)
- [ ] Semua node leaf memiliki penyakit (Distemper atau Rabies)
- [ ] Semua node non-leaf memiliki gejala
- [ ] Setiap penyakit memiliki aturan dengan gejala yang benar
- [ ] CF Pakar untuk setiap gejala > 0

---

## 🧪 Testing

Coba diagnosa dengan:
- **Distemper**: Pilih gejala Demam, Rontok, Lemas
- **Rabies**: Pilih gejala Gatal, Sesak Nafas

Hasil seharusnya menunjuk ke penyakit yang benar dengan CF > 0%.

