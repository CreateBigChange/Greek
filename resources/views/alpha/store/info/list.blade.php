@include('alpha.header')

<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">
						店铺基本信息<a style='margin-left:20px;' class="btn btn-primary btn-xs" data-toggle="modal" href="#add"><i class="icon-plus"></i></a>
					</header>
					<div class="panel-body">
						<section id="unseen">
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>id</th>
										<th>名称</th>
										<th>店主</th>
										<th>店主联系方式</th>
										<th>身份证</th>
										<th>营业执照</th>
										<th>地址</th>
										<th>审核</th>
										<th>状态</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody class="dragsort">
									@foreach ($storeInfos as $si)
										<tr>
											<td>{{ $si->id }}</td>
											<td>{{ $si->name }}</td>
											<td>{{ $si->legal_person }}</td>
											<td>{{ $si->legal_person_phone }}</td>
											<td>{{ $si->id_card }}</td>
											<td><img style="width:50px;height:50px;" src="{{ $si->business_license }}" alt="{{ $si->business_license }}" title="{{ $si->name }}" /></td>
											<td>{{ $si->address }}{{ $si->address_detail }}</td>
											<td>{{ $si->is_checked }}</td>
											<td>{{ $si->is_open }}</td>
											<td>
												<div p_id="{{ $si->id }}" title="添加子店铺"  class="btn btn-primary btn-xs addChild" data-toggle="modal" href="#addChild"><i class="icon-plus"></i></div>
												<div p_id="{{ $si->id }}" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
												<div url="/alpha/store/info/del/{{ $si->id }}" data-toggle="modal" href="#warning" class="btn btn-danger btn-xs warning"><i class="icon-trash "></i></div>
											</td>
										</tr>
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

@include('alpha.store.info.info_moduls')

@include('alpha.footer')
