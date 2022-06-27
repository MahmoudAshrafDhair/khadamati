<?php

namespace App\Jobs;

use App\Models\Notifications;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $title;
    public $body;
    public $platform;
    public $image;
    public function __construct($title,$body,$platform,$image)
    {
        $this->title=$title;
        $this->body=$body;
        $this->platform=$platform;
        $this->image=$image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->platform == 'web') {
            $users = User::query()->where('platform', 'web')->get();
            foreach ($users as $user) {
                Notifications::create([
                    'title' => $this->title,
                    'body' => $this->body,
                    'date' => Carbon::now(),
                    'is_read' => 0,
                    'data_backend' => "{}",
                    'user_id' => $user->id,
                    'link' => '',
                    'platform' => 'web'
                ]);
            }


        } elseif ($this->platform == 'andriod' ) {
            $users = User::Active()->where('platform', 'andriod')->get();

            foreach ($users as $user) {
                Notifications::create([
                    'title' => $this->title,
                    'body' => $this->body,
                    'date' => Carbon::now(),
                    'is_read' => 0,
                    'data_backend' => "{}",
                    'user_id' => $user->id,
                    'link' => '',
                    'platform' => $this->platform,
                    'image'=>$this->image
                ]);
                $response = $this->push_notification_order($user,$this->title, $this->body,$user->platform);
            }
        }elseif ($this->platform == 'ios' ) {
            $users = User::Active()->where('platform', 'ios')->get();

            foreach ($users as $user) {
                Notifications::create([
                    'title' => $this->title,
                    'body' => $this->body,
                    'date' => Carbon::now(),
                    'is_read' => 0,
                    'data_backend' => "{}",
                    'user_id' => $user->id,
                    'link' => '',
                    'platform' => $this->platform,
                    'image'=>$this->image

                ]);
                $response = $this->push_notification_order($user,$this->title, $this->body,$user->platform);
            }
        }
    }

    public function push_notification_order($user, $title, $message,$platform)
    {
        $SERVER_API_KEY = env('FIREBASE_FCM_KEY', 'AAAAIzMa7W0:APA91bFv-qaieR6RvFIp_7PQT3PDz54ymlW6--xU7SdfbaiD__7wQABNnJLGebnk9wyK7GqvyePOWpdSnQaxi4Lh-p4QylB4H9EAZsle_s9c2XRN4TZm8ezcFcdx-5LxKx2vAth-kwxE');

        $token_1 =$user->fcm_token ;


        $data = [
            "registration_ids" => [
                $token_1
            ],
            "topic" => "foo-bar",
            "notification" => [

                "title" => $title,
                "body" => $message,
                "sound" => "default", // required for sound on ios
                'click_action' => 'your activity name comes here',
                'icon' => 'new',
                'color' => 'red'

            ],
            'data' => [
                'title' => $title,
                'body' => $message,
                'date' => Carbon::now(),
                'is_read' => 0,
                'data_backend' => "{}",
                'user_id' => $user->id,
                'link' => '',
                'platform' => $platform,
                'click_action' => 'your activity name comes here',
                'icon' => 'new',
                'color' => 'red'
            ],


        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return $response;


    }

}
