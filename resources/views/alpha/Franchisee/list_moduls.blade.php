{{--修改--}}
<link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>

<!-- Modal -->

    <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <form class="form-horizontal tasi-form" method="get" action='/alpha/franchisee/update'>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">修改</h4>
                    </div>
                    <div class="modal-body">
                        <section class="panel" style="margin-bottom:0px">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">id</label>
                                    <div class="col-sm-10">

                                        <input type="text" readonly class="form-control" name='id' id="edit_id"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">名称</label>
                                    <div class="col-sm-10">

                                        <input type="text" class="form-control" name='name' id="edit_name"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">手机号码</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='mobile' id="edit_mobile"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">地址</label>
                                    <div class="col-sm-10"  >
                                        <input type="text" class="form-control" name='address' id="tipinput" title="tipinput" oninput="myFunction(this)"/>
                                        <div style="width:100%;boder:1px solid #000000;background:#ffffff;position:absolute;z-index:1000;opacity:10">
                                            <ul style="width:100%;" id="addText">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">地图</label>
                                    <div class="col-sm-10">
                                        <div class="form-control" style="width:100%;height:500px;" id="ditu_container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">是否联系</label>
                                    <div class="col-sm-10">
                                        <div class="switch switch-large" id="my-btnswitch" name="test">
                                            <input type="hidden" name="is_contact" value="1" id="btn-checked">
                                            <input type="checkbox"  checked   />
                                        </div>
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

<!--高德地图组件-->
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=9f01a5673f304b2b13a3b1d84c76b5f7&plugin=AMap.Autocomplete,AMap.PlaceSearch"></script>
<script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>

<script type="text/javascript">

    $('#my-btnswitch').on('switch-change', function (e, data) {
        var $el = $(data.el)
                , value = data.value;
        if(value){
            $("#btn-checked").attr("value",1);
        }else {
            $("#btn-checked").attr("value",0)
        }
    });


    //地图加载
    map = new AMap.Map("ditu_container", {
        resizeEnable: true
    });

    map.on('click', function(e) {

        var key      ="9f01a5673f304b2b13a3b1d84c76b5f7";
        var obj2=[e.lnglat.getLng(),e.lnglat.getLat()];
        var str = e.lnglat.getLng()+","+e.lnglat.getLat();
        map.setZoomAndCenter(14,obj2);

        var marker = new AMap.Marker({
            map: map,
            position: obj2
        });
        //地理信息逆编码
        var url = " http://restapi.amap.com/v3/geocode/regeo?output=json&location="+str+"&key="+key+"&radius=1000&extensions=all";
        $.ajax({
            url:url,
            type:"get",
            dataType:"json",
            success:function(data)
            {

                $("#tipinput").val(data.regeocode.formatted_address);
            },
            error:function()
            {
                alert("请求失败");
            }
        });




    });


    function myFunction(e){

        element = $(e);

        var keyword  =e.value;
        var datatype ="all";
        var key      ="9f01a5673f304b2b13a3b1d84c76b5f7";
        var url = "http://restapi.amap.com/v3/assistant/inputtips?key="+key+"&keywords="+keyword+"&datatype="+datatype;
        ajax(url);
    }

    /**
     * @param url      url
     * @param data     数据
     * @param callback 回调函数
     * @param method   方式
     */

    function  ajax(url,e) {

        $.ajax({
            url:url,
            type:"get",
            dataType:"json",
            success:function(data)
            {
                var tips =data.tips;
                var text="";
                for(var i = 0;i<tips.length;i++){
                    text+="<li style='width:100%;' location ='"+tips[i].location+"'>"+tips[i].address+tips[i].name+"</li>";
                }
                $("#addText").html(text);

                $("#addText").children("li").click(function () {
                    var location = $(this).attr("location");

                    var obj2 = location.split(",");

                    map.setZoomAndCenter(14,obj2);

                    var marker = new AMap.Marker({
                        map: map,
                        position: obj2
                    });

                    $("#tipinput").val($(this).html());
                    $("#addText").html("");
                })
                $("#addText").children("li").mouseover(function () {
                    $(this).css("background","lightgray")
                })
                $("#addText").children("li").mouseout(function () {
                    $(this).css("background","#ffffff");
                })

            },
            error:function()
            {
                alert("请求失败");
            }
        });
    }
    //输入提示
    var autoOptions = {
        input: "tipinput"
    };

    var auto = new AMap.Autocomplete(autoOptions);
    var placeSearch = new AMap.PlaceSearch({
        map: map
    });  //构造地点查询类

    AMap.event.addListener(auto, "select", select);//注册监听，当选中某条记录时会触发
    function select(e) {
        placeSearch.setCity(e.poi.adcode);
        placeSearch.search(e.poi.name);  //关键字查询查询
    }

</script>