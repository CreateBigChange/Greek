{{--搜索--}}
 <!-- Modal -->
<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="get" action='/alpha/goods'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">搜索商品</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">商品名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' id="search_name"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">分类</label>
                                <div class="col-lg-3">
                                    <select class="form-control m-bot15" name='c_one_id' id='search_c_one'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control m-bot15" name='c_two_id' id='search_c_two'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control m-bot15" name='c_id' id='search_category'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">品牌</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-bot15" name='b_id' id='search_brand'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">开启</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-bot15" name='is_open' id='search_brand'>
                                        <option value="-1">全部</option>
                                        <option value="1">开启</option>
                                        <option value="0">关闭</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">审核</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-bot15" name='is_checked' id='search_brand'>
                                        <option value="-1">全部</option>
                                        <option value="1">已审核</option>
                                        <option value="0">未审核</option>
                                        <option value="2">未通过</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="submit">搜索</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal -->

<script id='search_categories' type='text/html'>
    <% for(var i = 0; i<category.length ; i++){%>
        <option value="<%= category[i].id %>"><%= category[i].name %></option>
	<%}%>
</script>

<script id='search_brandTep' type='text/html'>
	<% for(var i = 0; i<brand.length ; i++){%>
		<option value="<%= brand[i].id %>"><%= brand[i].name %></option>
	<%}%>
</script>

<script>
    $('.searchDiaLog').bind('click' , function(){
        $.get('/alpha/goods/category/pid/0' , function(data){
            if(data.code == '0000'){
                var bt = baidu.template;
                var html = '<option value="0">选择</option>' + bt('categories' , data.data);
                $('#search_c_one').html(html);
            }
        });

    });

    $('#search_c_one').bind('change' , function(){
        $.get('/alpha/goods/category/pid/'+$(this).val() , function(data){
            if(data.code == '0000'){
                var bt = baidu.template;
                var html = '<option value="0">选择</option>' + bt('search_categories' , data.data);
                $('#search_c_two').html(html);
                $('#search_category').html('<option value="0">选择</option>');
                $('#search_brand').html('<option value="0">选择</option>');
            }
        });
    });

    $('#search_c_two').bind('change' , function(){
        $.get('/alpha/goods/category/pid/'+ $(this).val() , function(data){
            if(data.code == '0000'){
                var bt = baidu.template;
                var html = '<option value="0">选择</option>' + bt('search_categories' , data.data);
                $('#search_category').html(html);
                $('#search_brand').html('<option value="0">选择</option>');
            }
        });
    });

    $('#search_category').bind('change' , function(){
        $.get('/alpha/goods/brand/'+ $(this).val() , function(data){
            if(data.code == '0000'){
                var bt = baidu.template;
                var html = '<option value="0">选择</option>' + bt('search_brandTep' , data.data);
                $('#search_brand').html(html);
            }
        });
    });

</script>












{{--修改--}}
        <!-- Modal -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="post" action='/alpha/goods/update'>
                <input type="hidden" name="id" value="" id="edit_id" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">修改商品</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">商品名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' id="edit_name"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">分类</label>
                                <div class="col-lg-3">
                                    <select class="form-control m-bot15" name='c_one_id' id='edit_c_one'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control m-bot15" name='c_two_id' id='edit_c_two'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control m-bot15" name='c_id' id='edit_category'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">品牌</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-bot15" name='b_id' id='edit_brand'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">图片</label>
                                <div class="col-sm-10">
                                    <div class="file" style="margin-left:15px;">
                                        选择图片
                                        <input type="text" class="form-control" name='img' id="edit_img"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">预览</label>
                                <div class="col-sm-10">
                                    <img style="width: 200px;height: auto;" id='edit_img_pre' src='' />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">销售价</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='out_price' id="edit_out_price"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">规格</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='spec' id="edit_spec"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">描述</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='desc' id="edit_desc"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">开启</label>
                                <div class="col-sm-1 text-center">
                                    <input type="checkbox"  data-toggle="switch" name="is_open" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">审核</label>
                                <div class="col-sm-1 text-center">
                                    <input type="checkbox"  data-toggle="switch" name="is_checked" />
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

