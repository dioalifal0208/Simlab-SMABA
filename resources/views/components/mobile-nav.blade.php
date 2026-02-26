{{-- Mobile Bottom Navigation --}}
{{-- Add to app.blade.php layout --}}

@auth
<nav class="mobile-nav md:hidden">
    <div class="mobile-nav-items">
        <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home mobile-nav-icon"></i>
            <span class="mobile-nav-label">Home</span>
        </a>
        
        <a href="{{ route('items.index') }}" class="mobile-nav-item {{ request()->routeIs('items.*') ? 'active' : '' }}">
            <i class="fas fa-boxes mobile-nav-icon"></i>
            <span class="mobile-nav-label">Items</span>
        </a>
        
        <a href="{{ route('loans.index') }}" class="mobile-nav-item {{ request()->routeIs('loans.*') ? 'active' : '' }}">
            <i class="fas fa-hand-holding mobile-nav-icon"></i>
            <span class="mobile-nav-label">Loans</span>
            @if(isset($pendingLoansCount) && $pendingLoansCount > 0)
                <span class="mobile-nav-badge">{{ min($pendingLoansCount, 99) }}</span>
            @endif
        </a>
        
        <a href="{{ route('calendar.index') }}" class="mobile-nav-item {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
            <i class="fas fa-calendar mobile-nav-icon"></i>
            <span class="mobile-nav-label">Jadwal</span>
        </a>
        
        <a href="{{ route('profile.edit') }}" class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fas fa-user mobile-nav-icon"></i>
            <span class="mobile-nav-label">Profil</span>
        </a>
    </div>
</nav>

{{-- Floating Action Button (FAB) --}}
<div x-data="{ showMenu: false }" class="md:hidden">
    <button @click="showMenu = !showMenu" class="fab">
        <i class="fas" :class="showMenu ? 'fa-times' : 'fa-plus'"></i>
    </button>
    
    <div x-show="showMenu" x-transition class="fab-menu" style="display: none;">
        @can('is-admin')
            <a href="{{ route('items.create') }}" class="fab-menu-item">
                <i class="fas fa-box"></i>
                <span>Tambah Item</span>
            </a>
        @endcan
        
        <a href="{{ route('loans.create') }}" class="fab-menu-item">
            <i class="fas fa-hand-holding"></i>
            <span>Ajukan Peminjaman</span>
        </a>
        
        <a href="{{ route('bookings.index', ['create' => 'true']) }}" class="fab-menu-item">
            <i class="fas fa-calendar-plus"></i>
            <span>Booking Lab</span>
        </a>
    </div>
</div>
@endauth
