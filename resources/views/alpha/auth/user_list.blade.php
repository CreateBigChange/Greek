@include('alpha.header')
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
	<!-- page start-->
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
			<header class="panel-heading">
			管理员列表<a style='margin-left:20px;' class="btn btn-primary btn-xs" data-toggle="modal" href="#add"><i class="icon-plus"></i></a>
			</header>
			<table class="table table-striped table-advance table-hover">
				<thead>
					<tr>
						<th><i class="icon-bullhorn"></i>手机号</th>
						<th><i class="icon-bullhorn"></i>昵称</th>
						<th><i class="icon-bullhorn"></i>公司</th>
						<th><i class="icon-bullhorn"></i>角色</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($user as $u)
					<tr>
						<td>{{ $u->tel }}</td>
						<td>{{ $u->name }}</td>
						<td>{{ $u->sname }}</td>
						<td style="width:200px">
							@foreach ($u->role as $r)
							<div class="btn btn-primary btn-xs " style="margin-bottom:10px;margin-right:10px;">{{ $r->name }}</div>
							@endforeach
						</td>
						<td>
							<div class="btn btn-primary btn-xs edit" uid="{{ $u->id }}"><i class="icon-pencil"></i></div>
							<a href="/shop/del-user/{{ $u->id }}" class="btn btn-danger btn-xs"><i class="icon-trash "></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			</section>
		</div>
	</div>
	<!-- page end-->


	<!-- Modal -->
	<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal tasi-form" method="post" action='/admin/add-auth-user'>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">添加管理员</h4>
					</div>
					<div class="modal-body">
						<section class="panel">
							<div class="panel-body">
									<div class="form-group">
										<label class="col-sm-2 col-sm-2 control-label">账号</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name='tel' placeholder='最好为手机号'/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 col-sm-2 control-label">昵称</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name='name' />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 col-sm-2 control-label">密码</label>
										<div class="col-sm-10">
											<input type="password" class="form-control" name='password' />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 col-sm-2 control-label">角色</label>
										<div class="col-sm-10">
											@foreach ($role as $r)
											<label class="checkbox-inline">
												<input type="checkbox" name='role[]' id="inlineCheckbox1" value="{{ $r->id }}">{{ $r->name }}
											</label>
											@endforeach
										</div>
									</div>
									<!--
									<div class="form-group text-center" >
										<button type="submit" class="btn btn-success">添加</button>
									</div>
									-->
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



	</section>
</section>
<!--main content end-->

<script id='edit' type="text/html">
<!-- Modal -->
<div class="modal fade in" id="editform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style='display:block'>
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal tasi-form" method="post" action='/admin/edit-auth-user'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">修改管理员</h4>
				</div>
				<div class="modal-body">
					<section class="panel">
						<div class="panel-body">
							<input type="hidden" class="form-control" name="uid" value="<%= user.id %>" />
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">账号</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="tel" placeholder='最好为手机号' value="<%= user.tel %>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">昵称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="name" value="<%= user.name %>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">密码</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" name="password"  />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">角色</label>
								<div class="col-sm-10">
									<% for (var i = 0 ; i < role.length; i++){ %>
									<label class="checkbox-inline">
										<input type="checkbox" name="role[]" <% for (var j=0 ; j<user.group.length ; j++){ if(role[i].id == user.group[j].a_g_id ){ %> checked <%}}%>  id="inlineCheckbox1" value="<%= role[i].id %>" /><%= role[i].name %>
									</label>
									<% } %>
								</div>
							</div>
						</div>
					</section>

				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-default close" type="button">取消</button>
					<button class="btn btn-success" type="submit">修改</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- modal -->
</script>

<script>
$('.edit').bind('click' , function(){
	var uid = $(this).attr('uid');
	$.get('/admin/auth-user-info/' + uid , function(data){
		if(data.code == '0000'){
			var bt = baidu.template;
			var html = bt('edit' , data.data);			
			$('body').append(html);
		}
	})	
});

$('body').on('click' , '.close' , function(){
	$('.modal').hide();
});
</script>

@include('admin.footer')
