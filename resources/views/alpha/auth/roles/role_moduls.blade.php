<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal tasi-form" method="post" action='/alpha/role/update'>
				<input type="hidden" class="form-control" name='id' id="edit_id" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">修改角色</h4>
				</div>
				<div class="modal-body">
					<section class="panel">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="edit_name" name='name' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">角色描述</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="edit_description" name='description' />
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
			<form class="form-horizontal tasi-form" method="post" action='/alpha/role/add'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">添加角色</h4>
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
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">描述</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='description' />
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


<script>
$('.update').bind('click' , function(){
	var roleId = $(this).attr('role_id');
	$.get('/alpha/role/info/' + roleId , function(data){
		if(data){
			$('#edit_name').val(data.name);
			$('#edit_id').val(data.id);
			$('#edit_description').val(data.description);
		}
	});
});
</script>
