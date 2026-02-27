{{-- ================================================================
     TOPBAR — fixed, full-width, h-14
     ================================================================ --}}
<div class="fixed top-0 left-0 right-0 h-14 bg-white border-b border-gray-200 z-40 flex items-center px-4 gap-3">

    {{-- Mobile hamburger --}}
    <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors"
            aria-label="Toggle sidebar">
        <i class="fas fa-bars text-sm"></i>
    </button>

    {{-- Desktop: collapse toggle --}}
    <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="hidden lg:flex p-2 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors"
            aria-label="Collapse sidebar">
        <i class="fas fa-sidebar-flip text-sm" x-show="!sidebarCollapsed" style="display:none"></i>
        <i class="fas fa-bars text-sm" x-show="sidebarCollapsed"></i>
        <i class="fas fa-bars text-sm" x-show="!sidebarCollapsed"></i>
    </button>

    {{-- App name (visible when sidebar collapsed on desktop) --}}
    <span class="hidden font-bold text-green-700 text-sm" x-show="sidebarCollapsed" x-cloak>LAB SMABA</span>

    {{-- Spacer --}}
    <div class="flex-1"></div>

    {{-- RIGHT: actions --}}
    <div class="flex items-center gap-1">

        {{-- Global Search Trigger --}}
        <button id="global-search-trigger"
                class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm text-gray-400 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors"
                title="Pencarian Global (Ctrl+K)">
            <i class="fas fa-magnifying-glass text-xs"></i>
            <span class="hidden sm:inline text-xs text-gray-400">Cari...</span>
            <kbd class="hidden md:inline text-[10px] bg-white border border-gray-200 rounded px-1.5 py-0.5 font-mono text-gray-400">Ctrl+K</kbd>
        </button>

        {{-- Notifications --}}
        <div class="relative">
            <x-dropdown align="right" width="72">
                <x-slot name="trigger">
                    <button id="notification-bell"
                            data-unread="{{ $unreadCount ?? 0 }}"
                            class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-bell text-sm"></i>
                        @if(($unreadCount ?? 0) > 0)
                            <span data-role="notification-dot"
                                  class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        @endif
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <span class="font-semibold text-sm text-gray-800">{{ __('common.nav.notifications') }}</span>
                        @if(($unreadCount ?? 0) > 0)
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">{{ $unreadCount }} baru</span>
                        @endif
                    </div>
                    <div id="notification-list" class="max-h-64 overflow-y-auto">
                        @forelse($notifications as $notification)
                            <a href="{{ route('notifications.read', $notification->id) }}"
                               class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50 transition-colors">
                                <p class="font-medium text-gray-800 text-xs leading-relaxed">{{ $notification->data['message'] }}</p>
                                <span class="text-[11px] text-gray-400 mt-0.5 block">{{ $notification->created_at->diffForHumans() }}</span>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-gray-400">
                                <i class="fas fa-bell-slash text-2xl text-gray-200 block mb-2"></i>
                                {{ __('common.messages.no_notifications') }}
                            </div>
                        @endforelse
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

        {{-- Language Switcher --}}
        <div class="flex items-center bg-gray-100 rounded-lg p-0.5 ml-1">
            <a href="{{ route('lang.switch', 'id') }}"
               class="px-2.5 py-1 text-[10px] font-bold rounded-md transition-all {{ app()->getLocale() == 'id' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">ID</a>
            <a href="{{ route('lang.switch', 'en') }}"
               class="px-2.5 py-1 text-[10px] font-bold rounded-md transition-all {{ app()->getLocale() == 'en' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">EN</a>
        </div>

        {{-- User Dropdown --}}
        <div class="ml-1">
            <x-dropdown align="right" width="52">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-200">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-semibold text-gray-800 leading-tight">{{ Str::limit(Auth::user()->name, 16) }}</p>
                            <p class="text-[10px] text-gray-400 leading-tight capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400 hidden sm:block"></i>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-xs font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                    <x-dropdown-link :href="route('profile.edit')">
                        <i class="fas fa-circle-user w-4 mr-2 text-gray-400"></i>{{ __('common.nav.profile') }}
                    </x-dropdown-link>
                    <div class="border-t border-gray-100 mt-1 pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 hover:bg-red-50">
                                <i class="fas fa-right-from-bracket w-4 mr-2"></i>{{ __('common.nav.logout') }}
                            </x-dropdown-link>
                        </form>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

    </div>
</div>

{{-- ================================================================
     MOBILE BACKDROP
     ================================================================ --}}
<div x-show="sidebarOpen"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-30 lg:hidden"
     style="display:none"
     x-transition:enter="transition-opacity ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
</div>

{{-- ================================================================
     SIDEBAR — fixed left
     ================================================================ --}}
