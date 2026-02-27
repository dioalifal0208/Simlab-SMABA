{{--
    Global Search Command Palette
    Trigger: tombol di navbar, atau keyboard shortcut Ctrl+K / Cmd+K
--}}
<div id="global-search-palette"
     class="fixed inset-0 z-[200] flex items-start justify-center pt-[8vh] sm:pt-[12vh] px-4 hidden"
     role="dialog" aria-modal="true" aria-label="Pencarian Global">

    {{-- Backdrop --}}
    <div id="gs-backdrop"
         class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-200 opacity-0"></div>

    {{-- Palette Box --}}
    <div id="gs-box"
         class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden
                transition-all duration-200 scale-95 opacity-0"
         style="max-height: 80vh;">

        {{-- Search Input Row --}}
        <div class="flex items-center gap-3 px-4 py-3.5 border-b border-gray-100">
            <i class="fas fa-magnifying-glass text-gray-400 text-base flex-shrink-0"></i>
            <input
                id="gs-input"
                type="search"
                autocomplete="off"
                spellcheck="false"
                placeholder="Cari alat, dokumen, fitur..."
                class="flex-1 bg-transparent text-gray-900 text-sm placeholder-gray-400 outline-none border-none focus:ring-0"
            >
            <kbd class="hidden sm:inline-flex items-center gap-1 text-[10px] text-gray-400 bg-gray-100 border border-gray-200 rounded px-1.5 py-0.5 font-mono select-none">
                Esc
            </kbd>
        </div>

        {{-- Results Area --}}
        <div id="gs-results"
             class="overflow-y-auto overscroll-contain"
             style="max-height: calc(80vh - 58px);">

            {{-- Empty / Default State (tampil saat kosong) --}}
            <div id="gs-empty-state" class="py-3">
                <p class="px-4 pt-1 pb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Navigasi Cepat</p>
                <ul id="gs-nav-list" class="gs-group"></ul>
            </div>

            {{-- Loading State --}}
            <div id="gs-loading" class="hidden py-8 flex flex-col items-center gap-3 text-gray-400">
                <i class="fas fa-spinner fa-spin text-2xl text-green-500"></i>
                <span class="text-sm">Mencari...</span>
            </div>

            {{-- No Results --}}
            <div id="gs-no-results" class="hidden py-8 text-center text-gray-400">
                <i class="fas fa-face-meh text-3xl mb-2 text-gray-300"></i>
                <p class="text-sm">Tidak ditemukan hasil untuk kueri ini.</p>
            </div>

            {{-- Search Results (diisi via JS) --}}
            <div id="gs-search-results" class="hidden py-2 space-y-0.5">
                {{-- Item Alat/Bahan --}}
                <div id="gs-group-items" class="hidden">
                    <p class="px-4 pt-3 pb-1 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Alat & Bahan</p>
                    <ul class="gs-group"></ul>
                </div>
                {{-- Dokumen --}}
                <div id="gs-group-documents" class="hidden">
                    <p class="px-4 pt-3 pb-1 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Dokumen Digital</p>
                    <ul class="gs-group"></ul>
                </div>
                {{-- Booking --}}
                <div id="gs-group-bookings" class="hidden">
                    <p class="px-4 pt-3 pb-1 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Booking Lab</p>
                    <ul class="gs-group"></ul>
                </div>
                {{-- Loan --}}
                <div id="gs-group-loans" class="hidden">
                    <p class="px-4 pt-3 pb-1 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Peminjaman Alat</p>
                    <ul class="gs-group"></ul>
                </div>
                {{-- Navigasi --}}
                <div id="gs-group-nav" class="hidden">
                    <p class="px-4 pt-3 pb-1 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Navigasi</p>
                    <ul class="gs-group"></ul>
                </div>
            </div>

        </div>{{-- /results --}}

        {{-- Footer hint --}}
        <div class="border-t border-gray-100 px-4 py-2 flex items-center gap-4 text-[10px] text-gray-400 bg-gray-50">
            <span><kbd class="bg-white border border-gray-200 rounded px-1 mr-0.5">↑</kbd><kbd class="bg-white border border-gray-200 rounded px-1">↓</kbd> navigasi</span>
            <span><kbd class="bg-white border border-gray-200 rounded px-1">Enter</kbd> buka</span>
            <span><kbd class="bg-white border border-gray-200 rounded px-1">Esc</kbd> tutup</span>
        </div>

    </div>{{-- /gs-box --}}
