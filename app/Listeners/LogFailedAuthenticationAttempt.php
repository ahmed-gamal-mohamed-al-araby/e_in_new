<?php

namespace App\Listeners;

use Auth;
use Hash;
use App\User as User;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use MikeMcLin\WpPassword\Facades\WpPassword;

class LogFailedAuthenticationAttempt
{

    public function handle(Attempting $event)
    {
        $this->check($event->credentials['password'], \App\User::where('name', $event->credentials['name'])->first()->password ?? 'not found');
    }

    public function check($value, $hashedValue, array $options = [])
    {
        if ($this->needsRehash($hashedValue)) {

            if ($this->user_check_password($value, $hashedValue)) {

                $newHashedValue = (new \Illuminate\Hashing\BcryptHasher)->make($value, $options);
                \Illuminate\Support\Facades\DB::update('UPDATE users SET `password` = "' . $newHashedValue . '" WHERE `password` = "' . $hashedValue . '"');
                $hashedValue = $newHashedValue;
            }
        }
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return substr($hashedValue, 0, 4) != '$2y';
    }

    // WP PASSWORD FUNCTIONS
    function user_check_password($password, $stored_hash)
    {
        // $hash = md5($password);

        if (WpPassword::check($password, $stored_hash)) {
            // Password success!
            return true;
        } else {
            // Password failed :(
            return false;
        }

    }
}
