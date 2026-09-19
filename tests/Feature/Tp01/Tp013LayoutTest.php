<?php

namespace Tests\Feature\Tp01;

use Tests\TestCase;

/**
 * Bagian B — Layout induk dipakai kedua halaman (15 poin).
 *
 * Yang diperiksa bukan hanya keberadaan berkas layout, tetapi apakah
 * kedua halaman benar-benar memakainya dan berhenti menulis ulang
 * kerangka HTML sendiri-sendiri.
 *
 *   php artisan test --filter=Tp01Layout
 */
class Tp013LayoutTest extends TestCase
{
    /** Layout induk harus ada dan menyediakan lubang untuk halaman anak. */
    public function test_layout_induk_ada_dan_punya_yield(): void
    {
        $berkas = resource_path('views/layouts/app.blade.php');

        $this->assertFileExists(
            $berkas,
            'Berkas resources/views/layouts/app.blade.php tidak ditemukan.'
        );

        $isi = file_get_contents($berkas);

        $this->assertStringContainsString(
            '@yield(',
            $isi,
            'Layout induk belum memuat @yield. Tanpa itu, halaman anak tidak punya tempat mengisi.'
        );

        $this->assertMatchesRegularExpression(
            "/@yield\(\s*['\"]isi['\"]/",
            $isi,
            "Layout induk harus memuat @yield('isi') sebagai tempat isi halaman."
        );

        $this->assertStringContainsString(
            '<html',
            $isi,
            'Kerangka HTML (tag <html>) seharusnya berada di layout induk.'
        );
    }

    /** Kedua halaman harus menurunkan diri dari layout induk. */
    public function test_semua_halaman_memakai_layout(): void
    {
        foreach (['beranda', 'tentang'] as $halaman) {
            $berkas = resource_path("views/{$halaman}.blade.php");

            $this->assertFileExists($berkas, "Berkas view {$halaman}.blade.php tidak ditemukan.");

            $this->assertMatchesRegularExpression(
                "/@extends\(\s*['\"]layouts\.app['\"]\s*\)/",
                file_get_contents($berkas),
                "Berkas {$halaman}.blade.php belum memuat @extends('layouts.app')."
            );
        }
    }

    /** Halaman anak tidak boleh menulis ulang kerangka HTML. */
    public function test_halaman_anak_tidak_menulis_ulang_kerangka(): void
    {
        foreach (['beranda', 'tentang'] as $halaman) {
            $isi = file_get_contents(resource_path("views/{$halaman}.blade.php"));

            $this->assertStringNotContainsString(
                '<html',
                $isi,
                "Berkas {$halaman}.blade.php masih menulis tag <html> sendiri. "
                . 'Kerangka HTML cukup ditulis sekali di layout induk.'
            );

            $this->assertStringNotContainsString(
                '<body',
                $isi,
                "Berkas {$halaman}.blade.php masih menulis tag <body> sendiri."
            );
        }
    }

    /** Navigasi dari layout harus ikut muncul di setiap halaman. */
    public function test_navigasi_muncul_di_setiap_halaman(): void
    {
        foreach (['/', '/tentang'] as $alamat) {
            $respons = $this->get($alamat);

            $respons->assertStatus(200);
            $respons->assertSee('Beranda');
            $respons->assertSee('Tentang');
        }
    }
}
