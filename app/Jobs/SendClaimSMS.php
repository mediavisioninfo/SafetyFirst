<?php

namespace App\Jobs;

use Twilio\Rest\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendClaimSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobile;
    protected $link;

    public function __construct($mobile, $link)
    {
        $this->mobile = $mobile;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        // Check if the mobile number starts with +91 and format it if needed
        if (substr($this->mobile, 0, 3) !== '+91') {
            if (substr($this->mobile, 0, 1) === '0') {
                $this->mobile = '+91' . substr($this->mobile, 1);
            } else {
                $this->mobile = '+91' . $this->mobile;
            }
        }

        $twilio->messages->create(
            $this->mobile,
            [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => "Please upload your documents using the following link: {$this->link}"
            ]
        );
    }
}
