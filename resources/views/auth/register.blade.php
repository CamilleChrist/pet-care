<x-layouts.auth title="Créer un compte">

    <h1 class="text-xl font-bold mb-4">Créer un compte</h1>

    <form method="post" action="" class="flex flex-col gap-4">
        @csrf
        <label class="flex flex-col gap-1">
            Nom :
            <input name="name" type="text" class="border p-2">
        </label>

        <label class="flex flex-col gap-1">
            Email :
            <input name="email" type="email" class="border p-2">
        </label>

        <label class="flex flex-col gap-1">
            Mot de passe
            <input name="password" type="password" class="border p-2">
        </label>

        <label class="flex flex-col gap-1">
            Confirmer le mot de passe
            <input name="password_confirmation" type="password" class="border p-2">
        </label>

        <button type="submit" class="bg-blue-600 text-white p-2 self-start">Créer un compte</button>
    </form>

    @if ($errors->any())
        <ul class="mt-4 flex flex-col gap-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</x-layouts.auth>
