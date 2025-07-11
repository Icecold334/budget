<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    /** @use HasFactory<\Database\Factories\BudgetFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'income_amount',
        'target_savings',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'income_amount' => 'integer',
        'target_savings' => 'integer',
    ];
    public function getFormattedStartDateAttribute()
    {
        return Carbon::parse($this->start_date)->translatedFormat('d M Y');
    }

    public function getFormattedEndDateAttribute()
    {
        return Carbon::parse($this->end_date)->translatedFormat('d M Y');
    }

    public function getAmountRupiahAttribute()
    {
        return 'Rp ' . number_format($this->income_amount, 0, ',', '.');
    }

    public function getTargetRemainderRupiahAttribute()
    {
        return 'Rp ' . number_format($this->target_savings, 0, ',', '.');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getBudgetableAttribute()
    {
        return $this->income_amount - $this->target_savings;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
}
