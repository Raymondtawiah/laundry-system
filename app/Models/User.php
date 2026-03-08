<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_approved',
        'laundry_id',
        'verification_code',
        'verification_code_expires_at',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'is_verified' => 'boolean',
            'verification_code_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the user's laundry.
     */
    public function laundry(): BelongsTo
    {
        return $this->belongsTo(Laundry::class);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Generate a 6-digit verification code
     */
    public function generateVerificationCode(): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->verification_code = $code;
        $this->verification_code_expires_at = now()->addMinutes(10);
        $this->save();
        
        return $code;
    }

    /**
     * Verify the code
     */
    public function verifyCode(string $code): bool
    {
        if ($this->verification_code !== $code) {
            return false;
        }

        if ($this->verification_code_expires_at && now()->greaterThan($this->verification_code_expires_at)) {
            return false;
        }

        $this->verification_code = null;
        $this->verification_code_expires_at = null;
        $this->is_verified = true;
        $this->save();

        return true;
    }

    /**
     * Check if user needs verification
     */
    public function needsVerification(): bool
    {
        return !$this->is_verified;
    }

    /**
     * Clear verification code
     */
    public function clearVerificationCode(): void
    {
        $this->verification_code = null;
        $this->verification_code_expires_at = null;
        $this->save();
    }
}
