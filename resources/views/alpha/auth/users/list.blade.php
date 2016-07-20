@include('alpha.header')
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
				<header class="panel-heading">
				管理员列表<a style='margin-left:20px;' id="addUser" class="btn btn-primary btn-xs" data-toggle="modal" href="#add"><i class="icon-plus"></i></a>
				</header>
				<table class="table table-striped table-advance table-hover">
					<thead>
						<tr>
							<th><i class="icon-bullhorn"></i>登录帐号</th>
							<th><i class="icon-bullhorn"></i>真实名称</th>
							<th><i class="icon-bullhorn"></i>邮箱</th>
							<th><i class="icon-bullhorn"></i>角色</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($user as $u)
						<tr>
							<td>{{ $u->account }}</td>
							<td>{{ $u->real_name }}</td>
							<td>{{ $u->email }}</td>
							<td style="width:200px">
								@foreach ($u->role as $r)
								<div class="btn btn-primary btn-xs " style="margin-bottom:10px;margin-right:10px;">{{ $r->name }}</div>
								@endforeach
							</td>
							<td>
								<div class="btn btn-primary btn-xs edit" uid="{{ $u->id }}" data-toggle="modal" href="#edit"><i class="icon-pencil"></i></div>
								<a href="/alpha/admin/user/del/{{ $u->id }}" class="btn btn-danger btn-xs"><i class="icon-trash "></i></a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				</section>
			</div>
		</div>
		<!-- page end-->
	</section>
</section>
<!--main content end-->

@include('alpha.auth.users.admin_user_moduls')

@include('alpha.footer')
