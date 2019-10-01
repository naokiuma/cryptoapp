<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AutofollowController;


class UserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:usercommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'フォローユーザーのDB上データを更新します';

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
    {//実行するタスク
        AutofollowController::addfollow();
    }
}
