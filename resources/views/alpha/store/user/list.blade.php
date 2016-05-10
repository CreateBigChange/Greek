@include('alpha.header')

        <!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        店铺用户信息
                        <div style='margin-left:20px;' class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></div>
                        <div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>
                    </header>
                    <div class="panel-body">
                        <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>帐号</th>
                                    <th>真实名字</th>
                                    <th>手机号</th>
                                    <th>所属店铺</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                                @foreach ($userList as $u)
                                    <tr>
                                        <td>{{ $u->id }}</td>
                                        <td>{{ $u->account }}</td>
                                        <td>{{ $u->real_name }}</td>
                                        <td>{{ $u->tel }}</td>
                                        <td>{{ $u->sname }}</td>
                                        <td>{{ $u->created_at }}</td>
                                        <td>
                                            <div p_id="{{ $u->id }}" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
                                            <a title="删除这条" href="/alpha/store/user/del/{{ $u->id }}" class="btn btn-danger btn-xs"><i class="icon-trash"></i></a>
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

{{--@include('alpha.store.info.info_moduls')--}}

@include('alpha.footer')
