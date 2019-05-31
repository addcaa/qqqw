<?php

namespace App\Http\Controllers\Show;

use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Model\GoodsMods;
class ShowController extends Controller
{
    public function add(){
        return view('show.add');
    }
    //单点登录
    public function login(){
        $arr=DB::table('user')->where(['user_name'=>$_POST['user_name']])->first();
        if($arr){
            if(password_verify($_POST['user_pwd'],$arr->user_pwd)){
                $user=$arr->user_id;
                setcookie("user",$user,time()+3600,"/","shont.com",false,true);
                $res=[
                    'err'=>'0',
                    'msg'=>"登录成功"
                ];
//                dd($_COOKIE['user']);
                return json_encode($res,JSON_UNESCAPED_UNICODE);
            }else{
                $res=[
                    'err'=>'00-2',
                    'msg'=>"密码错误"
                ];
                return json_encode($res,JSON_UNESCAPED_UNICODE);
            }
        }else{
           $res=[
               'err'=>'00-1',
               'msg'=>"无此账号"
           ];
            return json_encode($res,JSON_UNESCAPED_UNICODE);
        };
    }

    //调用goods商品接口
    public function goodss(){
        $key="data";
        $arr_info=json_decode(Redis::get($key));
        if($arr_info){
            echo "redis";
            dd($arr_info);
        }else{
            echo "11";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://on.shont.com/goods');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);
            $arr=base64_decode($data);
            curl_close($curl);
            $k=openssl_get_publickey('file://'.storage_path('app/key/public.pem'));
            openssl_public_decrypt($arr,$goods_id,$k);
            $goods_id=explode(',',$goods_id);
            $info=DB::table('goods')->whereIn('goods_id',$goods_id)->get();
            $js=json_encode($info,JSON_UNESCAPED_UNICODE);
            Redis::set($key,$js);
            Redis::expire($key,3600);
            dd($info);
        }
    }

    //商品接口
    public function goods(){
        $arr=GoodsMods::pluck('goods_id');
        $js_arr=json_encode($arr);
        $k=openssl_pkey_get_private('file://'.storage_path('app/key/private.pem'));
        openssl_private_encrypt($js_arr,$enc_data,$k);
        $br=base64_encode($enc_data);
        return $br;
    }

    public function addres(){
        $info=DB::table('goods')->get();
        $arr=json_encode($info,JSON_UNESCAPED_UNICODE);
        $key="data";
        Redis::set($key,$arr);
        Redis::expire($key,3600);
    }
}