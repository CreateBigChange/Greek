{{--搜索--}}
 <!-- Modal -->
<div class="modal fade" id="pay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">

            <form class="form-horizontal tasi-form" method="get" action='/alpha/finance/finish_withdraw' id="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">提现付款</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">



                            <div class="form-group">
                                <input type="hidden"   class="form-control" value="" name="with_draw_id"  id="with_draw_id" />
                            </div>

                            <div class="form-group">
                                <input type="hidden"   class="form-control" value="" name="store_id"  id="store_id" />
                            </div>

                            <div class="form-group">
                                <input type="hidden"   class="form-control" value="" name="withdraw_cash_num"  id="withdraw_cash_num" />
                            </div>



                            <div class="form-group">
                                <label for='bank_card_num'>卡号</label>

                                <input type="text"   class="form-control" value=""  name ="bank_card_num"  id="bank_card_num"  disabled/>
                            </div>
                            <div class="form-group">
                                <label for='bank_card_holder'>持卡人</label>
                                <input type="text"   class="form-control"  value=""  name="bank_card_holder"  id="bank_card_holder"  disabled />
                            </div>
                            <div class="form-group">
                                <label for='bank_card_type'>银行卡类型</label>
                                <input type="text"   class="form-control"  value=""  name ="bank_card_type"  id="bank_card_type" disabled  />
                            </div>
                            <div class="form-group">
                                <label for='bank_name'>银行名</label>
                                <input type="text"   class="form-control"  value=""  name ="bank_name"  id="bank_name"  disabled />
                            </div>
                            <div class="form-group">
                                <label for='bank_reserved_telephone'>银行服务电话</label>
                                <input type="text"   class="form-control"  value=""  name ="bank_reserved_telephone" id="bank_reserved_telephone" disabled/>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="submit" >完成</button>
                </div>
            </form>

        </div>
    </div>
</div>
<script type="text/javascript">
$("#submit").click(function(){
    $("#form").submit();
})
</script>
<!-- modal -->