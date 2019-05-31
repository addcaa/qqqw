<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
class AdminController extends Controller
{
    //后台
    public function adminuser(Request $request){
        $user_id=$request->cookie('user_id');
        $key="token$user_id";
        $arr=Redis::get($key);
        $time=Redis::TTL($key);
        if($time==-2){
            $time="token已过期，需要重新登录";
        }
        return view('admin.adminuser',['time'=>$time]);
    }

    public function timeuser(Request $request){
        $time=$_GET['time'];
        if($time=="NaN"){
            return [
                'on'=>110,
                'msg'=>"秒数不能为"
            ];
        }
        $user_id=$request->cookie('user_id');
        $key="token$user_id";
        Redis::expire($key,$time);
    }
}
