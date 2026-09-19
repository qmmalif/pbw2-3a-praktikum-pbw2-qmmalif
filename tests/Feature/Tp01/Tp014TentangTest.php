<?php

namespace Tests\Feature\Tp01;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Bagian B — Halaman tentang memuat identitas (15 poin).
 *
 * Sekaligus memeriksa konsistensi: identitas di halaman harus sama dengan
 * isi .github/student.json. Ini menutup celah mahasiswa yang menempel nama
 * orang lain di salah satu tempat.
 *
 *   php artisan test --filter=Tp01Tentang
 */
class Tp014TentangTest extends TestCase
{
    /** Membaca identitas resmi dari berkas penanda. */
    private function identitas(): array
    {
        $berkas = base_path('.github/student.json');

        $this->assertFileExists($berkas, 'Berkas .github/student.json tidak ditemukan.');

        $data = json_decode(file_get_contents($berkas), true);

        $this->assertIsArray($data, 'Isi .github/student.json bukan JSON yang sah.');

        return $data;
    }

    /** Route /tentang harus terdaftar dan ditangani controller. */
    public function test_route_tentang_terdaftar(): void
    {
        $rute = Route::getRoutes()->getByName('tentang');

        $this->assertNotNull(
            $rute,
            'Route bernama "tentang" tidak ditemukan.'
        );

        $this->assertSame('tentang', $rute->uri(), 'Route "tentang" harus menangani alamat "/tentang".');

        $this->assertStringNotContainsString(
            'Closure',
            $rute->getActionName(),
            'Halaman tentang masih ditangani closure. Pindahkan ke TentangController.'
        );
    }

    /** Halaman harus memakai view tentang. */
    public function test_halaman_tentang_memakai_view(): void
    {
        $respons = $this->get('/tentang');

        $respons->assertStatus(200);
        $respons->assertViewIs('tentang');
    }

    /** Data harus dikirim controller, bukan ditulis langsung di berkas Blade. */
    public function test_identitas_dikirim_dari_controller(): void
    {
        $respons = $this->get('/tentang');

        // Identitas harus disiapkan di controller, bukan diketik langsung
        // di dalam tentang.blade.php.
        foreach (['nama', 'nim', 'kelas'] as $kunci) {
            $respons->assertViewHas($kunci);
        }
    }

    /** Identitas yang tampil harus sama dengan isi student.json. */
    public function test_identitas_tampil_dan_konsisten(): void
    {
        $identitas = $this->identitas();
        $respons = $this->get('/tentang');

        foreach (['nama', 'nim', 'kelas'] as $kunci) {
            $respons->assertSee((string) $identitas[$kunci]);
        }
    }
}
