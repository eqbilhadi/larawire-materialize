<?php

namespace App\Jobs;

use App\Models\SysUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ForgetCacheMenu implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $users = SysUser::all();

        foreach ($users as $user) {
            cache()->forget($user->id . '_menus');
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

    }
}
