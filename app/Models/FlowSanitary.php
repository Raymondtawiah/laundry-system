<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlowSanitary extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'price',
        'quantity',
        'laundry_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function laundry(): BelongsTo
    {
        return $this->belongsTo(Laundry::class);
    }
}
