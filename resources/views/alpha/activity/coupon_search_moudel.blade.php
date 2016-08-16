{{--搜索--}}
<!-- Modal -->
<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="get" action='/alpha/Activity/coupon'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">搜索</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input class="form-control name"  type="text" name="name"  placeholder="名称"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">内容</label>
                                <div class="col-sm-10">
                                    <input class="form-control"   type="text" name="content" placeholder="内容"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">条件</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="prerequisite"  placeholder="用券条件"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">类型</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="type" >
                                        <option value=0>通用券</option>
                                        <option value=1>减满券</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">商店名</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="store_name"  placeholder="名称"   />
                                </div>
                            </div>
                    </section>
                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="submit">确定</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal -->
