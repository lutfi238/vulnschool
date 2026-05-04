# TROUBLESHOOTING

## Port already in use
- Ubah port mapping di `docker-compose.yml`.
- Cek proses yang memakai port: `netstat -ano` (Windows) atau `lsof -i` (Linux/Mac).

## Database connection refused
- Pastikan service db status healthy: `docker compose ps`.
- Tunggu beberapa detik lalu ulangi migrate/seed.

## Permission denied pada upload
- Jalankan di container web:
  - `chown -R www-data:www-data writable public/uploads`
  - `chmod -R 775 writable public/uploads`

## Container web gagal start
- Cek log: `docker compose logs web`.
- Rebuild image: `docker compose up -d --build --force-recreate`.

## Halaman kosong / error 500
- Cek log aplikasi di `writable/logs`.
- Cek juga log web container.
