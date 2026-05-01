---
description: Deploy otomatis ke GitHub dan Hostinger. Trigger dengan prompt "deploy".
---

# Auto Deploy

// turbo-all

Deploy otomatis: build → commit → push GitHub → SSH Hostinger → deploy.sh

## Step 1: Build assets production

```powershell
cd c:\laragon\www\lab-smaba
npm run build
```

## Step 2: Stage semua perubahan

```powershell
cd c:\laragon\www\lab-smaba
git add .
```

## Step 3: Commit (gunakan pesan yang relevan dari konteks perubahan)

```powershell
cd c:\laragon\www\lab-smaba
git commit -m "update: deploy changes"
```

## Step 4: Push ke GitHub dan deploy ke Hostinger via SSH

```powershell
cd c:\laragon\www\lab-smaba
powershell -ExecutionPolicy Bypass -File .\remote_deploy.ps1
```
