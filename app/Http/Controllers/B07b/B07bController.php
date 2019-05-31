<?php

namespace App\Http\Controllers\B07b;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
class B07bController extends Controller
{
    //文件上传
    public function b07(){
//
        return view('bo7b.b07');
    }

    //文件上传
    public function b07in(Request $request){
        $file = $_FILES;
        //路径名
        $tmpname = $_FILES['img']['tmp_name'];
        //名
        $name = $_FILES['img']['name'];
        //给名字
        $namee=Str::random('20').'.'.substr($name,strrpos($name,'.')+1);
        //存
        $res = move_uploaded_file($tmpname,'/wwwroot/shont/public/uploads/'.$namee);
        //路径
        $arr="/uploads/$namee";
        //穿到接口
        $ba_arr=base64_encode($arr);
        $url="http://on.shont.com/b07j";
        // form-data
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$ba_arr);
        $data = curl_exec($ch);//运行curl
        echo curl_errno($ch);
        curl_close($ch);
        return ($data);
    }


    //文件上传
    public function b07j(){
        $data=base64_decode(file_get_contents("php://input"));
        $arr=DB::table('wx_user')->insert(['headimgurl'=>$data]);
        dd($arr);

    }
}
