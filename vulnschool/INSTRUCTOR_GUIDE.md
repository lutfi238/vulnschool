# INSTRUCTOR GUIDE - VulnSchool

## Tujuan
Panduan untuk dosen/lab assistant dalam menyiapkan dan menjalankan praktikum keamanan menggunakan VulnSchool.

## Setup Cepat
1. Clone repository.
2. Jalankan `bash setup.sh`.
3. Verifikasi `http://localhost:8080` dan `http://localhost:8081`.

## Checklist Pra-Praktikum
1. Docker Desktop berjalan.
2. Port 8080, 8081, 3306 tidak bentrok.
3. Semua mahasiswa memakai clone repo masing-masing.
4. Database sudah di-seed ulang sebelum kelas dimulai.

## Reset Antar Sesi
1. Jalankan `bash reset.sh`.
2. Atau manual: `docker compose down -v`, `docker compose up -d --build`, migrate + seed.

## Menambah Vulnerability Baru
1. Tambahkan kode rentan di controller/model/view.
2. Beri marker komentar `VULN-XXX` sesuai format standar.
3. Dokumentasikan pada `VULNERABILITIES.md` (lokasi, reproduksi, fix).
4. Tambahkan ke handout atau catatan dosen jika dipakai di sesi tertentu.

## Troubleshooting Ringkas
1. Container crash loop: cek `docker compose logs web`.
2. DB belum ready: ulangi `docker compose exec web php spark migrate --all`.
3. Error permission upload: cek permission `public/uploads` dan `writable`.
