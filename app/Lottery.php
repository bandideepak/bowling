<?php 

namespace App;

use Auth;
use Session;
use App\User;
use App\League;
use Illuminate\Database\Eloquent\Model;

class Lottery extends Model {	

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'lottery';

	public static function createLottery($league_id, $lottery_name){
			$LotteryID = \DB::table('lottery')
						->insertGetId(['lottery_name' => $lottery_name, 'league_id' => $league_id, 'jackpot' => 10000, 'balence' =>10000, 'payout' => 0, 'is_current_lottery' => 1]);

			$user_id = Auth::user()->id;
			
			\DB::table('jackpot')				
				->insert(['lottery_id' => $LotteryID, 'user_id' => $user_id, 'lottery_amount' => 0, 'ticket' =>0, 'is_winner' => 0]);

			return 1;
	}

	public static function getActiveLotteryID()
	{
		return \DB::table('lottery')		        
		        ->select('lottery.lottery_id')
		        ->where('is_current_lottery', '>', 0)		        
		        ->get();
	}

	public static function getAllCurrentJackpot()
	{
		return \DB::table('jackpot')
		        ->join('lottery', 'jackpot.lottery_id', '=', 'lottery.lottery_id')
		        ->join('users', 'jackpot.user_id', '=', 'users.id')
		        ->join('league', 'lottery.league_id', '=', 'league.league_id')		        
		        ->where('lottery.is_current_lottery', '>', 0)
		        ->select('jackpot.*','lottery.*', 'users.*', 'league.*')
		        ->get();
	}

	public static function getCurentJackpotDetailsByLotteryId($lottery_id)
	{
		return \DB::table('lottery')
		        ->join('jackpot', 'jackpot.lottery_id', '=', 'lottery.lottery_id', 'left outer')
		        ->join('users', 'jackpot.user_id', '=', 'users.id', 'left outer')		        
		        ->join('league', 'lottery.league_id', '=', 'league.league_id', 'left outer')	
		        ->where('lottery.lottery_id', '=', $lottery_id)
		        ->where('lottery.is_current_lottery', '>', 0)
		        ->select('jackpot.*','lottery.*', 'users.*', 'league.*')
		        ->get();
	}

	public static function getCurentJackpotDetailsByLotteryIdForUser($lottery_id, $user_id)
	{
		return \DB::table('jackpot')
		        ->join('lottery', 'jackpot.lottery_id', '=', 'lottery.lottery_id')
		        ->join('users', 'jackpot.user_id', '=', 'users.id')
		        ->join('league', 'lottery.league_id', '=', 'league.league_id')
		        ->join('game', 'league.league_id', '=', 'game.league_id')
		        ->where('game.user_id', '=', $user_id)	
		        ->where('lottery.lottery_id', '=', $lottery_id)
		        ->where('lottery.is_current_lottery', '>', 0)
		        ->select('jackpot.*','lottery.*', 'users.*', 'league.*')
		        ->get();
	}

	public static function getCurrentLotteryDetailsByLeagueId($league_id)
	{
		return \DB::table('lottery')
		        ->join('users', 'lottery.user_id', '=', 'users.id')
		        ->join('league', 'lottery.league_id', '=', 'league.league_id')
		        ->where('league.league_id', '=', $league_id)
		        ->where('lottery.price', '>', 0)
		        ->where('lottery.is_current_jackpot', '>', 0)
		        ->select('lottery.*', 'users.*', 'league.*')
		        ->get();
	}

	public static function checkBuyTicket($lottery_id, $user_id){
		return \DB::table('jackpot')
				->where('lottery_id', $lottery_id)
				->where('user_id', $user_id)
				->select('jackpot.jackpot_id')
				->get();
	}

	public static function buyticket($lottery_id, $user_id)
	{
		return \DB::table('jackpot')
				->where('lottery_id','=', $lottery_id)
				->where('user_id','=', $user_id)
				->update(['lottery_amount' => 0, 'ticket' => 10, 'is_winner' => 0]);		
		
	}

	public static function buyticketofLeague($league_id, $lottery_id, $user_id)
	{
				\DB::table('jackpot')				
				->insert(['lottery_id' => $lottery_id, 'user_id' => $user_id, 'lottery_amount' => 0, 'ticket' =>0, 'is_winner' => 0]);

			   \DB::table('jackpot')
				->join('lottery', 'lottery.lottery_id', '=', 'jackpot.lottery_id')
				->where('lottery.league_id','=', $league_id)
				->where('jackpot.lottery_id','=', $lottery_id)
				->where('user_id','=', $user_id)
				->update(['lottery_amount' => 0, 'ticket' => 10, 'is_winner' => 0]);	

		return \DB::table('jackpot')				
				->select('jackpot.*')			
				->where('jackpot.lottery_id','=', $lottery_id)
				->where('user_id','=', $user_id)
				->get();
	}

	public static function buyLotteryTicket($lottery_id, $user_id){
		return \DB::table('jackpot')
				->where('lottery_id', $lottery_id)
				->where('user_id', $user_id)
				->insert(['lottery_id' => $lottery_id, 'user_id' => $user_id, 'lottery_amount' => 0, 'ticket' =>10, 'is_winner' => 0]);
	}
	
	public static function getCurrentJackpot($lottery_id)
	{
		return \DB::table('lottery')
				->select('lottery.balence')
				->where('lottery_id','=',$lottery_id)
				->get();
	}

