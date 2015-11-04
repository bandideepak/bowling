<?php 
if(!(isset($baseURL))){
	$baseURL = "../../public/";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Jackpot Bowling</title>

	<!-- CSS StyleSheet -->
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ $baseURL }}css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="{{ $baseURL }}css/bootstrap-xl.css">
	<link rel="stylesheet" type="text/css" href="{{ $baseURL }}css/style.css">

	<!-- Fonts & Icons -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:100,400,300,500,700,900,300italic,400italic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="https://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- JavaScript -->
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="{{ $baseURL }}js/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{ $baseURL }}js/jquery.shuffleLetters.js"></script>
    <script type="text/javascript" src="{{ $baseURL }}js/script.js"></script>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/lottery') }}">Bowling</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">				
				<ul class="nav navbar-nav navbar-right">
					<li><a href="{{ url('/bowlers') }}">Bowlers</a></li>
					<li><a href="{{ url('/league') }}">League</a></li>
					<li><a href="{{ url('/lottery') }}">Lottery</a></li>
					<li><a href="{{ url('/results') }}">Results</a></li>					
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')	
</body>
</html>
