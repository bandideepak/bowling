<?php

use App\User;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/',function(){
	$baseURL = '../public/';
	return view('auth.login',[])->with('baseURL', $baseURL);;
});

Route::get('home', 'HomeController@lottery');

Route::get('lottery', 'HomeController@lottery');

Route::get('league', 'HomeController@league');

Route::get('play/{lottery_id}', 'HomeController@play');

Route::get('buyticket/{lottery_id}', 'HomeController@buyticket');

Route::get('createleague/{league_name}', 'HomeController@createleague');

Route::get('createlottery/{league_id}/{lottery_name}', 'HomeController@createlottery');

Route::get('joinleague/{league_id}/{lottery_id}', 'HomeController@joinleague');

Route::get('results', 'HomeController@results');

Route::get('bowlers', 'HomeController@bowlers');

Route::get('becomebowler', 'HomeController@becomebowler');

/* Rest API */

Route::post('api/login', 'RestController@login');

Route::post('api/leagues', 'RestController@createLeague');

Route::get('api/leagues', 'RestController@getAllLeague');

Route::get('api/leagues/{leagueId}', 'RestController@getLeagueByID');

Route::post('api/bowlers', 'RestController@setBowler');

Route::get('api/bowlers', 'RestController@getAllBowlers');

Route::get('api/bowlers/{bowlerId}', 'RestController@getBowlerByID');

Route::any('api/leagues/{leagueId}/bowlers', 'RestController@addBowler');

Route::get('api/leagues/{leagueId}/bowlers', 'RestController@getBowlersInLeague');

Route::get('api/leagues/{leagueId}/lotteries', 'RestController@getLottriesOfLeague');

Route::get('api/leagues/{leagueId}/lotteries/{lotteryId}', 'RestController@getLotteryOfLeague');

Route::post('api/leagues/{leagueId}/lotteries/{lotteryId}/tickets', 'RestController@buyTicketsforBowler');

Route::get('api/leagues/{leagueId}/lotteries/{lotteryId}/tickets', 'RestController@getAllTicketsofJackpot');

Route::get('api/leagues/{leagueId}/lotteries/{lotteryId}/roll', 'RestController@drawTicketforJackpot');

Route::post('api/leagues/{leagueId}/lotteries/{lotteryId}/roll', 'RestController@recordResultforRoll');

Route::any('testGetAllLeague', 'TestController@getAllLeague');

Route::any('api/users', function () {

	$postedData = file_get_contents("php://input"); 	
	$postedData = substr($postedData, 2, -2);		
	$postedData = explode(',', $postedData);	

	foreach ($postedData as $value) {
		$data[explode(':', $value)[0]] = explode(':', $value)[1];
	}

	$name =  $data['name'];
	$email =  $data['email'];
	$password = $data['password'];		
	$credentials['password'] = Hash::make($password);	

	$checkUser = User::userExists($email);	

	if(empty($checkUser)){
		$createuser = User::create([
						  'name' => $name,
						  'email' => $email,
						  'password' => $credentials['password']
						]);

		$userId = $createuser->id;
		$userDetails = User::getAPIUser($userId);

		return \Response::json($userDetails, 200);
	}
 	else{
 		return \Response::json("User Already Exists", 200);
 	}
	
});