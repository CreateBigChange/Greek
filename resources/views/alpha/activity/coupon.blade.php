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
                                           <a href="{{ url('/alpha/Activity/couponClose',$item->id)}}"> <button class="btn btn-success "
                                    }}

                                            >关闭</button></a>
                                        @else
                                            <a href="{{ url('/alpha/Activity/couponOpen',$item->id)}}"><button class="btn btn-success "
                                    }}

                                            >开启</button></a>
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
@include('alpha.activity.coupon_moudel')
@include('alpha.moduls.warning')
@include('alpha.footer')
