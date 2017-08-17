<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Mail;
class EmailCallsNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:email-calls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calls left';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::where('count_requests','<=',10)->get();
        foreach($users as $user){
            Mail::send('email.notification', array('count' => $user->count_requests), function($message) use($user)
            {
                $message->to($user->email, '')->subject('Lottoapi');
            });
        }
    }
}
