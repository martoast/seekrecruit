@extends('layouts.auth')

@section('title', 'Register - Seek & Recruit Network')
@section('heading', 'Start your journey')
@section('subheading', 'Create an account and find your dream job')

@section('content')
    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
        @csrf

        <x-ui.input
            label="Full Name"
            name="name"
            type="text"
            placeholder="John Doe"
            required
        />

        <x-ui.input
            label="Email Address"
            name="email"
            type="email"
            placeholder="you@example.com"
            required
        />

        <x-ui.select
            label="Gender"
            name="gender"
            placeholder="Select your gender"
            required
            :options="[
                'male' => 'Male',
                'female' => 'Female',
                'other' => 'Other',
                'prefer_not_to_say' => 'Prefer not to say',
            ]"
        />

        <x-ui.input
            label="Password"
            name="password"
            type="password"
            placeholder="Create a strong password"
            required
        />

        <x-ui.input
            label="Confirm Password"
            name="password_confirmation"
            type="password"
            placeholder="Re-enter your password"
            required
        />

        <label class="flex items-start gap-2">
            <input type="checkbox" name="terms" required class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded mt-1" />
            <span class="text-sm text-gray-700">
                I agree to the
                <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Terms and Conditions</a>
                and
                <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Privacy Policy</a>
            </span>
        </label>

        <x-ui.button type="submit" variant="primary" class="w-full">
            Create Account
        </x-ui.button>

        <p class="text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-700">Sign in</a>
        </p>
    </form>
@endsection
