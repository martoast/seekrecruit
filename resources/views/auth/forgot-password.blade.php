@extends('layouts.auth')

@section('title', 'Forgot Password - Seek & Recruit Network')
@section('heading', 'Forgot password?')
@section('subheading', "We'll send you a link to reset it")

@section('content')
    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        @if (session('success'))
            <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
        @endif

        <x-ui.input
            label="Email Address"
            name="email"
            type="email"
            placeholder="you@example.com"
            required
        />

        <x-ui.button type="submit" variant="primary" class="w-full">
            Send Reset Link
        </x-ui.button>

        <p class="text-center text-sm text-gray-600">
            Remember your password?
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-700">Sign in</a>
        </p>
    </form>
@endsection
