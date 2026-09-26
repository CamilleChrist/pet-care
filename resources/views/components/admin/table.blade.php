@props(['headers' => []])

<div class="admin-table__scroll">
    <table {{ $attributes->class(['admin-table']) }}>
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th scope="col" @if ($loop->last) class="admin-table__actions" @endif>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