</div>

{{-- ==================== ICON CONFIGS ==================== --}}
@php
$iconColors = [
    'items'     => 'text-green-600 bg-green-50',
    'documents' => 'text-blue-600 bg-blue-50',
    'bookings'  => 'text-purple-600 bg-purple-50',
    'loans'     => 'text-orange-600 bg-orange-50',
    'nav'       => 'text-gray-600 bg-gray-100',
];
@endphp

{{-- ==================== JAVASCRIPT ==================== --}}
<script>
(function () {
    const SEARCH_URL = '{{ route("search.global") }}';
    const CSRF       = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    const palette    = document.getElementById('global-search-palette');
    const backdrop   = document.getElementById('gs-backdrop');
    const box        = document.getElementById('gs-box');
    const input      = document.getElementById('gs-input');
    const emptyState = document.getElementById('gs-empty-state');
    const loading    = document.getElementById('gs-loading');
    const noResults  = document.getElementById('gs-no-results');
    const searchRes  = document.getElementById('gs-search-results');

    // State
    let isOpen     = false;
    let debounce   = null;
    let allItems   = [];   // flat list of all result <li> for keyboard nav
    let activeIdx  = -1;

    // ── Icon color map by category ──────────────────────────────
    const catColors = {
        items:     'text-green-600 bg-green-50',
        documents: 'text-blue-600 bg-blue-50',
        bookings:  'text-purple-600 bg-purple-50',
        loans:     'text-orange-600 bg-orange-50',
        nav:       'text-gray-600 bg-gray-100',
    };

    // ── Build a result <li> element ─────────────────────────────
    function buildItem(result) {
        const li = document.createElement('li');
        li.className = 'gs-item flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-gray-50 rounded-lg mx-1 transition-colors group';
        li.dataset.url = result.url;

        const color = catColors[result.category] || catColors.nav;
        li.innerHTML = `
            <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg ${color} text-sm">
                <i class="fas ${result.icon}"></i>
            </span>
            <span class="flex-1 min-w-0">
                <span class="block text-sm font-medium text-gray-800 truncate group-hover:text-green-700 transition-colors">${escHtml(result.title)}</span>
                <span class="block text-xs text-gray-400 truncate">${escHtml(result.subtitle || '')}</span>
            </span>
            <i class="fas fa-arrow-right text-[10px] text-gray-300 group-hover:text-green-500 transition-all group-hover:translate-x-0.5"></i>
        `;
        li.addEventListener('click', () => navigate(result.url));
        return li;
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Render group ────────────────────────────────────────────
    function renderGroup(groupId, results) {
        const container = document.getElementById(groupId);
        if (!container) return;
        const ul = container.querySelector('ul');
        ul.innerHTML = '';

        if (!results || results.length === 0) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');
        results.forEach(r => {
            const li = buildItem(r);
            ul.appendChild(li);
            allItems.push(li);
        });
    }

    // ── Show/hide states ─────────────────────────────────────────
    function showEmptyState(data) {
        emptyState.classList.remove('hidden');
        loading.classList.add('hidden');
        noResults.classList.add('hidden');
        searchRes.classList.add('hidden');

        // Render nav cepat
        const navList = document.getElementById('gs-nav-list');
        navList.innerHTML = '';
        allItems = [];
        (data?.nav || []).forEach(r => {
            const li = buildItem({...r, category: 'nav'});
            navList.appendChild(li);
            allItems.push(li);
        });
    }

    function showLoading() {
        emptyState.classList.add('hidden');
        loading.classList.remove('hidden');
        noResults.classList.add('hidden');
        searchRes.classList.add('hidden');
    }

    function showNoResults() {
        emptyState.classList.add('hidden');
        loading.classList.add('hidden');
        noResults.classList.remove('hidden');
        searchRes.classList.add('hidden');
    }

    function showResults(data) {
        allItems = [];
        emptyState.classList.add('hidden');
        loading.classList.add('hidden');
        noResults.classList.add('hidden');
        searchRes.classList.remove('hidden');

        renderGroup('gs-group-items',     data.items);
        renderGroup('gs-group-documents', data.documents);
        renderGroup('gs-group-bookings',  data.bookings);
        renderGroup('gs-group-loans',     data.loans);
        renderGroup('gs-group-nav',       data.nav);

        const hasAny = allItems.length > 0;
        if (!hasAny) showNoResults();
        activeIdx = -1;
    }

    // ── Keyboard navigation ──────────────────────────────────────
    function setActive(idx) {
        allItems.forEach((el, i) => {
            el.classList.toggle('bg-green-50', i === idx);
            el.classList.toggle('text-green-700', i === idx);
            if (i === idx) el.scrollIntoView({ block: 'nearest' });
        });
        activeIdx = idx;
    }

    // ── Fetch & render results ───────────────────────────────────
    async function doSearch(q) {
        try {
            const res  = await fetch(`${SEARCH_URL}?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.empty || !q || q.length < 2) {
                showEmptyState(data);
            } else {
                showResults(data);
            }
        } catch (e) {
            showNoResults();
        }
    }

    // ── Open / Close ─────────────────────────────────────────────
    function open() {
        if (isOpen) return;
        isOpen = true;
        palette.classList.remove('hidden');
        // Trickery: force reflow untuk trigger transition
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            });
        });
        input.value = '';
        input.focus();
        // Load navigasi cepat default
        doSearch('');
    }

    function close() {
        if (!isOpen) return;
        isOpen = false;
        backdrop.classList.add('opacity-0');
        box.classList.add('scale-95', 'opacity-0');
        box.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            palette.classList.add('hidden');
            input.value = '';
        }, 180);
    }

    function navigate(url) {
        close();
        setTimeout(() => { window.location.href = url; }, 100);
    }

    // ── Event Listeners ──────────────────────────────────────────
    // Input
    input.addEventListener('input', () => {
        const q = input.value.trim();
        clearTimeout(debounce);
        if (q.length === 0) { doSearch(''); return; }
        if (q.length < 2)   { return; }
        showLoading();
        debounce = setTimeout(() => doSearch(q), 300);
    });

    // Keyboard shortcut
    document.addEventListener('keydown', (e) => {
        // Ctrl+K atau Cmd+K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            isOpen ? close() : open();
            return;
        }

        if (!isOpen) return;

        if (e.key === 'Escape') {
            e.preventDefault();
            close();
            return;
        }

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            setActive(Math.min(activeIdx + 1, allItems.length - 1));
            return;
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            setActive(Math.max(activeIdx - 1, 0));
            return;
        }
        if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIdx >= 0 && allItems[activeIdx]) {
                navigate(allItems[activeIdx].dataset.url);
            }
            return;
        }
    });

    // Backdrop click
    backdrop.addEventListener('click', close);

    // Trigger buttons (ditetapkan setelah DOM siap)
    function attachTrigger(id) {
        const btn = document.getElementById(id);
        if (btn) btn.addEventListener('click', open);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            attachTrigger('global-search-trigger');
            attachTrigger('global-search-trigger-mobile');
        });
    } else {
        attachTrigger('global-search-trigger');
        attachTrigger('global-search-trigger-mobile');
    }

})();
</script>
