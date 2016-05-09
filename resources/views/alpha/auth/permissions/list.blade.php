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
					<div class="panel-body">
						<section id="unseen">
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>名称</th>
										<th>链接</th>
										<th>排序</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody class="dragsort">
									@foreach ($permissions as $p)
										<tr>
											<td><i class="{{ $p->icon }}"></i> {{ $p->name }}</td>
											<td>{{ preg_replace('/\,/' , '<br />' ,$p->url) }}</td>
											<td>{{ $p->sort }}</td>
											<td>
												<a p_id="{{ $p->id }}" display_name="{{ $p->name }}" level="1" class="btn btn-primary btn-xs addChild" data-toggle="modal" href="#addChild"><i class="icon-plus"></i></a>
												<div p_id="{{ $p->id }}" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
												<a url="/alpha/permission/del/{{ $p->id }}" data-toggle="modal" href="#warning" class="btn btn-danger btn-xs warning"><i class="icon-trash "></i></a>
											</td>
										</tr>
										@foreach ($p->child as $c)
											<tr>
												<td style='padding-left:30px;'>|----- {{ $c->name }}</td>
												<td>{!!   preg_replace('/\,/' , '<br>' ,$c->url) !!}</td>
												<td>{{ $c->sort }}</td>
												<td>
													<a p_id="{{ $c->id }}" display_name="{{ $c->name }}" level="2" class="btn btn-primary btn-xs addChild" data-toggle="modal" href="#addChild"><i class="icon-plus"></i></a>
													<div p_id="{{ $c->id }}" level="0" data-toggle="modal" href="#updateChild" class="btn btn-primary btn-xs updateChild"><i class="icon-pencil"></i></div>
													<a url="/alpha/permission/del/{{ $c->id }}" data-toggle="modal" href="#warning" class="btn btn-danger btn-xs warning"><i class="icon-trash "></i></a>
												</td>
											</tr>
											@foreach ($c->child as $cc)
												<tr>
													<td style='padding-left:60px;'>|----- {{ $cc->name }}</td>
													<td>{!! preg_replace('/\,/' , '<br>' ,$cc->url) !!} </td>
													<td>{{ $cc->sort }}</td>
													<td>
														<div p_id="{{ $cc->id }}" level="1" data-toggle="modal" href="#updateChild" class="btn btn-primary btn-xs updateChild"><i class="icon-pencil"></i></div>
														<a url="/alpha/permission/del/{{ $cc->id }}" data-toggle="modal" href="#warning" class="btn btn-danger btn-xs warning"><i class="icon-trash "></i></a>
													</td>
												</tr>
											@endforeach
										@endforeach
									@endforeach
								</tbody>
							</table>
						</section>
					</div>
				</section>
			</div>
		</div>

	</section>


</section>
<!--main content end-->

@include('alpha.auth.permissions.permission_moduls')

@include('alpha.moduls.warning')

<script>
$('.addChild').bind('click' , function(){
	var permissionId	= $(this).attr('p_id');
	var displayName		= $(this).attr('display_name');

	$('#add_fname').val(displayName);
	$('#add_fid').val(permissionId);
	
});
</script>

<!--
<script>

$('.dragsort').dragsort({
	dragEnd : function(){
		console.log($(this));  //拖动完成的回调函数，$(this)当前拖动对象
	},
	scrollSpeed:0,  //默认
});
	
</script>
-->

@include('alpha.footer')
