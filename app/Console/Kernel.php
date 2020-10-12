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
         $schedule->command('vendor:weather')
             ->everyThirtyMinutes()
             ->withoutOverlapping(15);

         $schedule->command('vendor:currency')
             ->everyThirtyMinutes()
             ->withoutOverlapping(15);

        $schedule->command('vendor:clear')
            ->daily()
            ->withoutOverlapping();

        $schedule->command('telescope:prune --hours=72')
            ->daily()
            ->withoutOverlapping();
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
