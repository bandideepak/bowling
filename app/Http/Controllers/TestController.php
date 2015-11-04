<?php 

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use App\Lottery;
use App\League;
use Cache;
use Response;
use Hash;
use Curl;

class TestController extends Controller {

	public function __construct()
	{

	}

	public function getAllLeague()
	{		
			$allLeagues = League::getAllLeague();			
			return \Response::json($allLeagues, 200);			
	}	

	public function users()
	{
		$postedData = file_get_contents("php://input"); 	
		str_replace("%40","@",$postedData);
		/*$postedData = substr($postedData, 2, -2);*/		
		$postedData = explode(',', $postedData);

		return $postedData;

		foreach ($postedData as $value) {
			$data[explode(':', $value)[0]] = explode(':', $value)[1];
		}
		$email =  $data['email'];
		$password = $data['password'];		

		$email = "abhi@gmail.com";
		$password = "admin123";

		if (Auth::attempt(array('email' => $email, 'password' => $password)))
		{
			$userId = Auth::id();			
			return json_encode(User::getUserDetails($userId));
		}
		else{
			return json_encode("Not a valid Login Credentials.");
		}
	}
}