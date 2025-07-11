<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransactionIndex extends Component
{
    public $activeBudget;
    public $transactions;
    public $totalSpent = 0;
    public $budgetLeft = 0;
    public $remainingDailyLimit = 0;
    public $limitToday = 0;

    public $date, $description, $amount, $category_id;

    protected $rules = [
        'date' => 'required|date',
        'amount' => 'required|numeric|min:1',
        'description' => 'nullable|string|max:255',
        'category_id' => 'nullable|exists:categories,id',
    ];

    public function mount()
    {
        $this->date = now()->toDateString(); // default hari ini
        $this->amount = '';
        $this->description = '';
        $this->category_id = null;
        $this->loadData();
    }
    public function loadData()
    {
        $this->activeBudget = Budget::where('user_id', Auth::id())
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest('start_date')
            ->first();


        if (!$this->activeBudget) return;

        $this->transactions = Transaction::with('category')
            ->where('user_id', Auth::id()) // ✅ Tambahkan filter user
            ->where('budget_id', $this->activeBudget->id)
            ->orderByDesc('date')
            ->get();
        $this->totalSpent = $this->transactions->sum('amount');
        $today = now();

        $totalSpentExceptToday = $this->transactions
            ->filter(fn($trx) => !$trx->date->isSameDay($today))
            ->sum('amount');

        $income = $this->activeBudget->income_amount;
        $target = $this->activeBudget->target_savings;
        $totalBudget = $income - $target;
        $budgetLeft = max($totalBudget - $this->totalSpent, 0);

        $this->budgetLeft = $budgetLeft;

        $start = $this->activeBudget->start_date->copy()->startOfDay();
        $end = $this->activeBudget->end_date->copy()->startOfDay();
        $totalDaysLeft = $today->lte($end) ? $today->diffInDays($end) + 1 : 0;
        $totalDays = $start->diffInDays($end) + 1;
        $spentToday = Transaction::with('category')
            ->where('user_id', Auth::id())
            ->where('budget_id', $this->activeBudget->id)
            ->whereDate('date', now())
            ->sum('amount');
        $budgetLeftExceptToday = $totalBudget - $totalSpentExceptToday;
        $totalLimitToday = $budgetLeftExceptToday / $totalDaysLeft;

        // $dailyNominal = floor($totalBudget / $totalDays);
        $dailyNominal = floor($totalLimitToday - $spentToday);

        // 💡 Pakai `$this->date` agar sesuai input user (bukan hanya hari ini)
        $currentDate = $this->date ? \Carbon\Carbon::parse($this->date)->toDateString() : now()->toDateString();

        $spentToday = $this->transactions
            ->where('date', $currentDate)
            ->sum('amount');

        $this->limitToday = max($dailyNominal - $spentToday, 0);
    }



    public function resetForm()
    {
        $this->reset(['date', 'description', 'amount', 'category_id']);
        $this->date = now()->toDateString();
    }
    public function saveTransaction()
    {
        $this->validate();

        $cleanAmount = preg_replace('/[^\d]/', '', $this->amount);
        if (!$cleanAmount || $cleanAmount < 1) {
            $this->addError('amount', 'Jumlah tidak valid.');
            return;
        }

        if (!$this->activeBudget) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Tidak ada budget aktif.',
            ]);
            return;
        }

        Transaction::create([
            'user_id' => Auth::id(),
            'budget_id' => $this->activeBudget->id,
            'date' => $this->date,
            'description' => $this->description,
            'amount' => $cleanAmount,
            'category_id' => $this->category_id,
        ]);

        $this->resetForm();
        $this->loadData();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Transaksi berhasil disimpan!',
        ]);
    }


    public function deleteTransaction($id)
    {
        $trx = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$trx) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Transaksi tidak ditemukan.',
            ]);
            return;
        }

        $trx->delete();

        $this->loadData();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Transaksi berhasil dihapus.',
        ]);
    }

    public function render()
    {
        return view('livewire.transaction-index');
    }
}
