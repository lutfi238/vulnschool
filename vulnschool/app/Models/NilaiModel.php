<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: NilaiModel
 * Mengelola data nilai mahasiswa per mata kuliah
 */
class NilaiModel extends Model
{
    protected $table            = 'nilai';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'grade',
        'semester_tahun',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil nilai mahasiswa tertentu di semua mata kuliah
     */
    public function getNilaiByMahasiswa(int $mahasiswaId): array
    {
        return $this->select('nilai.*, mata_kuliah.kode_mk, mata_kuliah.nama_mk, mata_kuliah.sks')
                    ->join('mata_kuliah', 'mata_kuliah.id = nilai.mata_kuliah_id')
                    ->where('nilai.mahasiswa_id', $mahasiswaId)
                    ->findAll();
    }

    /**
     * Ambil nilai semua mahasiswa di mata kuliah tertentu
     */
    public function getNilaiByMataKuliah(int $mataKuliahId): array
    {
        return $this->select('nilai.*, mahasiswa.nim, mahasiswa.nama')
                    ->join('mahasiswa', 'mahasiswa.id = nilai.mahasiswa_id')
                    ->where('nilai.mata_kuliah_id', $mataKuliahId)
                    ->findAll();
    }

    /**
     * Hitung nilai akhir berdasarkan bobot:
     * Tugas 30%, UTS 30%, UAS 40%
     */
    public static function hitungNilaiAkhir(float $tugas, float $uts, float $uas): float
    {
        return round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);
    }

    /**
     * Tentukan grade berdasarkan nilai akhir
     * Skala: A (>=85), A- (>=80), B+ (>=75), B (>=70), B- (>=65),
     *        C+ (>=60), C (>=55), D (>=50), E (<50)
     */
    public static function tentukanGrade(float $nilaiAkhir): string
    {
        if ($nilaiAkhir >= 85) return 'A';
        if ($nilaiAkhir >= 80) return 'A-';
        if ($nilaiAkhir >= 75) return 'B+';
        if ($nilaiAkhir >= 70) return 'B';
        if ($nilaiAkhir >= 65) return 'B-';
        if ($nilaiAkhir >= 60) return 'C+';
        if ($nilaiAkhir >= 55) return 'C';
        if ($nilaiAkhir >= 50) return 'D';
        return 'E';
    }

    /**
     * Konversi grade ke angka mutu untuk hitung IPK
     */
    public static function gradeToBobot(string $grade): float
    {
        return match ($grade) {
            'A'  => 4.00,
            'A-' => 3.75,
            'B+' => 3.50,
            'B'  => 3.00,
            'B-' => 2.75,
            'C+' => 2.50,
            'C'  => 2.00,
            'D'  => 1.00,
            'E'  => 0.00,
            default => 0.00,
        };
    }

    /**
     * Ambil detail 1 record nilai lengkap dengan data mahasiswa + MK + dosen
     */
    public function getNilaiDetail(int $nilaiId): ?array
    {
        return $this->select('nilai.*, mahasiswa.nim, mahasiswa.nama as nama_mahasiswa,
                              mata_kuliah.kode_mk, mata_kuliah.nama_mk, mata_kuliah.sks,
                              mata_kuliah.semester, dosen.nama as nama_dosen, dosen.nip as nip_dosen')
                    ->join('mahasiswa', 'mahasiswa.id = nilai.mahasiswa_id')
                    ->join('mata_kuliah', 'mata_kuliah.id = nilai.mata_kuliah_id')
                    ->join('dosen', 'dosen.id = mata_kuliah.dosen_id', 'left')
                    ->where('nilai.id', $nilaiId)
                    ->first();
    }

    /**
     * Ambil semua nilai lengkap (admin view)
     */
    public function getAllNilaiWithDetail(): array
    {
        return $this->select('nilai.*, mahasiswa.nim, mahasiswa.nama as nama_mahasiswa,
                              mata_kuliah.kode_mk, mata_kuliah.nama_mk, mata_kuliah.sks')
                    ->join('mahasiswa', 'mahasiswa.id = nilai.mahasiswa_id')
                    ->join('mata_kuliah', 'mata_kuliah.id = nilai.mata_kuliah_id')
                    ->orderBy('mahasiswa.nim', 'ASC')
                    ->findAll();
    }
}
