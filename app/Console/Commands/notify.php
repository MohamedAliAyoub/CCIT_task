<?php

namespace App\Console\Commands;

use App\Mail\NewUsers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class notify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send mail to admin that have new users ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $new_users = User::query()->whereDate('created_at' ,  now())
            ->where([['type' , 1], ['is_active', 1 ]])->count();
        dd($new_users);
        Mail::to('mohamedali163163@gmail.com')->send(new NewUsers($new_users));
    }
}
