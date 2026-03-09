<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'laundry_id',
        'branch',
        'name',
        'email',
        'phone',
        'address',
        'notes',
    ];

    /**
     * Get the laundry that owns the customer.
     */
    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    /**
     * Get the customer's orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
