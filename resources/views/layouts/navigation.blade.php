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
                        {{ __('common.nav.dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.*')">
                        {{ __('common.nav.inventory') }}
                    </x-nav-link>

                    {{-- Dropdown "Layanan Lab" --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->routeIs('loans.*') || request()->routeIs('bookings.*') || request()->routeIs('calendar.index')) ? 'border-green-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ __('common.nav.lab_services') }}</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('loans.index')" :active="request()->routeIs('loans.*')">
                                    {{ __('common.nav.loans') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                                    {{ __('common.nav.bookings') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('calendar.index')" :active="request()->routeIs('calendar.index')">
                                    {{ __('common.nav.calendar') }}
                                </x-dropdown-link>
                                @cannot('is-admin')
                                    <x-dropdown-link :href="route('item-requests.create')" :active="request()->routeIs('item-requests.create')">
                                        {{ __('common.nav.item_requests') }}
                                    </x-dropdown-link>
                                @endcannot
                                @can('is-admin')
                                    <x-dropdown-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.index')">
                                        {{ __('common.nav.testimonials') }}
                                    </x-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Dropdown "Manajemen" (Hanya Admin) --}}
                    @can('is-admin')
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->routeIs('damage-reports.*') || request()->routeIs('users.*') || request()->routeIs('reports.*') || request()->routeIs('announcements.index')) ? 'border-green-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium ...">
                            <div>{{ __('common.nav.management') }}</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('damage-reports.index')" :active="request()->routeIs('damage-reports.*')">
                                    {{ __('common.nav.damage_reports') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.item-requests.index')" :active="request()->routeIs('admin.item-requests.*')">
                                    {{ __('common.nav.item_requests') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                    {{ __('common.nav.users') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                                    {{ __('common.nav.reports') }}
                                </x-dropdown-link>
                                {{-- TAMBAHKAN LINK BARU INI --}}
                                <x-dropdown-link :href="route('announcements.index')" :active="request()->routeIs('announcements.index')">
                                    {{ __('common.nav.announcements') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.contact-conversations.index')" :active="request()->routeIs('admin.contact-conversations.*')">
                                    {{ __('common.nav.messages') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('audit-logs.index')" :active="request()->routeIs('audit-logs.*')">
                                    {{ __('common.nav.audit_logs') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcan
                    
                    {{-- Dropdown "Sumber Daya" --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->routeIs('documents.*') || request()->routeIs('practicum-modules.*')) ? 'border-green-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ __('common.nav.resources') }}</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('documents.index')" :active="request()->routeIs('documents.*')">
                                    {{ __('common.nav.documents') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('practicum-modules.index')" :active="request()->routeIs('practicum-modules.*')">
                                    {{ __('common.nav.practicum_modules') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            {{-- PERUBAHAN DI SINI: Menggabungkan Dropdown Notifikasi dan Profile ke dalam satu div --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                


                {{-- Product Tour Button --}}
                <button 
                    id="navbar-tour-button"
                    class="p-2 text-gray-400 hover:text-green-600 transition-colors duration-200 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    aria-label="{{ __('common.messages.start_tour') }}"
                    title="{{ __('common.messages.start_tour') }}"
                >
                    <i class="fas fa-compass text-lg"></i>
                </button>

                {{-- Dropdown Notifikasi --}}
                <div class="ms-3 relative"> {{-- Menggunakan ms-3 untuk jarak antar dropdown --}}
                    <x-dropdown align="right" width="64">
                        <x-slot name="trigger">
                            <button
                                id="notification-bell"
                                data-unread="{{ $unreadCount ?? 0 }}"
                                class="relative inline-flex items-center p-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                            >
                                <i class="fas fa-bell"></i>
                                @if(($unreadCount ?? 0) > 0)
                                    <span data-role="notification-dot" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-600 ring-2 ring-white"></span>
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-4 border-b">
                                <span class="font-semibold text-sm text-gray-700">{{ __('common.nav.notifications') }}</span>
                            </div>
                            <div id="notification-list" class="max-h-64 overflow-y-auto">
                                @forelse($notifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b border-gray-100">
                                        <p class="font-medium text-gray-800">{{ $notification->data['message'] }}</p>
                                        <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                    </a>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                        {{ __('common.messages.no_notifications') }}
                                    </div>
                                @endforelse
                            </div>
                            @if($unreadCount > 5)
                                <a href="#" class="block text-center text-sm py-2 bg-gray-50 hover:bg-gray-100 rounded-b-lg text-smaba-dark-blue font-semibold">
                                    {{ __('common.messages.view_all_notifications') }}
                                </a>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- Language Switcher --}}
                <div class="ms-3 flex items-center bg-gray-100 rounded-lg p-0.5">
                    <a href="{{ route('lang.switch', 'id') }}" 
                       class="px-3 py-1 text-[10px] font-bold rounded-md transition-all duration-200 {{ app()->getLocale() == 'id' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                        ID
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" 
                       class="px-3 py-1 text-[10px] font-bold rounded-md transition-all duration-200 {{ app()->getLocale() == 'en' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                        EN
                    </a>
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
                                {{ __('common.nav.profile') }}
                            </x-dropdown-link>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('common.nav.logout') }}
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
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"> {{ __('common.nav.dashboard') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('items.index')" :active="request()->routeIs('items.*')"> {{ __('common.nav.inventory') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('loans.index')" :active="request()->routeIs('loans.*')"> {{ __('common.nav.loans') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')"> {{ __('common.nav.bookings') }} </x-responsive-nav-link>
            @cannot('is-admin')
                <x-responsive-nav-link :href="route('item-requests.create')" :active="request()->routeIs('item-requests.create')"> {{ __('common.nav.item_requests') }} </x-responsive-nav-link>
            @endcannot
            @can('is-admin')
                <x-responsive-nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.index')"> {{ __('common.nav.testimonials') }} </x-responsive-nav-link>
            @endcan
            @can('is-admin')
                <x-responsive-nav-link :href="route('admin.item-requests.index')" :active="request()->routeIs('admin.item-requests.*')"> {{ __('common.nav.item_requests') }} </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('damage-reports.index')" :active="request()->routeIs('damage-reports.*')"> {{ __('common.nav.damage_reports') }} </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')"> {{ __('common.nav.users') }} </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')"> {{ __('common.nav.reports') }} </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.contact-conversations.index')" :active="request()->routeIs('admin.contact-conversations.*')"> {{ __('common.nav.messages') }} </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('audit-logs.index')" :active="request()->routeIs('audit-logs.*')"> {{ __('common.nav.audit_logs') }} </x-responsive-nav-link>
            @endcan
            <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.index')"> {{ __('common.nav.calendar') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')"> {{ __('common.nav.documents') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('practicum-modules.index')" :active="request()->routeIs('practicum-modules.*')"> {{ __('common.nav.practicum_modules') }} </x-responsive-nav-link>
        </div>

        {{-- Opsi Responsive Pengguna --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            
            {{-- Mobile: Dark Mode, Language & Product Tour Buttons --}}
            <div class="px-4 mt-3 space-y-2">
                <div class="flex gap-2">
                    <button 
                        id="navbar-tour-button-mobile"
                        class="flex-1 p-3 text-gray-600 hover:text-green-600 bg-gray-100 hover:bg-green-50 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                        aria-label="{{ __('common.messages.start_tour') }}"
                    >
                        <i class="fas fa-compass"></i>
                        <span class="text-sm font-medium">{{ __('common.nav.product_tour') }}</span>
                    </button>
                </div>
                
            </div>
            
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('common.nav.profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('common.nav.logout') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
