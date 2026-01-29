---
description: Cara update website Laravel di Hostinger (Shared Hosting)
---

# Update & Deploy Website

Karena ini adalah shared hosting dan folder `public/build` di-ignore oleh git, proses update dibagi menjadi dua bagian: **Local** dan **Server**.

## 1. Di Komputer Lokal (Windows)

Lakukan ini setiap kali ada perubahan pada kode atau tampilan (CSS/JS).

1. **Build Frontend Assets:**
   ```powershell
   cd c:\laragon\www\lab-smaba
   npm run build
   ```

2. **Upload Assets (Jika ada perubahan tampilan):**
   ```powershell
   # Upload folder build ke server
   scp -P 65002 -r public/build u451718575@178.16.135.238:~/Simlab-SMABA/public/
   ```

3. **Push Code ke GitHub:**
   (User sudah melakukan ini)
   ```powershell
   git add .
   git commit -m "Update pesan..."
   git push origin main
   ```

## 2. Di Server (SSH)

Lakukan ini untuk mengambil kode terbaru PHP dan update database.

1. **Login SSH:**
   ```powershell
   ssh -p 65002 u451718575@178.16.135.238
   ```

2. **Jalankan Perintah Update:**
   ```bash
   cd ~/Simlab-SMABA
   
   # Ambil kode terbaru
   git pull origin main
   
   # Install dependency php baru (jika ada)
   composer install --no-dev --optimize-autoloader
   
   # Update database (jika ada migration baru)
   php artisan migrate --force
   
   # Bersihkan dan refresh cache
   php artisan optimize:clear
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Shortcut (Script Otomatis)

Saya telah membuatkan script `deploy.sh` di server. Cukup jalankan:

```bash
cd ~/Simlab-SMABA
sh deploy.sh
```
