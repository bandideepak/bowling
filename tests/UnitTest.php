<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;

class UnitTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testGetAllLeague()
	{				
		$response = $this->action('GET', 'TestController@getAllLeague');	

		$curlResponse = Curl::to('http://localhost/Bowling/public/testGetAllLeague')						
        				->get();   

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAuth()
	{				
		$response = Curl::to('http://localhost/Bowling/public/api/leagues')
        			->get(); 

		$authMessage = '"Please enter valid Authentication Token"';
		
        $this->assertEquals($authMessage, $response);		
    }
}
