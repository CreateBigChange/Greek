{{--搜索--}}
<!-- Modal -->
@foreach($goods as $g)
<div class="modal fade" id="Update_{{ $g->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="get" action='/alpha/store/goods/by/update'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">修改</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">商品id</label>
                                <div class="col-sm-10">
                                    <input  type="hidden" class="form-control" name='id' value="{{$g->id}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">商品名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' value="{{$g->name}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">品牌</label>
                                    <div class="col-sm-10">
                                        <select class="form-control m-bot15" name='b_id' id='b_id'>
                                            @foreach( $g->brand as $brand)
                                               @if($brand->id == $g->brand_id)
                                                <option selected value="{{$brand->id}}">{{$brand->name}}</option>
                                                @else
                                                 <option  value="{{$brand->id}}">{{$brand->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">图片</label>
                                <div class="col-sm-2">
                                    <input type="file" class="form-control selectImg"  name="img">
                                </div>
                                <div class="col-sm-8">
                                        <img src="{{ $g->img }}" class="showImg">
                                </div>
                            </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">售价</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='out_price' value="{{ $g->out_price }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">规格</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='spec' value="{{ $g->spec }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">描述</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='desc' value="{{ $g->desc }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">店铺名称</label>
                                    <div class="col-sm-10">
                                        <select class="form-control m-bot15" name='store_id' id='store_id'>
                                            @foreach( $store as $s)
                                                @if($s->id == $g->store_id)
                                                    <option selected value="{{$s->id}}">{{$s->name}}</option>
                                                @else
                                                    <option  value="{{$s->id}}">{{$s->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">开启</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline">
                                        @if($g->is_open ==1)
                                            <input type="checkbox" id="inlineCheckbox1" value=""  checked name="is_open"> 开启
                                        @else
                                            <input type="checkbox" id="inlineCheckbox1" value=""  name="is_open"> 开启
                                        @endif

                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success submit" type="button">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
<!-- modal -->
<script>
    $(".selectImg").change(function(){
        console.log($(this));
        var file= $(this)[0].files[0];
        if(file)
        {
            var reader=new FileReader();

            reader.onload=function(){

                    $('.showImg').attr("src",this.result);
            }
            reader.readAsDataURL(file);
        }
    })

$(".submit").click(function () {
    var  form = $(this).parents(".form-horizontal");


    var file = form.find(".selectImg").val();

    if (file){
        $.ajax({
            dataType:'json',
            type:'post',
            url:'/upload/qiniu',
            cache: false,
            data: new FormData(form[0]),
            processData: false,
            contentType: false,
            success:function(data)
            {
                form.submit();
            },
            error:function(data)
            {
                alert("error");
            }
        })
    }
    else
    {
        form.submit();
    }
})
</script>