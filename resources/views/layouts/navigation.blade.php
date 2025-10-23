<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                {{-- Link Navigasi Desktop --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.*')">
                        {{ __('Inventaris') }}
                    </x-nav-link>

                    {{-- Dropdown "Layanan Lab" --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->routeIs('loans.*') || request()->routeIs('bookings.*') || request()->routeIs('calendar.index')) ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>Layanan Lab</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('loans.index')" :active="request()->routeIs('loans.*')">
                                    {{ __('Peminjaman') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                                    {{ __('Booking Lab') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('calendar.index')" :active="request()->routeIs('calendar.index')">
                                    {{ __('Kalender') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Dropdown "Manajemen" (Hanya Admin) --}}
                    @can('is-admin')
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->routeIs('damage-reports.*') || request()->routeIs('users.*') || request()->routeIs('reports.*') || request()->routeIs('announcements.index')) ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium ...">
                            <div>Manajemen</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('damage-reports.index')" :active="request()->routeIs('damage-reports.*')">
                                    {{ __('Laporan Kerusakan') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                    {{ __('Manajemen User') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                                    {{ __('Laporan & Analitik') }}
                                </x-dropdown-link>
                                {{-- TAMBAHKAN LINK BARU INI --}}
                                <x-dropdown-link :href="route('announcements.index')" :active="request()->routeIs('announcements.index')">
                                    {{ __('Pengumuman Global') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcan
                    
                    {{-- Dropdown "Sumber Daya" --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->routeIs('documents.*') || request()->routeIs('practicum-modules.*')) ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>Sumber Daya</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('documents.index')" :active="request()->routeIs('documents.*')">
                                    {{ __('Pustaka Digital') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('practicum-modules.index')" :active="request()->routeIs('practicum-modules.*')">
                                    {{ __('Modul Praktikum') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            {{-- PERUBAHAN DI SINI: Menggabungkan Dropdown Notifikasi dan Profile ke dalam satu div --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                
                {{-- Dropdown Notifikasi --}}
                <div class="ms-3 relative"> {{-- Menggunakan ms-3 untuk jarak antar dropdown --}}
                    <x-dropdown align="right" width="64">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center p-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <i class="fas fa-bell"></i>
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-600 ring-2 ring-white"></span>
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-4 border-b">
                                <span class="font-semibold text-sm text-gray-700">Notifikasi</span>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse($notifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b border-gray-100">
                                        <p class="font-medium text-gray-800">{{ $notification->data['message'] }}</p>
                                        <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                    </a>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                        Tidak ada notifikasi baru.
                                    </div>
                                @endforelse
                            </div>
                            @if($unreadCount > 5)
                                <a href="#" class="block text-center text-sm py-2 bg-gray-50 hover:bg-gray-100 rounded-b-lg text-smaba-dark-blue font-semibold">
                                    Lihat Semua Notifikasi
                                </a>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- Dropdown Pengaturan Pengguna --}}
                <div class="ms-3 relative"> {{-- Menggunakan ms-3 untuk jarak antar dropdown --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                             <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-smaba-dark-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-smaba-light-blue transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            @can('is-admin')
                                <x-dropdown-link :href="route('users.index')">
                                    {{ __('Manajemen User') }}
                                </x-dropdown-link>
                            @endcan
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            {{-- Tombol Hamburger (Mobile) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Menu Navigasi Responsive (Mobile) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"> {{ __('Dashboard') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('items.index')" :active="request()->routeIs('items.*')"> {{ __('Inventaris') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('loans.index')" :active="request()->routeIs('loans.*')"> {{ __('Peminjaman') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')"> {{ __('Booking Lab') }} </x-responsive-nav-link>
            @can('is-admin')
                <x-responsive-nav-link :href="route('damage-reports.index')" :active="request()->routeIs('damage-reports.*')"> {{ __('Laporan Kerusakan') }} </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')"> {{ __('Manajemen User') }} </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')"> {{ __('Laporan & Analitik') }} </x-responsive-nav-link>
            @endcan
            <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.index')"> {{ __('Kalender') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')"> {{ __('Pustaka Digital') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('practicum-modules.index')" :active="request()->routeIs('practicum-modules.*')"> {{ __('Modul Praktikum') }} </x-responsive-nav-link>
        </div>

        {{-- Opsi Responsive Pengguna --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                @can('is-admin')
                    <x-responsive-nav-link :href="route('users.index')">
                        {{ __('Manajemen User') }}
                    </x-responsive-nav-link>
                @endcan
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>