<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'laundry_id',
        'customer_id',
        'user_id',
        'status',
        'delivery_type',
        'service_type',
        'total_amount',
        'amount_paid',
        'payment_status',
        'balance',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_READY = 'ready';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Delivery type constants
     */
    const DELIVERY_PICKUP = 'pickup';
    const DELIVERY_DOORSTEP = 'doorstep';

    /**
     * Service type constants
     */
    const SERVICE_WASHING = 'washing';
    const SERVICE_IRONING = 'ironing';
    const SERVICE_DRYING = 'drying';
    const SERVICE_DEEP_CLEANING = 'deep_cleaning';

    /**
     * Payment status constants
     */
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PARTIAL = 'partial';
    const PAYMENT_PAID = 'paid';

    /**
     * Get the laundry that owns the order.
     */
    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    /**
     * Get the customer associated with the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user (staff/admin) who created the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'order_items')
                    ->withPivot('quantity', 'unit_price', 'subtotal')
                    ->withTimestamps();
    }

    /**
     * Calculate and update balance based on amount paid
     */
    public function updateBalance()
    {
        $this->balance = $this->total_amount - $this->amount_paid;
        
        if ($this->amount_paid >= $this->total_amount) {
            $this->payment_status = self::PAYMENT_PAID;
        } elseif ($this->amount_paid > 0) {
            $this->payment_status = self::PAYMENT_PARTIAL;
        } else {
            $this->payment_status = self::PAYMENT_UNPAID;
        }
        
        $this->save();
    }
}
