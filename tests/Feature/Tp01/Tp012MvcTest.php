<?php

namespace Tests\Feature\Tp01;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Bagian B — Beranda dirender lewat Controller dan View (30 poin).
 *
 * Ini kriteria berbobot terbesar karena di sinilah pola MVC dibuktikan.
 * Halaman yang benar tampilannya tetapi ditulis sebagai HTML statis di
 * routes/web.php tidak dihitung.
 *
 *   php artisan test --filter=Tp01Mvc
 */
class Tp012MvcTest extends TestCase
{
    /** Route "/" harus terdaftar dengan nama beranda. */
    public function test_route_beranda_terdaftar(): void
    {
        $rute = Route::getRoutes()->getByName('beranda');

        $this->assertNotNull(
            $rute,
            'Route bernama "beranda" tidak ditemukan. Tambahkan ->name(\'beranda\') di routes/web.php.'
        );

        $this->assertSame('/', $rute->uri(), 'Route "beranda" harus menangani alamat "/".');
    }

    /** Route tidak boleh ditangani closure — harus lewat controller. */
    public function test_beranda_ditangani_controller(): void
    {
        $aksi = Route::getRoutes()->getByName('beranda')->getActionName();

        $this->assertStringNotContainsString(
            'Closure',
            $aksi,
            'Halaman beranda masih ditangani closure di routes/web.php. '
            . 'Pindahkan logikanya ke sebuah Controller.'
        );

        $this->assertStringContainsString(
            'Controller',
            $aksi,
            'Route beranda harus menunjuk ke sebuah class Controller.'
        );
    }

    /** Berkas controller dan method index harus benar-benar ada. */
    public function test_beranda_controller_punya_method_index(): void
    {
        $kelas = '\\App\\Http\\Controllers\\BerandaController';

        $this->assertTrue(
            class_exists($kelas),
            'Class BerandaController tidak ditemukan di app/Http/Controllers.'
        );

        $this->assertTrue(
            method_exists($kelas, 'index'),
            'Method index() tidak ditemukan di BerandaController.'
        );
    }

    /** Respons harus memakai view bernama beranda, bukan mengembalikan string. */
    public function test_beranda_memakai_view_dan_menerima_data(): void
    {
        $respons = $this->get('/');

        $respons->assertStatus(200);
        $respons->assertViewIs('beranda');
        $respons->assertViewHas('kantin');

        $kantin = $respons->original->getData()['kantin'];

        $this->assertIsArray($kantin, 'Data $kantin yang dikirim ke view harus berupa array.');

        foreach (['nama', 'lokasi', 'jam'] as $kunci) {
            $this->assertArrayHasKey(
                $kunci,
                $kantin,
                "Array \$kantin harus memuat kunci \"{$kunci}\"."
            );
        }
    }

    /** Data dari controller harus benar-benar muncul di halaman. */
    public function test_data_dari_controller_tampil_di_halaman(): void
    {
        $respons = $this->get('/');
        $kantin = $respons->original->getData()['kantin'];

        $respons->assertSee($kantin['nama']);
        $respons->assertSee($kantin['lokasi']);
    }

    /** Berkas view harus ada di lokasi yang benar. */
    public function test_berkas_view_beranda_ada(): void
    {
        $this->assertFileExists(
            resource_path('views/beranda.blade.php'),
            'Berkas resources/views/beranda.blade.php tidak ditemukan. '
            . 'Perhatikan akhiran .blade.php, bukan .php saja.'
        );
    }
}
