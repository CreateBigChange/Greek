<!--footer start-->
<!--
<footer class="site-footer">
<div class="text-center">
	2013 &copy; FlatLab by VectorLab.
	<a href="#" class="go-top">
		<i class="icon-angle-up"></i>
	</a>
</div>
</footer>
-->
<!--footer end-->
</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="{{ URL::asset('/') }}js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="{{ URL::asset('/') }}js/jquery.dcjqaccordion.2.7.js"></script>
<script src="{{ URL::asset('/') }}js/jquery.scrollTo.min.js"></script>
<script src="{{ URL::asset('/') }}js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="{{ URL::asset('/') }}js/jquery.sparkline.js" type="text/javascript"></script>
<script src="{{ URL::asset('/') }}assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="{{ URL::asset('/') }}js/owl.carousel.js" ></script>
<script src="{{ URL::asset('/') }}js/jquery.customSelect.min.js" ></script>

<script type="text/javascript" src="{{ URL::asset('/') }}assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
<script src="{{ URL::asset('/') }}js/respond.min.js" ></script>


<script type="text/javascript" charset="utf-8" src="{{ URL::asset('/') }}js/baiduTemplate.js"></script>

<!--common script for all pages-->
<script src="{{ URL::asset('/') }}js/common-scripts.js"></script>

<!--script for this page-->
<script src="{{ URL::asset('/') }}js/sparkline-chart.js"></script>
<script src="{{ URL::asset('/') }}js/easy-pie-chart.js"></script>
<script src="{{ URL::asset('/') }}js/count.js"></script>

<!--custom switch-->
<script src="{{ URL::asset('/') }}js/bootstrap-switch.js"></script>
<!--custom tagsinput-->
<script src="{{ URL::asset('/') }}js/jquery.tagsinput.js"></script>
<!--custom checkbox & radio-->
<script type="text/javascript" src="{{ URL::asset('/') }}js/ga.js"></script>

<script src="{{ URL::asset('/') }}assets/dropzone/dropzone.js"></script>


<script type="text/javascript" charset="utf-8" src="{{ URL::asset('/') }}js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{{ URL::asset('/') }}js/ueditor/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="{{ URL::asset('/') }}js/ueditor/lang/zh-cn/zh-cn.js"></script>

 <!--script for this page-->
<script src="{{ URL::asset('/') }}js/form-component.js"></script>

<!-- 拖拽排序-->
<script type="text/javascript" src="{{ URL::asset('/') }}js/dragsort-0.5.2/jquery.dragsort-0.5.2.min.js"></script>


<script>

	//owl carousel

	$(document).ready(function() {
		$("#owl-demo").owlCarousel({
			navigation : true,
			slideSpeed : 300,
			paginationSpeed : 400,
			singleItem : true,
			autoPlay:true

		});

		var ue = UE.getEditor('editor');
	});

	//custom select box

	$(function(){
		$('select.styled').customSelect();
	});

	var dropz = new Dropzone("#uploadimg", {
		url: "http://spirit.test.com/admin/upload",
		addRemoveLinks: true,
		maxFiles: 1,
		paramName:'cover',
		maxFilesize: 5120,
		acceptedFiles: ".jpg , .png"
	});

	dropz.on('success' , function(file , data){
		if(data.code == '0000'){
			$('#uploadimg').val(data.data);
			$('#uploadimgpre').attr('src' , data.data);
		}
	});


</script>

  </body>
</html>
