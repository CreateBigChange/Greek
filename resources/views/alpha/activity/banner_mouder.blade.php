{{--添加--}}


{{--修改--}}
        <!-- Modal -->
<div class="modal fade" id="change"  p_id="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" enctype="multipart/form-data"  id="uploadForm" onsubmit="return false">
                <input type="hidden" name="id" value="" id="edit_id" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">修改banner</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group" style="display:none">
                                <label class="col-sm-2 col-sm-2 control-label">url</label>
                                 <div class="col-sm-10">
                                    <input type="text" class="form-control" name='redirect' id="edit_img"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">redirect</label>
                                 <div class="col-sm-10">
                                    <input type="text" class="form-control" name='redirect' id="edit_redirect"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">图片</label>
                                <div class="col-sm-10">
                                    <div class="file" style="margin-left:15px;">
                                        选择图片
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;" id="showimage" >
                                    </div>
	                                <span class="btn btn-white btn-file">
	                                <span class="fileupload-new" id="select" style="width:100%;"><i class="fa fa-paper-clip"></i> Select image</span>
	                                <div hidden=1><input type="file" name='img' class="default" id="hiddenform" /></div>
	                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">名字</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' id="edit_name"/>
                                </div>
                            </div>
                            <div class="form-group" style="display:none">
                                <label class="col-sm-2 col-sm-2 control-label">创建时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='create_time' id="edit_create_time"/>
                                </div>
                            </div>
                            <div class="form-group" style="display:none">
                                <label class="col-sm-2 col-sm-2 control-label">更新时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='updata_time' id="edit_update_time"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">开启</label>
                                <div class="col-sm-1 text-center">
                                    <input type="checkbox"  data-toggle="switch" name="is_open" checked="false" id="edit_is_open"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">排序</label>
                                <div class="col-sm-1 text-center">
                                    <input type="text"  class="form-control"  name="order" id="edit_order" />
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button" >取消</button>
                    <button style="display:none;width:100px;float:right" class="btn btn-success"   id="subForm">修改</button>
                    <button style="display:none;width:100px;float:right" class="btn btn-success"   id="addBanner">添加</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal -->









<script type="text/javascript">




	$("#select").click(function(){

		$("#hiddenform").click();
	})
	$("#hiddenform").change(function(){
		var file= document.getElementById('hiddenform').files[0];
		if(file)
		{
			var reader=new FileReader();

			reader.onload=function(){
				document.getElementById('showimage').innerHTML='<IMG src="'+this.result+'">';
			}

			reader.readAsDataURL(file);
		}
})

		function bannerChange(baseurl){
	
			var file= document.getElementById('hiddenform').files[0];
			var id=$("#change").attr("p_id");
			var redirect=$("#edit_redirect").val();
			var name=$("#edit_name").val();
			var create_time = $("#edit_create_time").val();
			var update_time = $("#edit_update_time").val();
			var img = $("#edit_img").val();

			if($("#edit_is_open").attr("checked")=="checked")

					var is_open =1
			else
					var is_open =0
			var order = $("#edit_order").val()



			var urlStr="id="+id+"&redirect="+redirect+"&name="+name+"&create_time="+create_time+"&update_time="+update_time+"&is_open="+is_open+"&order="+order+"&img="+img;
	
			if (file) {

					$.ajax({
					dataType:'json',
					type:'post',
					url:'/upload/qiniu',
					cache: false,
	    			data: new FormData($('#uploadForm')[0]),
	   				processData: false,
	    			contentType: false,
					success:function(data)
					{
						
						var str="str="+data['data']["host"]+"/"+data['data']["key"]+"&"+urlStr;
					
						document.location.href='./save?method='+baseurl+'&'+str;
						
					},
					error:function(data)
					{
						

					}
				})
			}
			else
			{
				document.location.href='./save?method='+baseurl+'&'+urlStr;	
			}


		}

		$("#subForm").click(function(){
		
			 baseurl = 'save';
			bannerChange(1);
		})

		$("#addBanner").click(function(){
		
			 baseurl='add';
				bannerChange(0);
		})
</script>

