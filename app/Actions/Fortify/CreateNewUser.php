<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Laundry;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Str;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        // Check if any admin already exists - only the first admin can register
        $adminExists = User::where('role', 'admin')->exists();
        
        if ($adminExists) {
            abort(403, 'Registration is closed. Please contact the admin to create your account.');
        }

        // Create a laundry for the user
        $laundry = Laundry::create([
            'name' => $input['laundry_name'] ?? 'My Laundry',
            'slug' => Str::slug($input['laundry_name'] ?? 'my-laundry'),
        ]);

        // Create the user with admin role
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => 'admin',
            'is_approved' => true,
            'laundry_id' => $laundry->id,
        ]);

        return $user;
    }
}
