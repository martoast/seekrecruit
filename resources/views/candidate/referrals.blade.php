@extends('layouts.candidate')

@section('title', 'Referral Program - Seek & Recruit Network')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Referral Program</h1>
            <p class="text-gray-600">Refer friends and earn rewards when they get hired</p>
        </div>

        <x-ui.card padding="lg">
            <div class="space-y-6">
                <div class="bg-linear-to-br from-primary-50 to-blue-50 rounded-xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">How it Works</h3>
                            <ul class="text-sm text-gray-700 space-y-2">
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-primary-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span>Refer a friend using their email address</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-primary-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span>They register and complete their profile</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-primary-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span>When they get hired, you earn rewards!</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Send a Referral</h3>
                    <form method="POST" action="{{ route('candidate.referrals.store') }}" class="space-y-4">
                        @csrf
                        <x-ui.input
                            label="Friend's Email"
                            name="referred_email"
                            type="email"
                            placeholder="friend@example.com"
                            required
                        />
                        <x-ui.button type="submit" variant="primary" class="w-full">Send Referral</x-ui.button>
                    </form>
                </div>
            </div>
        </x-ui.card>

        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">My Referrals</h2>

            @if ($referrals->isEmpty())
                <x-ui.empty-state
                    title="No referrals yet"
                    description="Refer your friends and earn rewards when they get hired!"
                />
            @else
                <div class="space-y-3">
                    @foreach ($referrals as $referral)
                        <x-ui.card padding="md">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $referral->referred_email }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Sent {{ $referral->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <x-ui.status-badge :status="$referral->status" type="referral" size="sm" />
                                    @if ($referral->status === \App\Enums\ReferralStatus::PENDING)
                                        <form method="POST" action="{{ route('candidate.referrals.resend', $referral) }}">
                                            @csrf
                                            <x-ui.button type="submit" variant="secondary" size="sm">Resend</x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('candidate.referrals.destroy', $referral) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="danger" size="sm">Cancel</x-ui.button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </x-ui.card>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
