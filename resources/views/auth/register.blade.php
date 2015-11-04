@extends('app')

@section('content')

<?php 
if(!(isset($baseURL))){
	$baseURL = "../../public/";
}
?>

<div class="login-bg">
<div class="container">
	<div class="sign-screen">
		<div class="row sing-screen-wrapper">
			<div class="col-sm-6">
				<div class="screen-logo-section">
					<img src="{{ $baseURL }}imgs/logo.png" alt="Logo">
					<h2>Bowling</h2>
					<div class="screen-title-section">
						<h1>Welcome to the <br>Jackpot Bowling.</h1>						
					</div>	
					<p>Play lottery and win a Jackpot.</p>	
					<p>Please Login / Register to start playing the game.</p>			
				</div>
			</div>
			<div class="col-sm-6">
				<div class="screen-login-section">
					<div class="panel panel-default">						
						<div class="panel-body">
							@if (count($errors) > 0)
								<div class="alert alert-danger">
									<strong>Whoops!</strong> There were some problems with your input.<br><br>
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif

							<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}" autocomplete="off">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">

								<div class="form-group">									
									<div class="col-md-12">
										<input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name">
									</div>
								</div>

								<div class="form-group">									
									<div class="col-md-12">
										<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email Address">
									</div>
								</div>

								<div class="form-group">									
									<div class="col-md-12">
										<input type="password" class="form-control" name="password" placeholder="Password">
									</div>
								</div>

								<div class="form-group">									
									<div class="col-md-12">
										<input type="password" class="form-control" name="password_confirmation" placeholder="Retype Password">
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-6">
										<button type="submit" class="btn sign-button">
											Register
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>	
</div>
</div>
@endsection
