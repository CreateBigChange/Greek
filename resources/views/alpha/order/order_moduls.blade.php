{{--搜索--}}
 <!-- Modal -->
<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="get" action='/alpha/order/{{$url}}' name="search" id="search">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">搜索订单</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <input type="hidden" class="form-control" value="{{$url}}" name='type' />
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">收货人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='consignee' />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">收货电话</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='consignee_tel' />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">订单号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='order_num' />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">店铺名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='store_name' />
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="submit">搜索</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal -->