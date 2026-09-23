<div class="vaccine-detail">
    @if(isset($separator) && $separator)
        <ul class="vaccine-detail-top">
            <li>
                <span>Fait le : </span>
                <span>{{ $record->administered_at->isoFormat('LL') }}</span>
            </li>
            <li>
                <span>Rappel le : </span>
                <span>{{ $record->next_due_at->isoFormat('LL') }}</span>
            </li>
        </ul>
    @endif

    <ul class="vaccine-detail-bottom">
        @if(isset($separator) && !$separator)
            <li>
                <span>Fait le : </span>
                <span>{{ $record->administered_at->isoFormat('LL') }}</span>
            </li>
            <li>
                <span>Rappel le : </span>
                <span>{{ $record->next_due_at->isoFormat('LL') }}</span>
            </li>
        @endif
        <li>
            <span>Vétérinaire : </span>
            <span>{{ $record->veterinarian_name }}</span>
        </li>
        <li>
            <span>Clinique : </span>
            <span>{{ $record->clinic_name }}</span>
        </li>
        <li>
            <span>N° de lot : </span>
            <span>{{ $record->lot_number }}</span>
        </li>
        <li>
            <span>Notes : </span>
            <span>{{ $record->notes }}</span>
        </li>
    </ul>
</div>
