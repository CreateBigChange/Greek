{{--搜索--}}
 <!-- Modal -->
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="post" enctype="multipart/form-data" action="{{'/alpha/store/goods/import'}}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">导入excel</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">导入文件</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name='file' />
                                </div>
                                <div class="col-sm-10">
                                    <input type="hidden" class="form-control" value ="{{$goods[0]->store_id}}" name='store_id' />
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            请在excle表格里面把一下字段填写完整。
                            c_id,	b_id,	name,	img,	in_price,	out_price,	give_points,	spec,	desc,	stock,	is_open,	is_checked,	is_del,	created_at,	updated_at,	out_num,	store_id,	goods_id,	nav_id,

                        </div>
                    </section>
                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="submit">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal -->