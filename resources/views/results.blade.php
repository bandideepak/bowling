@extends('app')

@section('content')

<div class="main-container">
         <div class="content-section">
            <div class="row">
               <div class="col-md-12 padding-0">
                  <div class="result-section-header">
                     <div class="container">
                        <div class="row">
                           <div class="col-sm-6">
                              <div class="user-img-holder">
                                 <img src="{{ $baseURL }}imgs/admin_<?php print ($userDetails->id > 9 ? $userDetails->id%10 : $userDetails->id) ?>.jpg" alt="Bowler">
                              </div>
                              <div class="user-content-holder">                                 
                                 <h4>{{$userDetails->name}}</h4>
                                 <p>{{$userDetails->email}}</p>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div class="user-info-holder">
                                 <div class="row">
                                    <div class="col-sm-6 info-holder-items">
                                       <h4>{{$jackpotCount}}</h4>
                                       <p>Jackpot Won</p>
                                    </div>
                                    <div class="col-sm-6 info-holder-items">
                                       <h4>{{$totalJackpot}}</h4>
                                       <p>$ Jackpot Amount</p>
                                    </div>
                                    <div class="col-sm-6 info-holder-items">
                                       <h4>{{$totalTickets}}</h4>
                                       <p>Total Lottries</p>
                                    </div>
                                    <div class="col-sm-6 info-holder-items">
                                       <h4>{{$totalLeagues}}</h4>
                                       <p>Total Leagues</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="result-section-content">
                     <div class="container">
                        <div class="row">
                           <div class="col-sm-12">
                              <table class="table table-striped table-responsive">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>Lottery Name</th>
                                       <th>League Name</th>
                                       <th>Bowler</th>
                                       <th>Lottery Amount</th>
                                       <th>Ticket</th>
                                       <th>Result</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php if($jackpotResults) : ?>
                                    <?php 
                                       $jackpotResults = json_encode( $jackpotResults ); 
                                       $jackpotResults = json_decode($jackpotResults); 
                                       ?>                    
                                    <?php foreach ($jackpotResults as $eachJackpot): ?>
                                    <?php foreach ($eachJackpot as $results): ?>
                                    <tr>
                                       <th scope="row"><?php print $results->data->jackpot_id; ?></th>
                                       <td><?php print $results->data->lottery_name; ?></td>
                                       <td><?php print $results->data->league_name; ?></td>
                                       <td><img class="table-bowler" src="{{ $baseURL }}imgs/admin_<?php print ($results->data->user_id > 9 ? $results->data->user_id%10 : $results->data->user_id) ?>.jpg"><?php print $results->data->name; ?></td>
                                       <td>$ <?php print $results->data->lottery_amount; ?></td>
                                       <td>$ <?php print $results->data->ticket; ?></td>
                                       <th scope="row"><?php if($results->data->is_winner == 1) { ?> <span class="label label-success">Winner</span> <?php } ?></th>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endforeach; ?>
                                    <?php endif; ?>                      
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

@endsection