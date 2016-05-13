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
                                    <tbody class="dragsort" id="level_1">
                                    @foreach ($category as $c)
                                        <tr>
                                            <td>{{ $c->id }}</td>
                                            <td id="c_{{ $c->id }}">{{ $c->name }}</td>
                                            <td>
                                                <div cid="{{ $c->id }}" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
                                                <div title="添加帐号" src="/alpha/goods/category/del/{{$c->id}}" href="#warning" data-toggle="modal" class="btn btn-danger btn-xs warning"><i class="icon-trash"></i></div>
                                                <div cid="{{ $c->id }}" class="btn btn-primary btn-xs openChild" ><i class="icon-eye-open"></i></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div style="width:100%;" pid="0" level="1" class="btn btn-primary btn-xs addCategory" data-toggle="modal" href="#addCategory"><i class="icon-plus"></i></div>
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
<script id='addBrandTr' type='text/html'>
    <tr>
        <td><%= id %> </td>
        <td id="b_<%= id %>"><%= name %></td>
        <td>
            <div bid="<%= id %>" data-toggle="modal" href="#updateBrand" class="btn btn-primary btn-xs updateBrand"><i class="icon-pencil"></i></div>
            <a title="添加帐号" href="/alpha/stores/users" class="btn btn-danger btn-xs"><i class="icon-trash"></i></a>
        </td>
    </tr>
</script>

<script id='addCategoryTr' type='text/html'>
    <tr>
        <td><%= id %> </td>
        <td id="c_<%= id %>"><%= name %></td>
        <td>
            <div cid="<%= id %>" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
            <a title="添加帐号" href="/alpha/stores/users" class="btn btn-danger btn-xs"><i class="icon-trash"></i></a>
            <% if(level == 1){%>
                <div cid="<%= id %>" class="btn btn-primary btn-xs openChild" ><i class="icon-eye-open"></i></div>
            <%}else if(level == 2){%>
                <div cid="<%= id %>" class="btn btn-primary btn-xs openGrandson" ><i class="icon-eye-open"></i></div>
            <%}else{%>
            <div cid="<%= id %>" class="btn btn-primary btn-xs openBrand" ><i class="icon-eye-open"></i></div>
            <%}%>
        </td>
    </tr>
