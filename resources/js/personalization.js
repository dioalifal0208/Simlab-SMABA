// Sistem Personalisasi
// Dinonaktifkan: Dark mode telah dihapus, aplikasi hanya menggunakan mode terang (light mode).

document.addEventListener('DOMContentLoaded', () => {
    // Hapus class 'dark' jika tersimpan dari sesi sebelumnya
    document.documentElement.classList.remove('dark');
    document.documentElement.setAttribute('data-theme', 'light');

    // Hapus preferensi dark mode lama dari localStorage jika ada
    const saved = localStorage.getItem('lab-smaba-preferences');
    if (saved) {
        try {
            const data = JSON.parse(saved);
            if (data.theme === 'dark') {
                localStorage.removeItem('lab-smaba-preferences');
            }
        } catch (e) {
            localStorage.removeItem('lab-smaba-preferences');
        }
    }

    // Hapus floating dark mode toggle jika masih ada di DOM
    const existingToggle = document.querySelector('.dark-mode-toggle');
    if (existingToggle) {
        existingToggle.remove();
    }
});
