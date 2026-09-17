<x-layouts.auth title="Mot de passe oublié" headline="Reprenez la main sur votre compte." description="Le lien reçu par e-mail est valable 60 minutes. Les fiches de vos animaux restent intactes.">

    <h1 class="text-xl font-bold mb-4">Mot de passe oublié</h1>

    <form method="post" action="{{ route('password.email') }}" class="flex flex-col gap-4">
        @csrf
        <label class="flex flex-col gap-1">
            Email :
            <input name="email" type="email" value="{{ old('email') }}" class="border p-2">
        </label>

        <button type="submit" class="bg-blue-600 text-white p-2 self-start">Envoyer le lien de réinitialisation</button>
    </form>

    @if ($errors->any())
        <ul class="mt-4 flex flex-col gap-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</x-layouts.auth>
