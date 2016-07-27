{{--搜索--}}
<!-- Modal -->
<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="get" action='/alpha/order/list' name="search" id="search">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">订单详情</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">收货人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='consignee'  id="other_consignee" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">收货人电话</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='consignee'  id="other_consignee_tel" disabled/>
                                </div>
                            </div>                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">拒绝理由</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='consignee' id="other_refund_reason" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">商店实际收入</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='consignee_tel'  id="other_store_income" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">备注</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='consignee_tel'  id="other_remark" disabled/>
                                </div>
                            </div>

                        </div>
                    </section>
                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal -->