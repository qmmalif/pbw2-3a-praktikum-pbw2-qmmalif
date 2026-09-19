# Praktikum Pemrograman Berbasis Web 2

Repositori ini dipakai **sepanjang satu semester**. Kamu meng-clone sekali, lalu
setiap praktikum dikerjakan di cabang (branch) baru di dalam repositori yang sama.

Program Studi D3 Rekayasa Perangkat Lunak Aplikasi — Telkom University

---

## 1. Isi repositori

| Lokasi | Isinya |
|---|---|
| `.github/student.json` | Identitasmu. **Wajib diisi** sebelum praktikum pertama. |
| `.github/workflows/test.yml` | Pengujian otomatis tiap kali kamu push ke branch `tpNN`. |
| `tests/Feature/TpNN/` | Berkas pengujian praktikum ke-NN. Folder baru muncul tiap pertemuan. |
| `app/`, `routes/`, `resources/views/` | Tempat kamu menulis kode. |

Folder `vendor/` dan berkas `.env` tidak ada di repositori — keduanya dibuat di
laptopmu pada langkah berikutnya.

---

## 2. Menyiapkan di laptop (sekali saja)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Buka <http://127.0.0.1:8000>. Kalau terbuka tanpa galat, lingkunganmu siap.

Lalu isi identitasmu di `.github/student.json`:

```json
{
    "nama": "Nama Lengkap Kamu",
    "nim": "6702234001",
    "kelas": "D3RPLA-3A"
}
```

Penilaian otomatis membaca berkas ini. Selama isinya masih teks contoh bawaan,
kriteria pertama tidak akan lolos.

---

## 3. Siklus tiap praktikum

Pola berikut **berulang setiap pertemuan**. Ganti `NN` dengan nomor praktikum
yang sedang berjalan.

### 3.1. Buat branch dari `main` terbaru

```bash
git checkout main
git pull origin main
git checkout -b tpNN
```

`main` adalah cabang resmi — isinya hanya pekerjaan yang sudah selesai. Semua
percobaan dilakukan di `tpNN`, sehingga `main` tetap utuh kalau pekerjaanmu kacau.

### 3.2. Kerjakan, lalu uji di laptop

```bash
php artisan test                       # seluruh kriteria
php artisan test tests/Feature/TpNN    # satu praktikum saja
php artisan test --filter=NamaKelasTes # satu kriteria saja
```

Baris hijau `PASS` berarti kriteria terpenuhi. Kalau merah, baca nama tes dan
pesan di bawahnya — di situ tertulis apa yang belum benar.

### 3.3. Commit bertahap

```bash
git add .
git commit -m "menambah BerandaController dan view beranda"
```

Commit setiap kali satu bagian selesai, jangan menumpuk di akhir. Tulis pesan
yang menjelaskan **apa yang berubah** — `update`, `fix`, atau `asdf` tidak
memberi informasi apa pun dan ikut dinilai.

### 3.4. Push ke GitHub

```bash
git push -u origin tpNN
```

Tanda `-u` cukup sekali per cabang; push berikutnya cukup `git push`.

### 3.5. Periksa tab Actions

Buka repositorimu di GitHub → tab **Actions**. Sebuah proses berjalan otomatis
dan berakhir dengan centang hijau atau silang merah.

Proses itu berjalan di komputer GitHub, bukan laptopmu. Centang hijau di sana
membuktikan kodemu berjalan di mesin mana pun — bukan sekadar "jalan di laptop
saya". Kalau merah, buka langkah yang gagal, baca baris galat paling bawah,
perbaiki, lalu push lagi. Boleh diulang sebanyak yang kamu perlukan.

### 3.6. Merge ke `main`

Nilai **baru dihitung** setelah pekerjaanmu masuk ke `main`.

1. Klik **Compare & pull request** di halaman repositori.
2. Pastikan arahnya: base `main`, compare `tpNN`.
3. Beri judul dan ringkasan singkat, lalu **Create pull request**.
4. Tunggu pemeriksaan otomatisnya hijau.
5. **Merge pull request** → pilih **Create a merge commit** → **Confirm merge**.

> **Pilih Create a merge commit.** Jangan Squash, jangan Rebase. Penilaian membaca
> riwayat Git untuk memastikan pekerjaanmu masuk lewat pull request, dan dua
> pilihan lain menghapus jejak itu.

Setelah merge, samakan salinan lokalmu — praktikum minggu depan dicabangkan dari
`main`:

```bash
git checkout main
git pull origin main
```

### 3.7. Lihat nilai

Beberapa menit setelah merge, nilaimu muncul di halaman Classroom 50 dan di
bagian atas repositorimu. Terakhir, tempel tautan repositori ini pada tugas di LMS.

---

## 4. Berkas pengujian

Berkas tes dikelompokkan per praktikum, dengan pola `Tp` + nomor praktikum +
nomor urut kriteria + nama singkat:

```
tests/Feature/
├── Tp01/Tp011SetupTest.php, Tp012MvcTest.php, ...
└── Tp02/Tp021...Test.php, ...
```

Angka urutnya membuat keluaran `php artisan test` mengikuti urutan modul.

Setiap pertemuan, berkas tes yang baru dibagikan lewat **LMS**. Ekstrak di akar
repositori supaya berkasnya masuk ke folder yang benar — **jangan taruh di folder
praktikum lain**, karena dua berkas dengan nama class yang sama membuat seluruh
pengujian gagal sebelum satu tes pun jalan.

Bacalah berkas-berkas ini — di situ tertulis persis apa yang diharapkan dari
pekerjaanmu. **Tetapi jangan mengubahnya.** Saat penilaian berjalan, berkas tes
diganti dengan versi resmi milik dosen, sehingga menyunting tes tidak menaikkan
nilai sama sekali.

---

## 5. Aturan yang tidak boleh dilanggar

- **Jangan meng-commit `.env`.** Isinya kunci aplikasi dan kata sandi database.
  Sekali ter-commit, rahasianya tetap tercatat di riwayat Git meskipun dihapus
  belakangan. Berkas ini sudah terdaftar di `.gitignore` — jangan keluarkan.
- **Jangan meng-commit `vendor/`.** Bisa diunduh ulang dengan `composer install`.
- **Jangan mengubah berkas di `tests/`.**
- **Jangan bekerja langsung di `main`.** Selalu lewat branch `tpNN`.
- **Pakai PHP 8.3.** Versi lain berisiko jalan di laptop tetapi gagal dinilai.

---

## 6. Batas waktu

Grading dikunci **Senin pagi**. Yang dihitung adalah isi branch `main` pada saat
itu. Sebelum batas tersebut kamu bebas memperbaiki: commit baru di `tpNN`, push,
lalu merge lagi.

---

## 7. Kalau bermasalah

| Gejala | Solusi |
|---|---|
| `composer install` menolak dipasang | Versi PHP aktif bukan 8.3. Samakan lewat Laragon → PHP → Version. |
| `No application encryption key` | Jalankan `php artisan key:generate` setelah menyalin `.env`. |
| `View [nama] not found` | Nama berkas harus `nama.blade.php` di `resources/views`. |
| `Undefined variable` di view | Nama variabel di controller dan di view harus sama