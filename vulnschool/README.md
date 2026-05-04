# VulnSchool - Sistem Akademik Kampus (Intentionally Vulnerable)

> WARNING
> Aplikasi ini sengaja dibuat rentan untuk pembelajaran keamanan siber.
> Jangan deploy ke internet publik atau production.
> Gunakan hanya di lab lokal yang terisolasi.

## Deskripsi
VulnSchool adalah simulasi sistem akademik kampus untuk mata kuliah Sistem Keamanan Informasi (SKI), D3 Teknik Informatika, Politeknik Negeri Pontianak.

Aplikasi bersifat multi-role:
- Admin
- Dosen
- Mahasiswa

Fitur utama:
- Kelola user, mahasiswa, dosen, mata kuliah
- Modul nilai dan absensi
- Pengumuman dan komentar
- Endpoint recon dan attack surface tambahan untuk praktikum

## Spesifikasi Sistem
- Framework: CodeIgniter 4.7.x
- Bahasa: PHP 8.2+
- Database: MySQL 8.0
- Web Server: Apache (container)
- Frontend: Bootstrap 5 + Vanilla JS
- Deployment: Docker Compose

## Menjalankan Aplikasi
Prasyarat:
- Docker Desktop aktif
- Port 8080, 8081, 3306 tidak dipakai aplikasi lain

### Opsi 1 (disarankan)
```bash
bash setup.sh
```

### Opsi 2 (manual)
```bash
docker compose up -d --build
docker compose exec web php spark migrate --all
docker compose exec web php spark db:seed DatabaseSeeder
```

Akses layanan:
- App: http://localhost:8080
- phpMyAdmin: http://localhost:8081

## Helper Command
- Start: `make up`
- Stop: `make down`
- Reset: `make reset`
- Logs: `make logs`
- Shell web container: `make shell`
- Migrate: `make migrate`
- Seed: `make seed`

## Akun Default
Catatan: password disimpan plain text (VULN-AUTH-001).

- Admin: `admin / admin123`
- Dosen: `dosen1 / dosen123`, `dosen2 / dosen123`, `dosen3 / dosen123`
- Mahasiswa: `mhs001 / mhs123` sampai `mhs010 / mhs123`

## Daftar Fitur
- Authentication: login, register, forgot/reset password
- Dashboard per role (admin/dosen/mahasiswa)
- CRUD data akademik
- Transcript nilai + IPK
- Rekap absensi
- Pengumuman + komentar
- Recon targets (`robots.txt`, `info.php`, `test.php`, backup, endpoint API)

## Quick Reference Vulnerabilities
Lihat detail lengkap di [VULNERABILITIES.md](VULNERABILITIES.md).

Target penting:
- VULN-IDOR-004: `/mahasiswa/nilai/{id}`
- VULN-IDOR-005: `/mahasiswa/nilai?mhs_id=...`
- VULN-AUTH-005: username enumeration di login
- VULN-SQLI-001: login SQL injection

## Mapping ke Pertemuan SKI
- P4: VULN-INFO-001, VULN-INFO-002
- P10: VULN-AUTH-003, VULN-AUTH-005
- P11 (target utama): VULN-IDOR-004, VULN-IDOR-005, VULN-MASS-001
- P13: rangkaian IDOR
- P15: rangkaian AUTH vulnerabilities
- P17: security audit komprehensif

## Dokumentasi Tambahan
- [VULNERABILITIES.md](VULNERABILITIES.md)
- [INSTRUCTOR_GUIDE.md](INSTRUCTOR_GUIDE.md)
- [STUDENT_HANDOUT.md](STUDENT_HANDOUT.md)
- [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
- [CHANGELOG.md](CHANGELOG.md)
- [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)
- [docs/RECON_TARGETS.md](docs/RECON_TARGETS.md)
- [docs/SQLI_PAYLOADS.md](docs/SQLI_PAYLOADS.md)
- [docs/RESET.md](docs/RESET.md)

## Reset Lingkungan
```bash
bash reset.sh
```

## Lisensi dan Disclaimer
Aplikasi ini dibuat untuk kebutuhan edukasi. Penggunaan teknik dari aplikasi ini pada sistem tanpa izin adalah tindakan ilegal dan di luar tanggung jawab pengembang.
