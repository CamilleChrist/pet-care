<?php

namespace App\Http\Controllers;

use App\Models\WeightRecord;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $lastWeighing = WeightRecord::whereRelation('pet', 'user_id', $user->id)->latest('recorded_at')->first();

        $description = trans_choice('{0} Aucun animal enregistré|{1} :count animal|[2,*] :count animaux', $user->pets->count());

        if ($lastWeighing) {
            $description .= ' - Dernière pesée le '.$lastWeighing->recorded_at->isoFormat('LL');
        }

        return view('dashboard', compact('description'));
    }
}
