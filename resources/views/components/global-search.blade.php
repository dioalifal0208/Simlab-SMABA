{{--
    Global Search Command Palette
    Trigger: tombol di navbar, atau keyboard shortcut Ctrl+K / Cmd+K
--}}

<style>
/* ── Palette overlay ──────────────────────────────────── */
#global-search-palette {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
#global-search-palette.gs-open {
    display: flex;
}

/* ── Backdrop ─────────────────────────────────────────── */
#gs-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    animation: gs-fade-in 0.18s ease;
}

/* ── Palette box ──────────────────────────────────────── */
#gs-box {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 560px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.22), 0 4px 16px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    animation: gs-slide-in 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.05);
}

/* ── Input row ────────────────────────────────────────── */
#gs-input-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}
#gs-input-row .gs-icon {
    color: #94a3b8;
    font-size: 15px;
    flex-shrink: 0;
}
#gs-input {
    flex: 1;
    border: 1px solid #e2e8f0;
    outline: none;
    background: #f8fafc;
    font-size: 14px;
    color: #1e293b;
    font-family: inherit;
    padding: 8px 14px;
    border-radius: 12px;
    transition: border-color 0.15s, box-shadow 0.15s;
}
#gs-input:focus {
    border-color: #22c55e;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    background: #fff;
}
#gs-input::placeholder { color: #94a3b8; }
.gs-esc-kbd {
    font-size: 10px;
    color: #94a3b8;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 5px;
    padding: 2px 6px;
    font-family: monospace;
    flex-shrink: 0;
}

/* ── Results area ─────────────────────────────────────── */
#gs-results {
    overflow-y: auto;
    overscroll-behavior: contain;
    flex: 1;
    min-height: 0;
}

/* ── Section label ────────────────────────────────────── */
.gs-section-label {
    padding: 12px 16px 4px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #94a3b8;
}

