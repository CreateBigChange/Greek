@include('alpha.header')

        <!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        店铺分类
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
                                    <th width="100px;">操作</th>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                                @foreach ($storeCategories as $sc)
                                    <tr>
                                        <td>{{ $sc->id }}</td>
                                        <td>{{ $sc->name }}</td>
                                        <td>
                                            <div id="{{ $sc->id }}" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
                                            <a title="删除这条" href="/alpha/store/category/del/{{ $sc->id }}" class="btn btn-danger btn-xs"><i class="icon-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </section>
                    </div>
                    {{--<div class="text-center">--}}
                        {{--{!! $pageHtml !!}--}}
                    {{--</div>--}}
                </section>
            </div>
        </div>

    </section>


</section>
<!--main content end-->

@include('alpha.store.category.category_moduls')

@include('alpha.footer')
