@include('alpha.header')
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
	<!-- page start-->
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
			<header class="panel-heading">
			节点列表<a style='margin-left:20px;' class="btn btn-primary btn-xs" data-toggle="modal" href="#add"><i class="icon-plus"></i></a>
			</header>
			@foreach ($node as $n)
			<div class="col-sm-6" style="margin-top:10px;">
				<section class="panel">
					<header class="panel-heading">
						 {{ $n->name }} 
					</header>
					<div class="panel-body">
						@foreach ($n->child as $c)
						@if ($c->is_menu)
						<button type="button" class="btn btn-primary">{{ $c->name }}</button>
						@else
						<button type="button" class="btn btn-default">{{ $c->name }}</button>
						@endif
						@endforeach
						<button style='margin-left:20px;' class="btn btn-info add-permission-child" pid={{ $n->id }} parent="{{ $n->name }}"><i class="icon-plus"></i></button>
					</div>
				</section>
			</div>
			@endforeach	
			</section>
		</div>
	</div>
	<!-- page end-->


	<!-- Modal -->
	<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal tasi-form" method="post" action='/admin/add-auth-permission'>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">添加节点</h4>
					</div>
					<div class="modal-body">
						<section class="panel">
							<div class="panel-body">
									<div class="form-group">
										<label class="col-sm-2 col-sm-2 control-label">名称</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name='name' />
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

	<!-- Modal -->
	<div class="modal fade" id="add-child" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal tasi-form" method="post" action='/admin/add-auth-permission'>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id='parent'>添加节点</h4>
					</div>
					<div class="modal-body">
						<section class="panel">
							<div class="panel-body">
									<input type="hidden" class="form-control" name='pid' id='pid' />
									<div class="form-group">
										<label class="col-sm-2 col-sm-2 control-label">名称</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name='name' />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 col-sm-2 control-label">node</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name='node' />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 col-sm-2 control-label">是否在左侧菜单显示</label>
										<div class="col-sm-1 text-center">
											<div class="switch has-switch" style='overflow:auto'>
												<input type="checkbox" checked data-toggle="switch" name='is_menu' />
											</div>
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
						<button data-dismiss="modal" class="btn btn-default close" type="button">取消</button>
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

<script>
$('.add-permission-child').bind('click' , function(){
	var pid		= $(this).attr('pid');
	var parent	= $(this).attr('parent');

	$('#pid').val(pid);
	$('#parent').html("添加"+parent+"的子节点");
	$('#add-child').addClass('in').show();
});

$('.close').bind('click' , function(){
	$('#add-child').removeClass('in').hide();
});
</script>
@include('alpha.footer')
