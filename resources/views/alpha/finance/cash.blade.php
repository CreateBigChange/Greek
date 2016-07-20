@include('alpha.header')

<!--main content start-->
<section id="main-content"> 
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        提现管理
                        <div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>
                    </header>
                    <div class="panel-body">
                         <section id="unseen">

                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>

                                    <td>店铺名称</td>
                                    <td>提现时间</td>
                                    <td>申请金额</td>
                                    <td>可提现金额(扣点后)</td>
                                    <td>账户余额</td>
                                    <td>提现账户</td>
                                    <td >操作</td>

                                </tr>

                                </thead>
                                <tbody class="dragsort">

                                    @foreach ($log as $l)
                                    <tr>
                                        <td>{{ $l->name}}</td>
                                        <td>{{ $l->created_at }}</td>
                                        <td>{{ $l->withdraw_cash_num }}</td>
                                        <td>{{ $l->money }}</td>
                                        <td>{{ $l->balance }}</td>
                                        <td>{{ $l->bank_card_num }}</td>
                                        <td>
                                            <div title="审核" class="btn btn-primary btn-xs" id="checked" withdrawId={{ $l->store_id }}>审核通过</div>
                                            <div title="审核" class="btn btn-primary btn-xs" data-toggle="modal" href="#check" id="nonchecked" data-withdrawId="{{ $l->store_id }}">审核不通过</div>

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
    $("#nonchecked").click(function(){
        $("#withdrawId").attr("value",$(this).attr("data-withdrawId"));
       
    })
</script>
@include('alpha.finance.cash_moduls')
@include('alpha.footer')
