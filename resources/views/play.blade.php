@extends('app')

@section('content')

<div class="main-container">	
		<div class="content-section">
			<div class="row">										
				<div class="fullscreen-bg">
					<?php foreach ($pinCount as $pin){ $pinValue = $pin; } ?>
					<video muted poster="{{ $baseURL }}imgs/videoframe.png" class="fullscreen-bg__video" id="media-video">
				        <source src="{{ $baseURL }}imgs/pin_count_<?php print $pinValue ?>.webm" type="video/webm">
				        <source src="{{ $baseURL }}imgs/pin_count_<?php print $pinValue ?>.mp4" type="video/mp4">
				        <source src="{{ $baseURL }}imgs/pin_count_<?php print $pinValue ?>.ogv" type="video/ogg">
				    </video>
				</div>
				<div class="play-league-wrapper">
					<?php if($isGame == 1): ?>
					<div class="play-league-section">
						<div class="play-league-header">
							<img src="{{ $baseURL }}imgs/league_3.jpg">
							<h1>Play League</h1>
						</div>
						<div class="play-league-body">
							<h4 class="label label-success">Jackpot : <span><?php foreach ($currentLotteryJackpot as $value){ print $value->balence; } ?></span></h4>
							<h4>Ticket Being Drawn & Winner is</h4>
							<div id="winner">
								<h4>{{$luckyBowler}}</h4>
							</div>			
							<?php if($isLuckyBowler == 1): ?>
								<button class="join-button bowl-now"><i class="fa fa-soccer-ball-o"> </i> Bowl Now</button>
							<?php else: ?>
								<?php if($pinValue > 1): ?>
									<h5>{{$luckyBowler}} got a Strike!!</h5>															
									<a href="../lottery" class="join-button back-button"><i class="fa fa-arrow-left"> </i> </i> Back</a>
								<?php else: ?>
									<h5>Lucky you! {{$luckyBowler}} did not get a Strike</h5>	
									<a href="#" class="join-button play-again"><i class="fa fa-soccer-ball-o"> </i> Play Again</a>
									<a href="{{ url('/lottery') }}" class="join-button back-button"><i class="fa fa-arrow-left"> </i> </i> Back</a>														
									
								<?php endif; ?>
							<?php endif; ?>				
							
						</div>
						<div class="play-league-result">							
							<?php if($pinValue > 1): ?>
								<h4 class="label label-success">You Won : <span><?php foreach ($currentLotteryJackpot as $value){ print $value->balence; } ?></span></h4>
								<h4>Congrats!! You got a Strike</h4>
								<div id="winner">
									<h4>{{$luckyBowler}}</h4>
								</div>							
								<a href="../lottery" class="join-button back-button"><i class="fa fa-arrow-left"> </i> </i> Back</a>
							<?php else: ?>
								<h4 class="label label-success">You Won : <span><?php foreach ($currentLotteryJackpot as $value){ print $value->balence / 10; } ?></span></h4>
								<h4>Sorry! You did not get a Strike</h4>
								<div id="winner">
									<h4>{{$luckyBowler}}</h4>
								</div>									
								<a href="#" class="join-button play-again"><i class="fa fa-soccer-ball-o"> </i> Play Again</a>
							<?php endif; ?>
						</div>								
					</div>
					<?php else: ?>
						<div class="play-league-section">
							<div class="play-league-header">
								<img src="{{ $baseURL }}imgs/league_3.jpg">
								<h1>Play League</h1>
							</div>
							<div class="play-league-body">
								<h4>Sorry!! There should be atleast 3 bowlers to play a Lottery.</h4>
								<h5>Please come back after some time.</h5>
								<a href="../lottery" class="join-button back-button"><i class="fa fa-arrow-left"> </i> </i> Back</a>
							</div>
						</div>
					<?php endif; ?>	
				</div>
				<div class="skip-video">
					<button class="join-button"><i class="fa fa-youtube-play"> </i> Skip Video</button>
				</div>					
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('.skip-video').hide();
		$('.play-league-result').hide();

		$('.bowl-now').click(function () {		   
		    $("#media-video").get(0).play();	
		    $('.play-league-wrapper').hide();
		    $('.skip-video').show();	   
		});

		$('#media-video').on('ended',function(){
	      	$('.play-league-wrapper').show();	
	      	$('.play-league-body').hide();  
	      	$('.play-league-result').show();  
	      	$('.skip-video').hide();  	
	    });

	    $('.skip-video').click(function(){
	    	$("#media-video").get(0).pause();
	    	$('.play-league-wrapper').show();
	    	$('.play-league-body').hide();  
	      	$('.play-league-result').show(); 
		    $('.skip-video').hide();	   
	    });	   

	    $('.play-again').click(function(){
	    	location.reload();
	    });
	</script>

@endsection