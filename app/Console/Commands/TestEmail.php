<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Test email configuration by sending a test email';

    public function handle()
    {
        $recipientEmail = $this->argument('email');

        $this->info('Testing email configuration...');
        $this->info('SMTP Host: ' . config('mail.mailers.smtp.host'));
        $this->info('SMTP Port: ' . config('mail.mailers.smtp.port'));
        $this->info('SMTP Username: ' . config('mail.mailers.smtp.username'));
        $this->info('From Address: ' . config('mail.from.address'));

        try {
            Mail::raw('This is a test email from GoRentUMS. If you receive this, your email configuration is working!', function ($message) use ($recipientEmail) {
                $message->to($recipientEmail)
                    ->subject('Test Email - GoRentUMS Email Verification Setup');
            });

            $this->info('✓ Email sent successfully to ' . $recipientEmail);
            $this->info('Please check your inbox (and spam folder).');
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email');
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
