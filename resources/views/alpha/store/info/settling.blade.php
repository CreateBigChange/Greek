@include('alpha.header')

        <!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        店铺入驻申请
                    </header>
                    <div class="panel-body">
                        <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>联系人</th>
                                    <th>联系电话</th>
                                    <th>地址</th>
                                    {{--<th>状态</th>--}}
                                    <th width="100px;">操作</th>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                                @foreach ($settlings as $s)
                                    <tr>
                                        <td>{{ $s->id }}</td>
                                        <td>{{ $s->name }}</td>
                                        <td>{{ $s->contact }}</td>
                                        <td>{{ $s->province }}{{ $s->city }}{{ $s->county }}{{ $s->address }}</td>
                                        {{--<td>@if ($s->status == 0) 未操作 @elseif ($s->status == 1) 已确认 @else 已完成 @endif</td>--}}
                                        <td>
                                            <a p_id="{{ $s->id }}" title="完成" data-toggle="modal" href="/alpha/stores/settlings/del/{{ $s->id }}" class="btn btn-primary btn-xs update"><i class="icon-ok"></i></a>
                                            {{--<div p_id="{{ $s->id }}" title="创建店铺"  class="btn btn-primary btn-xs addChild" data-toggle="modal" href="#addChild"><i class="icon-plus"></i></div>--}}
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

@include('alpha.footer')
