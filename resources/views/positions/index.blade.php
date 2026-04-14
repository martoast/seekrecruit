@extends('layouts.app')

@section('title', 'Open Positions - Seek & Recruit Network')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Open Positions</h1>
            <p class="text-gray-600">Discover exciting career opportunities</p>
        </div>

        @if ($positions->isEmpty())
            <x-ui.empty-state
                title="No positions available"
                description="There are currently no open positions. Please check back later."
            />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($positions as $position)
                    @include('positions._card', ['position' => $position])
                @endforeach
            </div>
        @endif
    </div>
@endsection
