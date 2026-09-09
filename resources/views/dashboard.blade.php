@extends('layouts.base')

@section('content')

    <h1 class="text-xl font-bold mb-4">Dashboard</h1>

    <p>Bienvenue {{ auth()->user()->name }}</p>

@endsection
