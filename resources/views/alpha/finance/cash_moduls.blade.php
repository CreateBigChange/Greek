{{--搜索--}}
 <!-- Modal -->
<div class="modal fade" id="check" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form class="form-horizontal tasi-form" method="get" action='/alpha/finance/withdrawReject' id="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">原因</h4>
                </div>
                <div class="modal-body">
                    <section class="panel" style="margin-bottom:0px">
                        <div class="panel-body">
                        
                            <div class="form-group">
                                <label for='reason'>拒绝原因</label>
                                <textarea name="reason" class="form-control" rows="5"></textarea>
                            </div>
                            <input type="hidden" name="id" value=""  id="withdrawId"/>
                        </div>
                    </section>

                </div>
                <div class="modal-footer"  style="margin-top:0px">
                    <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                    <button class="btn btn-success" type="submit" id="submit">确认</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$("#submit").click(function(){
    $("#form").submit();
})
</script>
<!-- modal -->