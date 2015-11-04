<?php 

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Auth;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public static function getAPIUser($id)
	{				
		return \DB::table('users')		        
				->select('users.id', 'users.email')	
				->where('id', '=', $id)
				->get();	
	}

	public static function userExists($email){
		return \DB::table('users')		        
				->select('users.id', 'users.email')	
				->where('email', '=', $email)
				->get();	
	}

	public static function getUserIdbyName($user_name){
		return \DB::table('users')		        
		        ->select('users.id')	
		        ->where('name', '=', $user_name)
		        ->get();
	}

	public static function isBolwer($user_id){
		return \DB::table('users')		        
		        ->select('users.is_bowler')	
		        ->where('id', '=', $user_id)
		        ->get();
	}

	public static function getUser($user_id){
		return \DB::table('users')		        
		        ->select('users.*')	
		        ->where('id', '=', $user_id)
		        ->get();
	}

	public static function getUserDetails($user_id){
		return \DB::table('users')		        
		        ->select('users.id', 'users.email')	
		        ->where('id', '=', $user_id)
		        ->get();
	}

	public static function getAllBolwers(){
		return \DB::table('users')		        
		        ->select('users.id', 'users.bowler_name')	
		        ->where('is_bowler', '=', 1)
		        ->get();
	}

	public static function getAllBolwerDetails(){
		return \DB::table('users')		        
		        ->select('users.*')	
		        ->where('is_bowler', '=', 1)
		        ->get();
	}

	public static function getUserByID($user_id){
		return \DB::table('users')		        
		        ->select('users.id', 'users.name')	
		        ->where('id', '=', $user_id)
		        ->get();
	}

	public static function getBowlerByID($user_id){
		return \DB::table('users')		        
		        ->select('users.id', 'users.bowler_name')	
		        ->where('id', '=', $user_id)
		        ->get();
	}

	public static function setBowler($user_id){
		return \DB::table('users')
				->where('id', $user_id)				
				->update(['is_bowler' => 1]);		
	}

	public static function setBowlerByName($bowlerID, $bowlerName){						
			   \DB::table('users')
				->where('id', $bowlerID)				
				->update(['is_bowler' => 1, 'bowler_name' => $bowlerName]);	

		return \DB::table('users')		        
		        ->select('users.id', 'users.bowler_name')	
		        ->where('id', '=', $bowlerID)
		        ->get();	
	}

	public static function getBowlerIDByName($bowlerName){	
		return \DB::table('users')		        
		        ->select('users.id')	
		        ->where('bowler_name', '=', $bowlerName)
		        ->get();
	}	

	public static function checkToken(){

		$token = 0;

		$allHeaders = getallheaders();		
		
		$dataPosted = file_get_contents("php://input");			
		
		foreach (getallheaders() as $name => $value) {	    
		    if($name == "Authorization"){
		    	$token = $value;
		    	$token = explode(" ", $token)[1];
		    }	  		    
		}

		return \DB::table('users')
				->select('users.id')	
				->where('remember_token', '=', $token)				
				->get();
	}

	public static function checkAuth(){		

		$token = 0;		
		
		foreach (getallheaders() as $name => $value) {	    
		    if($name == "Authorization"){
		    	$token = $value;
		    	$token = explode(" ", $token)[1];
		    }	  		    		   
		}				

		if ( base64_encode(base64_decode($token)) === $token){
			
			$decodePassword = base64_decode($token);

			$email = explode(":", $decodePassword)[0];
			$password = explode(":", $decodePassword)[1];				

			if (Auth::attempt(array('email' => $email, 'password' => $password)))
			{
			    return \DB::table('users')		        
				        ->select('users.id', 'users.email')	
				        ->where('email', '=', $email)
				        ->get();
			}
			else{
				return 0;
			}
		}
		else{
			return 0;
		}				
	}
}