	public static function getAllTicketsofJackpot($leagueId, $lotteryId)
	{
		return \DB::table('jackpot')
				->join('lottery', 'lottery.lottery_id', '=', 'jackpot.lottery_id')				
				->select('jackpot.*')			
				->where('jackpot.lottery_id','=', $lotteryId)
				->where('lottery.league_id','=', $leagueId)
				->get();
	}

	public static function getUsersofLottery($lottery_id)
	{
		return \DB::table('jackpot')
				->join('users', 'jackpot.user_id', '=', 'users.id')
				->select('users.*')
				->where('lottery_id','=',$lottery_id)
				->where('jackpot.ticket','=',10)
				->get();
	}	

	public static function strike($lottery_id, $user_id, $jackpotAmount)
	{
				\DB::table('jackpot')
				->where('lottery_id', '=', $lottery_id)
				->where('user_id', '=', $user_id)
				->increment('lottery_amount', $jackpotAmount);				

				\DB::table('jackpot')
				->where('lottery_id', '=', $lottery_id)
				->where('user_id', '=', $user_id)
				->update(['is_winner' => 1,'pin_count' => 10]);
				
		return	\DB::table('lottery')
				->where('lottery_id', '=', $lottery_id)
				->update(['is_current_lottery' => 0]);
	}

	public static function getstrike($lottery_id, $user_id, $jackpotAmount)
	{
				\DB::table('jackpot')
				->where('lottery_id', '=', $lottery_id)
				->where('user_id', '=', $user_id)
				->increment('lottery_amount', $jackpotAmount);				

				\DB::table('jackpot')
				->where('lottery_id', '=', $lottery_id)
				->where('user_id', '=', $user_id)
				->update(['is_winner' => 1,'pin_count' => 10]);
				
				\DB::table('lottery')
				->where('lottery_id', '=', $lottery_id)
				->update(['is_current_lottery' => 0]);

		return  \DB::table('jackpot')
				->where('lottery_id', '=', $lottery_id)
				->where('user_id', '=', $user_id)
				->get();
	}		

	public static function nonstrike($lottery_id, $user_id, $jackpotAmount)
	{
		return	\DB::table('jackpot')
				->where('lottery_id', '=', $lottery_id)
				->where('user_id', '=', $user_id)
				->increment('lottery_amount', $jackpotAmount);				
	}

	public static function getnonstrike($lottery_id, $user_id, $jackpotAmount, $pin_count)
	{
				\DB::table('jackpot')
				->where('lottery_id', '=', $lottery_id)
				->where('user_id', '=', $user_id)
				->increment('lottery_amount', $jackpotAmount);	

				\DB::table('jackpot')
				->where('lottery_id', '=', $lottery_id)
				->where('user_id', '=', $user_id)
				->update(['is_winner' => 0,'pin_count' => $pin_count]);

		return  \DB::table('jackpot')
				->where('lottery_id', '=', $lottery_id)
				->where('user_id', '=', $user_id)
				->get();			
	}

	public static function jackpotAmount($lottery_id, $user_id, $jackpotAmount)
	{
		 		\DB::table('lottery')
				->where('lottery_id', '=', $lottery_id)
				->increment('payout', $jackpotAmount);

		return	\DB::table('lottery')
				->where('lottery_id', '=', $lottery_id)				
				->decrement('balence', $jackpotAmount);
	}	

	public static function getInactiveLotteryResults(){
		return	\DB::table('lottery')
				->join('league', 'lottery.league_id', '=', 'league.league_id')	
				->join('jackpot', 'lottery.lottery_id', '=', 'jackpot.lottery_id')
				->join('users', 'jackpot.user_id', '=', 'users.id')
				->where('lottery.is_current_lottery', '<', 1)
				->select('jackpot.*','lottery.*', 'users.*', 'league.*')		        
				->get();
	}

	public static function getLottriesOfLeague($leagueId)
	{
		return \DB::table('lottery')
				->select('lottery_id','league_id','balence','payout')
				->where('league_id','=',$leagueId)
				->get();
	}

	public static function getLotteryOfLeague($leagueId, $lotteryId)
	{
		return \DB::table('lottery')
				->select('lottery_id','league_id','balence','payout')
				->where('league_id','=',$leagueId)
				->where('lottery_id','=',$lotteryId)
				->get();
	}

	public static function getJackpotResults()
	{
		return	\DB::table('lottery')
				->join('league', 'lottery.league_id', '=', 'league.league_id')	
				->join('jackpot', 'lottery.lottery_id', '=', 'jackpot.lottery_id')
				->join('users', 'jackpot.user_id', '=', 'users.id')
				->where('lottery.is_current_lottery', '<', 1)
				->where('jackpot.ticket', '=', 10)
				->select('jackpot.*','lottery.*', 'users.*', 'league.*')		        
				->get();
	}

	public static function getJackpotCountOfUser($userId)
	{
		return \DB::table('jackpot')
				->where('jackpot.user_id', '=', $userId)
				->where('jackpot.is_winner', '=', 1)
				->count();
	}

	public static function getTotalLotteryCount($userId)
	{
		return \DB::table('jackpot')
				->where('jackpot.user_id', '=', $userId)
				->where('jackpot.ticket', '=', 10)
				->count();
	}

	public static function getTotaljackpotAmountofUser($userId)
	{
		return \DB::table('jackpot')
				->where('jackpot.user_id', '=', $userId)				
				->sum('jackpot.lottery_amount');
	}
}
