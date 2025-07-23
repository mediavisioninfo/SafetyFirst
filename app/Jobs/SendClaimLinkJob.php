<?php

namespace App\Jobs;

use PHPMailer\PHPMailer\PHPMailer;
use Twilio\Rest\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendClaimLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $claim;
    protected $email;
    protected $mobile;
    protected $uploadLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($claim, $email, $mobile, $uploadLink)
    {
        $this->claim = $claim;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->uploadLink = $uploadLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = env('MAIL_PORT', 587);
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($this->email);
            $mail->isHTML(true);
            $mail->Subject = 'Upload Your Claim Documents';
            $mail->Body = "Please upload your documents using the following link: <a href='{$this->uploadLink}'>{$this->uploadLink}</a>";
            $mail->send();

            // Update claim status
            $this->claim->status = 'link_shared';
            $this->claim->save();
        } catch (\Exception $e) {
            $this->claim->status = 'documents_pending';
            $this->claim->save();
        }

        // Send SMS using Twilio
        if (env('TWILIO_SID') && env('TWILIO_AUTH_TOKEN') && env('TWILIO_PHONE_NUMBER')) {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

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
                    'body' => "Please upload your documents using the following link: {$this->uploadLink}"
                ]
            );
        }
    }
}
