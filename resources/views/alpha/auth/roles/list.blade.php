@include('alpha.header')
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<!-- page start-->
		<div class="row">
			<div class="col-lg-6">
				<section class="panel">
					<header class="panel-heading">
						角色列表<a style='margin-left:20px;' class="btn btn-primary btn-xs" data-toggle="modal" href="#add"><i class="icon-plus"></i></a>
					</header>
					<table class="table table-striped table-advance table-hover">
						<thead>
						<tr>
							<th><i class="icon-bullhorn"></i>名称</th>
							<th><i class="icon-bullhorn"></i>描述</th>
							<th>操作</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($roles as $r)
							<tr>
								<td>{{ $r->name }}</td>
								<td>{{ $r->description }}</td>
								<td>
									<div class="btn btn-primary btn-xs eye" role_id="{{ $r->id }}" role_name="{{ $r->name }}" ><i class="icon-eye-open"></i></div>
									<div role_id="{{ $r->id }}" data-toggle="modal" href="#edit" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
									<a href="/alpha/role/del/{{ $r->id }}" class="btn btn-danger btn-xs"><i class="icon-trash "></i></a>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</section>
			</div>
			
			<div class="col-lg-6" id="permissions">
			</div>
		</div>

	</section>

</section>
<!--main content end-->

@include('alpha.auth.roles.role_moduls')

<script id='permissionsList' type='text/html'>
	<section class="panel">
		<header class="panel-heading" role_id="<%= role.id %>" id="permissionRoleHeader">
			<%= role.name %> 权限列表<span style="color:#f00;font-size:5px;">(勾选的为可以访问,反之)</span>
		</header>
		<div class="panel-body">
			<section id="unseen">
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th></th>
							<th>名称</th>
							<th>地址</th>
							<th>描述</th>
						</tr>
					</thead>
					<tbody>
						<% for (var i=0 ; i<permissions.length ; i++){ %>
							<tr>
								<td>
									<div class="<% if(permissions[i].is_role){%> check_on <%}else{%> check_off <%}%> select_permission" for="checkbox-01" style="height:22px;width:22px;">
										<input style="position:absolute;left:9999px;" <% if(permissions[i].is_role){%>checked<%}%> name="sample-checkbox-01" id="checkbox-01" value="<%= permissions[i].id %>" type="checkbox">
									</div>	  
								</td>
								<td><i class="<%= permissions[i].icon %>"></i> <%= permissions[i].display_name %></td>
								<td><% if(permissions[i].name != null){%><%= permissions[i].name %><% }%></td>
								<td><%= permissions[i].description %></td>
							</tr>
							<% for (var j=0 ; j<permissions[i].child.length ; j++ ){ %>
								<tr>
									<td>
										<div class="<% if(permissions[i].child[j].is_role){%> check_on <%}else{%> check_off <%}%> select_permission" for="checkbox-01" style="height:22px;width:22px;">
											<input style="position:absolute;left:9999px;" <% if(permissions[i].child[j].is_role){%> checked <%}%> name="sample-checkbox-01" id="checkbox-01" value="<%= permissions[i].child[j].id %>" type="checkbox">
										</div>	  
									</td>
									<td style='padding-left:30px;'>|----- <%= permissions[i].child[j].display_name %></td>
									<td><%= permissions[i].child[j].name %></td>
									<td><%= permissions[i].child[j].description %></td>
								</tr>
							<% } %>
						<% } %>
					</tbody>
				</table>
			</section>
		</div>
	</section>
</script>

<script>
$('.eye').bind('click' , function(){
	var roleId		= $(this).attr('role_id');
	var roleName	= $(this).attr('role_name');
	$.get('/alpha/permission/role/relation/' + roleId , function(data){
		data.role = {
			id : roleId,
			name : roleName
		};
		var bt = baidu.template;
		var html = bt('permissionsList' , data);			
		$('#permissions').html(html);
	});
});

$('#permissions').on('click' , '.select_permission' , function(){
	var _this			= this;
	var _input			= $(_this).find('input');

	var postData	= {
		role_id	 : $('#permissionRoleHeader').attr('role_id'),
		permission_id : $(_this).find('input').val()
	}

	if($(_this).hasClass('check_off')){
		$.post('/alpha/permission/role/add' , postData , function(data){
			if(data.code == '0000'){
				$(_this).removeClass('check_off').addClass('check_on');
				_input.attr('checked' , true);
			}else{
				console.log('操作失败');	
			}
		});
	}else{
		$.post('/alpha/permission/role/delete' , postData , function(data){
			if(data.code == '0000'){
				$(_this).removeClass('check_on').addClass('check_off');
				_input.attr('checked' , false);
			}else{
				console.log('操作失败');	
			}
		})
	}

});

</script>

@include('alpha.footer')
