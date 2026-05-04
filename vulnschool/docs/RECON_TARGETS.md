# RECON TARGETS (P4)

## Endpoint Menarik
1. `/robots.txt`
2. `/info.php`
3. `/test.php`
4. `/backup/db_dummy.sql`
5. `/api/users`
6. `/admin/logs?file=`

## Header / Info Leak yang Umum
1. Informasi versi software dari response default server.
2. Version disclosure aplikasi pada footer.

## Hint Wordlist
1. Gunakan wordlist umum (`common.txt`, `directory-list-2.3-medium.txt`).
2. Prioritaskan kata: `admin`, `backup`, `test`, `api`, `logs`, `info`.
