
<?php
require 'vendor/autoload.php';
require 'helpers_bp.php';


$app = new \Slim\Slim();


$app->config(array(
		   'debug'=> true,
		   'mode' => 'development'
		   ));



$app->post('/add_voter', function () use ($app) {
    //$userid = $app->request->post('userid');
    $token = $app->request->post('token'); //Facebook access token
    
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

$app->post('/vote', function () use ($app){
	$validation_num = $app->request->post('validnum'); // Validation number given to user
    $userid = $app->request->post('userspecifiedid'); //User-specified id
    $vote = $app->request->post('vote');  //ASCII candidate ID
	
	
    $recorded = recordVote($userid, $validation_num, $vote);
	
	
    if($recorded) {
		$ret = array(
			 'success' => true,
			 'message' => "$userid",
			 );
    }else{
		$ret = array(
			 'success' => false,
			 'message' => ' ',
			 );
    }
    
    echo json_encode($ret);

  });


$app->post('/publish', function () use ($app){
    $token = $app->request->post('token');
    
    $ret = array(
		 'success' => true,
		 'message' => 'Voting Results',
                 'auth' => validToken($token),
		 'data' => publishResults()  //array( array('candidate'=> 'C1', 'votes' =>0) )
		 );
    

    echo json_encode($ret);
    

  });


  
  
/*
*********************************************************************  
*********************************************************************  
*********************************************************************  
************Testing functions****************************************  
*********************************************************************  
*********************************************************************  
*********************************************************************  
*********************************************************************  
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
