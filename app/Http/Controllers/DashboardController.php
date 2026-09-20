<?php

namespace App\Http\Controllers;

use App\Models\VaccinationRecord;
use App\Models\WeightRecord;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $pets = $user->pets()->with(['breed', 'weightRecords'])->get();
        $lastWeighing = WeightRecord::whereRelation('pet', 'user_id', $user->id)->latest('recorded_at')->first();

        $description = trans_choice('{0} Aucun animal enregistré|{1} :count animal|[2,*] :count animaux', $pets->count());

        if ($lastWeighing) {
            $description .= ' · Dernière pesée le '.$lastWeighing->recorded_at->isoFormat('LL');
        }

        // Prochains rappels : la dernière injection de chaque vaccin, par animal, triée par échéance.
        $reminders = VaccinationRecord::with(['pet', 'vaccine'])
            ->whereRelation('pet', 'user_id', $user->id)
            ->latest('administered_at')
            ->get()
            ->unique(fn (VaccinationRecord $record) => $record->pet_id.'|'.$record->display_name)
            ->filter(fn (VaccinationRecord $record) => $record->next_due_at)
            ->sortBy('next_due_at')
            ->values();

        $overdue = $reminders->first(fn (VaccinationRecord $record) => $record->next_due_at->lt(today()));

        return view('dashboard', [
            'title' => 'Bonjour '.$user->name,
            'description' => $description,
            'pets' => $pets,
            'reminders' => $reminders,
            'overdue' => $overdue,
        ]);
    }
}