<script id='edit_categories' type='text/html'>
    <% for(var i = 0; i<category.length ; i++){%>
        <option value="<%= category[i].id %>" <% if(select == category[i].id){ %> selected <% } %> ><%= category[i].name %></option>
	<%}%>
</script>

<script id='edit_brandTep' type='text/html'>
	<% for(var i = 0; i<brand.length ; i++){%>
		<option value="<%= brand[i].id %>" <% if(select == brand[i].id){ %> selected <% } %> ><%= brand[i].name %></option>
	<%}%>
</script>

<script>
	$('.update').bind('click' , function(){
		var goodsId = $(this).attr('p_id');
		$.get('/alpha/goods/info/' + goodsId , function(data){
			if(data.code == '0000'){
			    $('#edit_id').val(goodsId);
                $('#edit_name').val(data.data.info.name);
                $('#edit_img').val(data.data.info.img);
                $('#edit_img_pre').attr('src' , data.data.info.img);
                $('#edit_spec').val(data.data.info.spec);
                $('#edit_out_price').val(data.data.info.out_price);
                $('#edit_desc').val(data.data.info.desc);

                $('#update').find('.switch').each(function(index , val){
                	if(index == 0){
                		if(data.data.info.is_open == 1){
                			$(val).bootstrapSwitch('setState', true);
                		}
                	}
                	if(index == 1){
                		if(data.data.info.is_checked == 1){
                			$(val).bootstrapSwitch('setState', true);
                		}
                	}
                });

                $.get('/alpha/goods/category/level/1' , function(cOneData){
					if(cOneData.code == '0000'){
						cOneData.data.select = data.data.info.ccc_id;
						var bt = baidu.template;
						var html = '<option value="0">选择</option>' + bt('edit_categories' , cOneData.data);
						$('#edit_c_one').html(html);

                        $.get('/alpha/goods/category/level/2' , function(cTwoData){
                            if(cTwoData.code == '0000'){
                                cTwoData.data.select = data.data.info.cc_id;
                                var bt = baidu.template;
                                var html = '<option value="0">选择</option>' + bt('edit_categories' , cTwoData.data);
                                $('#edit_c_two').html(html);

                                $.get('/alpha/goods/category/level/3' , function(cData){
                                    if(cData.code == '0000'){
                                        cData.data.select = data.data.info.c_id;
                                        var bt = baidu.template;
                                        var html = '<option value="0">选择</option>' + bt('edit_categories' , cData.data);
                                        $('#edit_category').html(html);

                                        $.get('/alpha/goods/brand/' + data.data.info.c_id , function(brand){
                                            if(brand.code == '0000'){
                                                brand.data.select = data.data.info.b_id;
                                                console.log(brand.data.select);
                                                if(data.code == '0000'){
                                                    var bt = baidu.template;
                                                    var html = '<option value="0">选择</option>' + bt('edit_brandTep' , brand.data);
                                                    $('#edit_brand').html(html);
                                                }
                                            }
                                        });
                                    }
                                });
                            }
                        });
					}
				});
			}
		});

		var img = new Dropzone("#edit_img", {
		    url: "/alpha/upload/qiniu",
		    addRemoveLinks: true,
		    maxFiles: 1,
		    paramName:'img',
		    maxFilesize: 5120,
		    acceptedFiles: ".jpg , .png"
	    });

	    img.on('success' , function(file , data){
		    if(data.code == '0000'){
			    $('#edit_img').val(data.data.host + '/' + data.data.key);
			    $('#edit_img_pre').attr('src' , data.data.host + '/' + data.data.key);
		    }
	    });

		$('#edit_c_one').bind('change' , function(){
            $.get('/alpha/goods/category/pid/'+$(this).val() , function(data){
                if(data.code == '0000'){
                    var bt = baidu.template;
                    var html = '<option value="0">选择</option>' + bt('categories' , data.data);
                    $('#edit_c_two').html(html);
                    $('#edit_category').html('<option value="0">选择</option>');
                    $('#edit_brand').html('<option value="0">选择</option>');
                }
            });
        });

        $('#edit_c_two').bind('change' , function(){
            $.get('/alpha/goods/category/pid/'+ $(this).val() , function(data){
                if(data.code == '0000'){
                    var bt = baidu.template;
                    var html = '<option value="0">选择</option>' + bt('categories' , data.data);
                    $('#edit_category').html(html);
                    $('#edit_brand').html('<option value="0">选择</option>');
                }
            });
        });

        $('#edit_category').bind('change' , function(){
            $.get('/alpha/goods/brand/'+ $(this).val() , function(data){
                if(data.code == '0000'){
                    var bt = baidu.template;
                    var html = '<option value="0">选择</option>' + bt('brandTep' , data.data);
                    $('#edit_brand').html(html);
                }
            });
        });
	});
