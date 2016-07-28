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
                                    <th>品牌</th>
                                    <th>图片</th>
                                    <th>销售价</th>
                                    <th>规格</th>
                                    <th>描述</th>
                                    <th>店铺名称</th>
                                    <th>开启</th>
                                    <th>审核</th>
                                    <th width="100px;">操作</th>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                                @foreach ($goods as $g)
                                    <tr id="tr_{{ $g->id }}">
                                        <td>{{ $g->id }}</td>
                                        <td>{{ $g->name }}</td>
                                        <td>{{ $g->brand_name }}</td>
                                        <td><img style="width:50px;height:50px;" src="{{ $g->img }}" alt="{{ $g->img }}" title="{{ $g->name }}" /></td>
                                        <td>{{ $g->out_price }}</td>
                                        <td>{{ $g->spec }}</td>
                                        <td>{{ $g->desc }}</td>
                                        <td>{{ $g->sname }}</td>
                                        <td>@if ($g->is_open == 1) 开启 @else 关闭 @endif</td>
                                        <td>@if ($g->is_checked == 1) 已审核 @else 未审核 @endif</td>
                                        <td>
                                            <div g_id="{{ $g->id }}" class="btn btn-primary btn-xs check">审核通过</div>
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

<script>
    $('.check').bind('click' , function() {
        var goodsId = $(this).attr('g_id');
        $.post('/alpha/store/goods/check/' + goodsId , {'is_checked' : 1} , function (data) {
            if(data.code == '0000'){
                $('#tr_' + goodsId).remove();
            }
        });
    });
</script>

@include('alpha.footer')
