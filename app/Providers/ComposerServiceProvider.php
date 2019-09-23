<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\UserComposer;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      // 連想配列で渡します
      // キーにコンポーザーを指定し、値にビューを指定します（ワイルドカードも使えます）
      // この場合、layoutsディレクトリ配下のビューテンプレートが読み込まれた場合にUserComposerを読み込む（＝$userが作られる）という設定の仕方になります
      View::composers([
        UserComposer::class    => 'layouts.*'
      ]);
    }
}
