# WebApp
berikut roadmap terstruktur untuk project MAN1HST — aplikasi sistem manajemen madrasah digital multi-role dengan fitur lengkap (absensi, pembayaran, data guru/siswa, integrasi Fitur lainnya)

## Konfigurasi Lingkungan

Buat file `.env` di root proyek untuk menyimpan kredensial database:

```
DB_HOST=localhost
DB_NAME=absensi_db
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
ALLOWED_ORIGIN=http://localhost:3000
```

File `.env` sudah ditambahkan ke `.gitignore` dan tidak boleh dikomit ke repository.
