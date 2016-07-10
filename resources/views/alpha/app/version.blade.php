@include('alpha.header')

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        app列表
                        <div style='margin-left:20px;' class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></div>
                        <div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>
                    </header>

                    <div class="panel-body">
                        <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>系统</th>
                                    <th>版本</th>
                                    <th>下载链接</th>
                                    <th width="100px;">操作</th>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                                @foreach ($version as $v)
                                    <tr>
                                        <td>{{ $v->type }}</td>
                                        <td>{{ $v->version }}</td>
                                        <td>{{ $v->download }}</td>
                                        <td>
                                            {{--<div p_id="{{ $g->id }}" title="添加子店铺"  class="btn btn-primary btn-xs addChild" data-toggle="modal" href="#addChild"><i class="icon-plus"></i></div>--}}
                                            <div p_id="{{ $v->id }}" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
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

@include('alpha.app.moduls')
@include('alpha.moduls.warning')

@include('alpha.footer')
