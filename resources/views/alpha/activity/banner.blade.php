@include('alpha.header')

<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">
						轮播图基本信息
						<div style='margin-left:20px;' class="btn btn-primary btn-xs add" data-toggle="modal" id="add" href="#change"><i class="icon-plus"></i></div>
						<div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>
					</header>
					<div class="panel-body">
						<section id="unseen">
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>id</th>
										<!--<th>url</th>-->
										<th>redirect</th>
										<th>图片</th>
										<th>名字</th>
										<th>创建时间</th>
										<th>更新时间</th>
										<th>是否开启</th>
										<th>排序</th>
										<th width="100px;">操作</th>
									</tr>
								</thead>
								<tbody class="dragsort">
									@foreach ($list as $si)
										<tr >
											<td>{{ $si->id }}</td>
											<!--<td>{{ $si->img }}</td>-->
											<td>{{ $si->redirect }}</td>
											<td><img style="width:50px;height:50px;" src="{{ $si->img }}"  /></td>
											<td>{{ $si->name }}</td>
											<td>{{ $si->created_at }}</td>
											<td>{{ $si->updated_at }}</td>
											<td>{{ $si->is_open }}</td>
											<td>{{ $si->sort}}</td>
											<td>
												<div class="update" p_order={{$si->sort}}  p_is_open={{ $si->is_open }}  p_updateed_at = "{{ $si->updated_at }}" p_created_at="{{ $si->created_at }}" p_name="{{ $si->name }}" p_redirect="{{ $si->redirect }}" p_img="{{ $si->img }}" p_id="{{ $si->id }}" title="修改"  class="btn btn-primary btn-xs addChild" data-toggle="modal" href="#change"><button class="btn btn-success"><i class="icon-plus">修改</i></button></div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</section>
					</div>
					<div class="text-center">
						{!! $pageHtml !!}
					</div>
				</section>
			</div>
		</div>

	</section>

</section>
<script type="text/javascript">
	
	$(".update").click(function(){


	
		$("#addBanner").css("display","none");
		$("#subForm").css("display","block");


		$("#change").attr("p_id",$(this).attr("p_id"));
		$("#edit_redirect").attr("value",$(this).attr("p_redirect"));
		document.getElementById('showimage').innerHTML="<img src='"+$(this).attr("p_img")+"'>";
		$("#edit_name").attr("value",$(this).attr("p_name"));
		$("#edit_created_time").attr("value",$(this).attr("p_created_at"));
		$("#edit_update_time").attr("value",$(this).attr("p_updateed_at"));
		$("#edit_img").attr("value",$(this).attr("p_img"));
			if($(this).attr("p_is_open")==1)
						$("#edit_is_open").attr("checked","true");
			else
						$("#edit_is_open").attr("checked","false");
		$("#edit_order").attr("value",$(this).attr("p_order"))
	})	

	$("#add").click(function(){
		$("#addBanner").css("display","block");
		$("#subForm").css("display","none");

	})
</script>
<!--main content end-->

@include('alpha.activity.banner_mouder')

@include('alpha.footer')