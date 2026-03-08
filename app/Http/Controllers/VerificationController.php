<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    /**
     * Show the verification form
     */
    public function show()
    {
        $user = Auth::user();
        
        // If already verified, redirect to dashboard
        if ($user->is_verified) {
            return redirect()->route('dashboard');
        }

        // Generate new code if none exists or expired
        if (!$user->verification_code || 
            ($user->verification_code_expires_at && now()->greaterThan($user->verification_code_expires_at))) {
            $this->sendVerificationCode($user);
        }

        return view('auth.verify');
    }

    /**
     * Resend verification code
     */
    public function resend()
    {
        $user = Auth::user();
        
        if ($user->is_verified) {
            return redirect()->route('dashboard');
        }

        $this->sendVerificationCode($user);

        return back()->with('status', 'Verification code sent to your email!');
    }

    /**
     * Verify the code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if ($user->verifyCode($request->code)) {
            return redirect()->route('dashboard')->with('success', 'Verification successful!');
        }

        return back()->with('error', 'Invalid or expired verification code.');
    }

    /**
     * Send verification code via email
     */
    protected function sendVerificationCode(User $user): void
    {
        $code = $user->generateVerificationCode();
        
        $details = [
            'name' => $user->name,
            'code' => $code,
            'expires' => '10 minutes',
        ];

        Mail::send('emails.verification-code', $details, function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Your Verification Code');
        });
    }
}
