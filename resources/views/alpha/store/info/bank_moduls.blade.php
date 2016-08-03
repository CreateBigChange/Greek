{{--搜索--}}
<!-- Modal -->
@foreach($storeInfos as $storeInfo)
    <div class="modal fade" id="bank_info_{{$storeInfo->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <form class="form-horizontal tasi-form" method="post" action='/alpha/stores/bankinfo'>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">银行卡信息</h4>
                    </div>
                    <div class="modal-body">
                        <section class="panel" style="margin-bottom:0px">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">商店ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='store_id'
                                               value="{{$storeInfo->id}}" id="store_id" readonly/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">银行卡ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='bank_card_id'
                                               value="{{$storeInfo->bank_card_id}}" id="bank_card_id" readonly/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">银行卡号码</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='bank_card_num' id="bank_card_num"
                                               value="{{$storeInfo->bank_card_num}}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">持卡人</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{$storeInfo->bank_card_holder}}"
                                               name='bank_card_holder' id="bank_card_holder"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">银行卡类型</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{$storeInfo->bank_card_type}}"
                                               name='bank_card_type' id='bank_card_type'>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">银行名称</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='bank_name' id="bank_name"
                                               value="{{$storeInfo->bank_name}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">银行预留电话</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='bank_reserved_telephone'
                                               id="bank_reserved_telephone"
                                               value="{{$storeInfo->bank_reserved_telephone}}"/>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                    <div class="modal-footer" style="margin-top:0px">
                        <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                        <button class="btn btn-success" type="submit">修改</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach