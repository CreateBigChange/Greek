@include('alpha.header')






<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<!-- page start-->
		<div class="container">
			<form class="form-signin" action="/alpha/login" method="post">
				<h2 class="form-signin-heading">急所需</h2>
				<div class="login-wrap">
					<input type="text" class="form-control" placeholder="手机号" name='account' autofocus>
					<input type="password" class="form-control" placeholder="密码" name='password'>
					<label class="checkbox">
						<input type="checkbox" value="remember-me"> Remember me
						<span class="pull-right">
					<a data-toggle="modal" href="#myModal"> Forgot Password?</a>

				</span>
					</label>
					<button class="btn btn-lg btn-login btn-block" type="submit">登陆</button>
				</div>

			</form>
		</div>

	</section>
</section>
<!--main content end-->




@include('alpha.footer')