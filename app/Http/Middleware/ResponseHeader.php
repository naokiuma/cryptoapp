<?php

namespace App\Http\Middleware;

use Closure;

/**
 * レスポンスヘッダ用ミドルウェア
 * ※主にセキュリティ対策用
 */

class ResponseHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $response = $next($request);

      /**
       * キャッシュコントロール対策
       * ※デフォルトは「no-cache, private」だが、明示的に記載
       */
      $response->header('Cache-Control', 'no-cache, private');

      /**
       * クリックジャッキング対策
       * ※iframeを使用させない
       */
      $response->header('X-Frame-Options', 'DENY');

      /**
       * XSS対策(文字コード脆弱性対応)
       * ※デフォルトは「UTF-8」だが、明示的に記載
       */
      $response->header('Content-Type', 'charset=UTF-8');

        return $next($request);
    }
}
