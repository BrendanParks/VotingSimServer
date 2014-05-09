<?php
require 'vendor/autoload.php';
require 'helpers_cla.php';

//require 'cla.php';

$app = new \Slim\Slim();




if (!check_https()){

		$ret = array(
			 'success' => false,
			 'message' => "HTTPS must be enabled",
			 );	
			 
		echo json_encode($ret);
		return json_encode($ret);
}


/* Use cache to check if we're done with voting*/
/* If so, do nothing, return error letting user know*/
/* Note, this should ONLY be set externally by a cronjob
   or something else designed to execute after a certain time */
   
if (apc_exists('done_voting') && apc_fetch('done_voting') == true) {
	$ret = array(
		 'success' => false,
		 'message' => "votedone",
		 );	
		 
	echo json_encode($ret);
	return json_encode($ret);
}

/*
foreach ($_POST as $key => $value) {
	echo "<tr>";
	echo "<td>";
	echo $key;
	echo "</td>";
	echo "<td>";
	echo $value;
	echo "</td>";
	echo "</tr>";
}
*/


$app->config(array(
		   'debug'=> true,
		   'mode' => 'development'
		   ));

		   
		   
$app->post('/add_voter', function () use ($app) {
    //$userid = $app->request->post('userid');
	
    $token = $app->request->post('token'); //Facebook access token
	
	
	//If param is greater than max length, give error
	if (strlen($token) > 255) {
		$ret = array(
			 'success' => false,
			 'message' => "Param too long.",
			 );	
			 
		echo json_encode($ret);
		return json_encode($ret);
	}
	
    //echo "passing in... this".$token;
	//echo $token;
	$userid = id_by_token($token);
	
	if(!is_null($userid)) //If we were able to retrieve the user ID, add voter
    {
			//Stores the new validation number and associates with user ID
			//Returns NULL if user id already there
			$random_number = genValidationNumberAndStore($userid);

			if(!is_null($random_number))
			{
				
				$ret = array(
					 'success' => true,
					 'message' => "$random_number",
					 );
				

				echo json_encode($ret);
				return json_encode($ret);
				
			}

    }
    //Else the token was bad, couldn't retrieve user ID
	//OR the user has already requested a validation number in past
	//Perhaps return the same validation number?
	
	$ret = array(
			 'success' => false,
			 'message' => 'Bad token or user already requested validation number.',
			 );

    echo json_encode($ret);

});

/*The following serves as an encrypted way for the CTF to verify validation numbers*/
$app->post('/verify', function () use ($app) {


	$crypttext = $app->request->post('enc_validation_num'); //Encrypted validation number
	
	$fp=fopen("private.key","r");
	$priv_key=fread($fp,8192);
	fclose($fp);

	$res = openssl_get_privatekey($priv_key,$passphrase);

	/*Decrypted data stored in decrypttext*/
	openssl_private_decrypt($crypttext,$decrypttext,$res);	
	openssl_free_key($res);
		
	if (verifyValidationNum($decrypttext)) {
	
		$ret = array(
			 'success' => true,
			 'message' => 'valid'
			 );
		
		echo json_encode($ret);	
		return json_encode($ret);	
	
	} else {
	
		$ret = array(
			 'success' => false,
			 'message' => 'invalid'
			 );
		
		echo json_encode($ret);	
		return json_encode($ret);	
	}

});

$app->get('/num_voters', function () use ($app) {

	$result = mysql_query("SELECT * FROM cla_valid_nums");
	
	echo mysql_num_rows($result);
	return mysql_num_rows($result);
	
  }); 



/*
$app->post('/cla_validation_number', function () use ($app) {
  $token = $app->request->post('token');
  $cla = new CLA();
  if($cla->setID($token)==false){
    return false;
  }
  $cla->add_to_vnum_request_list();

}
*/

$app->get('/', function () {

    $ret = array(
		 'success' => true,
		 'message' => 'Voting API working!'
		 );
    
    echo json_encode($ret);
    
   
  });

$app->get('/testing', function () use ($app) {
    $ret = array(
                 'success' => true,
                 'message' => 'Voting API GET Test Method'
                 );
    echo json_encode($ret);
  }); 

$app->run();

?>
