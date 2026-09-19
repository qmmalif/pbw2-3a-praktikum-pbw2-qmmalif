<?php

namespace Tests\Feature\Tp01;

use Tests\TestCase;

/**
 * Bagian C — Mekanisme Git (10 poin).
 *
 * Memeriksa disiplin proses: pekerjaan disimpan bertahap dengan pesan yang
 * bermakna, dan masuk ke main lewat pull request, bukan didorong langsung.
 *
 *   php artisan test --filter=Tp01Git
 */
class Tp016GitTest extends TestCase
{
    private function git(string $argumen): array
    {
        $perintah = 'git -C ' . escapeshellarg(base_path()) . ' ' . $argumen . ' 2>&1';

        $keluaran = [];
        $kode = 0;
        exec($perintah, $keluaran, $kode);

        return ['keluaran' => $keluaran, 'kode' => $kode];
    }

    /**
     * Riwayat Git harus lengkap. Sebagian mesin penilai meng-clone secara
     * dangkal (shallow) sehingga hanya satu commit yang terbawa — dalam
     * keadaan itu pemeriksaan dilewati, bukan digagalkan, supaya mahasiswa
     * tidak dihukum karena hal di luar kendalinya.
     */
    private function pastikanRiwayatLengkap(): void
    {
        if ($this->git('rev-parse --is-inside-work-tree')['kode'] !== 0) {
            $this->markTestSkipped('Git tidak tersedia, pemeriksaan dilewati.');
        }

        $dangkal = trim(implode('', $this->git('rev-parse --is-shallow-repository')['keluaran']));

        if ($dangkal === 'true') {
            $this->markTestSkipped('Repositori di-clone secara dangkal, riwayat tidak lengkap.');
        }
    }

    /** Mengambil pesan commit, tanpa commit merge. */
    private function pesanCommit(): array
    {
        $hasil = $this->git('log --no-merges --format=%s');

        return array_values(array_filter(array_map('trim', $hasil['keluaran'])));
    }

    /** Pekerjaan harus disimpan bertahap, bukan sekali unggah di akhir. */
    public function test_minimal_dua_commit(): void
    {
        $this->pastikanRiwayatLengkap();

        $pesan = $this->pesanCommit();

        $this->assertGreaterThanOrEqual(
            2,
            count($pesan),
            'Riwayat hanya berisi ' . count($pesan) . ' commit. Commit setiap kali satu '
            . 'bagian selesai — minimal dua, bukan sekali unggah di akhir.'
        );
    }

    /** Pesan commit harus menjelaskan isi perubahan. */
    public function test_pesan_commit_bermakna(): void
    {
        $this->pastikanRiwayatLengkap();

        $pesan = $this->pesanCommit();
        $buruk = ['update', 'fix', 'asdf', 'test', 'wip', 'coba', 'coba lagi', 'revisi', '.'];

        $bermakna = 0;

        foreach ($pesan as $satu) {
            $ringkas = mb_strtolower($satu);
            if (in_array($ringkas, $buruk, true)) continue;
            if (mb_strlen($satu) < 15) continue;
            if (str_starts_with($ringkas, 'initial commit')) continue;
            $bermakna += 1;
        }

        $total = max(count($pesan), 1);

        $this->assertGreaterThanOrEqual(
            0.6,
            $bermakna / $total,
            "Hanya {$bermakna} dari {$total} pesan commit yang menjelaskan perubahannya. "
            . 'Pesan seperti "update" atau "fix" tidak memberi informasi apa pun.'
        );
    }

    /** Pekerjaan harus masuk ke main lewat pull request. */
    public function test_pekerjaan_masuk_lewat_merge(): void
    {
        $this->pastikanRiwayatLengkap();

        $hasil = $this->git('log --merges --format=%s');
        $merge = array_values(array_filter(array_map('trim', $hasil['keluaran'])));

        $this->assertNotEmpty(
            $merge,
            'Tidak ditemukan commit merge di riwayat. Pekerjaan harus dikerjakan di '
            . 'branch tp01 lalu dimasukkan ke main lewat pull request dengan pilihan '
            . '"Create a merge commit" — bukan Squash, bukan Rebase, dan bukan push '
            . 'langsung ke main.'
        );
    }
}
