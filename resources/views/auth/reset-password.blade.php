<x-layouts.auth title="Nouveau mot de passe" headline="Reprenez la main sur votre compte." description="Le lien reçu par e-mail est valable 60 minutes. Les fiches de vos animaux restent intactes.">

    <h1 class="text-xl font-bold mb-4">Réinitialiser le mot de passe</h1>

    <form method="post" action="{{ route('password.update') }}" class="flex flex-col gap-4">
        @csrf
        <input name="token" type="hidden" value="{{ $token }}">

        <label class="flex flex-col gap-1">
            Email :
            <input name="email" type="email" value="{{ old('email', $email) }}" class="border p-2">
        </label>

        <label class="flex flex-col gap-1">
            Mot de passe
            <input name="password" type="password" class="border p-2">
        </label>

        <label class="flex flex-col gap-1">
            Confirmer le mot de passe
            <input name="password_confirmation" type="password" class="border p-2">
        </label>

        <button type="submit" class="bg-blue-600 text-white p-2 self-start">Réinitialiser le mot de passe</button>
    </form>

    @if ($errors->any())
        <ul class="mt-4 flex flex-col gap-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</x-layouts.auth>
