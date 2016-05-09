{{--搜索--}}
		<!-- Modal -->
<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width: 80%;">
		<div class="modal-content">
			<form class="form-horizontal tasi-form" method="get" action='/alpha/stores/infos'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">搜索店铺</h4>
				</div>
				<div class="modal-body">
					<section class="panel" style="margin-bottom:0px">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">店铺名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='name' id="search_name"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">联系人</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='contacts' id="search_contacts"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">联系电话</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='contact_phone' id="search_contact_phone"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">分类</label>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='c_id' id='search_creategory'>
										<option value="0">选择</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">地区</label>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='province' id='search_province'>
										<option value="0">选择</option>
									</select>
								</div>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='city' id='search_city'>
										<option value="0">选择</option>
									</select>
								</div>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='county' id='search_county'>
										<option value="0">选择</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">地址</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='address' id="search_address"/>
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

<script>
	$('.searchDiaLog').bind('click' , function() {

	});
</script>



{{--修改--}}
<!-- Modal -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width: 80%;">
		<div class="modal-content">
			<form class="form-horizontal tasi-form" method="post" action='/alpha/stores/update'>
				<input type='hidden' name='id' id='edit_id' value="" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">修改店铺</h4>
				</div>
				<div class="modal-body">
					<section class="panel" style="margin-bottom:0px">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">店铺名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='name' id="edit_name"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">店铺类型</label>
								<div class="col-sm-10">
									<select class="form-control m-bot15" name='c_id' id='edit_category'>
										<option value="0">选择</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">身份证</label>
								<div class="col-sm-10">
									<div class="file" style="margin-left:15px;">
										选择图片
										<input type="text" class="form-control" name='id_card_img' id="edit_id_card_img"/>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">预览</label>
								<div class="col-sm-10">
									<img style="width: 200px;height: auto;" id='edit_id_card_img_pre' src='' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">营业执照</label>
								<div class="col-sm-10">
									<div class="file" style="margin-left:15px;">
										选择图片
										<input type="text" class="form-control" name='business_license' id="edit_business_license"/>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">预览</label>
								<div class="col-sm-10">
									<img style="width: 200px;height: auto;" id='edit_business_license_pre' src='' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">联系人</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='contacts' id="edit_contacts"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">联系电话</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='contact_phone' id="edit_contact_phone"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">联系邮箱</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='contact_email' id="edit_contact_email"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">区域</label>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='province' id='edit_province'>
										<option value="0">选择</option>
									</select>
								</div>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='city' id='edit_city'>
										<option value="0">选择</option>
									</select>
								</div>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='county' id='edit_county'>
										<option value="0">选择</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">地址</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='address' id="edit_address"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">店铺描述</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='description' id="edit_description"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">开启</label>
								<div class="col-sm-1 text-center">
									<input type="checkbox"  data-toggle="switch" name="is_open" id="edit_is_open"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">审核</label>
								<div class="col-sm-1 text-center">
									<input type="checkbox"  data-toggle="switch" name="is_checked" id="edit_is_open"/>
								</div>
							</div>
						</div>
					</section>

				</div>
				<div class="modal-footer"  style="margin-top:0px">
					<button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
					<button class="btn btn-success" type="submit">修改</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- modal -->

<script id='edit_categories' type='text/html'>
	<% for(var i = 0; i<storeCategories.length ; i++){%>
		<option value="<%= storeCategories[i].id %>" <% if(select == storeCategories[i].id){ %> selected <% } %> ><%= storeCategories[i].name %></option>
	<%}%>
</script>

<script id='edit_areas' type='text/html'>
	<% for(var i = 0; i<areas.length ; i++){%>
		<option value="<%= areas[i].id %>" <% if(select == areas[i].id){ %> selected <% } %> ><%= areas[i].name %></option>
	<%}%>
</script>

