{{--修改--}}
 <!-- Modal -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="post" action='/alpha/Activity/couponUpdate'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">修改</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">

                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">名字</label>
                                <div class="col-sm-10">
                                        <input class="form-control name" type="text" name="name"  id="coupon_name"/>
                                </div>
                            </div>
                                <input  class="form-control name" type="hidden" name="coupon_id"  id="coupon_id" value=""/>
                              
                            
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">内容</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="content" placeholder="内容/字符"  id="coupon_content"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">类型</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="type" id="coupon_type">
                                        <option value=1>减满券</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">有效期</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="effective_time"  placeholder="有效期/数字" id="coupon_effective_time" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">价值</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="value" placeholder="价值/数字" id="coupon_value" value=""/>
                                </div>
                            </div>
                           <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">条件</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="prerequisite"  placeholder="条件/数字" id="coupon_condition"/>
                                </div>

                           </div>
                           <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">total_num</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="total_num" id="coupon_total_num"/>
                                </div>

                           </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">in_num</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="in_num" id="coupon_in_num"/>
                                </div>

                           </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">out_num</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="out_num" id="coupon_out_num"/>
                                </div>

                           </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">stop_out</label>
                                <div class="col-sm-10">
                                        <select class="form-control" name="stop_out" id="coupon_stop_out">
                                            <option value=1>1</option>
                                             <option value=0>0</option>
                                        </select>
                                </div>

                            </div>
                          <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">num</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="num"  id="coupon_num"/>
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
                                        <input class="form-control name" type="text" name="name" />
                                </div>
                            </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">内容</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="content" placeholder="内容/字符" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">类型</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="type">
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
                               <label class="col-sm-2 col-sm-2 control-label">条件</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="prerequisite"  placeholder="条件/数字" />
                                </div>

                           </div>
                           <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">total_num</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="total_num" />
                                </div>

                           </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">in_num</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="in_num" />
                                </div>

                           </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">out_num</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="out_num" />
                                </div>

                           </div>
                            <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">stop_out</label>
                                <div class="col-sm-10">
                                        <select class="form-control" name='stop_out'>
                                            <option value=1>1</option>
                                             <option value=0>0</option>
                                        </select>
                                </div>

                            </div>
                          <div class="form-group">
                               <label class="col-sm-2 col-sm-2 control-label">num</label>
                                <div class="col-sm-10">
                                        <input class="form-control" type="text" name="num" />
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