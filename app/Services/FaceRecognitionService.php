<?php

namespace App\Services;

use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\PerceptualHash;

class FaceRecognitionService
{
    /**
     * Bandingkan foto selfie utama dengan hasil verifikasi aksi (faremuk).
     *
     * Proses ini menggunakan algoritma Perceptual Hash (pHash).
     * - pHash menghasilkan hash biner 64-bit dari sebuah gambar.
     * - Dua gambar yang mirip akan memiliki hash yang hampir sama.
     * - Tingkat kemiripan dihitung dari jarak Hamming antar hash (jumlah bit berbeda).
     *
     * @param string $selfiePath    Path ke file selfie utama (foto KTP/selfie pertama).
     * @param array  $capturePaths  Daftar path ke file hasil capture aksi (hadap kanan, kiri, atas, dsb).
     *
     * @return array
     *   - match   => true/false hasil verifikasi
     *   - score   => nilai skor terbaik (0–1, semakin kecil semakin mirip)
     *   - details => hasil perbandingan semua foto capture
     *   - message => pesan tambahan jika gagal
     */
    public function compareWithActions(string $selfiePath, array $capturePaths): array
    {
        // Validasi awal: path kosong → langsung return
        if (empty($selfiePath) || empty($capturePaths)) {
            return [
                'match'   => false,
                'score'   => null,
                'details' => [],
                'message' => 'Path selfie atau capture kosong'
            ];
        }

        $hasher = new ImageHash(new PerceptualHash());

        try {
            // Hash selfie utama
            $selfieHash = $hasher->hash($selfiePath);
        } catch (\Exception $e) {
            return [
                'match'   => false,
                'score'   => null,
                'details' => [],
                'message' => "Gagal membaca selfie: " . $e->getMessage()
            ];
        }

        $results = [];
        foreach ($capturePaths as $path) {
            try {
                $captureHash = $hasher->hash($path);

                // Hitung jarak Hamming (0–64)
                $distance = $hasher->distance($selfieHash, $captureHash);

                // Normalisasi ke skor 0–1
                $score = $distance / 64;

                // Threshold 55% → skor ≤ 0.45 dianggap mirip
                $match = $score <= 0.45;

                $results[] = [
                    'path'  => $path,
                    'score' => $score,
                    'match' => $match
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'path'  => $path,
                    'error' => $e->getMessage()
                ];
            }
        }

        $validResults = collect($results)->whereNotNull('score');
        $best = $validResults->sortBy('score')->first();

        if (!$best) {
            return [
                'match'   => false,
                'score'   => null,
                'details' => $results,
                'message' => 'Tidak ada capture yang bisa dibandingkan'
            ];
        }

        return [
            'match'   => $best['match'],
            'score'   => $best['score'],
            'details' => $results,
            'message' => $best['match']
                ? 'Wajah terverifikasi (≥ 55% kemiripan dengan selfie utama)'
                : 'Wajah tidak cocok (kurang dari 55% kemiripan)'
        ];
    }
}
