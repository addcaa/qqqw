<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
请输入要查询的天气 <input type="text" id="text"><input type="submit" id="sub">
</body>
</html>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
$(function(){
    var user_id=localStorage.user_id;
    $("#sub").click(function(){
        var text=$("#text").val();
        $.ajax('http://on.shont.com/weathero',{
            dataType:'json',//服务器返回json格式数据
            type:'post',//HTTP请求类型
            data:{text:text},
            success:function(data){
                console.log(data);
//            if(data.on==0){

//                var user_id=data.user_id;
//                localStorage.setItem("user_id",user_id);//存储变量名为key，值为value的变量
//                alert('登陆成功');
//                window.location.href = "http://on.shont.com/weather";
//            }
            }
        })
    })

})
</script>