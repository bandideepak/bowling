@extends('app')

@section('content')

<div class="main-container">		
		<div class="content-section">
			<div class="row">										
				<div class="col-xl-8 col-md-9 col-xl-offset-1">
					<div class="league-list-section">
						<div class="row">
							<div class="col-md-12">
								<div class="league-list-title">
									<h2>Lottery</h2>
									<a href="#" class="join-button create-lottery bg-green league-9 id-57">Create Lottery</a>
								</div>
							</div>
						</div>
						<div class="row">									
						<?php if($currentJackpot) : ?>
							<?php $currentJackpot = json_encode( $currentJackpot ); 
							$currentJackpot = json_decode($currentJackpot); ?>
							<?php foreach($currentJackpot as $league): ?>							
								<div class="col-xl-4 col-lg-6 col-md-6">
									<div class="league-holder">
										<div class="league-header">
											<img src="{{ $baseURL }}imgs/league_<?php print ($league[0]->data->league_id > 9 ? $league[0]->data->league_id%10 : $league[0]->data->league_id)?>.jpg">													
											<h4><?php print $league[0]->data->lottery_name ?></h4>
											<span><?php print $league[0]->data->league_name ?></span>
										</div>
										<div class="league-body">
											<h4 class="label label-success">Jackpot : $<span><?php print $league[0]->data->balence ?></span></h4>
											<?php $allUserBowlers = array(); ?>
											<?php foreach ($league as $bowlers): 	
													/* Creating an array with all Bowlers of the league */												
													array_push($allUserBowlers, $bowlers->data->user_id);		
											endforeach;
											$currentUser = Auth::user()->id; ?>

											<!-- If the Logged In User has joined the league -->											
											<?php if (!(in_array($currentUser, $allUserBowlers)) && $league[0]->is_joined_league == 0): ?>												
												<!-- If User has not joined the league -->
												<a href="#" class="join-button join-league league-<?php print $league[0]->data->league_id ?> id-<?php print $league[0]->data->lottery_id ?>"><i class="fa fa-sign-in"> </i> Join League</a>											    
											<?php else: ?>
												<?php $flagTicket = 0;						
													 if($currentUser == $league[0]->current_user): ?>														 	
													 	<?php foreach ($league as $bowlers):?>
													 		<?php if($bowlers->data->ticket == 10 && $currentUser == $bowlers->data->id): ?>
																<!-- If User has bought the ticket for the lottery -->	
																<?php $flagTicket = 1; ?>																
																<a href="play/<?php print $league[0]->data->lottery_id ?>" class="join-button bg-green play-now"><i class="fa fa-soccer-ball-o"> </i> Play Now</a>															
															<?php endif ?>
														<?php endforeach; ?>
														<?php if($flagTicket == 0): ?>
																<!-- If User has not bought the ticket for the lottery of a league-->																														
																<a href="#" class="join-button border-green buy-ticket id-<?php print $league[0]->data->lottery_id ?>"><i class="fa fa-ticket"> </i> Buy Tickets</a>	
														<?php endif ?>
													<?php else: ?>
														<!-- If User has not bought the ticket for the lottery of a league-->																												
															<a href="#" class="join-button border-green buy-ticket id-<?php print $league[0]->data->lottery_id ?>"><i class="fa fa-ticket"> </i> Buy Ticket</a>
													<?php endif; 
												?>												
											<?php endif ?>																			
										</div>
										<div class="league-info">
											<div class="league-desc">
												<h4><i class="fa fa-info"> </i> Info</h4>
												<p><i class="fa fa-clock-o"></i> : 5:00 PM CST</p>
												<p><i class="fa fa-map-marker"></i> : 10 Pin Chicago</p>
												<p class="italic more-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
											</div>
											<div class="profile-info">
												<h4><i class="fa fa-soccer-ball-o"> </i> Bowlers</h4>
												<?php foreach($league as $bowlers): ?>
													<?php if($bowlers->data->ticket == 10): ?>
														<img src="{{ $baseURL }}imgs/admin_<?php print ($bowlers->data->user_id > 9 ? $bowlers->data->user_id%10 : $bowlers->data->user_id) ?>.jpg" alt="Bowlers">
													<?php endif ?>
												<?php endforeach ?>																													
											</div>									
										</div>
										<div class="league-footer">
										</div>
									</div>
								</div>													    							    							     
							<?php endforeach ?>												       	
						<?php endif; ?>											
						</div>
					</div>
				</div>
				<div class="col-xl-2 col-md-3 col-xl-offset-1">
					<div class="right-sidebar-section">						
						<div class="sidebar-history">
							<div class="sidebar-history-title">
								<h4>Result's</h4>
							</div>
							<div class="sidebar-history-info">
								<?php if($pastLottery) : ?>
									<?php $counter = 0; ?>									
									<?php $pastLottery = json_encode( $pastLottery ); 
									$pastLottery = json_decode($pastLottery); ?>
									<?php foreach($pastLottery as $lottery): ?>
										<?php if($counter < 5): ?>
											<div class="single-history-league-holder">
												<div class="single-history-league-title">
													<div class="img-container-wrapper">
														<div class="img-container">
															<img src="{{ $baseURL }}imgs/league_<?php print $lottery[0]->data->league_id ?>.jpg">
														</div>		
													</div>							
													<h4><?php print $lottery[0]->data->lottery_name ?></h4>	
													<span><?php print $lottery[0]->data->league_name ?></span>								
												</div>												
												<div class="single-history-league-body">
												<?php foreach ($lottery as $results): ?>
													<div class="league-result-players">
														<div class="img-container">
															<img src="{{ $baseURL }}imgs/admin_<?php print ($results->data->user_id > 9 ? $results->data->user_id%10 : $results->data->user_id) ?>.jpg">
														</div>									
														<h4><?php print $results->data->name ?></h4>	
														<h5 class="label label-default"><span><?php print $results->data->lottery_amount ?></span></h5>
													</div>
												<?php endforeach ?>
												</div>
												<div class="single-history-league-footer">
													<a class="text-link" href="{{ url('results') }}">View Full Results</a>
													<a class="join-button" href="{{ url('results') }}"><i class="fa fa-eye"> </i> View</a>
												</div>
											</div>
											<?php $counter++; ?>
										<?php endif; ?>
									<?php endforeach ?>									
								<?php endif ?>																						
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Modal for Craating new league -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">  
  <div class="play-league-wrapper">
		<div class="play-league-section">
			<div class="play-league-header">
				<img src="{{ $baseURL }}imgs/league_3.jpg">							
			</div>
			<div class="play-league-body">
				<h3>Create a Lottery</h3>
				<form>
					<div class="form-group">
					    <label for="league-name">Lottery Name</label>
					    <input type="text" class="form-control" id="league-name" placeholder="Lottery Name" required>
					</div>			
					<div class="form-group">
						<label for="league-id">Select League</label>
						
							<?php if($allLeagueID) : ?>
								<?php $allLeagueID = json_encode( $allLeagueID ); 
								$allLeagueID = json_decode($allLeagueID); 
								$singleLeague = array();
								?>
								<select class="form-control" id="lottery-select">
								<?php foreach($allLeagueID as $league): ?>	
									<option value="<?php print $league->league_id ?>"><?php print $league->league_id ?> : <?php print $league->league_name ?></option>
								<?php endforeach; ?>
								</select>													
							<?php endif; ?>											 						
						
					</div>								
					<button type="button" class="btn btn-default new-lottery">Submit</button>					
				</form>	
														
				<button type="button" class="join-button close-window" data-dismiss="modal"> Close</button>
			</div>											
		</div>	
	</div>
