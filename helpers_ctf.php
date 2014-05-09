<?php
require 'helpers_all.php';

define('dbu', 'admin6QHMFM1');

define('dbpw', 'fsghjRCP6qW3');

define('dbh', 'localhost');

define('dbn', 'ctf_db');


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

/*Ask the CLA if the valid num (encrypted using verifyValidationNumber()) is valid*/
function curl_verify_validnum($encrypted_validnum) {

$data = array(
                 'enc_validation_num' => $encrypted_validnum
	      );

$url = 'https://main-securityproject.rhcloud.com/api_cla.php/verify';		  
		  
	$ch = curl_init();
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($data));
	// curl_setopt($ch, CURLOPT_USERPWD, $userid.":".$password); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,true);
	// RETURN THE CONTENTS OF THE CALL
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);

	$result = json_decode(curl_exec($ch),true);
	//close connection
	curl_close($ch);

	if (array_key_exists('success',$result) && $result['success'] == true) {
		return true;
	} else {
		return false;
	}

}

/*Prepare for asking CLA if validation number is okay, then ask*/
function verifyValidationNumber($validationNumber) {
	
	$fp=fopen("public.key","r");
	$pub_key=fread($fp,8192);
	fclose($fp);
	openssl_get_publickey($pub_key);
	$crypttext = '';
	openssl_public_encrypt($validationNumber,$crypttext,$pub_key);	
	openssl_free_key($pub_key);	
	
	/*Actually ask now, this time with an encrypted validation number*/
	return curl_verify_validnum($crypttext);
	
}

function recordVote($voter_id, $validation_num, $vote)  {


	echo "\nATTEMPTING TO RECORD VOTE...\n";
	echo "params are".$voter_id." and ".$vote;
	echo "\n";
	//Attempt to insert vote into table
	$query = sprintf("INSERT INTO `ctf_votes` (voter_id,candidate_id,validation_num) VALUES ('%s','%s','%s')",
				mysql_real_escape_string($voter_id),
				mysql_real_escape_string($vote),
				mysql_real_escape_string($validation_num)); 

	$successful_insertion = mysql_query($query);  
	echo "\n";
	echo mysql_error($dbc);
	echo "\n";
	if ($successful_insertion) {
	return true;
	} else {
	return false;
	}
	
}

function publishResults()
{

	//get total votes for each candidate

	//return

}

?>