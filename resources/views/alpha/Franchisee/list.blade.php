@include('alpha.header')

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        加盟商列表
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
                                    <th>手机号</th>
                                    <th>地址</th>
                                    <th>是否联系</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody class="dragsort">
                                @foreach ($lists as $list)
                                    <tr>
                                        <td>{{ $list->id }}</td>
                                        <td>{{ $list->name }}</td>
                                        <td>{{ $list->mobile }}</td>
                                        <td>{{ $list->address }}</td>
                                        <td>
                                            @if($list->is_contact==1)
                                                已联系
                                             @else
                                                没联系
                                            @endif
                                        </td>
                                        <td><button class="btn btn-success btn-update" data-toggle="modal" href="#update"
                                            name="{{ $list->name }}"             mobile ="{{ $list->mobile }}"
                                            address ="{{ $list->address }}"    is_contact = "{{ $list->is_contact }}"
                                            FranchiseeId="{{ $list->id }}"
                                            >修改</button></td>
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
<script>
    $(".btn-update").click(function () {


        $("#edit_id").attr("value",$(this).attr("FranchiseeId"));
        $("#edit_name").attr("value",$(this).attr("name"));
        $("#edit_mobile").attr("value",$(this).attr("mobile"));
        $("#tipinput").attr("value",$(this).attr("address"));
        if($("#this").attr("is_contact")==1){
            $("#my-btnswitch").bootstrapSwitch('setState', true);
            $("#btn-checked").attr("value",1);
        }
        else{
            $("#my-btnswitch").bootstrapSwitch('setState', false);
            $("#btn-checked").attr("value",0);
        }

    })

</script>
<!--main content end-->
@include('alpha.Franchisee.list_moduls')
@include('alpha.moduls.warning')
@include('alpha.footer')
