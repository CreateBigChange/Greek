@include('alpha.header')

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        店铺基本信息
                        <div style='margin-left:20px;' class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></div>
                        <div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>
                    </header>
                    <div class="panel-body">
                        <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>店铺名称</th>
                                    <th>当前可提现</th>
                                    <th>当前剩余积分</th>
                                    <th>申请时间</th>
                                    <th>提现金额</th>
                                    <th>状态</th>
                                    <th width="100px;">操作</th>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                                @foreach ($log as $l)
                                    <tr>
                                        <td>{{ $l->name }}</td>
                                        <td>{{ $l->money }}</td>
                                        <td>{{ $l->point }}</td>
                                        <td>{{ $l->created_at }}</td>
                                        <td>{{ $l->withdraw_cash_num }}</td>
                                        <td>@if ($l->status == 1) 提现中 @elseif ($l->status == 2) 审核中 @elseif ($l->status == 3) 未通过 @elseif ($l->status == 0) 完成 @endif</td>
                                        <td>
                                            <div title="审核" class="btn btn-primary btn-xs">审核通过</div>
                                            <div title="审核" class="btn btn-primary btn-xs">审核不通过</div>
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


@include('alpha.footer')
