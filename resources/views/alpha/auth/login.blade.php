<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="Mosaddek">
	<meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
	<link rel="shortcut icon" href="{{ URL::asset('/') }}img/favicon.png">

	<title>{{ $title }}</title>

	<!-- Bootstrap core CSS -->
	<link href="{{ URL::asset('/') }}css/bootstrap.min.css" rel="stylesheet">
	<link href="{{ URL::asset('/') }}css/bootstrap-reset.css" rel="stylesheet">
	<!--external css-->
	<link href="{{ URL::asset('/') }}assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<!-- Custom styles for this template -->
	<link href="{{ URL::asset('/') }}flatlib/css/style.css" rel="stylesheet">
	<link href="{{ URL::asset('/') }}css/style-responsive.css" rel="stylesheet" />

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
	<!--[if lt IE 9]>
	<script src="{{ URL::asset('/') }}js/html5shiv.js"></script>
	<script src="{{ URL::asset('/') }}js/respond.min.js"></script>
	<![endif]-->
</head>

  <body class="login-body">

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



	<!-- js placed at the end of the document so the pages load faster -->
	<script src="{{ URL::asset('/') }}js/jquery.js"></script>
	<script src="{{ URL::asset('/') }}js/bootstrap.min.js"></script>


  </body>
</html>
