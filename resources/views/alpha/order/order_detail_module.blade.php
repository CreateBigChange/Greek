@foreach ($orders  as $order)
<!-- Modal -->
<div class="modal fade" id="detail_{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
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
                                <input type="text" class="form-control" name='consignee' value="{{ $order->consignee }}"  id="other_consignee" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">收货人电话</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name='consignee' value="{{ $order->consignee_tel }}"  id="other_consignee_tel" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">收货人地址</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name='consignee' value="{{ $order->province }}{{ $order->city }}{{ $order->county }}{{ $order->street }}{{ $order->consignee_address }}"  id="other_consignee_tel" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">商店实际收入</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name='consignee_tel' value="{{ $order->store_income }}" id="other_store_income" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">备注</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name='consignee_tel' value="{{ $order->remark }}" id="other_remark" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">退款原因</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name='consignee' value="{{ $order->refund_reason }}" id="other_refund_reason" disabled/>
                            </div>
                        </div>
                    </div>
                </section>
                <table class="table">
                    <thead>
                    <tr>
                        <th>商品ID</th>
                        <th>图片</th>
                        <th>名称</th>
                        <th>品牌</th>
                        <th>规格</th>
                        <th>价格</th>
                        <th>数量</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($order->goods  as $g)
                        <tr class="table" >
                            <td>{{$g->goods_id}}</td>
                            <td><img style="width:50px;height:50px;" src="{{$g->img}}" title="{{$g->name}}"/></td>
                            <td>{{$g->name}}</td>
                            <td>{{$g->b_name}}</td>
                            <td>{{$g->spec}}</td>
                            <td>{{$g->out_price}}</td>
                            <td>{{$g->num}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal-footer"  style="margin-top:0px">
                <button data-dismiss="modal" class="btn btn-default" type="button">关闭</button>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
@endforeach