@extends('layouts.auth')

@section('title', 'Reset Password - Seek & Recruit Network')
@section('heading', 'Reset password')
@section('subheading', 'Enter your new password below')

@section('content')
    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}" />

        <x-ui.input
            label="Email Address"
            name="email"
            type="email"
            :value="$email"
            placeholder="you@example.com"
            required
        />

        <x-ui.input
            label="New Password"
            name="password"
            type="password"
            placeholder="Enter new password"
            required
        />

        <x-ui.input
            label="Confirm Password"
            name="password_confirmation"
            type="password"
            placeholder="Re-enter new password"
            required
        />

        <x-ui.button type="submit" variant="primary" class="w-full">
            Reset Password
        </x-ui.button>

        <p class="text-center text-sm text-gray-600">
            Remember your password?
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-700">Sign in</a>
        </p>
    </form>
@endsection
