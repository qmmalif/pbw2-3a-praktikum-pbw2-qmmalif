<?php

namespace Tests\Feature\Tp01;

use Tests\TestCase;

/**
 * Bagian C — Berkas .env tidak ikut ter-commit (10 poin).
 *
 * Bagian pertama dari challenge. Berkas .env memang ADA saat pengujian
 * berjalan, karena dibuat oleh setup command. Yang diuji bukan
 * keberadaannya, melainkan apakah ia terdaftar di Git.
 *
 *   php artisan test --filter=Tp01Env
 */
class Tp015EnvTest extends TestCase
{
    /** Menjalankan perintah git di dalam folder proyek. */
    private function git(string $argumen): array
    {
        $perintah = 'git -C ' . escapeshellarg(base_path()) . ' ' . $argumen . ' 2>&1';

        $keluaran = [];
        $kode = 0;
        exec($perintah, $keluaran, $kode);

        return ['keluaran' => $keluaran, 'kode' => $kode];
    }

    /** Berkas contoh harus tetap ada supaya orang lain bisa menjalankan proyek. */
    public function test_berkas_env_example_masih_ada(): void
    {
        $this->assertFileExists(
            base_path('.env.example'),
            'Berkas .env.example tidak boleh dihapus. Berkas inilah yang dipakai '
            . 'orang lain untuk membuat .env mereka sendiri.'
        );
    }

    /** .gitignore harus menyebut .env secara eksplisit. */
    public function test_gitignore_menyebut_env(): void
    {
        $berkas = base_path('.gitignore');

        $this->assertFileExists($berkas, 'Berkas .gitignore tidak ditemukan.');

        $baris = array_map('trim', file($berkas, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        $this->assertContains(
            '.env',
            $baris,
            'Baris ".env" tidak ditemukan di .gitignore. Jangan hapus baris bawaan Laravel ini.'
        );
    }

    /** Yang menentukan: .env tidak boleh terdaftar di indeks Git. */
    public function test_env_tidak_terlacak_git(): void
    {
        $cek = $this->git('rev-parse --is-inside-work-tree');

        if ($cek['kode'] !== 0) {
            $this->markTestSkipped('Git tidak tersedia, pemeriksaan dilewati.');
        }

        $hasil = $this->git('ls-files --error-unmatch .env');

        $this->assertNotSame(
            0,
            $hasil['kode'],
            'Berkas .env ikut ter-commit ke repositori. Berkas ini berisi kunci aplikasi '
            . 'dan kata sandi database. Keluarkan dengan: git rm --cached .env'
        );
    }

    /** Folder vendor juga tidak boleh ikut masuk. */
    public function test_folder_vendor_tidak_terlacak_git(): void
    {
        $cek = $this->git('rev-parse --is-inside-work-tree');

        if ($cek['kode'] !== 0) {
            $this->markTestSkipped('Git tidak tersedia, pemeriksaan dilewati.');
        }

        $hasil = $this->git('ls-files vendor');

        $this->assertEmpty(
            array_filter($hasil['keluaran']),
            'Folder vendor ikut ter-commit. Hapus dengan: git rm -r --cached vendor'
        );
    }
}
