@include('alpha.header')

<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">
						店铺基本信息
						<div style='margin-left:20px;' class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></div>
						<div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>
					</header>
					<div class="panel-body">
						<section id="unseen">
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>id</th>
										<th>名称</th>
										<th>类型</th>
										<th>身份证</th>
										<th>营业执照</th>
										<th>联系人</th>
										<th>联系电话</th>
										<th>地址</th>
										<th>地图标记</th>
										<th>审核</th>
										<th>状态</th>
										<th>营业状态</th>
										<th width="100px;">操作</th>
									</tr>
								</thead>
								<tbody class="dragsort">
									@foreach ($storeInfos as $si)
										<tr>
											<td>{{ $si->id }}</td>
											<td>{{ $si->store_name }}</td>
											<td>{{ $si->category_name }}</td>
											<td><img style="width:50px;height:50px;" src="{{ $si->id_card_img }}" alt="{{ $si->id_card_img }}" title="{{ $si->store_name }}" /></td>
											<td><img style="width:50px;height:50px;" src="{{ $si->business_license }}" alt="{{ $si->business_license }}" title="{{ $si->store_name }}" /></td>
											<td>{{ $si->contacts }}</td>
											<td>{{ $si->contact_phone }}</td>
											<td>{{ $si->province }}{{ $si->city }}{{ $si->county }}{{ $si->address }}</td>
											<td>@if ($si->is_sign == 1) 已标记 @else 未标记 @endif</td>
											<td>@if ($si->is_checked == 1) 已审核 @else 未审核 @endif</td>
											<td>@if ($si->is_open == 1) 开启 @else 关闭 @endif</td>
											<td>@if ($si->isDoBusiness == 1) 营业中 @else 休息中 @endif</td>
											<td>
												{{--<div p_id="{{ $si->id }}" title="添加子店铺"  class="btn btn-primary btn-xs addChild" data-toggle="modal" href="#addChild"><i class="icon-plus"></i></div>--}}
												<div p_id="{{ $si->id }}" page="{{ $page }}"  location="{{ $si->location }}" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
												<a title="店员" href="/alpha/store/user?store_id={{ $si->id }}" class="btn btn-primary btn-xs"><i class="icon-user"></i></a>
												<a title="店铺商品" href="/alpha/store/goods/{{ $si->id }}" class="btn btn-primary btn-xs"><i class="icon-hdd"></i></a>
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
<!--main content end-->

@include('alpha.store.info.info_moduls')

@include('alpha.footer')
