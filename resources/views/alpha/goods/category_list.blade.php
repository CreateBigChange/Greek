@include('alpha.header')

        <!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        商品分类
                        {{--<div style='margin-left:20px;' class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></div>--}}
                        {{--<div style='margin-left:20px;' class="btn btn-primary btn-xs searchDiaLog" data-toggle="modal" href="#search"><i class="icon-search"></i></div>--}}
                    </header>

                    <div class="panel-body" >
                        <div class="col-lg-3">
                            <header class="panel-heading text-center">
                                一级分类
                            </header>
                            <section id="unseen">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>名称</th>
                                        <th width="100px;">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody class="dragsort">
                                    @foreach ($category as $c)
                                        <tr>
                                            <td>{{ $c->id }}</td>
                                            <td id="c_{{ $c->id }}">{{ $c->name }}</td>
                                            <td>
                                                <div cid="{{ $c->id }}" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
                                                <a title="添加帐号" href="/alpha/stores/users" class="btn btn-danger btn-xs"><i class="icon-trash"></i></a>
                                                <div cid="{{ $c->id }}" class="btn btn-primary btn-xs openChild" ><i class="icon-eye-open"></i></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div style="width:100%;" class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></div>
                            </section>
                        </div>
                        <div class="col-lg-3" id="child">

                        </div>
                        <div class="col-lg-3" id="grandson">

                        </div>
                        <div class="col-lg-3" id="categoryBrand">

                        </div>
                    </div>
                    {{--<div class="text-center">--}}
                        {{--{!! $pageHtml !!}--}}
                    {{--</div>--}}
                </section>
            </div>
        </div>

    </section>


</section>
<!--main content end-->

<script id='openChildTlp' type='text/html'>
    <header class="panel-heading text-center">
        <%= title %>级分类
    </header>
    <section id="unseen">
        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>id</th>
                <th>名称</th>
                <th width="100px;">操作</th>
            </tr>
            </thead>
            <tbody class="dragsort">
            <% for( var i=0; i < category.length ; i++) { %>
                <tr>
                    <td><%= category[i].id %> </td>
                    <td id="c_<%= category[i].id %>"><%= category[i].name %></td>
                    <td>
                        <div cid="<%= category[i].id %>" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
                        <a title="添加帐号" href="/alpha/stores/users" class="btn btn-danger btn-xs"><i class="icon-trash"></i></a>
                        <% if(is_brand && is_show){%>
                            <div cid="<%= category[i].id %>" class="btn btn-primary btn-xs openBrand" ><i class="icon-eye-open"></i></div>
                        <%}else if(is_show){%>
                            <div cid="<%= category[i].id %>" class="btn btn-primary btn-xs openGrandson" ><i class="icon-eye-open"></i></div>
                        <%}%>
                    </td>
                </tr>
            <% } %>
            </tbody>
        </table>
        <div style="width:100%;" class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></div>
    </section>
</script>

<script id='openBrandTlp' type='text/html'>
    <header class="panel-heading text-center">
        品牌
    </header>
	<section id="unseen">
		<table class="table table-bordered table-striped table-condensed">
			<thead>
			<tr>
				<th>id</th>
				<th>名称</th>
				<th width="100px;">操作</th>
			</tr>
			</thead>
			<tbody class="dragsort">
			<% for( var i=0; i < brand.length ; i++) { %>
				<tr>
					<td><%= brand[i].id %> </td>
					<td id="b_<%= brand[i].id %>"><%= brand[i].name %></td>
					<td>
						<div bid="<%= brand[i].id %>" data-toggle="modal" href="#updateBrand" class="btn btn-primary btn-xs updateBrand"><i class="icon-pencil"></i></div>

						<div title="删除" class="btn btn-danger btn-xs"><i class="icon-trash"></i></div>
					</td>
				</tr>
			<% } %>
			</tbody>
		</table>
		<div style="width:100%;" class="btn btn-primary btn-xs add" data-toggle="modal" href="#add"><i class="icon-plus"></i></div>
	</section>
