@extends('layouts.app-layout')

@section('title', 'Libro Cafe')

@section('content')

<div class="row align-items-center">
    <div class="col-6 ps-5">
        <h1 class="text-primary display-2 fw-bold">
            Morning Coffee,
        </h1>
        <h1 class="display-3">
            now made easy!
        </h1>
        <p class="lead fs-3">
            Order your favorite coffee from the comfort of your class.
        </p>
    </div>    
    <div class="col-6">
        <img src="{{ asset('images/coffee.png') }}" alt="Coffee" class="img-fluid">
    </div>
</div>

@endsection