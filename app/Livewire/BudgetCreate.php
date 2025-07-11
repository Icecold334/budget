<?php

namespace App\Livewire;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BudgetCreate extends Component
{
    public $start_date;
    public $end_date;
    public $income_amount;
    public $target_savings;

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'income_amount' => 'required|numeric|min:1',
        'target_savings' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->start_date = now()->toDateString();
        $this->end_date = now()->addDays(30)->toDateString();
    }

    public function save()
    {
        $this->income_amount = preg_replace('/[^\d]/', '', $this->income_amount);
        $this->target_savings = preg_replace('/[^\d]/', '', $this->target_savings);
        $this->validate();

        $hasActive = Budget::where('user_id', Auth::id())
            ->where('status', 'active')
            ->exists();

        if ($hasActive) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Masih ada budget aktif. Batalkan terlebih dahulu.'
            ]);
            return;
        }

        Budget::create([
            'user_id' => Auth::id(),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'income_amount' => $this->income_amount,
            'target_savings' => $this->target_savings,
        ]);

        $this->reset();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Budget berhasil dibuat!'
        ]);

        return redirect()->route('budget.index');
    }

    public function render()
    {
        return view('livewire.budget-create');
    }
}
