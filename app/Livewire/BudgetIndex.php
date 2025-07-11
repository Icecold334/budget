<?php

namespace App\Livewire;

use App\Models\Budget;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BudgetIndex extends Component
{
    public function cancelBudget($id)
    {
        $budget = Budget::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        $budget->status = 'cancelled';
        $budget->save();

        $this->dispatch('budget-cancelled');
        $this->reset(); // Refresh data
    }
    public function render()
    {
        $user = Auth::user();

        $activeBudget = Budget::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest('start_date')
            ->first();


        $pastBudgets = Budget::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest('start_date')
            ->get();


        return view('livewire.budget-index', compact('activeBudget', 'pastBudgets'));
    }
}
