@props([
    'title' => '',
    'headline' => 'Vaccins, pesées et visites au même endroit.',
    'description' => "La prochaine échéance s'affiche dès l'ouverture du tableau de bord.",
])

<x-layouts.base :title="$title">

    <div class="auth">
        <aside class="auth__brand">
            <div class="auth__brand-header">
                <a class="logo logo--on-brand" href="{{ route('welcome') }}"><span>P</span> PetCare</a>
                <p class="auth__tagline">Carnet de santé chien &amp; chat</p>
            </div>
            <h2 class="auth__headline">{{ $headline }}</h2>
            <p class="auth__description">{{ $description }}</p>
        </aside>

        <main class="auth__panel">
            <div class="auth__card">
                @if (session('status'))
                    <p class="auth__status">{{ session('status') }}</p>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

</x-layouts.base>
