@extends('layouts.base')

@section('content')

    <h1>Dashboard</h1>

    <p>Bienvenue {{ auth()->user()->name }}</p>

@endsection