</script>
















{{--添加--}}
<!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width: 80%;">
		<div class="modal-content">
			<form class="form-horizontal tasi-form" method="post" action='/alpha/goods/add'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">添加商品</h4>
				</div>
				<div class="modal-body">
					<section class="panel" style="margin-bottom:0px">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">商品名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='name' />
								</div>
							</div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">分类</label>
                                <div class="col-lg-3">
                                    <select class="form-control m-bot15" name='c_one_id' id='c_one'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control m-bot15" name='c_two_id' id='c_two'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control m-bot15" name='c_id' id='category'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">品牌</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-bot15" name='b_id' id='brand'>
                                        <option value="0">选择</option>
                                    </select>
                                </div>
                            </div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">图片</label>
								<div class="col-sm-10">
									<div class="file" style="margin-left:15px;">
										选择图片
										<input type="text" class="form-control" name='img' id="img"/>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">预览</label>
								<div class="col-sm-10">
									<img style="width: 200px;height: auto;" id='img_pre' src='' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">销售价</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='out_price' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">规格</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='spec' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">描述</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='desc' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">开启</label>
								<div class="col-sm-1 text-center">
									<input type="checkbox"  data-toggle="switch" checked name="is_open" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">审核</label>
								<div class="col-sm-1 text-center">
									<input type="checkbox"  data-toggle="switch" checked name="is_checked" />
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

<script id='categories' type='text/html'>
	<% for(var i = 0; i<category.length ; i++){%>
		<option value="<%= category[i].id %>"><%= category[i].name %></option>
	<%}%>
</script>

<script id='brandTep' type='text/html'>
	<% for(var i = 0; i<brand.length ; i++){%>
		<option value="<%= brand[i].id %>"><%= brand[i].name %></option>
	<%}%>
</script>

<script>
$('.add').bind('click' , function(){
	$.get('/alpha/goods/category/pid/0' , function(data){
		if(data.code == '0000'){
			var bt = baidu.template;
			var html = '<option value="0">选择</option>' + bt('categories' , data.data);
			$('#c_one').html(html);
		}
	});

	var img = new Dropzone("#img", {
		url: "/alpha/upload/qiniu",
		addRemoveLinks: true,
		maxFiles: 1,
		paramName:'img',
		maxFilesize: 5120,
		acceptedFiles: ".jpg , .png"
	});

	img.on('success' , function(file , data){
		if(data.code == '0000'){
			$('#img').val(data.data.host + '/' + data.data.key);
			$('#img_pre').attr('src' , data.data.host + '/' + data.data.key);
		}
	});

});

$('#c_one').bind('change' , function(){
	$.get('/alpha/goods/category/pid/'+$(this).val() , function(data){
		if(data.code == '0000'){
			var bt = baidu.template;
			var html = '<option value="0">选择</option>' + bt('categories' , data.data);
			$('#c_two').html(html);
			$('#category').html('<option value="0">选择</option>');
            $('#brand').html('<option value="0">选择</option>');
		}
	});
});

$('#c_two').bind('change' , function(){
	$.get('/alpha/goods/category/pid/'+ $(this).val() , function(data){
		if(data.code == '0000'){
			var bt = baidu.template;
			var html = '<option value="0">选择</option>' + bt('categories' , data.data);
			$('#category').html(html);
			$('#brand').html('<option value="0">选择</option>');
		}
	});
});

$('#category').bind('change' , function(){
	$.get('/alpha/goods/brand/'+ $(this).val() , function(data){
		if(data.code == '0000'){
			var bt = baidu.template;
			var html = '<option value="0">选择</option>' + bt('brandTep' , data.data);
			$('#brand').html(html);
		}
	});
});

</script>



