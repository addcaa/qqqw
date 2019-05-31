<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
header("Access-Control-Allow-Origin:*");
class ExamController extends Controller
{
    //
    public function eadd(){
        return view('exam.eadd');
    }
    public function eaddo(Request $request){
        $data=$request->input();
        $arr=DB::table('user')->where(['user_name'=>$data['user_name']])->first();
        if($arr){
            if($data['user_pwd']==$arr->user_pwd){
                return json_encode([
                    'on'=>0,
                    'msg'=>"登陆成功",
                    'user_id'=>$arr->user_id
                ]);
            }else{
                return json_encode([
                    'on'=>40001,
                    'msg'=>"密码错误",
                ]);
            }
        }else{
            return json_encode([
                'on'=>5000,
                'msg'=>"无此账号",
            ]);
        }
    }


    //weather
    public function weather(){
        return view('exam.weather');
    }

    //天气预报
    public function weathero(Request $request){
        $data=$request->input('text');
        $js_arr=json_encode($data);
        $method="aes-256-cbc";
        $password="cuifang";
        $iv='1123123232323432';
        $on_arr=openssl_encrypt($js_arr,$method,$password,OPENSSL_RAW_DATA,$iv);
        $ba=base64_encode($on_arr);
        $htm=env('htm');
        $url="$htm/weathere";
        //初使化init方法
        $ch=curl_init();
        //指定URL
        curl_setopt($ch,CURLOPT_URL,$url);
        //设定请求后返回结果
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //声明使用POST方式来进行发送
        curl_setopt($ch,CURLOPT_POST,1);
        //发送什么数据呢
        curl_setopt($ch,CURLOPT_POSTFIELDS,$ba);
        $data = curl_exec($ch);//运行curl
        echo curl_errno($ch);
        curl_close($ch);
        return ($data);
    }
    //调用外部天气
    public function weathere(Request $request){
        $data=file_get_contents("php://input");
        $ba=base64_decode($data);
        $method="aes-256-cbc";
        $password="cuifang";
        $iv='1123123232323432';
        $arr=openssl_decrypt($ba, $method, $password,OPENSSL_RAW_DATA,$iv);
        $city=json_decode($arr,true);
        
//        $url="http://api.k780.com/?app=weather.future&weaid=42883&&appkey=42883&sign=d5d442af1237b3454a7b9e81474f3325&format=json";
        $url="http://api.k780.com/?app=weather.today&weaid=$city&&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json";
//
//        $url="https://free-api.heweather.net/s6/weather/now?key=HE1904161049361666&location=$city";
        $ch= curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
//        $content=file_get_contents("php://input");
        $time=date('Y-m-d H:i:s');
        $str=$time.$data."\n";
        file_put_contents("logs/weather.log",$str,FILE_APPEND);
        print_r($data);
    }
}
