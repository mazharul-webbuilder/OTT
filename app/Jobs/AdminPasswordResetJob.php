<?php

namespace App\Jobs;

use App\Mail\AdminPasswordResetMail;
use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class AdminPasswordResetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Admin $admin
    )
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $verificationCode = getVerificationCode(length: 6);

        $this->admin->update([
            'verification_code' => $verificationCode,
            'verification_code_created_at' => Carbon::now(),
            'verify_attempt_left' => 5
        ]);

        Mail::send(new AdminPasswordResetMail($this->admin));
    }
}
