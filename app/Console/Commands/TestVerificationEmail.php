<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestVerificationEmail extends Command
{
    protected $signature = 'email:test-verification {email}';
    protected $description = 'Send a test verification email to a specific user';

    public function handle()
    {
        $email = $this->argument('email');

        // Find user by email
        $user = User::where('Email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $this->info("Sending verification email to: {$user->Email}");
        $this->info("User Name: {$user->UserName}");

        try {
            // Send the verification notification
            $user->sendEmailVerificationNotification();

            $this->info('✓ Verification email sent successfully!');
            $this->info('Please check your inbox (and spam folder).');
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send verification email');
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
