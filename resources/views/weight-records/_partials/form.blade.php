<form method="post" action="{{ $action }}" class="flex flex-col gap-4">
    @csrf
    @method($method)

    <input type="hidden" name="pet_id" value="{{ $pet->id }}">
    <label class="flex flex-col gap-1">
        Poids :*
        <input
            name="weight"
            type="text"
            required
            value="{{ old('weight', $weightRecord?->weight) }}" class="border p-2">
    </label>

    <label class="flex flex-col gap-1">
        Date :*
        <input
            name="recorded_at"
            type="datetime-local"
            required
            max="{{ now()->format('Y-m-d\TH:i') }}"
            value="{{ old('recorded_at', $weightRecord?->recorded_at?->format('Y-m-d\TH:i')) }}"
            class="border p-2"
        >
    </label>

    <button type="submit" class="bg-blue-600 text-white p-2 self-start">
        {{ $submitLabel }}
    </button>
</form>

@if ($errors->any())
    <ul class="mt-4 flex flex-col gap-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
