
{{--修改--}}
        <!-- Modal -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="post" action='/alpha/stores/category/update'>
                <input type="hidden" class="form-control" name='id' id="edit_id" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">修改分类</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' id="edit_name" />
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="submit">添加</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal -->

<script>
    $('.update').bind('click' , function() {
        var cid = $(this).attr('id');

        $.get("/alpha/stores/category/info/" + cid , function(data){
            if(data){
                $('#edit_id').val(data.category.id);
                $('#edit_name').val(data.category.name);
            }
        });
    });

</script>


{{--添加--}}
<!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="post" action='/alpha/stores/category/add'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">添加分类</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' />
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="submit">添加</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal -->




