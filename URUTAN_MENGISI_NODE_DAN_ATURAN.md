# 🔄 Urutan Mengisi Node dan Aturan

## ❓ Pertanyaan: Node Dulu atau Aturan Dulu?

**Jawaban:** Bisa urutan bebas, tapi **lebih baik Decision Node dulu**, karena:

### 📊 Alur Kerja Sistem Diagnosa:

```
1. Decision Node (Langkah 1)
   ↓
   Traverse pohon → Menemukan kandidat penyakit
   
2. Aturan (Langkah 2)  
   ↓
   Hitung CF → Menentukan penyakit dengan keyakinan tertinggi
```

---

## ✅ REKOMENDASI: Decision Node Dulu

### 🎯 Alasan Decision Node Dulu Lebih Baik:

1. **Lebih Mudah Memahami Struktur**
   - Decision Node membentuk pohon keputusan yang visual
   - Lebih mudah melihat hubungan gejala → penyakit
   - Dapat mengetahui penyakit apa saja yang mungkin didiagnosa

2. **Lebih Logis Secara Urutan**
   - Sistem diagnosa menggunakan Decision Node dulu (langkah 1)
   - Decision Node menemukan kandidat penyakit
   - Aturan menghitung CF dari kandidat tersebut

3. **Mencegah Kesalahan**
   - Setelah Decision Node selesai, Anda tahu penyakit mana yang perlu dibuat aturannya
   - Tidak ada penyakit yang terlewat untuk dibuat aturan

---

## 📋 URUTAN YANG DISARANKAN

### **STEP 1: Siapkan Data Dasar** ⚠️ WAJIB DULU
- [ ] Buat **Gejala** (Gatal, Sesak Nafas, Rontok, Demam, Lemas)
- [ ] Buat **Penyakit** (Rabies, Distemper)

### **STEP 2: Buat Decision Node** 🌳
- [ ] Buat Root Node
- [ ] Buat Child Nodes
- [ ] Pastikan semua penyakit ada di node leaf

**Kenapa dulu?**
- Setelah selesai, Anda sudah tahu penyakit apa saja yang akan didiagnosa
- Struktur pohon sudah jelas

### **STEP 3: Buat Aturan** 📐
- [ ] Buat aturan untuk setiap penyakit yang ada di Decision Node
- [ ] Tambahkan gejala yang relevan dengan CF Pakar > 0

**Kenapa setelah?**
- Anda sudah tahu penyakit mana yang perlu aturan
- Tidak ada penyakit yang terlewat

---

## 🔄 ALTERNATIF: Bisa Juga Aturan Dulu

**Jika Anda ingin mengisi Aturan dulu juga boleh**, karena:

- ✅ Keduanya tidak saling bergantung secara data
- ✅ Bisa dibuat dalam urutan bebas
- ✅ Sistem akan tetap bekerja

**Tapi lebih repot karena:**
- ❌ Harus tahu dulu penyakit mana yang akan ada di Decision Node
- ❌ Bisa saja membuat aturan untuk penyakit yang tidak ada di Decision Node (tidak berguna)
- ❌ Atau lupa membuat aturan untuk penyakit yang ada di Decision Node

---

## ⚠️ YANG PENTING: Keduanya Harus Ada!

Sebelum diagnosa bisa bekerja dengan benar, **keduanya HARUS ada**:

### ❌ Jika Decision Node tidak ada:
```
Error: "Decision Tree belum dibuat oleh admin."
Diagnosa tidak bisa jalan sama sekali.
```

### ❌ Jika Aturan tidak ada:
```
Error: CF = 0%
Penyakit ditemukan tapi tidak bisa dihitung keyakinannya.
```

---

## 📝 CONTOH URUTAN PRAKTIS

### Untuk Rabies & Distemper:

**1. Buat Gejala & Penyakit** (5 menit)
- Gejala: Gatal, Sesak Nafas, Rontok, Demam, Lemas
- Penyakit: Rabies, Distemper

**2. Buat Decision Node** (15 menit)
- ROOT-DEMAM → NODE-DIST-1 → LEAF-DIST
- ROOT-DEMAM → NODE-RAB-1 → LEAF-RAB
- dst...

**3. Buat Aturan** (10 menit)
- Aturan untuk Distemper: Rontok, Demam, Lemas
- Aturan untuk Rabies: Gatal, Sesak Nafas

**Total: ~30 menit, dan diagnosa sudah bisa jalan!**

---

## ✅ CHECKLIST FINAL

Sebelum diagnosa, pastikan:

- [ ] ✅ Gejala sudah dibuat
- [ ] ✅ Penyakit sudah dibuat  
- [ ] ✅ Decision Node sudah dibuat (ada root node)
- [ ] ✅ Semua penyakit di Decision Node sudah ada
- [ ] ✅ Aturan sudah dibuat untuk setiap penyakit
- [ ] ✅ Setiap aturan memiliki minimal 1 gejala dengan CF > 0
- [ ] ✅ Gejala di aturan cocok dengan gejala yang bisa dipilih user

---

## 🎯 KESIMPULAN

**Urutan yang disarankan:**
1. **Gejala & Penyakit** (WAJIB DULU)
2. **Decision Node** (lebih baik dulu)
3. **Aturan** (setelah Decision Node)

**Tapi kalau mau urutan bebas juga tidak masalah**, asalkan **keduanya sudah ada sebelum diagnosa**.

