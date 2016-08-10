@include('alpha.header')






<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<!-- page start-->
		<div class="container">
			<form class="" action="/alpha/role/passwordChange" method="post">
				<div class="row">
					<div class="row col-xs-1"></div>
					<div class="row col-xs-1" style="line-height: 34px;height: 34px;">原始密码</div>
					<div class="row col-xs-4"><input type="password" class="form-group form-control" name="oldPassword" value="" id="oldPassword"/></div>
					<div class="row col-xs-6" id="oldPasswordInfo"></div>
				</div>
				<div class="row">
					<div class="row col-xs-1"></div>
					<div class="row col-xs-1" style="line-height: 34px;height: 34px;">新密码</div>
					<div class="row col-xs-4"><input type="password" class="form-group form-control" name ="newPassword" id="newPassword"/></div>
					<div class="row col-xs-6" ></div>
				</div>
				<div class="row">
					<div class="row col-xs-1"></div>
					<div class="row col-xs-1" style="line-height: 34px;height: 34px;">密码确认</div>
					<div class="row col-xs-4"><input type="password" class="form-group form-control" name="confirePassword" id="confirePassword"/></div>
					<div class="row col-xs-6" id="confirePasswordInfo"></div>
				</div>
				<div class="row">
					<div class="row col-xs-3"></div>
					<div class="row col-xs-2"><button type="button" class="btn btn-success" id="submitBtn">修改</button></div>
					<div class="row col-xs-1"></div>
					<div class="row col-xs-6"></div>
				</div>
			</form>
		</div>

	</section>
</section>
<!--main content end-->
<script>
		sign =0;
		$("#oldPassword").blur(function () {
			ajaxVerify();
		})

function  ajaxVerify() {

	var data =$("#oldPassword").val();

	$.ajax({
		async:true,
		data:{"password":data},
		dataType:"json",
		url:"/alpha/role/verifyPassword",
		type:"post",
		success:function (data	) {
			if(data.code==0){
				$("#oldPasswordInfo").html("<div style='color: green; height=34px;line-height: 34px;'>密码正确</div>");
				sign =1;
			}
			else{
				$("#oldPasswordInfo").html("<div style='color: red; height=34px;line-height: 34px;'>密码错误</div>");
				sign =0;
			}
		},
		error:function (data) {
			alert("error");
			sign =0;
		}

	})
}
		
function verifyPassword() {
	var newPassword = $("#newPassword").val();

	var confirePassword = $("#confirePassword").val();
	if(newPassword.length<6){
		$("#confirePasswordInfo").html("<div style='color: green; height=34px;line-height: 34px;'>密码长度不能小于6</div>")
		return false;
	}
	if(newPassword == confirePassword){
		$("#confirePasswordInfo").html("<div style='color: green; height=34px;line-height: 34px;'>两次密码相同</div>")
		return true;
	}else {
		$("#confirePasswordInfo").html("<div style='color: red; height=34px;line-height: 34px;'>两次密码不相同</div>")
		return false;
	}
	return true;
}

	$("#confirePassword").blur(function () {
		verifyPassword();
	})

	$("#submitBtn").click(function () {
		if(!verifyPassword())
			return false;
		if(sign ==0)
			return false;

		$("form").submit();
	})

</script>

@include('alpha.footer')