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
        <th>收入积分</th>
        <th>送出积分</th>
        <th>订单总价</th>
      </tr>
   </thead>
   <tbody>
    @foreach ($orders  as $order)
        <tr class="table" >
           <td>{{$order->consignee}}</td>
           <td>{{$order->consignee_tel }}</td>
           <td>{{$order->consignee_address}}</td>
           <td>{{$order->nick_name }}</td>
           <td>{{$order->true_name }}</td>
           <td>{{$order->mobile }}</td>
           <td>{{$order->pay_type_name }}</td>
           <td>{{$order->deliver }}</td>
           <td>{{$order->in_points }}</td>
           <td>{{$order->out_points }}</td>
           <td>{{$order->total }}</td>
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
</script>

@include('alpha.moduls.warning')

@include('alpha.footer')
