<?php 

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use App\Lottery;
use App\League;
use Cache;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->middleware('auth');
		$user = Auth::user();
		if ($user)
		{
		    /*echo "$user->id";*/
		}
				
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			
		}
		else{
			
		}		
	}
	
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$baseURL = '../public/';		

		$currentJackpot;
		$activeLottery = Lottery::getActiveLotteryID();
		foreach($activeLottery as $lottery){			

			foreach ($lottery as $lottery_id) {				
				$currentLottery = Lottery::getCurentJackpotDetailsByLotteryId($lottery_id);				
				
            	foreach($currentLottery as $lottery){
	                $currentJackpot[$lottery_id][] = [
	                    'data' => $lottery	                                          
	                ];
            	}
			}                
        }

        return \View::make('lottery')->with('currentJackpot', $currentJackpot)->with('baseURL', $baseURL);
	}

	public function league(){
		$baseURL = '../public/';
		$user = Auth::user();

		$allLeague = League::getAllLeague();	
		$allLeagueID = League::getAllLeaguebyGroup();

		foreach ($allLeagueID as $league) {

			$lottery = Lottery::getLottriesOfLeague($league->league_id);
			$bowlers = League::getBowlersinLeague($league->league_id);	

			$bowlersInLeague[$league->league_id][] = [
				'league_id' => $league->league_id,
				'league_name' => $league->league_name,
				'bowlers' => $bowlers,
				'lottery' => $lottery
			];	
		}			
		return \View::make('league')->with('allLeague', $allLeague)->with('bowlersInLeague', $bowlersInLeague)->with('baseURL', $baseURL);
	}

	public function createlottery(){
		$league_id = $_GET['league_id'];	
		$lottery_name = $_GET['lottery_name'];

		$newLottery = Lottery::createLottery($league_id, $lottery_name);
		return $newLottery;
	}

	public function createleague(){
		$league_name = $_GET['league_name'];
		League::createLeaguebyName($league_name);
		return 1;
	}

	public function lottery()
	{
		$baseURL = '../public/';
		$user = Auth::user();			

		$currentJackpot;		

		$allLeague = League::getAllLeagueID();
		$allLottery = Lottery::getActiveLotteryID();
		$is_bowler = User::isBolwer($user->id);

		foreach($allLottery as $lottery){			
			
			foreach ($lottery as $lottery_id) {	

				$currentLottery = Lottery::getCurentJackpotDetailsByLotteryId($lottery_id);				
				
            	foreach($currentLottery as $lottery){  
            		$hasJoinedLeague = League::hasJoinedLeague($lottery->league_id, $user->id);              			
            		

            		if(empty($hasJoinedLeague)){
            			$is_joined_league = 0;
            		}	
            		else{
            			$is_joined_league = 1;
            		}
            		$usersInLeague = League::getUsersinLeague($lottery->league_id);            		

	                $currentJackpot[$lottery_id][] = [
	                    'data' => $lottery,	                    
	                    'is_joined_league' => $is_joined_league,	
	                    'current_user' => $user->id,
	                    'usersInLeague' => $usersInLeague                                          
	                ];
            	}
			}               
        }		
        
        $pastLotteryResults = Lottery::getInactiveLotteryResults();	        
        if(empty($pastLotteryResults)){
        	$pastLottery = 0;
        }
        
        foreach ($pastLotteryResults as $lotteryResult) {
        		$pastLottery[$lotteryResult->lottery_id][] = [
	                    'data' => $lotteryResult,	                    
	            ];	            
        }   

        $pastLottery = array_reverse($pastLottery);      

        $allLeagueID = League::getAllLeaguebyGroup();
        
		return \View::make('lottery')->with('currentJackpot', $currentJackpot)->with('pastLottery', $pastLottery)->with('allLeagueID', $allLeagueID)->with('is_bowler', $is_bowler)->with('baseURL', $baseURL);
	}

	public function play($lottery_id)
	{
		$baseURL = '../../public/';
		$user = Auth::user()->id;

		$currentLotteryJackpot = Lottery::getCurrentJackpot($lottery_id);

		$bowlers = Lottery::getUsersofLottery($lottery_id);
		
		/* If there are more than two bowlers */
		if(count($bowlers) > 2){

			$allBowlers = array();
			$isGame = 1;

			foreach ($bowlers as $currentBowler) {
				$allBowlers[$currentBowler->id] = $currentBowler->name;
			}

			/* Picks a Bowler from drawing */
			$luckyBowler = $allBowlers[array_rand($allBowlers)];
			$luckyBowlerId = User::getUserIdbyName($luckyBowler);
			$bowlerId = $luckyBowlerId[0]->id;

			/* RollOut */
			$pinCount[0] = rand(0, 2);

			/* If the bowler gets a Strike */
			$jackpotAmount = Lottery::getCurrentJackpot($lottery_id);
			if($pinCount[0] == 2){									
				$winnerAmount = $jackpotAmount[0]->balence;				
				Lottery::strike($lottery_id, $bowlerId, $winnerAmount);						
			}
			else{
				$winnerAmount = $jackpotAmount[0]->balence / 10;
				Lottery::nonstrike($lottery_id, $bowlerId, $winnerAmount);	
			}

			/* If the lucky Bowler is current user */
			$isLuckyBowler = 0;
			if($user == $luckyBowlerId[0]->id){
				$isLuckyBowler = 1;
			}

			Lottery::jackpotAmount($lottery_id, $bowlerId, $winnerAmount);
			return \View::make('play')->with('currentLotteryJackpot', $currentLotteryJackpot)->with('luckyBowler', $luckyBowler)->with('isLuckyBowler', $isLuckyBowler)->with('pinCount', $pinCount)->with('isGame', $isGame)->with('baseURL', $baseURL);
		}	
		/* If there are not more than two bowlers */
		else{
			$isGame = 0;
			$pinCount[0] = 0;
			return \View::make('play')->with('pinCount', $pinCount)->with('isGame', $isGame)->with('baseURL', $baseURL);
		}			
		
	}

	public function buyticket()
	{
		$lottery_id = $_GET['lottery_id'];	

		$baseURL = '../../public/';
		$user = Auth::user()->id;


		$checkTicket = Lottery::checkBuyTicket($lottery_id, $user);		

		if(empty($checkTicket)){
			Lottery::buyLotteryTicket($lottery_id, $user);			
		}
		else{
			Lottery::buyticket($lottery_id, $user);	
		}		

		$currentLotteryJackpot = Lottery::getCurrentJackpot($lottery_id);
	}	

	public function becomebowler(){
		$baseURL = '../../public/';
		$user = Auth::user()->id;

		User::setBowler($user);
	}

	public function joinleague()
	{	
		$lottery_id = $_GET['lottery_id'];	
		$league_id = $_GET['league_id'];

		$baseURL = '../../public/';
		$user = Auth::user()->id;			

		$isBowler = User::isBolwer($user);	
		
		if($isBowler[0]->is_bowler == 0){
			return 0;
		}
		else{			
			League::joinLeague($league_id, $lottery_id, $user);
			return 1;
		}		
	}

	public function results()
	{
		$baseURL = '../public/';
		$allJackpotResults = Lottery::getJackpotResults();

		if(empty($allJackpotResults)){
        	$jackpotResults = 0;
        }
        
        foreach ($allJackpotResults as $lotteryResult) {
        		$jackpotResults[$lotteryResult->lottery_id][] = [
	                    'data' => $lotteryResult,	                    
	            ];	            
        }

        $userDetails = Auth::user();

        $jackpotCount = Lottery::getJackpotCountOfUser($userDetails->id);
        $totalTickets = Lottery::getTotalLotteryCount($userDetails->id);
        $totalJackpot = Lottery::getTotaljackpotAmountofUser($userDetails->id);
        $totalLeagues = League::getLeagueByBowler($userDetails->id);

		return \View::make('results')->with('jackpotResults', $jackpotResults)->with('userDetails', $userDetails)->with('jackpotCount', $jackpotCount)->with('totalTickets', $totalTickets)->with('totalJackpot', $totalJackpot)->with('totalLeagues', $totalLeagues)->with('baseURL', $baseURL);
	}

	public function bowlers(){
		$baseURL = '../public/';
		$allBowlers = User::getAllBolwerDetails();
		
		if(empty($allBowlers)){
        	$allBowlers = 0;
        }

        foreach ($allBowlers as $bowler) {
        		$Bowlers[$bowler->id][] = [
	                    'data' => $bowler,	                    
	            ];	            
        }       

        $userDetails = Auth::user();

        $jackpotCount = Lottery::getJackpotCountOfUser($userDetails->id);
        $totalTickets = Lottery::getTotalLotteryCount($userDetails->id);
        $totalJackpot = Lottery::getTotaljackpotAmountofUser($userDetails->id);
        $totalLeagues = League::getLeagueByBowler($userDetails->id);

		return \View::make('bowlers')->with('Bowlers', $Bowlers)->with('userDetails', $userDetails)->with('jackpotCount', $jackpotCount)->with('totalTickets', $totalTickets)->with('totalJackpot', $totalJackpot)->with('totalLeagues', $totalLeagues)->with('baseURL', $baseURL);
	}

}
