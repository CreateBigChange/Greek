@include('alpha.header')

<!--main content start this is a test-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        订单列表
                        <div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>
                        <div style='margin-left:20px;float: right;margin-right: 50px;' class="btn btn-primary btn-xs searchDiaLog"  href="#search">订单总数: {{ $pageData->totalNum }}</div>
                        <div style='margin-left:20px;float: right;margin-right: 50px;' class="btn btn-primary btn-xs searchDiaLog"  href="#search">订单总价: {{ $totalMoney[0]->totalMony }}</div>
                    </header>

                    <div id="table">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>收货人</th>

                                <th>收货地址</th>
                                <th>店铺</th>
                                <th>状态</th>
                                <th>订单号</th>

                                <th>支付类型</th>
                                <th>配送费</th>
                                <th>时间</th>
                                <th>优惠券类型</th>
                                <th>优惠券价格</th>
                                <th>扣点数</th>
                                <th>订单总价</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($orders  as $order)
                                <tr class="table" >
                                    <td>{{$order->consignee}}</td>

                                    <td>{{$order->consignee_address}}</td>
                                    <td>{{$order->sname}}</td>
                                    <td>
                                        <?php
                                        switch ($order->status) {
                                            case '1':
                                                echo "已完成";
                                                break;
                                            case '2':
                                                echo "已付款";
                                                break;
                                            case '3':
                                                echo "已接单";
                                                break;
                                            case '4':
                                                echo "配送中";
                                                break;
                                            case '5':
                                                echo "已送达";
                                                break;
                                            case '11':
                                                echo "未付款 ";
                                                break;
                                            case '12':
                                                echo "已取消";
                                                break;
                                            case '13':
                                                echo "退款中";
                                                break;
                                            case '14':
                                                echo "已退款";
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>{{$order->order_num}}</td>

                                    <td>{{$order->pay_type_name }}</td>
                                    <td>{{$order->deliver}}</td>
                                    <td>{{$order->created_at}}</td>
                                    <td><?php
                                        switch($order->coupon_type){
                                            case 1:echo "满减券";
                                                break;
                                            case 0: echo "平台券";
                                                break;
                                            default:break;
                                        }
                                        ?>
                                    </td>
                                    <td>{{$order->coupon_actual_reduce}}</td>
                                    <td>{{$order->money_reduce_points}}</td>
                                    <td>{{$order->total}}</td>
                                    <td><button class="btn btn-success detail_button" data-toggle="modal" href="#detail_{{ $order->id }}"><i class="icon-search">详情</i></button></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center">
                        {!! $pageHtml !!}
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>
<!--main content end-->
<script>

    var table = $(".table");
    var i=0;
    $("#table .table").each(function(){
        if(i%4==0)
            $(this).addClass("success");
        if(i%4==1)
            $(this).addClass("warning");
        if(i%4==2)
            $(this).addClass("danger");
        if(i%4==3)
            $(this).addClass("active");
        i++;
    });
    $('.showOrderGoods').bind('click' , function() {
        var _this = this;
        var orderId = $(_this).attr('orderId');
        if($("#i_"+orderId).hasClass('icon-double-angle-down')){
            $("#goods_"+orderId).css({"display":"table"});
            $("#i_"+orderId).removeClass('icon-double-angle-down').addClass('icon-double-angle-up');
        }else{
            $("#goods_"+orderId).css({"display":"none"});
            $("#i_"+orderId).removeClass('icon-double-angle-up').addClass('icon-double-angle-down');
        }
    });
    $('.changeStatus').bind('click' , function() {
        var _this   = this;
        var orderId = $(_this).attr('orderId');
        var status  = $(_this).attr('status');

        $.post('/alpha/order/change/status/' + orderId , {'status' : status} , function(data) {
            if(data.code == '0000'){
                $(_this).hide();
                if(status == 1){
                    $('#showOrderGoods_' + orderId).show();
                    $('#status_' + orderId).html('已完成');
                }
                if(status == 14) {
                    $('#showOrderGoods_' + orderId).show();
                    $('#status_' + orderId).html('已退款');
                }
                if(status == 3){
                    $('#show_' + orderId + '_3').show();
                    $('#status_' + orderId).html('已接单');
                }
                if(status == 4){
                    $('#show_' + orderId + '_4').show();
                    $('#status_' + orderId).html('配送中');
                }
                if(status == 5){
                    $('#show_' + orderId + '_5').show();
                    $('#status_' + orderId).html('已送达');
                }
            }
        })
    });
    $('.showDetail').bind('dblclick' , function(){
        var orderId = $(this).attr('orderId');
        $('.detail').hide(500);
        $('#detail_'+orderId).toggle(500);
    });
</script>

@include('alpha.moduls.warning')
@include('alpha.order.order_moduls')
@include('alpha.order.order_detail_module')
@include('alpha.footer')
