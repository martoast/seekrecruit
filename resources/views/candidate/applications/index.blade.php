@extends('layouts.candidate')

@section('title', 'My Applications - Seek & Recruit Network')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Applications</h1>
            <p class="text-gray-600">Track the status of your job applications</p>
        </div>

        @if ($applications->isEmpty())
            <x-ui.empty-state
                title="No applications yet"
                description="You haven't applied to any positions yet. Start exploring opportunities!"
            >
                <x-slot:action>
                    <a href="{{ route('positions.index') }}">
                        <x-ui.button variant="primary">Browse Positions</x-ui.button>
                    </a>
                </x-slot:action>
            </x-ui.empty-state>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($applications as $application)
                    @include('candidate.applications._card', ['application' => $application])
                @endforeach
            </div>
        @endif
    </div>
@endsection
