<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;

class FirebaseService
{
    protected $auth;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials'));
        $this->auth = $factory->createAuth();
    }

    public function sendOtp($phoneNumber)
    {
        // Generate the OTP
        $verification = $this->auth->createCustomToken($phoneNumber);

        // Send the OTP using Firebase Authentication
        return $verification->toString();
    }

    public function verifyOtp($phoneNumber, $otp)
    {
        // Verify OTP (You'll need to handle the OTP validation process yourself)
        // This is where you verify the OTP sent to the phone number.
        return $this->auth->verifyIdToken($otp);
    }
}
