<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="jisxu">
    <meta name="keyword" content="急所需 急所需商户">
    <link rel="shortcut icon" href="/img/favicon.png">

    <title>{{ $title }}</title>


    <script src="{{ URL::asset('/') }}js/jquery-1.8.3.min.js"></script>

    <style type="text/css">
        *{margin:0; padding:0;}
        a{text-decoration: none;}
        img{max-width: 100%; height: auto;}
        .weixin-tip{display: none; position: fixed; left:0; top:0; bottom:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80);  height: 100%; width: 100%; z-index: 100;}
        .weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}
    </style>


</head>

<body>

<div class="weixin-tip">
    <p id="notice">
        <img src="{{ URL::asset('/') }}img/live_weixin.png" alt="浏览器打开"/>
    </p>
</div>

</body>

<script>
    $(function () {
        @if ($type == 'android')
        var winHeight = $(window).height();
        function is_weixin() {
            var ua = navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == "micromessenger") {
                return true;
            } else {
                return false;
            }
        }
        var isWeixin = is_weixin();
        if(isWeixin){
            $(".weixin-tip").css("height",winHeight);
            $(".weixin-tip").show();
        }else {
            window.location.href = "{{ $download }}"
        }
        @elseif ($type == 'ios')
            $("#notice").html('IOS版在审核中,请稍等两天');
            $(".weixin-tip").show();
        @endif
    })

</script>

</html>