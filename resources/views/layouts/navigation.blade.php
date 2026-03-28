{{-- ================================================================
     TOPBAR — h-14, dimulai setelah sidebar di desktop
     ================================================================ --}}
<div id="top-navbar"
     class="fixed top-0 right-0 h-14 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-[0_1px_2px_rgba(0,0,0,0.02)] z-50 flex items-center px-4 lg:px-6 gap-3 transition-all duration-300"
     :style="'left:' + (window.innerWidth >= 1024 ? (sidebarCollapsed ? '80px' : '260px') : '0') ">

    {{-- Mobile: hamburger --}}
    <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors flex-shrink-0 focus:ring-2 focus:ring-green-500/20 outline-none"
            aria-label="Toggle sidebar">
        <i class="fas fa-bars text-sm"></i>
    </button>

    {{-- Search Bar — centered, clickable box --}}
    <button id="global-search-trigger"
            class="flex-1 max-w-md mx-auto flex items-center gap-3 px-4 py-2 rounded-xl
                   bg-gray-50/80 hover:bg-white border text-left transition-all duration-200 group cursor-pointer
                   border-transparent hover:border-gray-200 hover:shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05),0_0_0_2px_rgba(34,197,94,0.1)] focus:outline-none focus:ring-2 focus:ring-green-500/30"
            title="Pencarian Global (Ctrl+K)">
        <i class="fas fa-magnifying-glass text-gray-400 text-sm flex-shrink-0 group-hover:text-green-600 transition-colors"></i>
        <span class="flex-1 text-sm text-gray-400 group-hover:text-gray-600 transition-colors">Cari alat, dokumen, pengguna...</span>
        <kbd class="hidden sm:inline-flex items-center gap-1 text-[10px] text-gray-400 bg-white border border-gray-200 rounded px-1.5 py-0.5 font-sans font-medium flex-shrink-0 shadow-sm">Ctrl K</kbd>
    </button>

    {{-- Right side icons group — dengan spacing dan dividers --}}
    <div class="flex items-center gap-1 sm:gap-2">

        {{-- Tour Panduan --}}
        <button id="navbar-tour-button"
                class="flex items-center gap-2 px-3 py-1.5 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all border border-transparent"
                title="Tour Panduan">
            <i class="fas fa-circle-question text-sm"></i>
            <span class="text-xs font-semibold hidden md:block">Bantuan</span>
        </button>

        {{-- Notifications --}}
        <div class="relative">
            <x-dropdown align="right" width="80">
                <x-slot name="trigger">
                    <button id="notification-bell"
                            data-unread="{{ $unreadCount ?? 0 }}"
                            class="relative p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500/20"
                            title="{{ __('common.nav.notifications') }}">
                        <i class="fas fa-bell"></i>
                        @if(($unreadCount ?? 0) > 0)
                            <span data-role="notification-dot"
                                  class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        @endif
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <span class="font-semibold text-sm text-gray-800">{{ __('common.nav.notifications') }}</span>
                        @if(($unreadCount ?? 0) > 0)
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-600 px-2 py-0.5 rounded-full">{{ $unreadCount }} baru</span>
                        @endif
                    </div>
                    <div id="notification-list" class="max-h-80 overflow-y-auto overscroll-contain">
                        @forelse($notifications as $notification)
                            <a href="{{ route('notifications.read', $notification->id) }}"
                               class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50 transition-colors relative group">
                                <p class="font-medium text-gray-800 text-sm leading-snug pr-4">{{ $notification->data['message'] }}</p>
                                <span class="text-[11px] font-medium text-gray-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                            </a>
                        @empty
                            <div class="px-6 py-12 text-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100">
                                    <i class="fas fa-bell-slash text-gray-300"></i>
                                </div>
                                <p class="font-medium text-gray-600 text-sm">{{ __('common.messages.no_notifications') }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ app()->getLocale() == 'id' ? 'Anda sudah membaca semuanya.' : 'You are all caught up.' }}
                                </p>
                            </div>
                        @endforelse
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

        {{-- Divider --}}
        <div class="w-px h-5 bg-gray-200 mx-1 hidden sm:block"></div>

        {{-- User Dropdown --}}
        <div>
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2.5 px-2 py-1 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500/20">
                        <div class="w-8 h-8 rounded-full bg-green-50 text-green-700 flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm border border-green-200">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-semibold text-gray-700 leading-none">{{ Str::limit(Auth::user()->name, 16) }}</p>
                            <p class="text-[11px] font-medium text-gray-500 mt-1 leading-none capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400 ml-1 hidden sm:block"></i>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <x-dropdown-link :href="route('profile.edit')" class="text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-green-600 group">
                            <i class="fas fa-circle-user w-5 text-gray-400 group-hover:text-green-600 transition-colors"></i>{{ __('common.nav.profile') }}
                        </x-dropdown-link>
                        
                        <div class="flex items-center justify-between px-4 py-2 hover:bg-gray-50 cursor-pointer group">
                            <div class="flex items-center text-sm font-medium text-gray-700">
                                <i class="fas fa-language w-5 text-gray-400 group-hover:text-gray-600 transition-colors"></i> Bahasa
                            </div>
                            <div class="flex items-center bg-gray-100 rounded-md p-0.5">
                                <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 text-[10px] font-bold rounded {{ app()->getLocale() == 'id' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">ID</a>
                                <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-[10px] font-bold rounded {{ app()->getLocale() == 'en' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">EN</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm font-medium text-red-600 hover:bg-red-50 group">
                                <i class="fas fa-arrow-right-from-bracket w-5 group-hover:translate-x-1 transition-transform"></i>{{ __('common.nav.logout') }}
                            </x-dropdown-link>
                        </form>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

    </div>{{-- /right side icons --}}
</div>

{{-- ================================================================
     MOBILE BACKDROP — z-20 (di bawah sidebar z-40)
     ================================================================ --}}
<div x-show="sidebarOpen"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-30 lg:hidden"
     style="display:none"
     x-transition:enter="transition-opacity ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
</div>

{{-- ================================================================
     SIDEBAR — fixed left
     ================================================================ --}}
<aside id="main-sidebar"
       class="-translate-x-full lg:translate-x-0 ease-in-out shadow-2xl lg:shadow-[2px_0_8px_-2px_rgba(0,0,0,0.05)]"
       :class="{
           'translate-x-0': sidebarOpen,
           'transition-all duration-300': isSidebarMounted
       }"
       :style="'position:fixed; top:0; left:0; height:100vh; width:' + (sidebarCollapsed && window.innerWidth >= 1024 ? '80px' : '260px') + '; background:#ffffff; border-right:1px solid #f1f5f9; z-index:40; display:flex; flex-direction:column;'">

    {{-- ── Sidebar Header ── --}}
    <div class="h-14 flex items-center flex-shrink-0 justify-center px-4" :class="sidebarCollapsed ? 'border-b border-gray-50' : ''">
        {{-- Logo + Brand --}}
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0 mx-auto mt-2" x-show="!sidebarCollapsed">
            <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo" class="w-8 h-8 rounded-lg flex-shrink-0 object-contain shadow-sm">
            <span class="font-bold text-gray-900 tracking-tight text-[15px] truncate sidebar-label text-center">SimLab<span class="text-green-600">SMABA</span></span>
        </a>
        {{-- Logo only (collapsed) --}}
        <a href="{{ route('dashboard') }}" x-show="sidebarCollapsed" class="flex-shrink-0 mx-auto mt-2">
            <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain shadow-sm drop-shadow-sm">
        </a>

        {{-- Mobile close button --}}
        <button @click="sidebarOpen = false" class="absolute right-3 top-3.5 p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors lg:hidden focus:outline-none focus:ring-2 focus:ring-gray-200">
            <i class="fas fa-xmark text-lg"></i>
        </button>
    </div>

    {{-- ── Nav Menu ── --}}
    <nav class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden pt-6 pb-4 px-3 space-y-1.5 overscroll-contain" id="sidebar-nav">

        @php
            $isAdmin = Auth::user()->role === 'admin';
            $isGuru  = Auth::user()->role === 'guru';

            // Helper: active class
            $active = fn($routes) => request()->routeIs(is_array($routes) ? $routes : [$routes]) ? 'active' : '';
        @endphp

        {{-- === UTAMA === --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Utama</p>
            <a href="{{ route('dashboard') }}" title="Dashboard" class="sidebar-item {{ $active('dashboard') }}">
                <i class="fas fa-house sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.dashboard') }}</span>
            </a>
        </div>

        {{-- === INVENTARIS === --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Inventaris</p>
            <a href="{{ route('items.index') }}" title="Inventaris" class="sidebar-item {{ $active('items.*') }}">
                <i class="fas fa-boxes-stacked sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.inventory') }}</span>
            </a>
            <a href="{{ route('calendar.index') }}" title="Kalender" class="sidebar-item {{ $active('calendar.index') }}">
                <i class="fas fa-calendar-days sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.calendar') }}</span>
            </a>
        </div>

        {{-- === LAYANAN LAB === --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Layanan Lab</p>
            <a href="{{ route('bookings.index') }}" title="Booking Lab" class="sidebar-item {{ $active('bookings.*') }}">
                <i class="fas fa-calendar-check sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.bookings') }}</span>
            </a>
            <a href="{{ route('loans.index') }}" title="Peminjaman" class="sidebar-item {{ $active('loans.*') }}">
                <i class="fas fa-hand-holding-hand sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.loans') }}</span>
            </a>
            @if($isGuru)
                <a href="{{ route('item-requests.create') }}" title="Permintaan Item" class="sidebar-item {{ $active('item-requests.create') }}">
                    <i class="fas fa-paper-plane sidebar-icon"></i>
                    <span class="sidebar-label">{{ __('common.nav.item_requests') }}</span>
                </a>
            @endif
            @if($isAdmin)
                <a href="{{ route('admin.testimonials.index') }}" title="Testimoni" class="sidebar-item {{ $active('admin.testimonials.*') }}">
                    <i class="fas fa-star sidebar-icon"></i>
                    <span class="sidebar-label">{{ __('common.nav.testimonials') }}</span>
                </a>
            @endif
        </div>

        {{-- === SUMBER DAYA === --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Sumber Daya</p>
            <a href="{{ route('documents.index') }}" title="Pustaka Digital" class="sidebar-item {{ $active('documents.*') }}">
                <i class="fas fa-file-lines sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.documents') }}</span>
            </a>
            <a href="{{ route('practicum-modules.index') }}" title="Modul Praktikum" class="sidebar-item {{ $active('practicum-modules.*') }}">
                <i class="fas fa-flask sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.practicum_modules') }}</span>
            </a>
        </div>

        {{-- === MANAJEMEN (admin only) === --}}
        @if($isAdmin)
        <div class="sidebar-section">
            <p class="sidebar-section-label">Manajemen</p>
            <a href="{{ route('admin.sop-laboratories.index') }}" title="Pengaturan SOP" class="sidebar-item {{ $active('admin.sop-laboratories.*') }}">
                <i class="fas fa-file-signature sidebar-icon"></i>
                <span class="sidebar-label">Pengaturan SOP</span>
            </a>
            <a href="{{ route('users.index') }}" title="Manajemen User" class="sidebar-item {{ $active('users.*') }}">
                <i class="fas fa-users-gear sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.users') }}</span>
            </a>
            <a href="{{ route('admin.item-requests.index') }}" title="Permintaan Item" class="sidebar-item {{ $active('admin.item-requests.*') }}">
                <i class="fas fa-inbox sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.item_requests') }}</span>
            </a>
            <a href="{{ route('reports.index') }}" title="Laporan" class="sidebar-item {{ $active('reports.*') }}">
                <i class="fas fa-chart-bar sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.reports') }}</span>
            </a>
            <a href="{{ route('damage-reports.index') }}" title="Laporan Kerusakan" class="sidebar-item {{ $active('damage-reports.*') }}">
                <i class="fas fa-triangle-exclamation sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.damage_reports') }}</span>
            </a>
            <a href="{{ route('announcements.index') }}" title="Pengumuman" class="sidebar-item {{ $active('announcements.index') }}">
                <i class="fas fa-bullhorn sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.announcements') }}</span>
            </a>
            <a href="{{ route('admin.contact-conversations.index') }}" title="Pesan Pesan" class="sidebar-item {{ $active('admin.contact-conversations.*') }}">
                <i class="fas fa-envelope sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.messages') }}</span>
            </a>
            <a href="{{ route('audit-logs.index') }}" title="Audit Log" class="sidebar-item {{ $active('audit-logs.*') }}">
                <i class="fas fa-shield-halved sidebar-icon"></i>
                <span class="sidebar-label">{{ __('common.nav.audit_logs') }}</span>
            </a>
        </div>
        @endif

    </nav>

    {{-- ── Sidebar Footer ── --}}
    <div class="p-4 flex-shrink-0 space-y-3 relative" :class="sidebarCollapsed ? 'px-2' : ''">
        {{-- Modernized User Card --}}
        <div class="sidebar-user-card group" x-show="!sidebarCollapsed" x-transition>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-700 flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm border border-green-200">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900 truncate tracking-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] font-medium text-gray-500 capitalize truncate mt-0.5">{{ Auth::user()->role }} &bull; {{ Auth::user()->laboratorium ?? 'Pusat' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-md text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors tooltip-trigger" title="Logout">
                        <i class="fas fa-power-off text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Toggle collapse button (desktop only) --}}
        <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed); document.body.classList.toggle('sidebar-collapsed', sidebarCollapsed)"
                class="hidden lg:flex w-full items-center py-2.5 px-3 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100/80 transition-all border border-transparent shadow-sm hover:shadow-none hover:border-gray-200"
                :class="sidebarCollapsed ? 'justify-center mx-auto max-w-[48px]' : 'justify-start gap-3'"
                title="Toggle Sidebar">
            <i class="fas text-[13px]" :class="sidebarCollapsed ? 'fa-chevron-right' : 'fa-chalkboard'"></i>
            <span class="text-xs font-semibold sidebar-label" x-show="!sidebarCollapsed">Tutup Sidebar</span>
        </button>
    </div>

</aside>

{{-- ================================================================
     SIDEBAR CSS - MODERN SAAS
     ================================================================ --}}
<style>
/* Section label */
.sidebar-section { 
    margin-bottom: 24px; 
}
.sidebar-section-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: #94a3b8; /* slate-400 */
    padding: 0 16px 8px;
    transition: opacity 0.2s;
}

/* Nav item */
.sidebar-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 9px 12px;
    margin: 2px 8px; 
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 500;
    color: #64748b; /* slate-500 */
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    position: relative;
    border: 1px solid transparent;
}
.sidebar-item:hover {
    background-color: #f8fafc; /* slate-50 */
    color: #0f172a; /* slate-900 */
}

/* Active State */
.sidebar-item.active {
    background-color: transparent !important;
    color: #0f172a !important; /* slate-900 */
    font-weight: 600;
}

/* Indicator line */
.sidebar-item.active::before {
    content: '';
    position: absolute;
    left: -8px; /* Offset margin */
    top: 50%;
    transform: translateY(-50%);
    height: 70%;
    width: 3px;
    background-color: #16a34a; /* green-600 */
    border-radius: 0 4px 4px 0;
}

/* Icon */
.sidebar-icon {
    font-size: 16px;
    width: 22px;
    text-align: center;
    flex-shrink: 0;
    transition: color 0.2s, transform 0.2s;
    color: #94a3b8; /* slate-400 */
}
.sidebar-item:hover .sidebar-icon {
    color: #475569; /* slate-600 */
}
.sidebar-item.active .sidebar-icon {
    color: #16a34a; /* green-600 */
}

/* User Card Bottom Section */
.sidebar-user-card {
    background-color: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    transition: all 0.2s ease;
}
.sidebar-user-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    border-color: #e2e8f0;
}

/* Collapsed sidebar: hide labels */
@media (min-width: 1024px) {
    body.sidebar-collapsed .sidebar-label,
    body.sidebar-collapsed .sidebar-section-label {
        display: none !important;
    }
    body.sidebar-collapsed .sidebar-item {
        justify-content: center;
        padding: 12px;
        margin: 4px;
        gap: 0;
    }
    /* Hide active left border indicator when collapsed */
    body.sidebar-collapsed .sidebar-item.active::before {
        display: none;
    }
    /* Slightly differently colored full active state when collapsed */
    body.sidebar-collapsed .sidebar-item.active {
        background-color: #f8fafc !important; /* light gray instead of green */
        border-color: transparent;
    }
    body.sidebar-collapsed .sidebar-icon {
        width: auto;
        font-size: 18px;
    }
    body.sidebar-collapsed .sidebar-section {
        margin-bottom: 12px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0; /* slate-200 */
    }
    body.sidebar-collapsed .sidebar-section:first-child {
        border-top: none;
        padding-top: 0;
    }
    
    /* Tooltip on hover when collapsed */
    body.sidebar-collapsed .sidebar-item {
        position: relative;
    }
    body.sidebar-collapsed .sidebar-item::after {
        content: attr(title);
        position: absolute;
        left: calc(100% + 14px);
        top: 50%;
        transform: translateY(-50%) translateX(10px);
        background: #1e293b;
        color: white;
        font-size: 12px;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 6px;
        white-space: nowrap;
        z-index: 999;
        pointer-events: none;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
    body.sidebar-collapsed .sidebar-item::before {
        content: '';
        display: block !important; /* overriding line 506 */
        position: absolute;
        left: calc(100% + 8px);
        top: 50%;
        transform: translateY(-50%);
        height: 0; width: 0;
        border-width: 6px;
        border-style: solid;
        border-color: transparent #1e293b transparent transparent;
        background-color: transparent; /* Reset background from active indicator */
        border-radius: 0; /* Reset border radius */
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
    }
    body.sidebar-collapsed .sidebar-item:hover::after,
    body.sidebar-collapsed .sidebar-item:hover::before {
        opacity: 1;
        visibility: visible;
        transform: translateY(-50%) translateX(0);
    }
}

/* Scrollbar in sidebar */
#sidebar-nav::-webkit-scrollbar { width: 5px; }
#sidebar-nav::-webkit-scrollbar-track { background: transparent; }
#sidebar-nav::-webkit-scrollbar-thumb { background: transparent; border-radius: 10px; transition: background 0.3s; }
#sidebar-nav:hover::-webkit-scrollbar-thumb { background: #cbd5e1; } /* slate-300 */
</style>

{{-- Sidebar collapse: restore state from localStorage --}}
<script>
(function () {
    var collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (collapsed) document.body.classList.add('sidebar-collapsed');
})();
</script>

{{-- Global Search Palette --}}
<x-global-search />
