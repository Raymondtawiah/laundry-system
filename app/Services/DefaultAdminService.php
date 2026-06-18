<?php

namespace App\Services;

use App\Models\Laundry;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultAdminService
{
    public function getCredentials(): array
    {
        return [
            'email' => config('admin.default_email', 'gptundertogo@gmail.com'),
            'password' => config('admin.default_password', 'admin123'),
        ];
    }

    public function isDefaultAdmin(User $user): bool
    {
        $credentials = $this->getCredentials();

        return $user->email === $credentials['email'] && $user->role === 'admin';
    }

    public function ensureDefaultAdminExists(): User
    {
        $credentials = $this->getCredentials();

        $existingAdmin = User::where('email', $credentials['email'])->first();

        if ($existingAdmin) {
            return $existingAdmin;
        }

        $laundry = Laundry::firstOrCreate(
            ['slug' => 'default-laundry'],
            ['name' => config('admin.laundry_name', 'Laundry System')]
        );

        return User::create([
            'name' => config('admin.default_name', 'System Administrator'),
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
            'role' => 'admin',
            'is_approved' => true,
            'is_verified' => true,
            'laundry_id' => $laundry->id,
        ]);
    }
}
