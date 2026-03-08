# 🌳 Struktur Decision Tree untuk Banyak Penyakit

## ❌ MITOS: 1 Root = 2 Penyakit

**Tidak benar!** Anda tidak perlu 5 root untuk 10 penyakit.

## ✅ KENYATAAN: 1 Root Bisa Untuk Banyak Penyakit

**1 root bisa menghasilkan puluhan bahkan ratusan penyakit** tergantung struktur pohon keputusan.

---

## 📊 STRUKTUR YANG BENAR

### Opsi 1: 1 ROOT untuk Semua Penyakit (RECOMMENDED)

```
                    [ROOT: Gejala Utama]
                           |
        ┌──────────────────┼──────────────────┐
        |                  |                  |
    [Ya: Gejala2]    [Tidak: Gejala3]    [Mungkin: Gejala4]
        |                  |                  |
    ┌───┴───┐          ┌───┴───┐          ┌───┴───┐
    |       |          |       |          |       |
  [Ya]   [Tidak]     [Ya]   [Tidak]     [Ya]   [Tidak]
   |       |          |       |          |       |
 Penyakit Penyakit  Penyakit Penyakit Penyakit Penyakit
 1 & 2    3 & 4      5 & 6    7 & 8      9      10
```

**1 Root → 10 Penyakit!**

---

## 🎯 STRATEGI MEMBUAT DECISION TREE

### Strategi 1: Hierarki Gejala Umum → Khusus

**Contoh untuk 10 Penyakit:**

```
                    [ROOT: Demam Tinggi?]
                           |
            ┌──────────────┴──────────────┐
            |                             |
        Ya: [Gejala Sistemik]        Tidak: [Gejala Lokal]
            |                             |
    ┌───────┼───────┐             ┌───────┼───────┐
    |       |       |             |       |       |
  Ya:    Ya:    Tidak:          Ya:    Ya:    Tidak:
 Batuk  Sesak  Lainnya        Gatal  Rontok  Lainnya
   |      |       |             |      |       |
 Penyakit Penyakit Penyakit   Penyakit Penyakit Penyakit
 1,2,3   4,5      6,7         8       9        10
```

---

## 📝 CONTOH: 10 Penyakit dengan 1 Root

Misalkan 10 penyakit:
1. Rabies
2. Distemper  
3. Parvovirus
4. Hepatitis
5. Leptospirosis
6. Scabies
7. Ringworm
8. Pneumonia
9. Gastroenteritis
10. Flu

### Struktur Decision Tree:

```
                    [ROOT: Demam?]
                           |
            ┌──────────────┴──────────────┐
            |                             |
        Ya: [Gejala Saraf]          Tidak: [Gejala Kulit]
            |                             |
    ┌───────┼───────┐             ┌───────┼───────┐
    |       |       |             |       |       |
  Agresif  Kejang  Tidak         Gatal  Rontok  Tidak
   |        |       |             |      |       |
 Rabies  Distemper Lainnya      Scabies Ringworm Lainnya
         Parvovirus                  |        |
         Hepatitis                  Pneumonia Gastroenteritis
         Leptospirosis                        Flu
```

**1 Root → 10 Penyakit!**

---

## 🎨 PANDUAN MEMBUAT DECISION TREE

### Langkah 1: Identifikasi Gejala Pembedaan

**Tanyakan pada diri sendiri:**
- Gejala apa yang membedakan penyakit satu dengan lainnya?
- Gejala mana yang paling umum muncul di sebagian besar penyakit?
- Urutkan gejala dari umum → khusus

### Langkah 2: Buat Hierarki

**Contoh:**
```
Level 1 (Root): Demam? 
  ├─ Ya → Gejala sistemik
  └─ Tidak → Gejala lokal

Level 2: Sesak Nafas?
  ├─ Ya → Penyakit pernapasan
  └─ Tidak → Penyakit lainnya

Level 3: Gatal?
  ├─ Ya → Penyakit kulit
  └─ Tidak → Penyakit lainnya
```

### Langkah 3: Pisahkan ke Leaf Nodes

Setiap akhir cabang = 1 penyakit (leaf node)

---

## ⚡ EFEISIENSI: 1 Root vs Banyak Root

### ❌ Jika 1 Root = 2 Penyakit:
- 10 penyakit = 5 root
- **Masalah:**
  - Harus pilih root mana yang dipakai
  - User bingung harus mulai dari mana
  - Tidak efisien

### ✅ Jika 1 Root untuk Semua:
- 10 penyakit = 1 root
- **Keuntungan:**
  - User mulai dari 1 titik
  - Alur jelas dan terstruktur
  - Lebih efisien

---

## 📋 CONTOH IMPLEMENTASI: 10 Penyakit

### Root Node:

```
✅ Kode Node: ROOT-ALL
✅ Parent: [KOSONG]
✅ Gejala: Demam
✅ Penyakit: [KOSONG]
✅ Leaf: ☐
```

### Child Nodes Level 1:

**Node untuk Demam = Ya:**
```
✅ Kode: NODE-SYSTEMIC
✅ Parent: ROOT-ALL
✅ Gejala: Sesak Nafas
✅ Leaf: ☐
```

**Node untuk Demam = Tidak:**
```
✅ Kode: NODE-LOCAL
✅ Parent: ROOT-ALL
✅ Gejala: Gatal
✅ Leaf: ☐
```

### Child Nodes Level 2 & Leaf Nodes:

**Dari NODE-SYSTEMIC → Sesak Nafas:**
- Ya → Pneumonia (LEAF-1)
- Tidak → Lanjut ke gejala berikutnya → Rabies, Distemper, dll (LEAF-2, LEAF-3, ...)

**Dari NODE-LOCAL → Gatal:**
- Ya → Scabies, Ringworm (LEAF-8, LEAF-9)
- Tidak → Lanjut → Gastroenteritis, Flu (LEAF-10, LEAF-11)

**Total: 1 Root → 10+ Leaf Nodes → 10 Penyakit**

---

## ✅ REKOMENDASI

### Untuk 10 Penyakit:
- ✅ **1 Root Node** (gejala pembeda utama)
- ✅ **Struktur Hierarki** (gejala umum → khusus)
- ✅ **10 Leaf Nodes** (satu untuk setiap penyakit)

### Struktur Minimal:
```
Root (1) 
  └─ Child Nodes (berbagai level)
      └─ Leaf Nodes (10) → 10 Penyakit
```

---

## 🔍 PENTING

**Tidak ada aturan baku tentang jumlah root!**

- Bisa **1 root untuk semua** (recommended)
- Bisa **beberapa root** jika penyakit benar-benar berbeda kategorinya (jarang)
- Tapi **TIDAK harus 1 root = 2 penyakit**

---

## 📊 Perbandingan

| Jumlah Penyakit | Root Recommended | Root Maksimal |
|----------------|------------------|---------------|
| 2-5 penyakit    | 1 root           | 1-2 root      |
| 6-15 penyakit   | 1 root           | 1-3 root      |
| 16+ penyakit    | 1-2 root         | 1-5 root      |

---

## 🎯 KESIMPULAN

**Untuk 10 penyakit:**
- ✅ Gunakan **1 Root Node**
- ✅ Buat struktur hierarki yang logis
- ✅ Setiap penyakit punya **1 Leaf Node**
- ❌ **JANGAN** buat 5 root!

**Contoh struktur:**
```
1 Root → Banyak Child Nodes → 10 Leaf Nodes (10 Penyakit)
```

Sederhana, efisien, dan mudah dikelola!

