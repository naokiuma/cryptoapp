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
         //\App\Console\Commands\TestCommand::class,   //コマンドの登録
         \App\Console\Commands\UserCommand::class,
         \App\Console\Commands\CoinCommand::class,
         \App\Console\Commands\CoindayCommand::class,
         \App\Console\Commands\CoinhourCommand::class,
         \App\Console\Commands\CoinweekCommand::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      //スケジュールの登録(->everyFiveMinutes();は5分ごと。
      $schedule->command('command:usercommand')->hourly();//1日に一度、ユーザー情報を更新
      $schedule->command('command:coincommand')->hourly();//1日に一度、coinの取引高を更新
      $schedule->command('command:coinhourcommand')->hourly();//1時間に一度、1時間のツイート数を更新
      $schedule->command('command:coindaycommand')->hourly();//1日に一度、1日のツイート数を更新
      $schedule->command('command:coinweekcommand')->hourly();//1日に一度、1週間のツイート数を更新
    }
        // $schedule->command('inspire')
        //          ->hourly();


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
