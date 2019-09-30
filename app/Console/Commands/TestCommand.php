<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CoinController;



class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
         //コマンド名を設定（元：command:name→変更後：command:testcommand）
    protected $signature = 'command:testcommand';

    /**
     * The console command description.
     *
     * @var string
     */
         //コマンドの説明（元：Command description→変更後：testcommandのコマンド説明）
    protected $description = 'testcommandのコマンド説明';

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
      // ここに処理を記述
      logger()->info('testcommand実行！！coinのhighandlowも処理します。');
      CoinController::highandlow(); //実行するタスク
      //return ('news/test2');
      //echo "testcommand実行！！\n";

    }
}
