@php
    $user = auth()->user();
    $initials = $user
        ? collect(explode(' ', $user->name))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->implode('')
        : '?';
@endphp
<nav class="bg-white border-b border-gray-200 sticky top-0 z-40 backdrop-blur-sm bg-white/95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Seek & Recruit" class="w-10 h-10 object-contain">
                    <span class="text-xl font-bold text-gray-900 hidden sm:block">Seek & Recruit</span>
                </a>

                @auth
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        @if ($user->isCandidate())
                            <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('candidate.dashboard') ? 'text-gray-900' : 'text-gray-600' }} hover:text-primary-600 transition-colors">Dashboard</a>
                            <a href="{{ route('candidate.profile.edit') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('candidate.profile.*') ? 'text-gray-900' : 'text-gray-600' }} hover:text-primary-600 transition-colors">Profile</a>
                            <a href="{{ route('candidate.applications.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('candidate.applications.*') ? 'text-gray-900' : 'text-gray-600' }} hover:text-primary-600 transition-colors">Applications</a>
                            <a href="{{ route('positions.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('positions.*') ? 'text-gray-900' : 'text-gray-600' }} hover:text-primary-600 transition-colors">Positions</a>
                            <a href="{{ route('lessons.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('lessons.*') ? 'text-gray-900' : 'text-gray-600' }} hover:text-primary-600 transition-colors">Lessons</a>
                            <a href="{{ route('candidate.referrals.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('candidate.referrals.*') ? 'text-gray-900' : 'text-gray-600' }} hover:text-primary-600 transition-colors">Referrals</a>
                        @elseif ($user->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 hover:text-primary-600 transition-colors">Dashboard</a>
                        @endif
                    </div>
                @else
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('positions.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-600 hover:text-primary-600 transition-colors">Positions</a>
                        <a href="{{ route('lessons.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-600 hover:text-primary-600 transition-colors">Lessons</a>
                    </div>
                @endauth
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                        @csrf
                        <div class="flex items-center space-x-2 px-3 py-2 rounded-xl bg-gray-50">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-primary-700 font-semibold text-sm">{{ $initials }}</span>
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-gray-700">{{ $user->name }}</span>
                        </div>
                        <button type="submit" class="ml-2 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Logout">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}">
                        <x-ui.button variant="ghost">Login</x-ui.button>
                    </a>
                    <a href="{{ route('register') }}">
                        <x-ui.button variant="primary">Register</x-ui.button>
                    </a>
                @endauth

                <button type="button" data-mobile-menu-toggle class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
