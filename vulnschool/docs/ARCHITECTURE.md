# Arsitektur Teknis VulnSchool

## Komponen
1. Web App: CodeIgniter 4 (PHP 8.2 + Apache)
2. Database: MySQL 8.0
3. Admin DB UI: phpMyAdmin

## Alur Dasar
1. Request masuk ke Apache container (`web`).
2. Routing CodeIgniter mengarahkan ke controller.
3. Controller membaca/menulis data via model ke MySQL.
4. View merender respons HTML.

## Deployment
- Orkestrasi menggunakan `docker-compose.yml` pada network `vulnschool-net`.
- Data database persisten di volume `vulnschool-db-data`.
