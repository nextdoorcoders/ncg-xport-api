<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('vendor:currency')
             ->everyThirtyMinutes()
             ->withoutOverlapping(15);

         $schedule->command('vendor:keyword')
             ->everyThreeHours()
             ->withoutOverlapping(15);

        $schedule->command('vendor:media-sync')
            ->everyMinute()
            ->withoutOverlapping(1);

        $schedule->command('vendor:uptime-robot')
            ->everyMinute()
            ->withoutOverlapping(1);

        $schedule->command('vendor:weather')
            ->everyThirtyMinutes()
            ->withoutOverlapping(15);


        $schedule->command('vendor:clear')
            ->hourly()
            ->withoutOverlapping(60);

        $schedule->command('telescope:prune --hours=72')
            ->everySixHours()
            ->withoutOverlapping(60);
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
}
