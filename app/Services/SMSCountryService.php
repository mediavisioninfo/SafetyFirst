<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMSCountryService
{
    protected $username;
    protected $apikey;
    protected $senderId;

    public function __construct()
    {
        $this->username = config('services.smscountry.username');
        $this->apikey = config('services.smscountry.apikey');
        $this->senderId = config('services.smscountry.senderid');
    }

    public function sendSMS($to, $message)
    {
        $url = "https://restapi.smscountry.com/v0.1/Accounts/{$this->username}/Messages";

        $headers = [
            'Authorization' => 'Basic ' . base64_encode("{$this->username}:{$this->apikey}"),
            'Content-Type'  => 'application/json',
        ];

        $body = [
            'Text' => $message,
            'To' => $to,
            'SenderId' => $this->senderId,
            'Is_Unicode' => false,
        ];

        $response = Http::withHeaders($headers)->post($url, $body);

        return $response->json();
    }
}
