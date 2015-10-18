<?php

class Insta
{
	const CLIENT_ID = '980d4a1b64974f9ea5e47144503cba76';

	const REDIRECT_URI = 'http://127.0.0.1/insta'; 

	const API_URL =  'https://api.instagram.com';

	const CLIENT_SECRET = '8f88966137124444906efdbce78c6c3b';

	private $code;

	private $access_token; 

	private $oauth_token;

	public $ch;

	public function __construct () 
	{
	
		session_start();
			
		$this->ch = curl_init();

		$this->code = $_SESSION['code'];

		$this->access_token = $_SESSION['access_token'];
 
        $this->oauth_token = $_SESSION['oauth_token'];

        $this->access_token = $_SESSION['access_token'];
	}

	public function getCode() 
	{

		return $this->code;
	}

	public function getAccessToken() 
	{

		return $this->access_token;
	}

	public function getoAuthToken()
	{

		return $this->oauth_token;
	}

	public function setCode($code) 
	{

		$this->code = $code;
	}

	public function setAccessToken($t) 
	{

		$this->access_token = $t;
	}

	public function setoAuthToken($t) 
	{

		$this->oauth_token = $t;

	}

	public function isCodeSet() {

		return isset($this->code);
	}

	public function isAccessTokenSet() {

		return isset($this->access_token);
	}

	public function redirectUser() {

		$url = 	 self::API_URL.'/oauth/authorize/?client_id='.self::CLIENT_ID.'&redirect_uri='.self::REDIRECT_URI.'&response_type=code';

		header('Location:'.$url);
	}

	public function getToken () 
	{

		$url = self::API_URL.'/oauth/access_token';
		
		$postData = array(
			'client_id='.self::CLIENT_ID,
			'client_secret='.self::CLIENT_SECRET,
			'grant_type=authorization_code',
			'redirect_uri='.self::REDIRECT_URI,
			'code='.$this->code
		);

		$postData = implode('&',$postData);
  				
		curl_setopt($this->ch, CURLOPT_URL, $url);
		
		curl_setopt($this->ch, CURLOPT_POST, 1);

		curl_setopt($this->ch, CURLOPT_POSTFIELDS,$postData);

		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);

		$output = curl_exec($this->ch);

		if (curl_errno($this->ch))
		{
    			die('Curl error: ' . curl_error($this->ch));
		}

		return $output;
	}

	public function destroy_session () {
		
		unset($_SESSION);

		session_destroy();
	}

	public function reset() {

		unset($this->access_token);
		unset($this->oauth_token);
		unset($this->code);
	}
}
