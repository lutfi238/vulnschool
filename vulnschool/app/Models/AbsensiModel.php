<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: AbsensiModel
 * Mengelola data kehadiran/absensi mahasiswa
 */
class AbsensiModel extends Model
{
    protected $table            = 'absensi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'pertemuan',
        'tanggal',
        'status',
        'keterangan',
    ];

    // Hanya created_at, tidak ada updated_at pada tabel absensi
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada updated_at

    /**
     * Ambil absensi mahasiswa tertentu di mata kuliah tertentu
     */
    public function getAbsensiMahasiswa(int $mahasiswaId, int $mataKuliahId): array
    {
        return $this->where('mahasiswa_id', $mahasiswaId)
                    ->where('mata_kuliah_id', $mataKuliahId)
                    ->orderBy('pertemuan', 'ASC')
                    ->findAll();
    }

    /**
     * Ambil rekap absensi per mata kuliah (semua mahasiswa)
     */
    public function getRekapByMataKuliah(int $mataKuliahId): array
    {
        return $this->select('absensi.*, mahasiswa.nim, mahasiswa.nama')
                    ->join('mahasiswa', 'mahasiswa.id = absensi.mahasiswa_id')
                    ->where('absensi.mata_kuliah_id', $mataKuliahId)
                    ->orderBy('mahasiswa.nim', 'ASC')
                    ->orderBy('absensi.pertemuan', 'ASC')
                    ->findAll();
    }

    /**
     * Hitung persentase kehadiran mahasiswa di suatu mata kuliah
     */
    public function hitungPersentaseKehadiran(int $mahasiswaId, int $mataKuliahId): float
    {
        $total = $this->where('mahasiswa_id', $mahasiswaId)
                      ->where('mata_kuliah_id', $mataKuliahId)
                      ->countAllResults(false);

        if ($total === 0) {
            return 0;
        }

        $hadir = $this->where('mahasiswa_id', $mahasiswaId)
                      ->where('mata_kuliah_id', $mataKuliahId)
                      ->where('status', 'hadir')
                      ->countAllResults();

        return round(($hadir / $total) * 100, 2);
    }

    /**
     * Ambil semua absensi lengkap (admin view) dengan data mahasiswa + MK
     */
    public function getAllWithDetail(): array
    {
        return $this->select('absensi.*, mahasiswa.nim, mahasiswa.nama as nama_mahasiswa,
                              mata_kuliah.kode_mk, mata_kuliah.nama_mk')
                    ->join('mahasiswa', 'mahasiswa.id = absensi.mahasiswa_id')
                    ->join('mata_kuliah', 'mata_kuliah.id = absensi.mata_kuliah_id')
                    ->orderBy('absensi.tanggal', 'DESC')
                    ->orderBy('mahasiswa.nim', 'ASC')
                    ->findAll();
    }

    /**
     * Ambil daftar MK yang diambil mahasiswa berdasarkan data absensi
     * (distinct mata_kuliah_id yang ada record absensinya)
     */
    public function getMataKuliahAbsensi(int $mahasiswaId): array
    {
        return $this->select('mata_kuliah.id, mata_kuliah.kode_mk, mata_kuliah.nama_mk, mata_kuliah.sks,
                              mata_kuliah.semester, COUNT(absensi.id) as total_pertemuan,
                              SUM(CASE WHEN absensi.status = "hadir" THEN 1 ELSE 0 END) as total_hadir,
                              SUM(CASE WHEN absensi.status = "sakit" THEN 1 ELSE 0 END) as total_sakit,
                              SUM(CASE WHEN absensi.status = "izin" THEN 1 ELSE 0 END) as total_izin,
                              SUM(CASE WHEN absensi.status = "alpha" THEN 1 ELSE 0 END) as total_alpha')
                    ->join('mata_kuliah', 'mata_kuliah.id = absensi.mata_kuliah_id')
                    ->where('absensi.mahasiswa_id', $mahasiswaId)
                    ->groupBy('mata_kuliah.id')
                    ->findAll();
    }

    /**
     * Ambil daftar mahasiswa unik beserta ringkasan absensi per MK
     */
    public function getRekapSummary(int $mataKuliahId): array
    {
        return $this->select('mahasiswa.id as mahasiswa_id, mahasiswa.nim, mahasiswa.nama,
                              COUNT(absensi.id) as total_pertemuan,
                              SUM(CASE WHEN absensi.status = "hadir" THEN 1 ELSE 0 END) as total_hadir,
                              SUM(CASE WHEN absensi.status = "sakit" THEN 1 ELSE 0 END) as total_sakit,
                              SUM(CASE WHEN absensi.status = "izin" THEN 1 ELSE 0 END) as total_izin,
                              SUM(CASE WHEN absensi.status = "alpha" THEN 1 ELSE 0 END) as total_alpha')
                    ->join('mahasiswa', 'mahasiswa.id = absensi.mahasiswa_id')
                    ->where('absensi.mata_kuliah_id', $mataKuliahId)
                    ->groupBy('mahasiswa.id')
                    ->orderBy('mahasiswa.nim', 'ASC')
                    ->findAll();
    }
}
