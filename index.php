<?php

include 'Insta.php';

$bot = new Insta();

//restart
if(isset($_POST['reset']))
{

	$bot->reset();

	unset($_POST['start']);

	$bot->destroy_session();

}

else if(isset($_POST['start']) || isset($_GET['code']) || $bot->isCodeSet() || $bot->isAccessTokenSet()){


	if (!$bot->isCodeSet()) 
	{
		if(isset($_GET['code']))
		{
		
	        $bot->setCode($_GET['code']);
	                
	        $_SESSION['code'] = $bot->getCode();

		} 
		else if (isset($_GET['error'])) 
		{

			die('Error Reason : '.$_GET['error_reason'].'<br>Error Description: '.$_GET['error_description']);

		}
		else 
		{
			$bot->redirectUser();

		}
	} 
	else if (!$bot->isAccessTokenSet()) 
	{
		
		//request access token
		$bot->setoAuthToken($bot->getToken());

		//convert json into assoc array
		$t = json_decode($bot->getoAuthToken(), true);

		//check for error
		if(isset($t['meta']))
		{

			die('Error type:'.$t['meta']['error_type']
				.'<br> Error code: '.$t['meta']['error_code']
				.'<br> Error Description: '.$t['meta']['Description'].'<br>');

		}
		else
		{	

			$_SESSION['oauth_token'] = $bot->getoAuthToken();

			$_SESSION['access_token'] = $t['access_token'];

			$bot->setAccessToken($t['access_token']);
		
		}				
	} 
	else 
	{
		//do Api calls
	   $at = json_decode($bot->getoAuthToken(), true)['access_token'];
		
		$url = 'https://api.instagram.com/v1/users/search?q=aqib&count=3&access_token='.$at;
		
		curl_setopt($bot->ch, CURLOPT_URL, $url);
		
		curl_setopt($bot->ch, CURLOPT_RETURNTRANSFER, 1);
		
		var_dump(curl_exec($bot->ch));

		curl_close($bot->ch);
	} 
}

?>

<html lang="en">
	<head>
		<title>
			Instagram bot
		</title>
	</head>
	<body>
	
	<?php	
 		if(!$bot->isCodeSet()){
	?>
			<form name="form1" action="index.php" method="post">
				<input type="hidden" name="start" value="true">
				<input type="submit" value="CONNECT"/>
			</form>
	<?php
		} else if (!$bot->isAccessTokenSet()) {
	?>
			<form name="form2" action="index.php" method="post">
				<input type="hidden" name="start" value="true">
				<input type="submit" value="GET ACCESS TOKEN"/>
			</form>
	<?php
		} else {

	?>
			<form name="form3" action="index.php" method="post">
				<input type="hidden" name="start" value="true">
				<input type="submit" value="GET USERS"/>
			</form>
	<?php

		}
	?>

	<form name="form4" action="index.php" method="post">
		<input type="hidden" name="reset" value="true">
		<input type="submit" value="RESTART"/>
	</form>

	</body>
</html>