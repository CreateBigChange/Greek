@include('alpha.header')

        <!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        商品列表<a style='margin-left:20px;' class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></a>
                    </header>
                    <div class="panel-body">
                        <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>名称</th>
                                    <th>分类</th>
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
                                        <td>{{ $g->cname }}</td>
                                        <td>{{ $g->bname }}</td>
                                        <td><img style="width:50px;height:50px;" src="{{ $g->img }}" alt="{{ $g->img }}" title="{{ $g->name }}" /></td>
                                        <td>{{ $g->out_price }}</td>
                                        <td>{{ $g->spec }}</td>
                                        <td>{{ $g->desc }}</td>
                                        <td>@if ($g->is_open == 1) 开启 @else 关闭 @endif</td>
                                        <td>@if ($g->is_checked == 1) 已审核 @else 未审核 @endif</td>
                                        <td>
                                            {{--<div p_id="{{ $g->id }}" title="添加子店铺"  class="btn btn-primary btn-xs addChild" data-toggle="modal" href="#addChild"><i class="icon-plus"></i></div>--}}
                                            <div p_id="{{ $g->id }}" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
                                            <a title="添加帐号" href="/alpha/stores/users" class="btn btn-danger btn-xs"><i class="icon-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </section>
                    </div>
                </section>
            </div>
        </div>

    </section>


</section>
<!--main content end-->

@include('alpha.goods.goods_moduls')

@include('alpha.footer')
