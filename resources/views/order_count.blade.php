<!DOCTYPE html>
<html>
<head>
    <title>订单统计</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="Content-Type" content="text/html; charset=GBK">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="生活,购物,急所需,急所需平台,急所需加盟,急所需APP,急所需入驻"/>
    <meta name="description" content="急所需是一站式便捷服务平台,轻松一点，即刻到达,让您足不出户,开启便捷新生活"/>
    <link rel="stylesheet" href="{{ URL::asset('/') }}css/count.css">
    <script src="{{ URL::asset('/') }}scripts/echarts.common.min.js"></script>
</head>
<body>
<nav>
    <ul id="time">
        <li class="act" data-type="1">今日</li>
        <li data-type="2">本周</li>
        <li data-type="3">本月</li>
    </ul>
</nav>
<div id="main" style="height:400px;"></div>
<ul id="data">
    <li>
        <span class="data-num" id="orderNum">0</span><br/>
        <span class="data-title">所有</span>
    </li>
    <li>
        <span class="data-num" id="orderCompleteNum">0</span><br/>
        <span class="data-title">完成</span>
    </li>
    <li>
        <span class="data-num" id="orderAccidentNum">0</span><br/>
        <span class="data-title">意外</span>
    </li>
</ul>
<script type="text/javascript" src="{{ URL::asset('/') }}scripts/jquery-2.2.1.js" ></script>
<script>

    var myChart = echarts.init(document.getElementById('main'));

    //今日数据
    function Today(data){
        var option = {
            tooltip : {
                trigger: 'axis'
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data :data.time
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'今日',
                    type:'line',
                    stack: '总量',
                    areaStyle: {normal: {}},
                    data:data.orderCount
                }
            ]
        };
        myChart.setOption(option);
    }

    //本周数据
    function Week(data){
        var option = {
            tooltip : {
                trigger: 'axis'
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data :data.time
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'本周',
                    type:'line',
                    stack: '总量',
                    areaStyle: {normal: {}},
                    data:data.orderCount
                }
            ]
        };
        myChart.setOption(option);
    }

    //本月数据
    function Month(data){
        var option = {
            tooltip : {
                trigger: 'axis'
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data :data.time
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'本月',
                    type:'line',
                    stack: '总量',
                    areaStyle: {normal: {}},
                    data:data.orderCount
                }
            ]
        };
        myChart.setOption(option);
    }

    $(function(){

        $.post('/gamma/order/count' , {'type': 1} , function (data , status) {
            $('#orderNum').html(data.data.orderNum);
            $('#orderCompleteNum').html(data.data.orderCompleteNum);
            $('#orderAccidentNum').html(data.data.orderAccidentNum);
            Today(data.data);
        })

    })
    //菜单切换
    $("#time li").click(function(){
        $(this).addClass('act').siblings().removeClass('act');
        var type =$(this).data("type");
        if(type==1){
            $.post('/gamma/order/count' , {'type' : 1} , function (data , status) {
                $('#orderNum').html(data.data.orderNum);
                $('#orderCompleteNum').html(data.data.orderCompleteNum);
                $('#orderAccidentNum').html(data.data.orderAccidentNum);
                Today(data.data);
            })
        }else if (type==2) {
            $.post('/gamma/order/count' , {'type' : 2} , function (data , status) {
                $('#orderNum').html(data.data.orderNum);
                $('#orderCompleteNum').html(data.data.orderCompleteNum);
                $('#orderAccidentNum').html(data.data.orderAccidentNum);
                Week(data.data);
            })

        }else if (type==3) {
            $.post('/gamma/order/count' , {'type' : 3} , function (data , status) {
                $('#orderNum').html(data.data.orderNum);
                $('#orderCompleteNum').html(data.data.orderCompleteNum);
                $('#orderAccidentNum').html(data.data.orderAccidentNum);
                Month(data.data);
            })
        }
    })
</script>
</body>
</html>