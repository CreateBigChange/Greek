<!-- Modal -->
<div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form class="form-horizontal tasi-form" method="get" id='form-warning' action=''>
			<div class="modal-content">
				<div class="modal-header" style='background:#FCB322'>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">警告</h4>
				</div>
				<div class="modal-body">
					确定要删除该内容么?
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
					<button class="btn btn-success" type="submit">确定</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- modal -->

<script>
$('.warning').bind('click' , function(){
	$('#form-warning').attr('action' , $(this).attr('url'));
});
</script>
