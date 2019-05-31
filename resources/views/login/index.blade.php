<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<input type="button" value="点击退出登录" id="sub">
</body>
</html>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    var lastTime = new Date().getTime();   //更新操作时间
    var currentTime = new Date().getTime();  //更新当前时间
    var timeOut = 10000; //设置超时时间： 10秒

    $(function(){
        $(document).mouseover(function(){
            console.log('111');
            lastTime = new Date().getTime(); //更新操作时间

        });
    })

    function toLoginPage(){
        currentTime = new Date().getTime(); //更新当前时间
        if(currentTime - lastTime > timeOut){ //判断是否超时
            window.location.href="/";

        }
    }

    window.setInterval(toLoginPage, 1000);
</script>
