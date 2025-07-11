<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Carbon;

class Dashboard extends Component
{
    public $activeBudget;
    public $totalSpentToday = 0;
    public $totalSpentThisMonth = 0;
    public $totalTransactionsToday = 0;
    public $totalTransactionsThisMonth = 0;
    public $budgetLeft = 0;
    public $targetSavings = 0;
    public $limitToday = 0;
    public $chartData = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $userId = Auth::id();
        $today = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();

        $this->activeBudget = Budget::where('user_id', $userId)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if ($this->activeBudget) {
            $transactions = Transaction::where('budget_id', $this->activeBudget->id)->get();

            $this->totalSpentToday = $transactions->where('date', $today)->sum('amount');
            $this->totalTransactionsToday = $transactions->where('date', $today)->count();

            $this->totalSpentThisMonth = $transactions->whereBetween('date', [$startOfMonth, $today])->sum('amount');
            $this->totalTransactionsThisMonth = $transactions->whereBetween('date', [$startOfMonth, $today])->count();

            $income = $this->activeBudget->income_amount;
            $target = $this->activeBudget->target_savings;
            $totalBudget = $income - $target;
            $this->budgetLeft = max($totalBudget - $transactions->sum('amount'), 0);
            $this->targetSavings = $target;

            // Hitung sisa maksimal hari ini (limitToday)
            $start = $this->activeBudget->start_date->copy()->startOfDay();
            $end = $this->activeBudget->end_date->copy()->startOfDay();
            $now = now()->startOfDay();

            $totalDays = $start->diffInDays($end) + 1;
            $remainingDays = $now->lte($end) ? $now->diffInDays($end) + 1 : 0;

            $spentExceptToday = $transactions->where('date', '!=', $today)->sum('amount');
            $budgetRemaining = max($totalBudget - $spentExceptToday, 0);

            $this->limitToday = $remainingDays > 0
                ? max(floor($budgetRemaining / $remainingDays) - $this->totalSpentToday, 0)
                : 0;

            $this->generateChartData();
        }
    }

    public function generateChartData()
    {
        $data = [];
        $today = Carbon::today();

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i)->toDateString();
            $total = Transaction::where('user_id', Auth::id())
                ->where('budget_id', $this->activeBudget->id)
                ->whereDate('date', $date)
                ->sum('amount');

            $data[] = [
                'date' => Carbon::parse($date)->format('d M'),
                'total' => $total,
            ];
        }

        $this->chartData = $data;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
