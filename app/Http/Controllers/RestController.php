<?php 

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use App\Lottery;
use App\League;
use Cache;
use Response;
use Request;
use Hash;
use Curl;

class RestController extends Controller {

	public function __construct()
	{

	}

	public function getToken(){
	    return Response::json(['token'=>csrf_token()]);
	}	

	public function users()
	{		
			$postedData = file_get_contents("php://input"); 	
			$postedData = substr($postedData, 2, -2);		
			$postedData = explode(',', $postedData);				

			foreach ($postedData as $value) {
				$data[explode(':', $value)[0]] = explode(':', $value)[1];
			}

			$name =  $data['name'];
			$email =  $data['email'];
			$password = $data['password'];		

			$credentials['name'] = $name;
			$credentials['email'] = $email;
			$credentials['password'] = Hash::make($password);	
		 	
			try {
		       	$user = User::create($credentials);
		       	return $user[0]->id;
		   	} catch (Exception $e) {
		       	return Response::json(['error' => 'User already exists.'], Illuminate\Http\Response::HTTP_CONFLICT);
		   	}
	}

	public function login()
	{	
		$isValidToken = User::checkAuth();
	
		if($isValidToken != 0){
			return json_encode($isValidToken);
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function createLeague()
	{
		$postedData = file_get_contents("php://input"); 	
		$postedData = substr($postedData, 2, -2);		

		$isValidToken = User::checkAuth();

		if($isValidToken != 0){
			$leagueName = explode(':', $postedData)[1];
			return League::createLeaguebyName($leagueName);
		}	
		else{
			return json_encode("Please enter valid Authentication Token");
		}				
	}

	public function getAllLeague()
	{
		$isValidToken = User::checkAuth();

		if($isValidToken != 0){
			$allLeagues = League::getAllLeague();
			return \Response::json($allLeagues, 200);
		}	
		else{
			return json_encode("Please enter valid Authentication Token");
		}		
	}

	public function getLeagueByID($leagueId)
	{		
		$isValidToken = User::checkAuth();

		if($isValidToken != 0){
			$thisLeague = League::getLeagueByID($leagueId);
			$leagueDetails = \Response::json($thisLeague, 200);
			if(empty($thisLeague)){
				return json_encode("Invalid League ID");
			}
			else{
				return $leagueDetails;
			}
		}	
		else{
			return json_encode("Please enter valid Authentication Token");
		}		
	}

	public function setBowler()
	{
		$postedData = file_get_contents("php://input"); 	
		$postedData = substr($postedData, 2, -2);	

		$bowlerName = explode(':', $postedData)[1];

		$isValidToken = User::checkAuth();		

		if($isValidToken != 0){

			foreach ($isValidToken as $key => $value) {
				$bowlerDetails = json_encode($value);
			}
			$bowlerDetails = substr($bowlerDetails, 3, -3);		
			$bowlerDetails = explode(',', $bowlerDetails);

			foreach ($bowlerDetails as $value) {				
				$bowlerID = explode(':', $value)[1];	

				$bowler = User::setBowlerByName($bowlerID, $bowlerName);	
				return \Response::json($bowler, 200);			
			}					
		}	
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function getAllBowlers()
	{
		$isValidToken = User::checkAuth();

		if($isValidToken != 0){
			$allBowlers = User::getAllBolwers();
			return \Response::json($allBowlers, 200);
		}
		else{
			return \Response::json("Please enter valid Authentication Token", 200);			
		}		
	}

	public function getBowlerByID ($bowlerId)
	{
		$isValidToken = User::checkAuth();

		if($isValidToken != 0){
			$thisBowler = User::getUserByID($bowlerId);

			if(empty($thisBowler)){
				return "Invalid Bowler ID";
			}
			return \Response::json($thisBowler, 200);
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
		
	}

	public function addBowler($leagueId)
	{
		$isValidToken = User::checkAuth();			

		if($isValidToken != 0){
			$postedData = file_get_contents( 'php://input');			
			$postedData = substr($postedData, 2, -2);

			$BowlerName = explode(':', $postedData)[1];
			$BowlerId = User::getBowlerIDByName($BowlerName);
			$BowlerId = $BowlerId[0]->id;			

			$addedBowler = League::addBowlertoLeague($BowlerId, $leagueId);
			return \Response::json($addedBowler, 200);
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function getBowlersInLeague($leagueId)
	{		
		$isValidToken = User::checkAuth();
 
		if($isValidToken != 0){
			$allBowlers = League::getBowlersinLeague($leagueId);

			if(empty($allBowlers)){
				return "Invalid League ID or No Bowlers in this League";
			}
			return \Response::json($allBowlers, 200);
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function getLottriesOfLeague($leagueId)
	{
		$isValidToken = User::checkAuth();
		
		if($isValidToken != 0){
			$allLottries = Lottery::getLottriesOfLeague($leagueId);

			if(empty($allLottries)){
				return "Invalid Lottery ID or No Lottries in this League";
			}
			return \Response::json($allLottries, 200);
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function getLotteryOfLeague($leagueId, $lotteryId)
	{			
		$isValidToken = User::checkAuth();
		
		if($isValidToken != 0){
			$Lottery = Lottery::getLotteryOfLeague($leagueId, $lotteryId);

			if(empty($Lottery)){
				return "Invalid Lottery ID or No Lottries in this League";
			}
			return \Response::json($Lottery, 200);
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function buyTicketsforBowler($leagueId, $lotteryId)
	{
		$isValidToken = User::checkAuth();

		$postedData = file_get_contents("php://input"); 	
		$postedData = substr($postedData, 2, -2);	
		$bowlerID = explode(':', $postedData)[1];		

		if($isValidToken != 0){
			$buyticket = Lottery::buyticketofLeague($leagueId, $lotteryId, $bowlerID);
			if($buyticket == 0){
				return json_encode("Invalid Bowler ID / League ID / Lottery ID");
			}
			if(empty($buyticket)){
				return json_encode("Invalid Bowler ID / League ID / Lottery ID");
			}
			return \Response::json($buyticket, 200);
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function getAllTicketsofJackpot($leagueId, $lotteryId)
	{
		$isValidToken = User::checkAuth();

		if($isValidToken != 0){
			$jackpotticket = Lottery::getAllTicketsofJackpot($leagueId, $lotteryId);			
			if(empty($jackpotticket)){
				return json_encode("Invalid League ID / Lottery ID");
			}
			return \Response::json($jackpotticket, 200);
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function drawTicketforJackpot($leagueId, $lotteryId)
	{
		$isValidToken = User::checkAuth();

		if($isValidToken != 0){
			$bowlers = Lottery::getUsersofLottery($lotteryId);

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
				$pinCount[0] = rand(0 , 10);

				/* If the bowler gets a Strike */
				$jackpotAmount = Lottery::getCurrentJackpot($lotteryId);
				if($pinCount[0] == 10){									
					$winnerAmount = $jackpotAmount[0]->balence;						
					$jackpot = Lottery::getstrike($lotteryId, $bowlerId, $winnerAmount);	
					return \Response::json($jackpot, 200);						
				}
				else{
					$winnerAmount = $jackpotAmount[0]->balence / 10;
					$jackpot = Lottery::getnonstrike($lotteryId, $bowlerId, $winnerAmount, $pinCount[0]);
					return \Response::json($jackpot, 200);		
				}

				return $pinCount[0];
			}
			else{
				return json_encode("Sorry! There should be minimum 3 players to draw a lottery.");
			}
			return $bowlers;
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function recordResultforRoll($leagueId, $lotteryId){
		$isValidToken = User::checkAuth();

		$postedData = file_get_contents("php://input"); 	
		$postedData = substr($postedData, 2, -2);	
		$pinCount = explode(':', $postedData)[1];		

		$jackpotAmount = Lottery::getCurrentJackpot($lotteryId);
		if($isValidToken != 0){
			$bowlerId = $isValidToken[0]->id;
			if($pinCount == 10){
				$winnerAmount = $jackpotAmount[0]->balence;						
				$jackpot = Lottery::getstrike($lotteryId, $bowlerId, $winnerAmount);	
				return \Response::json($jackpot, 200);
			}
			else{
				$winnerAmount = $jackpotAmount[0]->balence / 10;
				$jackpot = Lottery::getnonstrike($lotteryId, $bowlerId, $winnerAmount, $pinCount[0]);
				return \Response::json($jackpot, 200);
			}
		}
		else{
			return json_encode("Please enter valid Authentication Token");
		}
	}

	public function foo(){		
	
		$checkToken = User::checkAuth();

		$dataPosted = file_get_contents("php://input");		

		if(empty($checkToken)){
			return json_encode("Authentication Token Error");
		}
		else{			
			return json_encode($checkToken);
		}			
		
	}

	public function curlGetAllLeague(){		
		$sample = Curl::to('http://www.google.com')
        			->get();        	        
	}
	
}