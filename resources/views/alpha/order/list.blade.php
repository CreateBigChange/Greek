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
                        <div style='margin-left:20px;float: right;margin-right: 50px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search">订单总数: {{ $pageData->totalNum }}</div>
                    </header>
              <div id="table">
              <table class="table">
                 <thead>
                    <tr>
                      <th>收货人</th>
                      <th>收货电话</th>
                      <th>收货地址</th>
                      <th>昵称</th>
                      <th>真实姓名</th>
                      <th>电话</th>
                      <th>支付类型</th>
                      <th>配送费</th>
                      <th>订单总价</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($orders  as $order)
                      <tr class="table" >
                         <td>{{$order->consignee}}</td>
                         <td>{{$order->consignee_tel }}</td>
                         <td>{{$order->consignee_address}}</td>
                         <td>{{$order->nick_name}}</td>
                         <td>{{$order->true_name}}</td>
                         <td>{{$order->mobile}}</td>
                         <td>{{$order->pay_type_name }}</td>
                         <td>{{$order->deliver}}</td>
                         <td>{{$order->total}}</td>
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
@include('alpha.footer')
