@extends('app')

@section('content')

<div class="main-container">		
		<div class="content-section">
			<div class="row">										
				<div class="col-xl-10 col-md-12 col-xl-offset-1">
					<div class="league-list-section">
						<div class="row">
							<div class="col-md-12">
								<div class="league-list-title">
									<h2>League</h2>
									<a href="#" class="join-button create-lottery bg-green league-9 id-57">Create League</a>
								</div>
							</div>
						</div>
						<div class="row">									
						<?php if($bowlersInLeague) : ?>
							<?php $bowlersInLeague = json_encode( $bowlersInLeague ); 
							$bowlersInLeague = json_decode($bowlersInLeague); ?>
							<?php foreach($bowlersInLeague as $league): ?>												
								<div class="col-xl-3 col-lg-4 col-md-6">
									<div class="league-holder">
										<div class="league-header">
											<img src="{{ $baseURL }}imgs/league_<?php print ($league[0]->league_id > 9 ? $league[0]->league_id%10 : $league[0]->league_id)?>.jpg">													
											<h4><?php print $league[0]->league_name ?></h4>					
										</div>
										<div class="league-body">	
											<?php $allUserBowlers = array(); ?>
											<?php foreach ($league[0]->bowlers as $bowlers): 	
													/* Creating an array with all Bowlers of the league */												
													array_push($allUserBowlers, $bowlers->id);		
											endforeach;
											$currentUser = Auth::user()->id; ?>
											<?php if (!(in_array($currentUser, $allUserBowlers))): ?>												
												<!-- If User has not joined the league -->
												<a href="#" class="join-button join-league league-<?php print $league[0]->league_id ?> id-<?php print $league[0]->lottery[0]->lottery_id ?>"><i class="fa fa-sign-in"> </i> Join League</a>											    
											<?php else: ?>
												<button class="join-button bg-green"><i class="fa fa-sign-in"> </i> Joined</button>
											<?php endif; ?>
										</div>
										<div class="league-info">
											<div class="league-desc">
												<h4><i class="fa fa-info"> </i> Info</h4>
												<p><i class="fa fa-clock-o"></i> : 10:00 PM CST</p>
												<p><i class="fa fa-map-marker"></i> : 10:00 PM</p>
												<p class="italic more-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
											</div>
											<div class="profile-info">
												<h4><i class="fa fa-soccer-ball-o"> </i> Bowlers</h4>	
												<?php foreach ($league[0]->bowlers as $Bowlers): ?>
													<img src="{{ $baseURL }}imgs/admin_<?php print ($Bowlers->id > 9 ? $Bowlers->id%10 : $Bowlers->id)?>.jpg">
												<?php endforeach; ?>																																						
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
				<h3>Create a League</h3>
				<form>
					<div class="form-group">
					    <label for="league-name">League Name</label>
					    <input type="text" class="form-control" id="league-name" placeholder="League Name" required>
					</div>											
					<button type="button" class="btn btn-default new-league">Submit</button>
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
				<h5>A League has been successfully created</h5>														
				<button type="button" class="join-button close-window" data-dismiss="modal"> Close</button>
			</div>											
		</div>	
	</div>
</div>

<script type="text/javascript">
 $(function(){

 		$('.new-league').click(function() {
       		var leagueName = $('#league-name').val();       			
           $.ajax({
                url: 'createleague/{league_name}',
                type: 'GET',
                data: { league_name:leagueName },
                success: function(data)
                {                                     	                    	
                	if(data == 1){
                		$('#createNotifyModal').modal('show');
                	}
                	else{                		
                		location.reload();
                	}                                       
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
</script>


@endsection