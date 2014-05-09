<?php
require 'vendor/autoload.php';
require 'helpers_ctf.php';

//require 'ctf.php';


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



$app = new \Slim\Slim();


$app->config(array(
		   'debug'=> true,
		   'mode' => 'development'
		   ));

$app->get('/testing', function () use ($app) {
    $ret = array(
                 'success' => true,
                 'message' => 'Voting API GET Test Method'
                 );
    echo json_encode($ret);
  }); 
  
$app->post('/testing', function () use ($app) {
    $ret = array(
                 'success' => true,
                 'message' => 'Voting API GET Test Method'
                 );
    echo json_encode($ret);
  }); 
  

$app->post('/vote', function () use ($app){
	$validation_num = $app->request->post('validnum'); // Validation number given to user
    $voter_id = $app->request->post('voter_id'); //User-specified id
    $vote = $app->request->post('vote');  //ASCII candidate ID
		
	if (strlen($token) > 255 || strlen($voter_id) > 255 || strlen($vote) > 255) {
		$ret = array(
			 'success' => false,
			 'message' => "Param(s) too long.",
			 );	
			 
		echo json_encode($ret);
		return json_encode($ret);
	}
	
	//The following function should:
	//1. Check if validation number is in list
	//2. Check if user-specified ID is unique (gen one?)
	//3. check if candidate ID is valid (does it matter?)
	
	
	
	//Check if validation number is valid
	if (!verifyValidationNumber($validation_num)) {
		$ret = array(
			 'success' => false,
			 'message' => 'Bad validation number.',
			 );
		echo json_encode($ret);
		return json_encode($ret);		
	}	
	
	
	//Attempt to vote
    $recorded = recordVote($voter_id, $validation_num, $vote);
	
	
    if($recorded) {
		$ret = array(
			 'success' => true,
			 'message' => "Vote recorded! Voter id is ".$voter_id
			 );
    }else{
		$ret = array(
			 'success' => false,
			 'message' => 'Could not vote. Have you voted already?',
			 );
    }


});


//Deprecated, publishing will be done via cronjob
$app->post('/publish', function () use ($app){
    $token = $app->request->post('token');
    
    $ret = array(
		 'success' => true,
		 'message' => 'Voting Results',
                 'auth' => validToken($token),
		 'data' => $ctf->publishResults()  //array( array('candidate'=> 'C1', 'votes' =>0) )
		 );
    

    echo json_encode($ret);
    

  });

  
$app->get('/results_tabulated', function () use ($app) {

	$result = mysql_query("SELECT candidate_id, COUNT(*) FROM ctf_votes GROUP BY candidate_id");
	$res_arr = mysql_fetch_array($result);

	if(mysql_num_rows($result)){
		while($row=mysql_fetch_row($result)){
			$json[]=$row;
		}
	}	
	
	echo json_encode($json);
	return json_encode($json);
	
});  

$app->get('/results', function () use ($app) {

	$result = mysql_query("SELECT voter_id,candidate_id FROM ctf_votes");
		
	if(mysql_num_rows($result)){
		while($row=mysql_fetch_row($result)){
			$json[]=$row;
		}
	}	
	
	echo json_encode($json);
	return json_encode($json);
	
	
});  
  
  
$app->run();

?>