</div>

<!-- Modal for Ticket Booking Confirmation -->
<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel">  
  <div class="play-league-wrapper">
		<div class="play-league-section">
			<div class="play-league-header">
				<img src="{{ $baseURL }}imgs/league_3.jpg">							
			</div>
			<div class="play-league-body">							
				<h3>Transaction Success</h3>
				<img src="{{ $baseURL }}imgs/ticket.png">															
				<h5>You have successfully bought the ticket.</h5>													
				<button type="button" class="join-button close-window" data-dismiss="modal"> Close</button>
			</div>											
		</div>	
	</div>
</div>

<!-- Modal for Joining League Confirmation -->
<div class="modal fade" id="leagueModal" tabindex="-1" role="dialog" aria-labelledby="leagueModalLabel">  
  <div class="play-league-wrapper">
		<div class="play-league-section">
			<div class="play-league-header">
				<img src="{{ $baseURL }}imgs/league_3.jpg">							
			</div>
			<div class="play-league-body">							
				<h3>Thank you</h3>
				<img src="{{ $baseURL }}imgs/ball.png">															
				<h5>You have successfully joined the league.</h5>													
				<button type="button" class="join-button close-window" data-dismiss="modal"> Close</button>
			</div>											
		</div>	
	</div>
</div>


<!-- Modal to become a bowler -->
<div class="modal fade" id="bowlerModal" tabindex="-1" role="dialog" aria-labelledby="bowlerModalLabel">  
  <div class="play-league-wrapper">
		<div class="play-league-section">
			<div class="play-league-header">
				<img src="{{ $baseURL }}imgs/league_3.jpg">							
			</div>
			<div class="play-league-body">							
				<h3>Sorry!!</h3>
				<img src="{{ $baseURL }}imgs/pincrushers.jpg">															
				<h5>You need to become Bowler to Join League</h5>														
				<button type="button" class="join-button become-bowler" data-dismiss="modal"> Become a Bowler</button>
			</div>											
		</div>	
	</div>
