@extends('layouts.guest-layout')

@section('title', 'Register')

@section('content')

<x-card-shadow-center>
    <h1 class="text-center"><strong>Register</strong></h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <x-form-input label="name" name="name" id="InputName" />
        <x-form-input label="email" name="email" id="InputEmail" type="email" />
        <x-form-input label="password" name="password" id="InputPassword" type="password" />
        <x-form-input label="password_confirmation" name="password_confirmation" id="Password_confirmation" type="password" />
        <div class="mb-3">
            <p>already have an account? <a href="/auth/login">login</a></p>
        </div>
        <x-button-pill class="btn-primary py-2">Register</x-button-pill>
        </form>
</x-card-shadow-center>

@endsection