<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal tasi-form" method="post" action='/alpha/admin/user/update'>
				<input type="hidden" class="form-control" name='id' id="edit_id" value />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">修改管理用户</h4>
				</div>
				<div class="modal-body">
					<section class="panel">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">登录名</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="edit_name" name='account' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">登录密码</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" id="edit_password" name='password' placeholder="不更改密码则不用填写"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">真实名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="edit_real_name" name='real_name' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">邮箱</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="edit_email" name='email' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">角色</label>
								<div class="col-sm-10 rolesParent" id="edit_roles">
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
					<button class="btn btn-success" type="submit">修改</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- modal -->


<!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal tasi-form" method="post" action='/alpha/admin/user/add'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">添加管理员</h4>
				</div>
				<div class="modal-body">
					<section class="panel">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">登录帐号</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" required name='account' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">登录密码</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" required name='password' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">真实名</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" required name='real_name' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">邮箱</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" required name='email' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">角色</label>
								<div class="col-sm-10 rolesParent" id="roles">
								</div>
							</div>
						</div>
					</section>

				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
					<button class="btn btn-success" type="submit">添加</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- modal -->
<script id='rolesList' type='text/html'>
	<% for(var i = 0; i<roles.length ; i++){%>
		<div class="btn btn-primary select_roles" style="margin-right:20px;margin-bottom:10px;">
			<div class="check_off" style="height:22px;width:22px;float:left;margin-right:10px;">
				<input style="position:absolute;left:9999px;" name="roles[]" value="<%= roles[i].id %>" type="checkbox">
			</div>	  
			<span><%= roles[i].name %></span>
		</div>
	<%}%>
</script>

<script id='editRoles' type='text/html'>
	<% for(var i = 0; i<roles.length ; i++){%>
		<div class="btn btn-primary select_roles" style="margin-right:20px;margin-bottom:10px;">
			<div class="<% if(roles[i].is_user == 1){%> check_on <%}else{%> check_off <%}%>" style="height:22px;width:22px;float:left;margin-right:10px;">
				<input style="position:absolute;left:9999px;" <% if(roles[i].is_user == 1){%> checked <%}%> name="roles[]" value="<%= roles[i].id %>" type="checkbox">
			</div>	  
			<span><%= roles[i].name %></span>
		</div>
	<%}%>
</script>

<script>
$('#addUser').click('click' , function(){
	$.get('/alpha/roles/ajax' , function(data){
		if(data.code == '0000'){
			var bt = baidu.template;
			var html = bt('rolesList' , data);			
			$('#roles').html(html);
		}
	})
});

$('.rolesParent').on('click' , '.select_roles' , function(){
	var _this = this;
	var _input			= $(_this).find('input');
	if($(_this).find('div').hasClass('check_off')){
		$(_this).find('div').removeClass('check_off').addClass('check_on');
		_input.attr('checked' , true);
	}else{
		$(_this).find('div').removeClass('check_on').addClass('check_off');
		_input.attr('checked' , false);
	}
})


$('.edit').bind('click' , function(){
	var uid = $(this).attr('uid');
	$.get('/alpha/admin/user/info/' + uid , function(data){
		if(data.code == '0000'){
			$.get('/alpha/roles/user/ajax/' + uid , function(ruData){
				if(ruData.code == '0000'){
					var bt = baidu.template;
					var html = bt('editRoles' , ruData);			
					$('#edit_roles').html(html);
				}
				
				$('#edit_id').val(data.info.id);

				$('#edit_name').val(data.info.name);
				$('#edit_email').val(data.info.email);
				$('#edit_real_name').val(data.info.real_name);
			})
		}
	})	
});
</script>
