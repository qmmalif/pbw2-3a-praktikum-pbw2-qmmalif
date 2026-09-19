<?php

namespace Tests\Feature\Tp01;

use Tests\TestCase;

/**
 * Bagian A — Mengenal framework, repository, running project (20 poin).
 *
 * Tes ini adalah gerbang: kalau aplikasi tidak bisa dinyalakan sama sekali,
 * seluruh kriteria berikutnya pasti ikut gagal.
 *
 *   php artisan test --filter=Tp01Setup
 */
class Tp011SetupTest extends TestCase
{
    /** Aplikasi harus bisa melayani permintaan ke halaman beranda. */
    public function test_aplikasi_berjalan(): void
    {
        $this->get('/')->assertStatus(200);
    }

    /** Berkas penanda identitas harus ada dan berisi JSON yang sah. */
    public function test_berkas_identitas_ada_dan_sah(): void
    {
        $berkas = base_path('.github/student.json');

        $this->assertFileExists(
            $berkas,
            'Berkas .github/student.json tidak ditemukan. Jangan dihapus atau dipindah.'
        );

        $data = json_decode(file_get_contents($berkas), true);

        $this->assertIsArray(
            $data,
            'Isi .github/student.json bukan JSON yang sah. Periksa tanda kutip dan koma.'
        );

        foreach (['nama', 'nim', 'kelas'] as $kunci) {
            $this->assertArrayHasKey(
                $kunci,
                $data,
                "Kunci \"{$kunci}\" tidak ada di .github/student.json."
            );
        }
    }

    /** Nilai contoh dari template harus sudah diganti dengan data sendiri. */
    public function test_identitas_sudah_diisi_bukan_contoh(): void
    {
        $data = json_decode(file_get_contents(base_path('.github/student.json')), true);

        $contoh = ['Nama Lengkap', '123456789012', 'D3IF-49-XX', ''];

        foreach (['nama', 'nim', 'kelas'] as $kunci) {
            $nilai = trim((string) ($data[$kunci] ?? ''));

            $this->assertNotContains(
                $nilai,
                $contoh,
                "Nilai \"{$kunci}\" masih memakai teks contoh dari template. "
                . 'Isi dengan datamu sendiri.'
            );
        }

        $this->assertMatchesRegularExpression(
            '/^\d{12}$/',
            (string) $data['nim'],
            'NIM harus 12 digit angka tanpa spasi atau tanda baca.'
        );
    }
}
