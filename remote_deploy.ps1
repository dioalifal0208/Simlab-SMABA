# script ini digunakan untuk otomatisasi deployment ke GitHub dan Hostinger
# Penggunaan: .\remote_deploy.ps1

$SSH_USER = "u203096280"
$SSH_HOST = "45.90.229.210"
$SSH_PORT = "65002"
$REMOTE_PATH = "~/domains/smanegeri1babatlmg.sch.id/public_html/lab"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "🚀 MEMULAI PROSES DEPLOYMENT OTOMATIS" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# 1. Cek Status Git
Write-Host "`n[1/3] Sinkronisasi ke GitHub..." -ForegroundColor Yellow
$gitStatus = git status --porcelain
if ($gitStatus) {
    Write-Host "⚠️ Ada perubahan yang belum di-commit. Proses dihentikan." -ForegroundColor Red
    Write-Host "Gunakan 'git add' dan 'git commit' terlebih dahulu."
    exit
}

# 2. Push ke GitHub
Write-Host "Memulai git push origin main..."
git push origin main
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Gagal melakukan push ke GitHub. Deployment dibatalkan." -ForegroundColor Red
    exit
}
Write-Host "✅ GitHub sinkron!" -ForegroundColor Green

# 3. Jalankan Deploy di Hostinger via SSH
Write-Host "`n[2/3] Menghubungkan ke Hostinger (Port $SSH_PORT)..." -ForegroundColor Yellow
$remoteCommand = "cd $REMOTE_PATH && ./deploy.sh"

ssh -p $SSH_PORT "${SSH_USER}@${SSH_HOST}" $remoteCommand

if ($LASTEXITCODE -ne 0) {
    Write-Host "`n❌ Terjadi kesalahan saat menjalankan skrip di server." -ForegroundColor Red
    exit
}

Write-Host "`n==========================================" -ForegroundColor Green
Write-Host "🎉 DEPLOYMENT SELESAI DENGAN SUKSES!" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Green
