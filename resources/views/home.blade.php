@extends('layouts.app')

@section('title', 'Seek & Recruit Network — Where talent meets opportunity')

@section('content')
    <div class="overflow-hidden">

        {{-- Hero --}}
        <section class="bg-dark-500 py-24 px-4">
            <div class="max-w-4xl mx-auto text-center space-y-8">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                    Where Talent Meets<br>
                    <span class="text-primary-400">Opportunity</span>
                </h1>
                <p class="text-xl text-dark-100 max-w-2xl mx-auto leading-relaxed">
                    Seek & Recruit connects job seekers with companies hiring in Baja California.
                    Build your profile, apply to curated positions, learn new skills, and get referred by people in your network.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                    <a href="{{ route('register') }}">
                        <x-ui.button variant="primary" size="lg">Create Your Profile</x-ui.button>
                    </a>
                    <a href="{{ route('positions.index') }}">
                        <x-ui.button variant="secondary" size="lg">Browse Open Positions</x-ui.button>
                    </a>
                </div>
            </div>
        </section>

        {{-- How it works --}}
        <section class="bg-white py-24 px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-dark-500">How It Works</h2>
                    <p class="text-gray-500 mt-3 text-lg">Get hired in four simple steps</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ([
                        ['step' => '1', 'title' => 'Create Your Profile', 'desc' => 'Sign up and fill out your candidate profile — experience, skills, education, and a professional photo.'],
                        ['step' => '2', 'title' => 'Apply to Positions', 'desc' => 'Browse open positions and apply directly. Companies review your profile and reach out if there\'s a fit.'],
                        ['step' => '3', 'title' => 'Learn & Earn Badges', 'desc' => 'Complete free lessons on software, sales, and personal development. Badges show up on your profile.'],
                        ['step' => '4', 'title' => 'Get Referred', 'desc' => 'People in your network can refer you directly to companies, giving your application a boost.'],
                    ] as $item)
                        <div class="text-center space-y-4">
                            <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-700 font-bold text-lg flex items-center justify-center mx-auto">
                                {{ $item['step'] }}
                            </div>
                            <h3 class="font-semibold text-dark-500 text-lg">{{ $item['title'] }}</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section class="bg-primary-50 py-24 px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-dark-500">Everything You Need</h2>
                    <p class="text-gray-500 mt-3 text-lg">One platform for candidates and companies</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ([
                        [
                            'title' => 'Candidate Profiles',
                            'desc' => 'Showcase your skills, upload your CV, add a profile photo, and let companies find you.',
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        ],
                        [
                            'title' => 'Curated Job Positions',
                            'desc' => 'Companies post open roles with salary, location, and modality. You apply with one click.',
                            'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                        ],
                        [
                            'title' => 'Lessons & Badges',
                            'desc' => 'Free video lessons on software, sales, and career development. Complete them and earn badges on your profile.',
                            'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                        ],
                        [
                            'title' => 'Referral Program',
                            'desc' => 'Refer friends to the platform. When they get hired, everyone wins.',
                            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                        ],
                        [
                            'title' => 'Interview Scheduling',
                            'desc' => 'HR admins schedule and track interviews directly in the platform, keeping everything in one place.',
                            'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        ],
                        [
                            'title' => 'Application Tracking',
                            'desc' => 'Candidates see the status of every application in real time. No more wondering where things stand.',
                            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                        ],
                    ] as $feature)
                        <div class="bg-white rounded-2xl p-6 space-y-4 shadow-sm border border-primary-100">
                            <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-dark-500">{{ $feature['title'] }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="bg-dark-500 py-20 px-4">
            <div class="max-w-3xl mx-auto text-center space-y-6">
                <h2 class="text-3xl font-bold text-white">Ready to get started?</h2>
                <p class="text-dark-100 text-lg">Create your free profile and start applying to open positions today.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}">
                        <x-ui.button variant="primary" size="lg">Create Free Profile</x-ui.button>
                    </a>
                    <a href="{{ route('lessons.index') }}">
                        <x-ui.button variant="secondary" size="lg">Explore Lessons</x-ui.button>
                    </a>
                </div>
            </div>
        </section>

    </div>
@endsection