<script>
	$('.update').bind('click' , function(){
		var storeId = $(this).attr('p_id');
		$.get('/alpha/stores/info/' + storeId , function(data){
			if(data.code == '0000'){
				var categoryId 	= data.data.c_id;
				var province 	= data.data.province_id;
				var city 		= data.data.city_id;
				var county 		= data.data.county_id;

				$('#edit_id').val(data.data.id);
				$('#edit_name').val(data.data.name);
				$('#edit_business_license').val(data.data.business_license);
				$('#edit_business_license_pre').attr('src' , data.data.business_license);
				$('#edit_contact_email').val(data.data.contact_email);
				$('#edit_contact_phone').val(data.data.contact_phone);
				$('#edit_contacts').val(data.data.contacts);
				$('#edit_description').val(data.data.description);
				$('#edit_id_card_img').val(data.data.id_card_img);
				$('#edit_id_card_img_pre').attr('src' , data.data.id_card_img);
				$('#edit_address').val(data.data.address);

				$.get('/alpha/stores/categories' , function(categories){
					if(categories){
						categories.select = categoryId;
						var bt = baidu.template;
						var html = bt('edit_categories' , categories);
						$('#edit_category').append(html);
					}
				});

				$.get('/alpha/areas/0' , function(provinceData){
					if(provinceData){
						provinceData.select = province;
						var bt = baidu.template;
						var html = '<option value="0">选择</option>' + bt('edit_areas' , provinceData);
						$('#edit_province').html(html);

						$.get('/alpha/areas/' + province , function(cityData){
							if(cityData){
								cityData.select = city;
								var bt = baidu.template;
								var html = '<option value="0">选择</option>' + bt('edit_areas' , cityData);
								$('#edit_city').html(html);

								$.get('/alpha/areas/' + city , function(countyData){
									if(countyData){
										countyData.select = county;
										var bt = baidu.template;
										var html = '<option value="0">选择</option>' + bt('edit_areas' , countyData);
										$('#edit_county').append(html);
									}
								});
							}
						});
					}
				});

                $('.switch').each(function(index , val){
                	if(index == 0){
                		if(data.data.is_open == 1){
                			$(val).bootstrapSwitch('setState', true);
                		}
                	}
                	if(index == 1){
                		if(data.data.is_checked == 1){
                			$(val).bootstrapSwitch('setState', true);
                		}
                	}
                }); 
			}
		});
		
		var edit_business = new Dropzone("#edit_business_license", {
			url: "/alpha/upload",
			addRemoveLinks: true,
			maxFiles: 1,
			paramName:'img',
			maxFilesize: 5120,
			acceptedFiles: ".jpg , .png"
		});

		edit_business.on('success' , function(file , data){
			if(data.code == '0000'){
				$('#edit_business_license').val(data.data);
				$('#edit_business_license_pre').attr('src' , data.data);
			}
		});

		var edit_idCard = new Dropzone("#edit_id_card_img", {
			url: "/alpha/upload",
			addRemoveLinks: true,
			maxFiles: 1,
			paramName:'img',
			maxFilesize: 5120,
			acceptedFiles: ".jpg , .png"
		});

		edit_idCard.on('success' , function(file , data){
			if(data.code == '0000'){
				$('#edit_id_card_img').val(data.data);
				$('#edit_id_card_img_pre').attr('src' , data.data);
			}
		});

		$('#edit_province').bind('change' , function(){
			$.get('/alpha/areas/'+$(this).val() , function(data){
				if(data){
					var bt = baidu.template;
					var html = '<option value="0">选择</option>' + bt('areas' , data);
					$('#edit_city').html(html);
					$('#edit_county').html('<option value="0">选择</option>');
				}
			});
		});

		$('#edit_city').bind('change' , function(){
			$.get('/alpha/areas/'+ $(this).val() , function(data){
				if(data){
					var bt = baidu.template;
					var html = '<option value="0">选择</option>' + bt('areas' , data);
					$('#edit_county').html(html);
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
			<form class="form-horizontal tasi-form" method="post" action='/alpha/stores/add'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">添加店铺</h4>
				</div>
				<div class="modal-body">
					<section class="panel" style="margin-bottom:0px">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">店铺名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='name' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">店铺类型</label>
								<div class="col-sm-10">
									<select class="form-control m-bot15" name='c_id' id='category'>
										<option value="0">选择</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">身份证</label>
								<div class="col-sm-10">
									<div class="file" style="margin-left:15px;">
										选择图片
										<input type="text" class="form-control" name='id_card_img' id="id_card_img"/>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">预览</label>
								<div class="col-sm-10">
									<img style="width: 200px;height: auto;" id='id_card_img_pre' src='' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">营业执照</label>
								<div class="col-sm-10">
									<div class="file" style="margin-left:15px;">
										选择图片
										<input type="text" class="form-control" name='business_license' id="business_license"/>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">预览</label>
								<div class="col-sm-10">
									<img style="width: 200px;height: auto;" id='business_license_pre' src='' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">联系人</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='contacts' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">联系电话</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='contact_phone' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">联系邮箱</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='contact_email' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">区域</label>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='province' id='province'>
										<option value="0">选择</option>
									</select>
								</div>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='city' id='city'>
										<option value="0">选择</option>
									</select>
								</div>
								<div class="col-lg-3">
									<select class="form-control m-bot15" name='county' id='county'>
										<option value="0">选择</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">地址</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='address' />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label">店铺描述</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name='description' />
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

<script id='categories' type='text/html'>
	<% for(var i = 0; i<storeCategories.length ; i++){%>
		<option value="<%= storeCategories[i].id %>"><%= storeCategories[i].name %></option>
	<%}%>
</script>

<script id='areas' type='text/html'>
	<% for(var i = 0; i<areas.length ; i++){%>
		<option value="<%= areas[i].id %>"><%= areas[i].name %></option>
	<%}%>
</script>

<script>
$('.add').bind('click' , function(){
	$.get('/alpha/stores/categories' , function(data){
		if(data){
			var bt = baidu.template;
			var html = bt('categories' , data);
			$('#category').append(html);
		}
	});
	$.get('/alpha/areas/0' , function(data){
		if(data){
			var bt = baidu.template;
			var html = '<option value="0">选择</option>' + bt('areas' , data);
			$('#province').html(html);
		}
	});

	var business = new Dropzone("#business_license", {
		url: "/alpha/upload",
		addRemoveLinks: true,
		maxFiles: 1,
		paramName:'img',
		maxFilesize: 5120,
		acceptedFiles: ".jpg , .png"
	});

	business.on('success' , function(file , data){
		if(data.code == '0000'){
			$('#business_license').val(data.data);
			$('#business_license_pre').attr('src' , data.data);
		}
	});

	var idCard = new Dropzone("#id_card_img", {
		url: "/alpha/upload",
		addRemoveLinks: true,
		maxFiles: 1,
		paramName:'img',
		maxFilesize: 5120,
		acceptedFiles: ".jpg , .png"
	});

	idCard.on('success' , function(file , data){
		if(data.code == '0000'){
			$('#id_card_img').val(data.data);
			$('#id_card_img_pre').attr('src' , data.data);
		}
	});
});

$('#province').bind('change' , function(){
	$.get('/alpha/areas/'+$(this).val() , function(data){
		if(data){
			var bt = baidu.template;
			var html = '<option value="0">选择</option>' + bt('areas' , data);
			$('#city').html(html);
			$('#county').html('<option value="0">选择</option>');
		}
	});
});

$('#city').bind('change' , function(){
	$.get('/alpha/areas/'+ $(this).val() , function(data){
		if(data){
			var bt = baidu.template;
			var html = '<option value="0">选择</option>' + bt('areas' , data);
			$('#county').html(html);
		}
	});
});

</script>



