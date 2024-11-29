<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;

class SendOTPSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobile_no, $verification_code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->mobile_no = $user->mobile_no;
        $this->verification_code = $user->verification_code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $basic  = new \Nexmo\Client\Credentials\Basic( env('NEXMO_API_KEY'), env('NEXMO_API_SECRET') );
        $client = new \Nexmo\Client($basic);

        //$receiver_phone = ltrim($this->mobile_no, '+');
        $receiver_phone = str_replace(['-', '_', ' ', '(', ')'], '', $this->mobile_no);

        try {
            $message = $client->message()->send([
                'to' => $receiver_phone,
                'from' => env('APP_NAME'),
                'text' => 'OTP code is ' . $this->verification_code
            ]);

            $response = $message->getResponseData();

            if ($response['messages'][0]['status'] == 0) {
                //echo "The message was sent successfully\n";
            } else {
                //echo "The message failed with status: " . $response['messages'][0]['status'] . "\n";
                return response(['errors' => "The message failed with status: " . $response['messages'][0]['status'] . "\n" ], 403);
            }
        } catch (Exception $e) {
            //echo "The message was not sent. Error: " . $e->getMessage() . "\n";
            return response(['errors' => "The message was not sent. Error: " . $e->getMessage() . "\n" ], 403);
        }

    }
}
