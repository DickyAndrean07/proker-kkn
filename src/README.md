# Portal Informasi Desa Bendung (Aplikasi Laravel)

Kode aplikasi untuk Portal Informasi Desa Bendung. Gambaran proyek, tangkapan layar, dan dokumentasi lengkap ada di [README utama](../README.md).

## Kebutuhan

- PHP 8.3 atau lebih baru
- Composer dan Node.js 20+
- MariaDB atau MySQL

## Perintah umum

```bash
composer setup   # install dependensi, salin .env, generate key, build aset
composer dev     # jalankan server pengembangan
composer test    # jalankan test suite
```

## Catatan

- Konfigurasi database dan AI diatur lewat environment variable. Gunakan `.env.example` sebagai acuan dan jangan commit `.env`.
- Session dan cache berbasis file, queue berjalan sinkron.
- Data disimpan dalam UTC, tampilan memakai `Asia/Jakarta`.
