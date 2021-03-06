<?php

namespace App\Console;

use App\Console\Commands\ExpandJackpot;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ExpandJackpot::class,
        Commands\ResultJackpot::class,
        Commands\EmailCallsNotification::class,
        Commands\TheLotterXml::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:email-calls')->everyMinute();
        $schedule->command('command:expand-jackpot')->cron('*/45 * * * *');
        $schedule->command('command:result-jackpot')->cron('0 */12 * * *');
//        $schedule->command('command:thelotter')->cron('*/30 * * * *');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
