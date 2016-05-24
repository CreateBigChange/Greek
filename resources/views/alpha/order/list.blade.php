@include('alpha.header')

        <!--main content start-->
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

                    <div class="panel-body">
                        <section id="unseen">
                            @foreach ($orders as $o)
                                <div class="col-lg-12">
                                    <!--widget start-->
                                    <aside class="profile-nav alt green-border">
                                        <section class="panel">
                                            <div id="status_{{ $o->id }}" style="
                                                transform:rotate(-45deg);
                                                -ms-transform:rotate(-45deg); 	/* IE 9 */
                                                -moz-transform:rotate(-45deg); 	/* Firefox */
                                                -webkit-transform:rotate(-45deg); /* Safari 和 Chrome */
                                                -o-transform:rotate(-45deg); 	/* Opera */
                                                position: absolute;
                                                font-size: 50px;
                                                top:50px;
                                                right:30px;
                                                color: #f00;
                                                filter:alpha(opacity=10); /*IE滤镜，透明度50%*/
                                                -moz-opacity:0.1; /*Firefox私有，透明度50%*/
                                                opacity:0.1;/*其他，透明度50%*/
                                            ">
                                                @if ($o->status == '2')
                                                    新订单
                                                @elseif ($o->status == '3')
                                                    已接单
                                                @elseif ($o->status == '4')
                                                    配送中
                                                @elseif ($o->status == '5')
                                                    已送达
                                                @elseif ($o->status == '13')
                                                    退款中
                                                @elseif ($o->status == '14')
                                                    已退款
                                                @elseif ($o->status == '12' || $o->status == '11')
                                                    已取消
                                                @elseif ($o->status == '1')
                                                    完成
                                                @endif
                                            </div>
                                            <div class="user-heading alt green-bg showDetail"  orderId="{{ $o->id }}" style="padding: 20px;">
                                                <div class="row">
                                                    <div class="bio-row text-center">
                                                        <h1><span>收货人 :</span>{{ $o->consignee }}</h1>
                                                    </div>
                                                    <div class="bio-row text-center">
                                                        <h1><span>收货电话 : </span>{{ $o->consignee_tel }}</h1>
                                                    </div>
                                                </div>
                                                <div class="row text-center" style="margin-bottom: 20px;">

                                                        <h1>{{ $o->consignee_address }}</h1>

                                                </div>
                                                <div class="row text-center">

                                                    <div class="bio-row">
                                                        <p><span>昵称 :</span>{{ $o->nick_name }}</p>
                                                    </div>
                                                    <div class="bio-row">
                                                        <p><span>真实名称 : </span>{{ $o->true_name }}</p>
                                                    </div>
                                                    <div class="bio-row">
                                                        <p><span>电话 : </span>{{ $o->mobile }}</p>
                                                    </div>
                                                    <div class="bio-row">
                                                        <p><span>支付类型 : </span>{{ $o->pay_type_name }}</p>
                                                    </div>
                                                    <div class="bio-row">
                                                        <p><span>配送费 : </span>{{ $o->deliver }}</p>
                                                    </div>
                                                    <div class="bio-row">
                                                        <p><span>收入积分 : </span>{{ $o->in_points }}</p>
                                                    </div>
                                                    <div class="bio-row">
                                                        <p><span>送出积分 : </span>{{ $o->out_points }}</p>
                                                    </div>
                                                    <div class="bio-row">
                                                        <p><span>订单总价 : </span>{{ $o->total }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--<div class="panel-body bio-graph-info detail" id="detail_{{ $o->id }}" style="display: none">--}}
                                                {{--<div class="row">--}}
                                                    {{--<div class="bio-row">--}}
                                                        {{--<p><span>昵称 :</span>{{ $o->nick_name }}</p>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="bio-row">--}}
                                                        {{--<p><span>真实名称 : </span>{{ $o->true_name }}</p>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="bio-row">--}}
                                                        {{--<p><span>联系电话 : </span>{{ $o->mobile }}</p>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="bio-row">--}}
                                                        {{--<p><span>支付类型 : </span>{{ $o->pay_type_name }}</p>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="bio-row">--}}
                                                        {{--<p><span>配送费 : </span>{{ $o->deliver }}</p>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="bio-row">--}}
                                                        {{--<p><span>收入积分 : </span>{{ $o->in_points }}</p>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="bio-row">--}}
                                                        {{--<p><span>送出积分 : </span>{{ $o->out_points }}</p>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="bio-row">--}}
                                                        {{--<p><span>订单总价 : </span>{{ $o->total }}</p>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            <table id="goods_{{ $o->id }}" class="table table-bordered table-striped table-condensed" style="display: @if ($o->status == 2 || $o->status == 3  || $o->status == 13)  table @else none @endif;" >
                                                <thead>
                                                    <tr>
                                                        <th>名称</th>
                                                        <th>品牌</th>
                                                        <th>规格</th>
                                                        <th>数量</th>
                                                        <th>单价</th>
                                                        <th>积分</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="dragsort">
                                                @foreach ($o->goods as $og)
                                                    <tr>
                                                        <td>{{ $og->name }}</td>
                                                        <td>{{ $og->b_name }}</td>
                                                        <td>{{ $og->spec }}</td>
                                                        <td>{{ $og->num }}</td>
                                                        <td>{{ $og->out_price }}</td>
                                                        <td>{{ $og->give_points }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                            <botton style="display: @if ($o->status == '2') block @else none @endif ;width: 100%;line-height: 50px;background: #aec785" type="button" status="3" id="show_{{ $o->id }}_2" class="btn btn-success changeStatus" orderId="{{ $o->id }}">接单</botton>
                                            <botton style="display: @if ($o->status == '3') block @else none @endif ;width: 100%;line-height: 50px;background: #aec785" type="button" status="4" id="show_{{ $o->id }}_3" class="btn btn-success changeStatus" orderId="{{ $o->id }}">配送</botton>
                                            <botton style="display: @if ($o->status == '4') block @else none @endif ;width: 100%;line-height: 50px;background: #aec785" type="button" status="5" id="show_{{ $o->id }}_4" class="btn btn-success changeStatus" orderId="{{ $o->id }}">已送达</botton>
                                            <botton style="display: @if ($o->status == '5') block @else none @endif ;width: 100%;line-height: 50px;background: #aec785" type="button" status="1" id="show_{{ $o->id }}_5" class="btn btn-success changeStatus" orderId="{{ $o->id }}">完成</botton>
                                            <botton style="display: @if ($o->status == '13') block @else none @endif ;width: 100%;line-height: 50px;background: #aec785" type="button" status="14" id="show_{{ $o->id }}_13" class="btn btn-success changeStatus" orderId="{{ $o->id }}">确认退款</botton>

                                            <div style="display: @if ($o->status == '1' || $o->status == '14'  || $o->status == '12' || $o->status == '11' ) block @else none @endif;font-size: 35px;background: #aec785;color: #fff;" orderId="{{ $o->id }}" id="showOrderGoods_{{ $o->id }}" class="text-center showOrderGoods"  >
                                                <i class="icon-double-angle-down down" id="i_{{ $o->id }}"></i>
                                            </div>
                                        </section>
                                    </aside>
                                    <!--widget end-->
                                </div>
                            @endforeach
                        </section>
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

@include('alpha.footer')
