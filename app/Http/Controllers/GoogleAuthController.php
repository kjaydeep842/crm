<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // New users get their OWN new organization (tenant)
                $org = Organization::create([
                    'name' => $googleUser->getName() . "'s Company",
                    'package' => 'starter', // Or 'trial', or 'none' requiring them to pay
                    'ai_credits_used' => 0,
                ]);

                // Create a new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(16)), // random password
                    'role' => 'admin', // Make them an admin of the default org
                    'organization_id' => $org->id,
                ]);
            } else {
                // Update their google id and avatar if they already exist
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Something went wrong with Google Login: ' . $e->getMessage());
        }
    }
}