</script>
<script id='openChildTlp' type='text/html'>
    <header class="panel-heading text-center">
        <%= title %>
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
            <tbody class="dragsort" id="level_<%= level %>">
            <% for( var i=0; i < category.length ; i++) { %>
                <tr>
                    <td><%= category[i].id %> </td>
                    <td id="c_<%= category[i].id %>"><%= category[i].name %></td>
                    <td>
                        <div cid="<%= category[i].id %>" data-toggle="modal" href="#update" class="btn btn-primary btn-xs update"><i class="icon-pencil"></i></div>
                        <div title="删除" src="/alpha/goods/category/del/<%= category[i].id %>" href="#warning" data-toggle="modal" class="btn btn-danger btn-xs warning"><i class="icon-trash"></i></div>
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
        <div style="width:100%;" pid="<%= pid %>" level="<%= level %>" class="btn btn-primary btn-xs addCategory" data-toggle="modal" href="#addCategory"><i class="icon-plus"></i></div>
    </section>
</script>

<script id='openBrandTlp' type='text/html'>
    <header class="panel-heading text-center">
        <%= title %>
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
			<tbody class="dragsort" id="cid_<%= cid %>">
			<% for( var i=0; i < brand.length ; i++) { %>
				<tr>
					<td><%= brand[i].id %> </td>
					<td id="b_<%= brand[i].id %>"><%= brand[i].name %></td>
					<td>
						<div bid="<%= brand[i].id %>" data-toggle="modal" href="#updateBrand" class="btn btn-primary btn-xs updateBrand"><i class="icon-pencil"></i></div>
						<div title="删除" src="/alpha/goods/brand/del/<%= brand[i].id %>" href="#warning" data-toggle="modal" class="btn btn-danger btn-xs warning"><i class="icon-trash"></i></div>
					</td>
				</tr>
			<% } %>
			</tbody>
		</table>
		<div style="width:100%;" cid="<%= cid %>"  class="btn btn-primary btn-xs addBrand" data-toggle="modal" href="#addBrand"><i class="icon-plus"></i></div>
	</section>
</script>

<script>
    $('.openChild').bind('click' , function(){
        var pid = $(this).attr('cid');
        var name = $('#c_' + pid).html();
        $.get('/alpha/goods/category/pid/' + pid , function(data){
                var bt = baidu.template;
                data.data.is_brand = false;
                data.data.is_show = true;
                data.data.title = name + '的二级分类';
                data.data.pid = pid;
                data.data.level = 2;
                var html = bt('openChildTlp' , data.data);
                $('#child').html(html);
                $('#grandson').html('');
                $('#categoryBrand').html('');
        });
    });

    $('.panel-body').on('click' , '.openGrandson' , function(){
        var pid = $(this).attr('cid');
        var name = $('#c_' + pid).html();
        $.get('/alpha/goods/category/pid/' + pid , function(data){
                var bt = baidu.template;
                data.data.is_brand = true;
                data.data.is_show = true;
                data.data.title = name + '的三级分类';
                data.data.pid = pid;
                data.data.level = 3;
                var html = bt('openChildTlp' , data.data);
                $('#grandson').html(html);
                $('#categoryBrand').html('');
        });
    });

    $('.panel-body').on('click' , '.openBrand' , function(){
        var cid = $(this).attr('cid');
        var name = $('#c_' + cid).html();
        $.get('/alpha/goods/brand/' + cid , function(data){
                data.data.title = name + '的品牌';
                data.data.cid = cid;
                var bt = baidu.template;
                var html = bt('openBrandTlp' , data.data);
                $('#categoryBrand').html(html);
        });
    });
</script>


{{--添加分类--}}
<!-- Modal -->
<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">添加商品分类</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input id="add_c_name" type="text" class="form-control" name='name' />
                                    <input id="add_c_pid" type="hidden" class="form-control" name='pid' />
                                    <input id="add_c_level" type="hidden" class="form-control" name='level' />
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button" >取消</button>
                    <button class="btn btn-success" type="button" id="addCategorySubmit">添加</button>
                </div>
        </div>
    </div>
</div>
<!-- modal -->

{{--添加品牌--}}
<!-- Modal -->
<div class="modal fade" id="addBrand" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">添加商品品牌</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input id="add_b_name" type="text" class="form-control" name='name' />
                                    <input id="add_b_cid" type="hidden" class="form-control" name='cid' />
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button" >取消</button>
                    <button class="btn btn-success" type="button" id="addBrandSubmit">添加</button>
                </div>
        </div>
    </div>
</div>
<!-- modal -->


{{--修改分类--}}
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


{{--修改品牌--}}
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

    $('.panel-body').on('click' , '.addCategory' , function(){
        var pid     = $(this).attr('pid');
        var level   = $(this).attr('level');

        $('#add_c_pid').val(pid);
        $('#add_c_level').val(level);
    });


    $('#addCategorySubmit').bind('click' , function(){
        var param = {
            'name'  : $('#add_c_name').val(),
            'pid'   : $('#add_c_pid').val(),
            'level' : $('#add_c_level').val()
        }

        $.post('/alpha/goods/category/add' , param , function(data){
            if(data.code == '0000'){
                param.id = data.data;
                $('#add_c_name').val('');
                var bt = baidu.template;
                var html = bt('addCategoryTr' , param);
                $('#level_' + param['level']).append(html);
                $('#addCategory').modal('hide');
            }else{
                $('#addCategory').modal('hide');
                $('#fail').modal('show');
            }
        });
    });

    $('.panel-body').on('click' , '.addBrand' , function(){
        var cid     = $(this).attr('cid');

        $('#add_b_cid').val(cid);
    });


    $('#addBrandSubmit').bind('click' , function(){
        var param = {
            'name'  : $('#add_b_name').val(),
            'cid'   : $('#add_b_cid').val()
        }

        $.post('/alpha/goods/brand/add' , param , function(data){
            if(data.code == '0000'){
                param.id = data.data;
                $('#add_b_name').val('');
                var bt = baidu.template;
                var html = bt('addBrandTr' , param);
                $('#cid_' + param['cid']).append(html);
                $('#addBrand').modal('hide');
            }else{
                $('#addBrand').modal('hide');
                $('#fail').modal('show');
            }
        });
    });
</script>




<!-- Modal -->
<div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
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
				<button class="btn btn-success" type="button" id="submitDel">确定</button>
			</div>
		</div>
	</div>
</div>
<!-- modal -->

<script>
$('.warning').bind('click' , function(){
	var url = $(this).attr('src');
	var _this = this;
	$('#submitDel').bind('click' , function(){
	    $.get(url , function(data){
            if(data.code == '0000'){
                $(_this).parent().parent().remove();
                $('#warning').modal('hide');
            }else{
                $('#warning').modal('hide');
                $('#fail').modal('show');
            }
        });
	});
});
</script>



@include('alpha.footer')
