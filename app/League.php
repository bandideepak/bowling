<?php 

namespace App;

use App\User;
use App\Lottery;
use Illuminate\Database\Eloquent\Model;

class League extends Model {	

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'league';

	/* Create a League */
	public static function createLeague(){
		$lastInsertedID = \DB::table('league')
						->insertGetId(['league_name' => 'League']);

		$lastInsertedID = 'League ' . $lastInsertedID;

		$LotteryID = \DB::table('lottery')
						->insert(['lottery_name' => 'The Lottery', 'league_id' => $lastInsertedID, 'jackpot' => 10000, 'balence' =>10000, 'payout' => 0, 'is_current_lottery' => 1]);

		return \DB::table('league')
				->update(['league_name' => $lastInsertedID]);				
	}		

	public static function createLeaguebyName($leagueName){
		$lastInsertedID = \DB::table('league')
						->insertGetId(['league_name' => $leagueName]);

		$LotteryID = \DB::table('lottery')
						->insert(['lottery_name' => 'The Lottery', 'league_id' => $lastInsertedID, 'jackpot' => 10000, 'balence' =>10000, 'payout' => 0, 'is_current_lottery' => 1]);

		return \DB::table('league')		        
		        ->select('league.league_id', 'league.league_name')	
		        ->where('league_id', '=', $lastInsertedID)	        
		        ->get();
	}

	/* Get All League */
	public static function getAllLeague(){
		return \DB::table('league')		        
		        ->select('league.*')		        
		        ->get();
	}

	/* Get League By ID*/
	public static function getLeagueByID($league_id){
		return \DB::table('league')		        
		        ->select('league.*')
		        ->where('league_id', '=', $league_id)		        
		        ->get();
	}

	/* Get ID of All Active League */
	public static function getAllLeagueID(){
		return \DB::table('league')		        
		        ->select('league.league_id')
		        ->groupBy('league.league_id')		        
		        ->get();
	}

	/* Get ID of All Active League */
	public static function getAllLeaguebyGroup(){
		return \DB::table('league')		        
		        ->select('league.*')
		        ->groupBy('league.league_id')		        
		        ->get();
	}

	/* Join for a League */
	public static function joinLeague($league_id, $lottery_id, $user_id){
		 \DB::table('game')
				->insert(['league_id' => $league_id, 'user_id' => $user_id]);

		return \DB::table('jackpot')
				->insert(['lottery_id' => $lottery_id, 'user_id' => $user_id, 'lottery_amount' => 0, 'ticket' => 0, 'is_winner' => 0]);
	}

	/* Check the user has joined the league */
	public static function hasJoinedLeague($league_id, $user_id){
		return \DB::table('game')
				->where('game.league_id', '=', $league_id)
				->where('game.user_id', '=', $user_id)					       
		        ->select('game.*')
		        ->get();
	}

	public static function getUsersinLeague($league_id){
		return \DB::table('game')
				->where('game.league_id', '=', $league_id)					       
		        ->select('game.*')
		        ->get();
	}	

	public static function getBowlersinLeague($league_id){
		return \DB::table('game')
				->join('users', 'game.user_id', '=', 'users.id')
				->select('users.id', 'users.name')
				->where('game.league_id', '=', $league_id)
				->where('is_bowler', '=', 1)					       		        
		        ->get();
	}

	public static function getLeagueByBowler($user_id){
		return \DB::table('game')
				->where('game.user_id', '=', $user_id)				
		        ->count();
	}

	public static function addBowlertoLeague($BowlerId, $league_id){		
		$lastInsertedID = \DB::table('game')
						->insertGetId(['user_id' => $BowlerId, 'league_id' => $league_id]);

		return \DB::table('game')
				->join('users', 'game.user_id', '=', 'users.id')
				->select('users.id', 'game.league_id', 'users.bowler_name')
				->where('game.game_id', '=', $lastInsertedID)				       		       
		        ->get();
	}

}
