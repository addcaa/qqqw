<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
class Login extends Controller
{
    //登录
    public function add(){
        return view('login.add');
    }
    public function logadd(Request $request){
        $data=$request->input();
        $js_arr=json_encode($data);
        $method="aes-256-cbc";
        $password="cuifang";
        $iv='1123123232323432';
        $on_arr=openssl_encrypt($js_arr,$method,$password,OPENSSL_RAW_DATA,$iv);
        $ba64=base64_encode($on_arr);
        $url="http://on.shont.com/loginj";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$ba64);
        $data = curl_exec($ch);
//        echo curl_errno($ch);
        curl_close($ch);
        $arr=json_decode($data,true);
        if($arr){
            if($arr['no']==0){
                $user_id=$arr['user_id'];
                Cookie::queue('user_id',$user_id);
                header("location:index");
            }else{
                return $data;
            }
        }else{
            return $data;

        }
    }

    //接口
    public function loginj(Request $request){
        $data=file_get_contents("php://input");
        $ba64=base64_decode($data);
        $method="aes-256-cbc";
        $password="cuifang";
        $iv='1123123232323432';
        $arr=openssl_decrypt($ba64, $method, $password,OPENSSL_RAW_DATA,$iv);
        $js_arr=json_decode($arr,true);
        $user=DB::table('user')->where(['user_name'=>$js_arr['user_name']])->first();
        if($user){
            if($user->user_pwd==$js_arr['user_pwd']){
                $u_id=$user->user_id;
                $key="token$u_id";
                $token=Str::random('40');
                Redis::set($key,$token);
                Redis::expire($key,3600);
                $ser=[
                    'no'=>0,
                    'msg'=>'登录成功',
                    'user_id'=>$u_id
                ];
                return json_encode($ser,JSON_UNESCAPED_UNICODE);
            }else{
                $u_id=$_SERVER["REMOTE_ADDR"].$user->user_id;
                $incr=Redis::incr($u_id);
                echo $incr;
                $ser=[
                    'no'=>00-2,
                    'msg'=>'密码错误',
                ];
                return json_encode($ser,JSON_UNESCAPED_UNICODE);
            }
        }else{
            $ser=[
                'no'=>00-1,
                'msg'=>'没有此账号'
            ];
            return json_encode($ser,JSON_UNESCAPED_UNICODE);
        }
    }

    public function index(Request $request){
        $user_id=$request->cookie('user_id');
        $key="token$user_id";
//        dd(Redis::get($key));
        if(empty(Redis::get($key))){
            echo "<script>alert('token过期请重启登录');location.href='/add';</script>";
        }
        return view('login.index');
    }

    public  function tui(Request $request){
        if($request->cookie('user_id')){
            $user_id=$request->cookie('user_id');
            $token="token$user_id";
            $add=Redis::del($token);
            Cookie::queue('user_id',null,-1);
            if($add){
                return [
                    'on'=>0,
                    'msg'=>'退出成功'
                ];
            }else{
                return [
                    'on'=>1-0,
                    'msg'=>'退出失败'
                ];
            }
        }else{
            return [
                'on'=>0,
                'msg'=>'退出成功'
            ];
        };
    }
}
