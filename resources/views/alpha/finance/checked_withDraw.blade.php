@include('alpha.header')

<!--main content start-->
<section id="main-content"> 
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="row">
                            <div class="col-lg-2"> 提现管理</div>
                            <div class="col-lg-4">
                                <form role="form" action="/alpha/finance/checked?page=1" method="get">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="时间格式:1900-01-01 00:00:00 " name="searchTime">
                                        <span class="input-group-btn">
                                  <button class="btn btn-default" type="submit">
                                     搜索
                                  </button>
                               </span>
                                    </div><!-- /input-group -->
                                </form>
                            </div>
                            <div class="col-lg-4"></div>
                        </div>
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
                                            <button   class="btn btn-success payment" data-toggle="modal" href="#pay"
                                                bank_card_num            = "{{ $l->all_bank_card_num }}",
                                                bank_card_holder         = "{{ $l->bank_card_holder }}",
                                                bank_card_type           = "{{ $l->bank_card_type }}",
                                                bank_name                = "{{ $l->bank_name }}",
                                                bank_reserved_telephone  = "{{ $l->bank_reserved_telephone }}",
                                                with_draw_id             ="{{ $l->id}}",
                                                store_id                 = "{{ $l->store_id}}",
                                                withdraw_cash_num        ="{{ $l->withdraw_cash_num}}"
                                            >付款</button>
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


     $(".payment").click(function(){
            $("#bank_card_num").attr("value",$(this).attr("bank_card_num"));
            $("#bank_card_holder").attr("value",$(this).attr("bank_card_holder"));
            $("#bank_card_type").attr("value",$(this).attr("bank_card_type"));
            $("#bank_name").attr("value",$(this).attr("bank_name"));
            $("#bank_reserved_telephone").attr("value",$(this).attr("bank_reserved_telephone"));
            $("#with_draw_id").attr("value",$(this).attr("with_draw_id"));
            $("#store_id").attr("value",$(this).attr("store_id"));
            $("#withdraw_cash_num").attr("value",$(this).attr("withdraw_cash_num"));

     })   

    $("#nonchecked").click(function(){
        $("#withdrawId").attr("value",$(this).attr("data-withdrawId"));
       
    })
</script>
@include('alpha.finance.checked_moduls')
@include('alpha.finance.cash_moduls')
@include('alpha.footer')
