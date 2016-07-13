<!-- Modal -->
<div class="modal fade" id="addChild" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal tasi-form" method="post" action='/alpha/permission/add'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">添加子节点</h4>
				</div>
				<div class="modal-body">
					<section class="panel" style="margin-bottom:0px">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">上一级名称</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" name='fname' id='add_fname' />
									<input type="hidden" class="form-control" name='fid' id='add_fid' />
									<input type="hidden" class="form-control level" name='level' value='1' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='name' />
								</div>
							</div>
							{{--<div class="form-group">--}}
								{{--<label class="col-sm-2 col-sm-2 control-label">图标</label>--}}
								{{--<div class="col-sm-10">--}}
									{{--<input type="text" class="form-control" name='icon' />--}}
								{{--</div>--}}
							{{--</div>--}}
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">链接</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='url' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">排序</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='sort' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">菜单显示</label>
								<div class="col-sm-1 text-center">
									<input type="checkbox" data-toggle="switch" name='is_menu' />
								</div>
							</div>
						</div>
					</section>

				</div>
				<div class="modal-footer"  style="margin-top:0px">
					<button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
					<button class="btn btn-success" type="submit">添加</button>
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
			<form class="form-horizontal tasi-form" method="post" action='/alpha/permission/add'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">添加顶级节点</h4>
				</div>
				<div class="modal-body">
					<section class="panel" style="margin-bottom:0px">
						<div class="panel-body">
							<!--顶级节点默认fid为0;is_menu为1 -->
							<input type="hidden" class="form-control" name='fid' value='0' />
							<input type="hidden" class="form-control" name='level' value='0' />
							<input type="hidden" class="form-control" name='is_menu' value='on' />
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='name' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">图标</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='icon' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">链接</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='url' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">排序</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='sort' />
								</div>
							</div>
						</div>
					</section>

				</div>
				<div class="modal-footer"  style="margin-top:0px">
					<button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
					<button class="btn btn-success" type="submit">添加</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- modal -->




<!-- Modal -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal tasi-form updataform" method="post" action='/alpha/permission/update'>

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">修改顶级节点</h4>
				</div>
				<div class="modal-body">
					<section class="panel" style="margin-bottom:0px">
						<div class="panel-body">
							<!--顶级节点默认fid为0;is_menu为1 -->
							<input type="hidden" class="form-control updataform" name='id' id='update_id'  value="" />
							<input type="hidden" class="form-control" name='fid' value='0' />
							<input type="hidden" class="form-control" name='is_menu' value='on' />
	
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='name' id='update_name' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">图标</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='icon' id='update_icon' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">链接</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='url' id='update_child_icon' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">排序</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='sort' id='update_sort' />
								</div>
							</div>
						</div>
					</section>

				</div>
				<div class="modal-footer"  style="margin-top:0px">
					<button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
					<button class="btn btn-success" type="submit" >修改</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- modal -->


<!-- Modal -->
<div class="modal fade" id="updateChild" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal tasi-form " method="post" action='/alpha/permission/update'>
				
		
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">修改子节点</h4>
				</div>
				<div class="modal-body">
					<section class="panel" style="margin-bottom:0px">
						<div class="panel-body">
							<input type="hidden" class="form-control" name='id' id='update_child_id' value="" />
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">上级名称</label>
								<div class="col-sm-10">
									<select class="form-control" id='update_f_name' name='fid'>
										<option value='0'>顶级</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label"></label>
								<div class="col-sm-10">
									<input type="hidden" class="form-control updataform" name='id' id='update_child_name1' value="" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='name' id='update_child_name' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">链接</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='url' id='update_child_url' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">排序</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='sort' id='update_child_sort' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">菜单显示</label>
								<div class="col-sm-1 text-center">
									<input type="checkbox" data-toggle="switch" name='is_menu' id='update_child_menu' />
								</div>
							</div>
						</div>
					</section>

				</div>
				<div class="modal-footer"  style="margin-top:0px">
					<button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
					<button class="btn btn-success"  type="submit"   >修改</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- modal -->

<script>
$('.update').bind('click' , function(){
	var pid = $(this).attr('p_id');
	$.get('/alpha/permission/info/' + pid , function(data){
		if(data){
			$('#update_id').val(pid);
			$('#update_name').val(data.name);
			//$('#update_description').val(data.description);
			$('#update_icon').val(data.icon);
			$('#update_sort').val(data.sort);
		}
	});
});

$('.updateChild').bind('click' , function(){

	var pid = $(this).attr('p_id');
	var level = $(this).attr('level');

	$.get('/alpha/permission/info/' + pid , function(data){
		if(data){

			$.get('/alpha/permission/level/' + level , function(top){
				if(top){
					var option = '';
					for(var i=0 ; i<top.length ; i++){
						if(top[i].id == data.fid){
							option += "<option value="+ top[i].id +" selected>"+ top[i].name +"</option>";
						}else{
							option += "<option value="+ top[i].id +">"+ top[i].name +"</option>";
						}
					}
					$('#update_f_name').append(option);
				}
			});
			$('#update_child_id').val(pid);
			$('#update_child_name').val(data.name);
			//$('#update_child_display_name').val(data.display_name);
			//$('#update_child_description').val(data.description);
			$('#update_child_url').val(data.url);
			$('#update_child_sort').val(data.sort);

			if(data.is_menu == 1){
				$('#update_child_menu').attr('checked' , 'checked');
				$('#update_child_menu').parent().removeClass('switch-off').addClass('switch-on');
			}
		}
	});
});

	$('.addChild').bind('click' , function(){
		$('.level').val($(this).attr('level'));
	});
</script>
