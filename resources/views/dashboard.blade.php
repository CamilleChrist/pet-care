@extends('layouts.base')

@section('content')

    <h1>Dashboard</h1>

    <p>Bienvenue {{ auth()->user()->name }}</p>

    <nav>
        <ul>
            <li>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Se déconnecter</button>
                </form>
            </li>
        </ul>
    </nav>
@endsection
