---
description: Cara update website Laravel di Hosting menggunakan Workflow Git
---

# Update & Deploy Website

Proses update sudah menggunakan Git-based workflow untuk deployment yang lebih mudah.

## 1. Di Komputer Lokal (Windows)

Setiap kali ada perubahan kode atau tampilan, komit dan push ke GitHub:

```powershell
cd c:\laragon\www\lab-smaba
git add .
git commit -m "Update deskripsi..."
git push origin main
```

## 2. Di Server Hostinger

Login via SSH ke server Hostinger, kemudian jalankan script deploy:

```bash
# Masuk ke direktori web
cd ~/domains/websmaba.site/public_html

# Jalankan skrip deploy
./deploy.sh
```

Jika terjadi masalah permission pada direktori, pastikan Anda memberikan hak akses yang benar:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```
