<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="Mosaddek">
		<meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
		<link rel="shortcut icon" href="/img/favicon.png">

		<title>{{ $title }}</title>

		<!-- Bootstrap core CSS -->
		<link href="{{ URL::asset('/') }}css/bootstrap.min.css" rel="stylesheet">
		<link href="{{ URL::asset('/') }}css/bootstrap-reset.css" rel="stylesheet">
		<!--external css-->
		<link href="{{ URL::asset('/') }}assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

		<link href="{{ URL::asset('/') }}assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
		<link rel="stylesheet" href="{{ URL::asset('/') }}css/owl.carousel.css" type="text/css">
		<!-- Custom styles for this template -->
		<link href="{{ URL::asset('/') }}flatlib/css/style.css" rel="stylesheet">
		<link href="{{ URL::asset('/') }}css/style-responsive.css" rel="stylesheet" />

		<link href="{{ URL::asset('/') }}assets/dropzone/css/dropzone.css" rel="stylesheet"/>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
		<!--[if lt IE 9]>
		<script src="{{ URL::asset('/') }}js/html5shiv.js"></script>
		<script src="{{ URL::asset('/') }}js/respond.min.js"></script>
		<![endif]-->
		<link href="{{ URL::asset('/') }}css/style.css" rel="stylesheet">

		<link rel="stylesheet" type="text/css" href="{{ URL::asset('/') }}assets/nestable/jquery.nestable.css" />

		<script src="{{ URL::asset('/') }}js/jquery-1.8.3.min.js"></script>

		<!-- 拖拽排序-->
		<script type="text/javascript" src="{{ URL::asset('/') }}js/dragsort-0.5.2/jquery.dragsort-0.5.2.min.js"></script>
		<!--ECHART 2.0-->
		<script type="text/javascript" src="{{ URL::asset('/') }}js/echarts.min.js"></script>
		
	</head>

	<body>

		<section id="container" >
		<!--header start-->
		<header class="header white-bg">

		<div class="nav notify-row" id="top_menu">
			<!--  notification start -->
			<ul class="nav top-menu">



			</ul>
			<!--  notification end -->
		</div>
		<div class="top-nav ">
			<!--search & user info start-->
			<ul class="nav pull-right top-menu">
				<li>
				<input type="text" class="form-control search" placeholder="Search">
				</li>
				<!-- user login dropdown start-->
				<li class="dropdown">
				<a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<img alt="" src="/img/avatar1_small.jpg">
					<span class="username"></span>
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu extended logout">
					<div class="log-arrow-up"></div>
					<li><a href="#"><i class=" icon-suitcase"></i>Profile</a></li>
					<li><a href="#"><i class="icon-cog"></i> Settings</a></li>
					<li><a href="#"><i class="icon-bell-alt"></i> Notification</a></li>
					<li><a href="/alpha/logout"><i class="icon-key"></i>退出登录</a></li>
				</ul>
				</li>
				<!-- user login dropdown end -->
			</ul>
			<!--search & user info end-->
		</div>
		</header>
		<!--header end-->

		@include('alpha.menu')
