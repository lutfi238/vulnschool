#!/usr/bin/env bash
set -euo pipefail

echo "VulnSchool Setup"
docker compose up -d --build

echo "Menunggu database siap..."
sleep 15

docker compose exec web php spark migrate --all
docker compose exec web php spark db:seed DatabaseSeeder

echo "VulnSchool ready"
echo "URL: http://localhost:8080"
echo "phpMyAdmin: http://localhost:8081"
echo "Akun admin: admin / admin123"
