<?php
use Facebook\FacebookSession;

require 'helpers_all.php';

define('dbu', 'admin6QHMFM1');

define('dbpw', 'fsghjRCP6qW3');

define('dbh', 'localhost');

define('dbn', 'cla_db');

/* BEGIN SETUP MYSQL */
/* BEGIN SETUP MYSQL */
/* BEGIN SETUP MYSQL */


if($dbc = mysql_connect(dbh,dbu,dbpw))
  {
    
    if(!mysql_select_db(dbn))
      {
	
	trigger_error("Could not select the database");
	
	$ret = array(
	 'success' => false,
	 'message' => "Unknown error 1"
	 );
    echo json_encode($ret);
	return json_encode($ret);
	exit();
	
      }
    

  }
else
  {
    
    trigger_error("Could not connect");
    
	$ret = array(
	 'success' => false,
	 'message' => "Unknown error 2"
	 );
    echo json_encode($ret);
	return json_encode($ret);
    exit();
    

  }

/* END SETUP MYSQL */
/* END SETUP MYSQL */
/* END SETUP MYSQL */
  
function id_by_token($token)
{
	
  
  $graph = curl_get_contents('https://graph.facebook.com/me?fields=id&access_token='.$token);
  
  //echo $graph;
  $json = json_decode($graph, true);
  
  if (array_key_exists('id', $json)) {
	
	return $json['id'];
  }
  
  return NULL;

}  


/* Verify that a validation number exists (used by CTF, but NEVER send unencrypted, use the API) */
function verifyValidationNum($validation_num) {

	//Check if user already requested ID
	$query = sprintf("SELECT valid_num FROM cla_valid_nums WHERE valid_num = '%s'",
				mysql_real_escape_string($validation_num));
	$result = mysql_query($query);
	if(mysql_num_rows($result) != 0) {
		return true;
	} else {
		return false;
	}


}

function genValidationNumberAndStore($userid)
{

	//Check if user already requested ID
	$query = sprintf("SELECT user_id FROM cla_valid_nums WHERE user_id = '%s'",
				mysql_real_escape_string($userid));
	$result = mysql_query($query);
	if(mysql_num_rows($result) != 0) {
		return NULL;
	}

  //select new random number
  $tmp = openssl_random_pseudo_bytes(50, $cstrong);
  $random_number = bin2hex($tmp);
  
  //Will fail if not unique
  
	$query = sprintf("INSERT INTO `cla_valid_nums` (user_id,valid_num) VALUES ('%s','".$random_number."')",
			mysql_real_escape_string($userid));
	$successful_insertion = mysql_query($query);  
  
  if ($successful_insertion) {
	return $random_number;
  } else {
	return newValidationNumber();
  }

}









/*
*********************************************
*********************************************
*********************************************
*********************************************
*********************************************
**********Deprecated Functions**************
*********************************************
*********************************************
**************Following Here*****************
*********************************************
*********************************************
*********************************************
*********************************************
*********************************************
*********************************************
*********************************************
*/


function validToken($token)
{
  FacebookSession::setDefaultApplication('399558776852663', '240b7a471130f9c9cc75dbcd634933b3');
  // If you already have a valid access token:
  $session = new FacebookSession($token);
  // To validate the session:
  try 
    {
      $session->validate();

      return true;
      
    }
  catch (FacebookRequestException $ex) 
    {
      
      // Session not valid, Graph API returned an exception with the reason.
      //echo $ex->getMessage();
      return false;
    }
  catch (\Exception $ex) 
    {
      // Graph API returned info, but it may mismatch the current app or have expired.
      //echo $ex->getMessage();
      return false;

    }
}

?>

