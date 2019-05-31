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

        $htm=env('htm');
        $url="$htm/weathere?text=$data";
        //初使化init方法
        $ch= curl_init();
        //设置抓取的url
        curl_setopt($ch,CURLOPT_URL,$url);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($ch);
        //关闭URL请求
        curl_close($ch);
        //显示获得的数据
        print_r($data);

    }

    //调用外部天气
        public function weathere(Request $request){
            $city=$_GET['text'];
        $url="http://api.k780.com/?app=weather.today&weaid=$city&&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json";
        $ch= curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $time=date('Y-m-d H:i:s');
        $str=$time.$data."\n";
        file_put_contents("logs/weather.log",$str,FILE_APPEND);
//        print_r($data);
        //标签对称加密
        $arr=base64_decode($data);
        $k=openssl_get_publickey('file://'.storage_path('app/key/public.pem'));
        openssl_public_decrypt($arr,$barr,$k);
        return $barr;
    }

}
