-- =============================================================================
-- VulnSchool - Inisialisasi Database
-- File ini dijalankan otomatis saat container MySQL pertama kali dibuat
-- =============================================================================

-- Pastikan database vulnschool sudah ada (dibuat oleh MYSQL_DATABASE env var)
USE vulnschool;

-- Set karakter UTF-8
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Info: Tabel akan dibuat oleh CodeIgniter Migrations (php spark migrate)
-- File ini hanya memastikan database dan user sudah siap

-- Beri hak akses penuh ke user vulnschool
GRANT ALL PRIVILEGES ON vulnschool.* TO 'vulnschool'@'%';
FLUSH PRIVILEGES;

SELECT 'Database vulnschool siap digunakan!' AS status;
