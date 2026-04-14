@php
    $user = auth()->user();
@endphp
<div data-mobile-menu class="fixed inset-0 z-50 md:hidden hidden">
    <div data-mobile-menu-close class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="fixed inset-y-0 left-0 w-full max-w-sm bg-white shadow-xl">
        <div class="flex flex-col h-full">
            <div class="px-4 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-linear-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-xl">S&R</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Seek & Recruit</span>
                    </div>
                    <button type="button" data-mobile-menu-close class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
                @auth
                    @if ($user->isCandidate())
                        <a href="{{ route('candidate.dashboard') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-900 rounded-xl hover:bg-gray-50">Dashboard</a>
                        <a href="{{ route('candidate.profile.edit') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Profile</a>
                        <a href="{{ route('candidate.applications.index') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Applications</a>
                        <a href="{{ route('positions.index') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Positions</a>
                        <a href="{{ route('candidate.referrals.index') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Referrals</a>
                    @elseif ($user->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-900 rounded-xl hover:bg-gray-50">Dashboard</a>
                        <a href="{{ route('admin.candidates.index') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Candidates</a>
                        <a href="{{ route('admin.applications.index') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Applications</a>
                        <a href="{{ route('admin.interviews.index') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Interviews</a>
                        <a href="{{ route('admin.positions.index') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Positions</a>
                    @endif
                @else
                    <a href="{{ route('positions.index') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Positions</a>
                @endauth
            </nav>

            <div class="border-t border-gray-200 px-4 py-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-ui.button type="submit" variant="ghost" class="w-full">Logout</x-ui.button>
                    </form>
                @else
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" class="block"><x-ui.button variant="ghost" class="w-full">Login</x-ui.button></a>
                        <a href="{{ route('register') }}" class="block"><x-ui.button variant="primary" class="w-full">Register</x-ui.button></a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
