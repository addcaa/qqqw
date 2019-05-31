<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
class Token
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
        $key=$_SERVER["REQUEST_URI"].$_SERVER["REMOTE_ADDR"];
        $incr=Redis::incr($key);
        Redis::expire($key,60);
        if($incr>=20){
            Redis::expire($key,90);
            die(json_encode(
                [
                    'no'=>0001,
                    'msg'=>"调用接口过限制，3分钟后重试"
                ],JSON_UNESCAPED_UNICODE
            ));
        }
        return $next($request);
    }
}
