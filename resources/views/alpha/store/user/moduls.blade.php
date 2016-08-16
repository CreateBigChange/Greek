
{{--搜索--}}
<!-- Modal -->
@foreach($userList as $user)
    <div class="modal fade" id="store_info_{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <form class="form-horizontal tasi-form" method="post" action='/alpha/store/updateStoreInfo'>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">店铺用户信息</h4>
                    </div>
                    <div class="modal-body">
                        <section class="panel" style="margin-bottom:0px">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='id'
                                               value="{{$user->id}}" id="user_id" readonly/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">账号</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='account' id="user_account"
                                               value="{{$user->account}}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">真实名字</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{$user->real_name}}"
                                               name='real_name' id="user_real_name"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">手机号</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{$user->tel}}"
                                               name='tel' id='user_tel'>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">所属店铺</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='sname' id="user_sname"
                                               value="{{$user->sname}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">店铺ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='sid'
                                               id="user_sid"
                                               value="{{$user->sid}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">创建时间</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='created_at'
                                               id="user_created_at"
                                               value="{{$user->created_at}}"/>
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