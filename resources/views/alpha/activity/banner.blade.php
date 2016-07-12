@include('alpha.header')
              <!--Advanced File Input start-->
              <div class="row" >
                  <div class="col-md-12">
                      <section class="panel">
                          <header class="panel-heading">
                              Advanced File Input
                                  <span class="tools pull-right">
                                    <a href="javascript:;" class="fa fa-chevron-down"></a>
                                    <a href="javascript:;" class="fa fa-times"></a>
                                </span>
                          </header>
                          <div class="panel-body">
                              <form  class="form-horizontal tasi-form" style="margin-top:100px;height:100%;" enctype="multipart/form-data"  id="uploadForm">
                                      <div class="form-group last">
                                          <label class="control-label col-md-3">Image Upload</label>
                                          <div class="col-md-9">
                                              <div class="fileupload fileupload-new" data-provides="fileupload">	
                                                  <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;" id="showimage" >
                                                  </div>
	                                                   <span class="btn btn-white btn-file">
	                                                   <span class="fileupload-new" id="select" style="width:100%;"><i class="fa fa-paper-clip"></i> Select image</span>
	                                                   <div hidden=1><input type="file" name='img' class="default" id="hiddenform" /></div>
	                                                   </span>
	                                                      <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> 
	                                                      	<select name='vesion' id="vesion">

															@foreach ($list as $li)
															    <option value="{{ $li->id }}">{{ $li->id }}	</option>
															@endforeach

	                                                      	</select>
	                                                      </a>
                                                  </div>
                                              </div>
                                              <span class="label label-danger">NOTE!</span>
                                          </div>
                                      </div>
                                      <div style="width:100%;"><center><button type="button" class="btn btn-success btn-mid btn-block" style="width:50%" id="buttonImg">提交</button></center></div>
                              </form>
                          </div>
                      </section>
                  </div>
              </div>
              <!--Advanced File Input end-->
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

		$("#buttonImg").click(function(){
		    //获取要替换的bannerID
			var BannerId = $("#vesion option:selected").val();
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
					console.log(data);
					var str="str="+data['data']["host"]+"/"+data['data']["key"]+"&id="+BannerId;
					document.location.href='./save?'+str;
					
				},
				error:function(data)
				{
					console.log(data);				
				}
			})
		})
	})
</script>

@include('alpha.moduls.warning')
@include('alpha.footer')
