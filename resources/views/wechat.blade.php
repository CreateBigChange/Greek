<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Mosaddek">
<meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
<link rel="shortcut icon" href="/img/favicon.png">

<script src="{{ URL::asset('/') }}js/jquery-1.8.3.min.js"></script>
<body>
    <bottom class="xiadan">下单</bottom>

</body>
<script>

    $('.xiadan').bind('click' , function() {

    });

    function onBridgeReady(){
        $.get('http://preview.jisxu.com/wechat/pay' , function(data) {
            if(data.code == '0000'){

                var data = data.data;

                WeixinJSBridge.invoke(
                        'getBrandWCPayRequest', {
                            data
                        },
                        function(res){
                            if(res.err_msg == "get_brand_wcpay_request：ok" ) {}     // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
                        }
                );
            }
        });

    }
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }
</script>
</html>