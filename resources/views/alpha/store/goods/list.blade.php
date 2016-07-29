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
                                    <th>开启</th>
                                    <th>审核</th>
                                    <th width="100px;">操作</th>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                                @foreach ($goods as $g)
                                    <tr>
                                        <td>{{ $g->id }}</td>
                                        <td>{{ $g->name }}</td>
                                        {{--<td>{{ $g->cccname }}->{{ $g->ccname }}->{{ $g->cname }}</td>--}}
                                        <td>{{ $g->brand_name }}</td>
                                        <td><img style="width:50px;height:50px;" src="{{ $g->img }}" alt="{{ $g->img }}" title="{{ $g->name }}" /></td>
                                        <td>{{ $g->out_price }}</td>
                                        <td>{{ $g->spec }}</td>
                                        <td>{{ $g->desc }}</td>
                                        <td>@if ($g->is_open == 1) 开启 @else 关闭 @endif</td>
                                        <td>@if ($g->is_checked == 1) 已审核 @else 未审核 @endif</td>
                                        <td>
                                            {{--<div p_id="{{ $g->id }}" title="添加子店铺"  class="btn btn-primary btn-xs addChild" data-toggle="modal" href="#addChild"><i class="icon-plus"></i></div>--}}
                                            <div p_id="{{ $g->id }}"   brand_id ="{{ $g->brand_id }}"   category_id ="{{ $g->category_id }}"  data-toggle="modal" href="#update_{{ $g->id }}" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
                                            <div title="删除商品" url="/alpha/goods/del/{{ $g->id }}" href="#warning" data-toggle="modal" class="btn btn-danger btn-xs warning" ><i class="icon-trash"></i></div>
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

@include('alpha.store.goods.moduls')
@include('alpha.moduls.warning')

@include('alpha.footer')
