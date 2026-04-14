@extends('layouts.auth')

@section('title', 'Login - Seek & Recruit Network')
@section('heading', 'Welcome back')
@section('subheading', 'Sign in to your account to continue')

@section('content')
    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf

        <x-ui.input
            label="Email Address"
            name="email"
            type="email"
            placeholder="you@example.com"
            required
        />

        <x-ui.input
            label="Password"
            name="password"
            type="password"
            placeholder="Enter your password"
            required
        />

        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
                <span class="ml-2 block text-sm text-gray-700">Remember me</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                Forgot password?
            </a>
        </div>

        <x-ui.button type="submit" variant="primary" class="w-full">
            Sign In
        </x-ui.button>

        <p class="text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-700">Register now</a>
        </p>
    </form>
@endsection