</div>

<!-- Modal for Bowler Notification -->
<div class="modal fade" id="bowlerNotifyModal" tabindex="-1" role="dialog" aria-labelledby="bowlerModalNotifyLabel">  
  <div class="play-league-wrapper">
		<div class="play-league-section">
			<div class="play-league-header">
				<img src="{{ $baseURL }}imgs/league_3.jpg">							
			</div>
			<div class="play-league-body">							
				<h3>Congrats</h3>
				<img src="{{ $baseURL }}imgs/ball.png">															
				<h5>Go ahead and Join Leagues</h5>														
				<button type="button" class="join-button close-window" data-dismiss="modal"> Close</button>
			</div>											
		</div>	
	</div>
</div>

<!-- Modal for League Notification -->
<div class="modal fade" id="createNotifyModal" tabindex="-1" role="dialog" aria-labelledby="createModalNotifyLabel">  
  <div class="play-league-wrapper">
		<div class="play-league-section">
			<div class="play-league-header">
				<img src="{{ $baseURL }}imgs/league_3.jpg">							
			</div>
			<div class="play-league-body">							
				<h3>Thank you</h3>
				<img src="{{ $baseURL }}imgs/ball.png">															
				<h5>A Lottery has been successfully created</h5>														
				<button type="button" class="join-button close-window" data-dismiss="modal"> Close</button>
			</div>											
		</div>	
	</div>
</div>

<script type="text/javascript">
 $(function(){
 		
 		$('.new-lottery').click(function() {
       		var lotteryName = $('#league-name').val();
       		var leagueId = $( "#lottery-select" ).val();       		
           $.ajax({
                url: 'createlottery/{league_id}/{lottery_name}',
                type: 'GET',
                data: { league_id:leagueId, lottery_name:lotteryName },
                success: function(data)
                {                                     	      
                	$('#createNotifyModal').modal('show');
                }
            });
       	});


       	$('.buy-ticket').click(function() {
       		var dataString = $(this).attr('class').split('id-')[1];
            $.ajax({
                url: 'buyticket/{lottery_id}',
                type: 'GET',
                data: { lottery_id:dataString },
                success: function(data)
                {
                    $('#ticketModal').modal('show');
                }
            });
       	});

       	$('.join-league').click(function() {
       		var lotteryId = $(this).attr('class').split('id-')[1];
       		var leagueId = $(this).attr('class').split('league-')[1].split(' ')[0];
            $.ajax({
                url: 'joinleague/{league_id}/{lottery_id}',
                type: 'GET',
                data: { league_id:leagueId, lottery_id:lotteryId },
                success: function(data)
                {                             	
                	if(data == 1){
                		$('#leagueModal').modal('show');
                	}
                	else{                		
                		$('#bowlerModal').modal('show');
                	}                    
                    
                }
            });
       	});

       	$('.become-bowler').click(function() {       		
            $.ajax({
                url: 'becomebowler',
                type: 'GET',
                data: { id:"" },
                success: function(data)
                {
                    $('#bowlerNotifyModal').modal('show');
                }
            });
       	});
    });  

$('.create-lottery').click(function(){
	$('#createModal').modal('show');
});

 $('.close-window').click(function(){
 	location.reload();
 });

$('.new-lottery').click(function(){
	$('#createModal').addClass('hide');
})

</script>

@endsection