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
                            @foreach ($storeData as $l)
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>

                                <tr>
                                    <td>ID</td><td>{{ $l['store_id'] }}</td><td>店铺名称</td><td>{{ $l['name'] }}</td><td>申请提现时间</td><td>{{ $l['created_at'] }}</td>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                             


                                    <tr>
                                        <td>上期结算日期</td>
                                        <td>当期结算日期</td>
                                        <td>当期流水</td>
                                        <td>当期收入</td>
                                        <td>当期扣点</td>
                                        <td>上期余额</td>
                                        <td>当期订单数</td>
                                        <td>可提现金额</td>
                                        <td>申请提现金额</td>
                                        <td>当期余额</td>
                                        <td width="100px;">操作</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $l['lastTime'] }}</td>
                                        <td>{{ $l['updated_at'] }}</td>
                                        <td>{{ $l['allmoney'] }}</td>
                                        <td>{{ $l['income'] }}</td>
                                        <td>{{ $l['remain'] }}</td>
                                        <td>{{ $l['balance'] }}</td>
                                         <td>{{ $l['all'] }}</td>
                                        <td>{{ $l['money'] }}</td>
                                       
                                        <td>{{ $l['withdraw_cash_num'] }}</td>
                                        <td>{{ $l['now_balance'] }}</td>
                                        <td>
                                            <div title="审核" class="btn btn-primary btn-xs" id="checked" withdrawId={{ $l['store_id'] }}>审核通过</div>
                                            <div title="审核" class="btn btn-primary btn-xs" data-toggle="modal" href="#check" id="nonchecked" data-withdrawId="{{ $l['store_id'] }}">审核不通过</div>
                                        </td>
                                    </tr>
                               
                                </tbody>
                            </table>
                             @endforeach
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
