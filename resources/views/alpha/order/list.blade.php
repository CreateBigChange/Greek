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
                        <th>订单号</th>
                        <th>店铺</th>
                        <th>创建时间</th>
                        <th>状态</th>

                        <th>支付类型</th>

                        <th>优惠券类型</th>
                        <th>优惠券价格</th>
                        <th>配送费</th>
                        <th>商品总价</th>
                        <th>扣点数</th>
                        <th>操作</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($orders  as $order)
                      <tr class="table" >
                          <td>{{$order->order_num}}</td>
                          <td>{{$order->sname}}</td>
                          <td>{{$order->created_at}}</td>
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
                          <td>{{$order->pay_type_name }}</td>
                          <td><?php
                              if($order->store_id != 0){
                                  echo "店铺专用券";
                              }else{
                                  echo "通用券";
                              }
                              ?>
                          </td>
                          <td>{{$order->coupon_actual_reduce}}</td>
                          <td>{{$order->deliver}}</td>
                          <td>{{$order->total}}</td>
                          <td>{{$order->money_reduce_points}}</td>
                          <td><button class="btn btn-success" data-toggle="modal" href="#detail"
                                      id="detail_button"
                                      other_consignee =  "{{$order->consignee}}"   other_id =  '{{$order->id}}'  other_consignee_tel =  '{{$order->consignee_tel}}'
                                      other_refund_reason ='{{$order->refund_reason}}'   other_mobile ='{{$order->mobile}}'  other_ture_name = '{{$order->true_name}}'
                                      other_store_income = '{{$order->store_income}}'   other_trade_no = '{{$order->trade_no}}'  other_updated_at='{{$order->updated_at}}'
                                      other_city = '{{$order->city}}'  other_street = '{{$order->street}}'  other_country = '{{$order->county}}'
                                      other_deliver= '{{$order->deliver}}'other_remark = '{{$order->remark}}'   other_transaction_id = '{{$order->transaction_id}}'
                                      other_trade_no = '{{$order->trade_no}}'

                              ><i class="icon-search">详情</i></button></td>
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

    $("#detail_button").click(function () {

        $("#other_city").attr("value",$(this).attr("other_city"));
        $("#other_consignee").attr("value",$(this).attr("other_consignee"));
        $("#other_consignee_tel").attr("value",$(this).attr("other_consignee_tel"));
        $("#other_deliver").attr("value",$(this).attr("other_deliver"));
        $("#other_updated_at").attr("value",$(this).attr("other_updated_at"));
        $("#other_country").attr("value",$(this).attr("other_country"));
        $("#other_id").attr("value",$(this).attr("other_id"));
        $("#other_mobile").attr("value",$(this).attr("other_mobile"));
        $("#other_refund_reason").attr("value",$(this).attr("other_refund_reason"));
        $("#other_remark").attr("value",$(this).attr("other_remark"));
        $("#other_store_income").attr("value",$(this).attr("other_store_income"));
        $("#other_street").attr("value",$(this).attr("other_street"));
        $("#other_trade_no").attr("value",$(this).attr("other_trade_no"));
        $("#other_transaction_id").attr("value",$(this).attr("other_transaction_id"));
        $("#other_true_name").attr("value",$(this).attr("other_true_name"));

    });

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
