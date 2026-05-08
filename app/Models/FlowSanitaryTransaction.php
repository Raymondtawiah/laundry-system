<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlowSanitaryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'flow_sanitary_id',
        'transaction_type',
        'quantity',
        'unit_price',
        'total_amount',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function flowSanitary(): BelongsTo
    {
        return $this->belongsTo(FlowSanitary::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
