#!/usr/bin/env sh
set -e

cd /var/www/html

DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
INIT_MARKER="writable/.vulnschool_initialized"

# Pastikan dependency PHP tersedia (berguna saat volume mount source aktif)
if [ ! -f vendor/autoload.php ]; then
  echo "[entrypoint] vendor belum ada, menjalankan composer install..."
  composer install --no-dev --no-interaction --optimize-autoloader
fi

mkdir -p writable/cache writable/logs writable/session writable/uploads public/uploads/foto
chown -R www-data:www-data writable public/uploads || true
chmod -R 775 writable public/uploads || true

# Tunggu MySQL siap menerima koneksi
MAX_ATTEMPTS=60
ATTEMPT=1
DB_READY=0
while [ $ATTEMPT -le $MAX_ATTEMPTS ]; do
  if php -r "new mysqli(getenv('DB_HOST') ?: 'db', getenv('database.default.username') ?: 'vulnschool', getenv('database.default.password') ?: 'vulnschool123', getenv('database.default.database') ?: 'vulnschool', (int)(getenv('DB_PORT') ?: 3306));" >/dev/null 2>&1; then
    echo "[entrypoint] Database siap."
    DB_READY=1
    break
  fi

  echo "[entrypoint] Menunggu DB $DB_HOST:$DB_PORT... ($ATTEMPT/$MAX_ATTEMPTS)"
  ATTEMPT=$((ATTEMPT + 1))
  sleep 2
done

if [ "$DB_READY" -ne 1 ]; then
  echo "[entrypoint] Gagal terhubung ke database setelah $MAX_ATTEMPTS percobaan."
  exit 1
fi

# Inisialisasi otomatis hanya sekali
if [ ! -f "$INIT_MARKER" ]; then
  echo "[entrypoint] First run terdeteksi. Menjalankan migrate + seed..."
  php spark migrate --all
  php spark db:seed DatabaseSeeder
  touch "$INIT_MARKER"
  echo "[entrypoint] Inisialisasi selesai."
else
  echo "[entrypoint] Marker inisialisasi ditemukan, skip seed. Menjalankan migrate incremental..."
  php spark migrate --all || true
fi

exec apache2-foreground
