#!/usr/bin/env bash
set -euo pipefail

echo "Reset VulnSchool ke state awal..."
docker compose down -v

mkdir -p public/uploads/foto
find public/uploads -mindepth 1 -delete
mkdir -p public/uploads/foto
touch public/uploads/.gitkeep
touch public/uploads/foto/.gitkeep

docker compose up -d --build
sleep 15

docker compose exec web php spark migrate:refresh --all
docker compose exec web php spark db:seed DatabaseSeeder

echo "Reset complete"