</script>

<script>
    $('.openChild').bind('click' , function(){
        var pid = $(this).attr('cid');
        $.get('/alpha/goods/category/pid/' + pid , function(data){
                var bt = baidu.template;
                data.data.is_brand = false;
                data.data.is_show = true;
                data.data.title = '二';
                var html = bt('openChildTlp' , data.data);
                $('#child').html(html);
                $('#grandson').html('');
                $('#categoryBrand').html('');
        });
    });

    $('.panel-body').on('click' , '.openGrandson' , function(){
        var pid = $(this).attr('cid');
        $.get('/alpha/goods/category/pid/' + pid , function(data){
                var bt = baidu.template;
                data.data.is_brand = true;
                data.data.is_show = true;
                data.data.title = '三';
                var html = bt('openChildTlp' , data.data);
                $('#grandson').html(html);
                $('#categoryBrand').html('');
        });
    });

    $('.panel-body').on('click' , '.openBrand' , function(){
        var cid = $(this).attr('cid');
        $.get('/alpha/goods/brand/' + cid , function(data){

                var bt = baidu.template;
                var html = bt('openBrandTlp' , data.data);
                $('#categoryBrand').html(html);
        });
    });
</script>


{{--修改--}}
<!-- Modal -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <input type="hidden" name="id" value="" id="edit_id" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">修改商品分类</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' id="edit_name"/>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button" >取消</button>
                    <button class="btn btn-success" type="button" id="submit">修改</button>
                </div>
        </div>
    </div>
</div>
<!-- modal -->


{{--修改--}}
<!-- Modal -->
<div class="modal fade" id="updateBrand" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <input type="hidden" name="id" value="" id="edit_brand_id" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">修改商品品牌</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' id="edit_brand_name"/>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button" >取消</button>
                    <button class="btn btn-success" type="button" id="submitBrand">修改</button>
                </div>
        </div>
    </div>
</div>
<!-- modal -->




<!-- Modal -->
<div class="modal fade" id="fail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form class="form-horizontal tasi-form" method="get" id='form-warning' action=''>
			<div class="modal-content">
				<div class="modal-header" style='background:#FCB322'>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">失败</h4>
				</div>
				<div class="modal-body">
					操作失败
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
    $('.panel-body').on('click' , '.update' , function(){
        var cid = $(this).attr('cid');
        $.get('/alpha/goods/category/info/' + cid , function(data){
            if(data){
                $('#edit_name').val(data.category.name);
                $('#edit_id').val(data.category.id);
            }
        });
    });

    $('#submit').bind('click' , function(){
        var param = {
            'name'  : $('#edit_name').val(),
            'id'    : $('#edit_id').val()
        }

        $.post('/alpha/goods/category/update' , param , function(data){
            if(data.code == '0000'){
                $('#c_'+ param.id).html(param.name);
                $('#update').modal('hide');
            }else{
                $('#update').modal('hide');
                $('#fail').modal('show');
            }
        });
    });

    $('.panel-body').on('click' , '.updateBrand' , function(){
        var bid = $(this).attr('bid');
        $.get('/alpha/goods/brand/info/' + bid , function(data){
            if(data){
                $('#edit_brand_name').val(data.brand.name);
                $('#edit_brand_id').val(data.brand.id);
            }
        });
    });

    $('#submitBrand').bind('click' , function(){
        var param = {
            'name'  : $('#edit_brand_name').val(),
            'id'    : $('#edit_brand_id').val()
        }

        $.post('/alpha/goods/brand/update' , param , function(data){
            if(data.code == '0000'){
                $('#b_'+ param.id).html(param.name);
                $('#updateBrand').modal('hide');
            }else{
                $('#updateBrand').modal('hide');
                $('#fail').modal('show');
            }
        });
    });
</script>

@include('alpha.footer')
