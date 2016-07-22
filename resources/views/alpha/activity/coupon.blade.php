@include('alpha.header')

        <!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        商品列表
                        <div style='margin-left:20px;' class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></div>

                        <div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>

                    </header>

                    <div class="panel-body">
                        <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>名称</th>
                                    <th>内容</th>
                                    <th>类型</th>
                                    <th>有效时间</th>
                                    <th>价值</th>
                                    <th>条件</th>
                                    <th>商店名</th>
                                    <th>总数目</th>
                                    <th>收回的数量</th>
                                    <th>已发出的数量</th>
                                    <th>开启</th>
                                    <th>剩余数量</th>
                                    <th width="100px;">操作</th>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                                @foreach($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->content }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->effective_time }}</td>
                                    <td>{{ $item->value }}</td>
                                    <td>{{ $item->prerequisite }}</td>
                                    <td>
                                    @if($item->store_id == 0 )
                                        通用平台
                                    @else
                                        {{ $item->store_name}}
                                    @endif
                                    </td>
                                    <td>{{ $item->total_num }}</td>
                                    <td>{{ $item->in_num }}</td>
                                    <td>{{ $item->out_num }}</td>
                                    <td>
                                        @if($item->stop_out == 0)
                                        开启
                                        @else
                                        关闭
                                        @endif
                                    </td>
                                    <td>{{ $item->num }}</td>
                                    <td>
                                        @if($item->stop_out == 0)
                                           <a href="{{ url('/alpha/Activity/couponUpdate',$item->id)}}"> <button class="btn btn-success change"  data-toggle="modal" href="#update" coupon_id={{ $item->id
                                    }}
                                                    coupon_name="{{ $item->name }}"  coupon_content="{{ $item->content }} "   coupon_type="{{ $item->type }}"   coupon_effective_time="{{ $item->effective_time }}"   coupon_value="{{ $item->value }}"     coupon_condition="{{ $item->prerequisite }}"   coupon_store_id="{{ $item->store_id }}"  coupon_total_num="{{ $item->total_num }}"  coupon_in_num="{{ $item->in_num }}"  coupon_out_num="{{ $item->out_num }}" coupon_stop_out="{{ $item->stop_out }}"  coupon_num="{{ $item->num }}"
                                            >关闭</button></a>
                                        @else
                                            <button class="btn btn-success change"  data-toggle="modal" href="#update" coupon_id={{ $item->id
                                    }}
                                                    coupon_name="{{ $item->name }}"  coupon_content="{{ $item->content }} "   coupon_type="{{ $item->type }}"   coupon_effective_time="{{ $item->effective_time }}"   coupon_value="{{ $item->value }}"     coupon_condition="{{ $item->prerequisite }}"   coupon_store_id="{{ $item->store_id }}"  coupon_total_num="{{ $item->total_num }}"  coupon_in_num="{{ $item->in_num }}"  coupon_out_num="{{ $item->out_num }}" coupon_stop_out="{{ $item->stop_out }}"  coupon_num="{{ $item->num }}"
                                            >开启</button>
                                        @endif


                                    </td>
                                  </tr>  
                                @endforeach
                                </tbody>
                            </table>
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

<script type="text/javascript">
    

    $(".change").click(function(){


        $("#coupon_id").attr("value",$(this).attr("coupon_id"));
        $("#coupon_stop_out").attr("value",$(this).attr("coupon_stop_out"));
        $("#coupon_name").attr("value",$(this).attr("coupon_name"));
        $("#coupon_content").attr("value",$(this).attr("coupon_content"));
        $("#coupon_condition").attr("value",$(this).attr("coupon_condition"));
        $("#coupon_store_id").attr("value",$(this).attr("coupon_store_id"));
        $("#coupon_total_num").attr("value",$(this).attr("coupon_total_num"));
        $("#coupon_in_num").attr("value",$(this).attr("coupon_in_num"));
        $("#coupon_out_num").attr("value",$(this).attr("coupon_out_num"));


        if($(this).attr("coupon_stop_out")==1)
        {
            $("#coupon_stop_out").parent().removeClass("switch-on");
            $("#coupon_stop_out").parent().addClass("switch-off");

        }
        else
        {

            $("#coupon_stop_out").parent().removeClass("switch-off");
            $("#coupon_stop_out").parent().addClass("switch-on");
        }

        $("#coupon_num").attr("value",$(this).attr("coupon_num"));
        $("#coupon_type").attr("value",$(this).attr("coupon_type"));
        $("#coupon_value").attr("value",$(this).attr("coupon_value"));
        $("#coupon_effective_time").attr("value",$(this).attr("coupon_effective_time"));

    })
</script>
@include('alpha.activity.coupon_moudel')
@include('alpha.moduls.warning')
@include('alpha.footer')
