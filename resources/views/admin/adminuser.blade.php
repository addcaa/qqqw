<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
过期时间 <p>{{$time}}</p>秒
填写token过期时间：<input type="text" id="time">秒
</body>
</html>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(function(){
       $("#time").blur(function () {
           var time=parseInt($(this).val());

               $.get(
                       'timeuser',
                       {time:time},
                       function(res){
                            if(res.on==110){
                                alert(res.msg);
                            }
                           console.log(res);
                       }
               )

       })
    })
</script>