/* ── Result item ──────────────────────────────────────── */
.gs-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    margin: 0 6px;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.12s, color 0.12s;
    list-style: none;
}
.gs-item:hover, .gs-item.gs-active {
    background: #f0fdf4;
}
.gs-item:hover .gs-item-title, .gs-item.gs-active .gs-item-title {
    color: #16a34a;
}
.gs-item-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    flex-shrink: 0;
    font-size: 13px;
}
.gs-icon-items     { background: #f0fdf4; color: #16a34a; }
.gs-icon-documents { background: #eff6ff; color: #2563eb; }
.gs-icon-bookings  { background: #faf5ff; color: #7c3aed; }
.gs-icon-loans     { background: #fff7ed; color: #ea580c; }
.gs-icon-nav       { background: #f8fafc; color: #475569; }

.gs-item-text { flex: 1; min-width: 0; }
.gs-item-title {
    font-size: 13.5px;
    font-weight: 500;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: color 0.12s;
}
.gs-item-sub {
    font-size: 11.5px;
    color: #94a3b8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.gs-item-arrow { font-size: 10px; color: #cbd5e1; transition: color 0.12s, transform 0.12s; }
.gs-item:hover .gs-item-arrow { color: #16a34a; transform: translateX(2px); }

/* ── States ───────────────────────────────────────────── */
#gs-loading, #gs-no-results {
    display: none;
    padding: 32px 16px;
    text-align: center;
    color: #94a3b8;
    font-size: 14px;
}
#gs-loading i { font-size: 24px; color: #22c55e; display: block; margin-bottom: 8px; }
#gs-no-results i { font-size: 28px; color: #e2e8f0; display: block; margin-bottom: 8px; }

/* ── Footer ───────────────────────────────────────────── */
#gs-footer {
    padding: 8px 16px;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
    display: flex;
    gap: 16px;
    flex-shrink: 0;
}
#gs-footer span { font-size: 10px; color: #94a3b8; }
.gs-kbd {
    display: inline-block;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 1px 5px;
    font-family: monospace;
    font-size: 9px;
    color: #64748b;
    margin-right: 2px;
}

/* ── Animations ───────────────────────────────────────── */
@keyframes gs-fade-in  { from { opacity: 0 } to { opacity: 1 } }
@keyframes gs-slide-in { from { opacity: 0; transform: translateY(-10px) scale(0.97) } to { opacity: 1; transform: translateY(0) scale(1) } }
</style>

{{-- ==================== MARKUP ==================== --}}
<div id="global-search-palette" role="dialog" aria-modal="true" aria-label="Pencarian Global">

    {{-- Backdrop --}}
    <div id="gs-backdrop"></div>

    {{-- Palette Box --}}
    <div id="gs-box">

        {{-- Input Row --}}
        <div id="gs-input-row">
            <i class="fas fa-magnifying-glass gs-icon"></i>
            <input
                id="gs-input"
                type="search"
                autocomplete="off"
                spellcheck="false"
                placeholder="Cari alat, dokumen, fitur..."
            >
            <span class="gs-esc-kbd">Esc</span>
        </div>

        {{-- Results Area --}}
        <div id="gs-results">

            {{-- Loading --}}
            <div id="gs-loading">
                <i class="fas fa-spinner fa-spin"></i>
                Mencari...
            </div>

            {{-- No Results --}}
            <div id="gs-no-results">
                <i class="fas fa-face-meh"></i>
                Tidak ditemukan hasil. Coba kata kunci lain.
            </div>

            {{-- Groups --}}
            <div id="gs-groups">
                <div id="gs-group-nav"       class="gs-group-wrap"><p class="gs-section-label">Navigasi Cepat</p><ul></ul></div>
                <div id="gs-group-items"     class="gs-group-wrap"><p class="gs-section-label">Alat &amp; Bahan</p><ul></ul></div>
                <div id="gs-group-documents" class="gs-group-wrap"><p class="gs-section-label">Dokumen Digital</p><ul></ul></div>
                <div id="gs-group-bookings"  class="gs-group-wrap"><p class="gs-section-label">Booking Lab</p><ul></ul></div>
                <div id="gs-group-loans"     class="gs-group-wrap"><p class="gs-section-label">Peminjaman Alat</p><ul></ul></div>
            </div>

        </div>

        {{-- Footer --}}
        <div id="gs-footer">
            <span><span class="gs-kbd">↑</span><span class="gs-kbd">↓</span> navigasi</span>
            <span><span class="gs-kbd">Enter</span> buka</span>
            <span><span class="gs-kbd">Esc</span> tutup</span>
        </div>

    </div>
</div>

{{-- ==================== JAVASCRIPT ==================== --}}
<script>
(function () {
    const SEARCH_URL = '{{ route("search.global") }}';

    const palette   = document.getElementById('global-search-palette');
    const backdrop  = document.getElementById('gs-backdrop');
    const input     = document.getElementById('gs-input');
    const loading   = document.getElementById('gs-loading');
    const noResults = document.getElementById('gs-no-results');
    const groups    = document.getElementById('gs-groups');

    let isOpen    = false;
    let debounce  = null;
    let allItems  = [];
    let activeIdx = -1;

    // ── Icon class map ───────────────────────────────────
    const iconClass = {
        items:     'gs-icon-items',
        documents: 'gs-icon-documents',
        bookings:  'gs-icon-bookings',
        loans:     'gs-icon-loans',
        nav:       'gs-icon-nav',
    };

    // ── Build result <li> ────────────────────────────────
    function buildItem(r) {
        const li       = document.createElement('li');
        li.className   = 'gs-item';
        li.dataset.url = r.url;
        const ic       = iconClass[r.category] || 'gs-icon-nav';
        li.innerHTML = `
            <span class="gs-item-icon ${ic}"><i class="fas ${r.icon}"></i></span>
            <span class="gs-item-text">
                <span class="gs-item-title">${esc(r.title)}</span>
                <span class="gs-item-sub">${esc(r.subtitle || '')}</span>
            </span>
            <i class="fas fa-arrow-right gs-item-arrow"></i>`;
        li.addEventListener('click', () => navigate(r.url));
        return li;
    }

    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    // ── Render a group ────────────────────────────────────
    function renderGroup(id, results, category) {
        const wrap = document.getElementById(id);
        const ul   = wrap.querySelector('ul');
        ul.innerHTML = '';

        if (!results || results.length === 0) {
            wrap.style.display = 'none';
            return;
        }

        wrap.style.display = 'block';
        results.forEach(r => {
            const li = buildItem({ ...r, category });
            ul.appendChild(li);
            allItems.push(li);
        });
    }

    // ── State helpers ─────────────────────────────────────
    function setLoading(show) {
        loading.style.display  = show ? 'block' : 'none';
        noResults.style.display = 'none';
        groups.style.display    = show ? 'none' : 'block';
    }

    function setNoResults() {
        loading.style.display   = 'none';
        noResults.style.display = 'block';
        groups.style.display    = 'none';
    }

    function resetGroups() {
        allItems  = [];
        activeIdx = -1;
        ['gs-group-nav','gs-group-items','gs-group-documents','gs-group-bookings','gs-group-loans']
            .forEach(id => {
                const w = document.getElementById(id);
                w.style.display = 'none';
                w.querySelector('ul').innerHTML = '';
            });
    }

    // ── Keyboard navigation ───────────────────────────────
    function setActive(idx) {
        allItems.forEach((el, i) => el.classList.toggle('gs-active', i === idx));
        if (allItems[idx]) allItems[idx].scrollIntoView({ block: 'nearest' });
        activeIdx = idx;
    }

    // ── Fetch ─────────────────────────────────────────────
    async function doSearch(q) {
        try {
            const res  = await fetch(`${SEARCH_URL}?q=${encodeURIComponent(q)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || ''
                }
            });

            if (!res.ok) { setNoResults(); return; }
            const data = await res.json();

            setLoading(false);
            resetGroups();

            // Untuk q kosong → tampilkan semua nav cepat
            if (!q || q.length < 2) {
                renderGroup('gs-group-nav', data.nav, 'nav');
            } else {
                renderGroup('gs-group-items',     data.items,     'items');
                renderGroup('gs-group-documents', data.documents, 'documents');
                renderGroup('gs-group-bookings',  data.bookings,  'bookings');
                renderGroup('gs-group-loans',     data.loans,     'loans');
                renderGroup('gs-group-nav',       data.nav,       'nav');
            }

            const hasAny = allItems.length > 0;
            if (!hasAny) setNoResults();

        } catch (e) {
            console.error('[GlobalSearch] Error:', e);
            setNoResults();
        }
    }

    // ── Hardcoded quick navigation (fallback & default) ──
    const defaultNav = [
        { title: 'Dashboard',        subtitle: 'Halaman utama',              icon: 'fa-gauge-high',      url: '{{ route("dashboard") }}' },
        { title: 'Inventaris',       subtitle: 'Daftar alat & bahan lab',    icon: 'fa-boxes-stacked',   url: '{{ route("items.index") }}' },
        { title: 'Peminjaman Alat',  subtitle: 'Ajukan atau lihat pinjaman', icon: 'fa-hand-holding',    url: '{{ route("loans.index") }}' },
        { title: 'Booking Lab',      subtitle: 'Jadwal penggunaan lab',      icon: 'fa-calendar-check',  url: '{{ route("bookings.index") }}' },
        { title: 'Kalender',         subtitle: 'Jadwal lab sebulan penuh',   icon: 'fa-calendar-days',   url: '{{ route("calendar.index") }}' },
        { title: 'Dokumen Digital',   subtitle: 'Pustaka & file lab',         icon: 'fa-folder-open',     url: '{{ route("documents.index") }}' },
        { title: 'Modul Praktikum',  subtitle: 'Panduan kegiatan lab',       icon: 'fa-flask',           url: '{{ route("practicum-modules.index") }}' },
        { title: 'Profil Saya',      subtitle: 'Pengaturan akun',            icon: 'fa-circle-user',     url: '{{ route("profile.edit") }}' },
    ];

    // Tampilkan navigasi cepat hardcoded (tanpa fetch)
    function showDefaultNav() {
        setLoading(false);
        resetGroups();
        renderGroup('gs-group-nav', defaultNav, 'nav');
    }

    // ── Open ──────────────────────────────────────────────
    function open() {
        if (isOpen) return;
        isOpen = true;
        palette.classList.add('gs-open');
        input.value = '';
        input.focus();
        // Langsung tampilkan navigasi cepat dari data hardcoded
        showDefaultNav();
    }

    // ── Close ─────────────────────────────────────────────
    function close() {
        if (!isOpen) return;
        isOpen = false;
        palette.classList.remove('gs-open');
    }

    function navigate(url) {
        close();
        window.location.href = url;
    }

    // ── Input handler ─────────────────────────────────────
    input.addEventListener('input', () => {
        const q = input.value.trim();
        clearTimeout(debounce);

        // Jika input kosong, tampilkan navigasi cepat hardcoded
        if (q.length === 0)  { showDefaultNav(); return; }
        if (q.length < 2)    { return; }

        setLoading(true);
        debounce = setTimeout(() => doSearch(q), 300);
    });

    // ── Keyboard ──────────────────────────────────────────
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            isOpen ? close() : open();
            return;
        }
        if (!isOpen) return;

        if (e.key === 'Escape')    { e.preventDefault(); close(); }
        else if (e.key === 'ArrowDown') { e.preventDefault(); setActive(Math.min(activeIdx + 1, allItems.length - 1)); }
        else if (e.key === 'ArrowUp')   { e.preventDefault(); setActive(Math.max(activeIdx - 1, 0)); }
        else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIdx >= 0 && allItems[activeIdx]) navigate(allItems[activeIdx].dataset.url);
        }
    });

    // ── Backdrop click ────────────────────────────────────
    backdrop.addEventListener('click', close);

    // ── Bind trigger buttons ──────────────────────────────
    function bindBtn(id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', open);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => { bindBtn('global-search-trigger'); bindBtn('global-search-trigger-mobile'); });
    } else {
        bindBtn('global-search-trigger');
        bindBtn('global-search-trigger-mobile');
    }

})();
</script>
