<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\RefreshYahooAPIAuth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $this->checkYahooAuth();
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function checkYahooAuth()
    {
        $users = User::where([
            ['yahoo_client_id', '!=', null],
            ['yahoo_secret', '!=', null],
            ['yahoo_access_token', '!=', null],
            ['yahoo_refresh_token', '!=', null]
        ])->get();
        foreach ($users as $user) {
            dispatch(new RefreshYahooAPIAuth($user));
        }
    }
}