<aside id="main-sidebar"
       class="fixed top-0 left-0 h-screen bg-white border-r border-gray-200 z-30 flex flex-col
              transition-all duration-300 ease-in-out
              -translate-x-full lg:translate-x-0"
       :class="{
           'translate-x-0 shadow-xl lg:shadow-none': sidebarOpen,
           'lg:w-[260px]': !sidebarCollapsed,
           'lg:w-16': sidebarCollapsed
       }"
       style="width: 260px">

    {{-- ── Sidebar Header ── --}}
    <div class="h-14 flex items-center px-4 border-b border-gray-100 gap-3 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0">
            <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo" class="w-8 h-8 rounded-lg flex-shrink-0 object-contain">
            <span class="font-bold text-gray-900 text-sm truncate sidebar-label">LAB SMABA</span>
        </a>

        {{-- Mobile close button --}}
        <button @click="sidebarOpen = false" class="ml-auto p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 lg:hidden">
            <i class="fas fa-xmark text-sm"></i>
        </button>
    </div>

    {{-- ── Nav Menu ── --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 space-y-0.5" id="sidebar-nav">

        @php
            $isAdmin = Auth::user()->role === 'admin';
            $isGuru  = Auth::user()->role === 'guru';

            // Helper: active class
            $active = fn($routes) => request()->routeIs(is_array($routes) ? $routes : [$routes])
                ? 'bg-green-50 text-green-700 font-semibold'
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';
            $activeIcon = fn($routes) => request()->routeIs(is_array($routes) ? $routes : [$routes])
                ? 'text-green-600'
                : 'text-gray-400 group-hover:text-gray-600';
        @endphp

        {{-- === UTAMA === --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Utama</p>
            <a href="{{ route('dashboard') }}" class="sidebar-item group {{ $active('dashboard') }}">
                <i class="fas fa-gauge-high sidebar-icon {{ $activeIcon('dashboard') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.dashboard') }}</span>
                @if(request()->routeIs('dashboard'))
                    <span class="sidebar-active-dot"></span>
                @endif
            </a>
        </div>

        {{-- === INVENTARIS === --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Inventaris</p>
            <a href="{{ route('items.index') }}" class="sidebar-item group {{ $active('items.*') }}">
                <i class="fas fa-boxes-stacked sidebar-icon {{ $activeIcon('items.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.inventory') }}</span>
            </a>
            <a href="{{ route('calendar.index') }}" class="sidebar-item group {{ $active('calendar.index') }}">
                <i class="fas fa-calendar-days sidebar-icon {{ $activeIcon('calendar.index') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.calendar') }}</span>
            </a>
        </div>

        {{-- === LAYANAN LAB === --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Layanan Lab</p>
            <a href="{{ route('bookings.index') }}" class="sidebar-item group {{ $active('bookings.*') }}">
                <i class="fas fa-calendar-check sidebar-icon {{ $activeIcon('bookings.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.bookings') }}</span>
            </a>
            <a href="{{ route('loans.index') }}" class="sidebar-item group {{ $active('loans.*') }}">
                <i class="fas fa-hand-holding sidebar-icon {{ $activeIcon('loans.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.loans') }}</span>
            </a>
            @if($isGuru)
                <a href="{{ route('item-requests.create') }}" class="sidebar-item group {{ $active('item-requests.create') }}">
                    <i class="fas fa-paper-plane sidebar-icon {{ $activeIcon('item-requests.create') }}"></i>
                    <span class="sidebar-label">{{ __('common.nav.item_requests') }}</span>
                </a>
            @endif
            @if($isAdmin)
                <a href="{{ route('admin.testimonials.index') }}" class="sidebar-item group {{ $active('admin.testimonials.*') }}">
                    <i class="fas fa-star sidebar-icon {{ $activeIcon('admin.testimonials.*') }}"></i>
                    <span class="sidebar-label">{{ __('common.nav.testimonials') }}</span>
                </a>
            @endif
        </div>

        {{-- === SUMBER DAYA === --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Sumber Daya</p>
            <a href="{{ route('documents.index') }}" class="sidebar-item group {{ $active('documents.*') }}">
                <i class="fas fa-file-lines sidebar-icon {{ $activeIcon('documents.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.documents') }}</span>
            </a>
            <a href="{{ route('practicum-modules.index') }}" class="sidebar-item group {{ $active('practicum-modules.*') }}">
                <i class="fas fa-flask sidebar-icon {{ $activeIcon('practicum-modules.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.practicum_modules') }}</span>
            </a>
        </div>

        {{-- === MANAJEMEN (admin only) === --}}
        @if($isAdmin)
        <div class="sidebar-section">
            <p class="sidebar-section-label">Manajemen</p>
            <a href="{{ route('users.index') }}" class="sidebar-item group {{ $active('users.*') }}">
                <i class="fas fa-users-gear sidebar-icon {{ $activeIcon('users.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.users') }}</span>
            </a>
            <a href="{{ route('admin.item-requests.index') }}" class="sidebar-item group {{ $active('admin.item-requests.*') }}">
                <i class="fas fa-inbox sidebar-icon {{ $activeIcon('admin.item-requests.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.item_requests') }}</span>
            </a>
            <a href="{{ route('reports.index') }}" class="sidebar-item group {{ $active('reports.*') }}">
                <i class="fas fa-chart-bar sidebar-icon {{ $activeIcon('reports.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.reports') }}</span>
            </a>
            <a href="{{ route('damage-reports.index') }}" class="sidebar-item group {{ $active('damage-reports.*') }}">
                <i class="fas fa-triangle-exclamation sidebar-icon {{ $activeIcon('damage-reports.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.damage_reports') }}</span>
            </a>
            <a href="{{ route('announcements.index') }}" class="sidebar-item group {{ $active('announcements.index') }}">
                <i class="fas fa-bullhorn sidebar-icon {{ $activeIcon('announcements.index') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.announcements') }}</span>
            </a>
            <a href="{{ route('admin.contact-conversations.index') }}" class="sidebar-item group {{ $active('admin.contact-conversations.*') }}">
                <i class="fas fa-envelope sidebar-icon {{ $activeIcon('admin.contact-conversations.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.messages') }}</span>
            </a>
            <a href="{{ route('audit-logs.index') }}" class="sidebar-item group {{ $active('audit-logs.*') }}">
                <i class="fas fa-shield-halved sidebar-icon {{ $activeIcon('audit-logs.*') }}"></i>
                <span class="sidebar-label">{{ __('common.nav.audit_logs') }}</span>
            </a>
        </div>
        @endif

    </nav>

    {{-- ── Sidebar Footer ── --}}
    <div class="border-t border-gray-100 p-3 flex-shrink-0 space-y-0.5">
        {{-- Tour --}}
        <button id="navbar-tour-button"
                id="navbar-tour-button-mobile"
                class="sidebar-item group text-gray-600 hover:bg-gray-50 hover:text-gray-900 w-full text-left">
            <i class="fas fa-compass sidebar-icon text-gray-400 group-hover:text-gray-600"></i>
            <span class="sidebar-label">{{ __('common.nav.product_tour') }}</span>
        </button>

        {{-- User info --}}
        <div class="sidebar-item group text-gray-600 rounded-lg bg-gray-50 mt-2 cursor-default">
            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0 sidebar-label">
                <p class="text-xs font-semibold text-gray-800 truncate leading-tight">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-400 capitalize leading-tight">{{ Auth::user()->role }} · {{ Auth::user()->laboratorium ?? 'Semua Lab' }}</p>
            </div>
        </div>
    </div>

</aside>

{{-- ================================================================
     SIDEBAR CSS
     ================================================================ --}}
<style>
/* Section label */
.sidebar-section { margin-bottom: 4px; }
.sidebar-section-label {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #9ca3af;
    padding: 8px 10px 4px;
}

/* Nav item */
.sidebar-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 8px;
    font-size: 13.5px;
    transition: background 0.15s, color 0.15s;
    text-decoration: none;
    position: relative;
}

/* Icon */
.sidebar-icon {
    font-size: 14px;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
    transition: color 0.15s;
}

/* Active dot */
.sidebar-active-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #16a34a;
    margin-left: auto;
    flex-shrink: 0;
}

/* Collapsed sidebar: hide labels */
@media (min-width: 1024px) {
    body.sidebar-collapsed #main-sidebar {
        width: 64px !important;
    }
    body.sidebar-collapsed .sidebar-label,
    body.sidebar-collapsed .sidebar-section-label,
    body.sidebar-collapsed .sidebar-active-dot {
        display: none !important;
    }
    body.sidebar-collapsed .sidebar-item {
        justify-content: center;
        padding: 10px;
    }
    body.sidebar-collapsed .sidebar-icon {
        width: auto;
    }
    body.sidebar-collapsed #main-wrapper {
        padding-left: 64px;
    }
}

/* Scrollbar in sidebar */
#sidebar-nav::-webkit-scrollbar { width: 3px; }
#sidebar-nav::-webkit-scrollbar-track { background: transparent; }
#sidebar-nav::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
</style>

{{-- Sidebar collapse via class on body --}}
<script>
(function () {
    // Restore collapsed state from localStorage
    const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (collapsed) document.body.classList.add('sidebar-collapsed');

    // Watch Alpine sidebarCollapsed and sync to body class + localStorage
    document.addEventListener('alpine:init', () => {
        // Alpine data is on body, watch for changes
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Sync collapse toggle button with body class
        document.body.addEventListener('click', (e) => {
            const btn = e.target.closest('[\\@click*="sidebarCollapsed"]');
            if (btn) {
                const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                // Update Alpine state
                const wrapper = document.getElementById('main-wrapper');
                if (wrapper) {
                    wrapper.style.paddingLeft = isCollapsed ? '64px' : '260px';
                }
            }
        });
    });
})();
</script>

{{-- Global Search Palette --}}
<x-global-search />
