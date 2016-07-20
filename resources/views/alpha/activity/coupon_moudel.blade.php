
{{--增加--}}
 <!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="post" action='/alpha/Activity/couponAdd'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">增加</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">

                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                        <input class="form-control name"  type="text" name="name" value="通用券" placeholder="通用券"/>
                                </div>
                            </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">内容</label>
                                <div class="col-sm-10">
                                        <input class="form-control"   type="text" name="content" placeholder="内容/字符" id="content" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">条件</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="prerequisite"  placeholder="条件/数字"  value id="prerequisite"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">类型</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="type" id="typeChange">
                                        <option >全部</option>
                                        <option value=1>减满券</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">有效期</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="effective_time"  placeholder="有效期/数字"/>
                                </div>
                            </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">价值</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="value" placeholder="价值/数字"/>
                                </div>
                            </div>
                           <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">总数目</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="total_num" id="allNum"/>
                                </div>
                           </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">开启</label>
                                 <div class="switch-on switch-animate">
                                    <input type="checkbox" data-toggle="switch"  name="stop_out" id='stop_out'>
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
<script>
    $("#typeChange").change(function(){
        if($(this).attr("value")==1)
        {
                $("#content").val("减满"+$("#prerequisite").val()+"可用");
               $("#content").attr("value","减满"+$("#prerequisite").val()+"可用");
        }
    })
    $("#prerequisite").change(function () {
        if( $("#typeChange").attr("value")==1)
        {
            $("#content").val("减满"+$("#prerequisite").val()+"可用");
            $("#content").attr("value","减满"+$("#prerequisite").val()+"可用");
        }
    })
</script>