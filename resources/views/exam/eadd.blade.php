<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>崔芳芳</title>
</head>
<body>
用户名：<input type="user_name" id="user_name">
密码：<input type="user_pwd" id="user_pwd">
<input type="submit" id="sub">
</body>
</html>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(function(){
        $('#sub').click(function(){
            var user_name=$("#user_name").val();
            var user_pwd=$("#user_pwd").val();
            $.ajax('http://on.shont.com/eaddo',{
                data:{user_name:user_name,user_pwd:user_pwd},
                dataType:'json',//服务器返回json格式数据
                type:'post',//HTTP请求类型
                success:function(data){
                    if(data.on==0){
                        var user_id=data.user_id;
                        localStorage.setItem("user_id",user_id);//存储变量名为key，值为value的变量
                        alert('登陆成功');
                        window.location.href = "http://on.shont.com/weather";
                    }
                }
        })

    })
    })
</script>