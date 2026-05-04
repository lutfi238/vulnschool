# RESET STATE VulnSchool

## Opsi Cepat
Jalankan:

```bash
bash reset.sh
```

## Opsi Manual
```bash
docker compose down -v
docker compose up -d --build
docker compose exec web php spark migrate:refresh --all
docker compose exec web php spark db:seed DatabaseSeeder
```

## Bersihkan Upload dan Session
```bash
rm -rf public/uploads/*
rm -rf writable/session/*
mkdir -p public/uploads/foto
touch public/uploads/.gitkeep
```
