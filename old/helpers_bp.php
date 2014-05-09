<?php
use Facebook\FacebookSession;

define('dbu', 'admin6QHMFM1');

define('dbpw', 'fsghjRCP6qW3');

define('dbh', 'localhost');

define('dbn', 'voting_bp');


if($dbc = mysql_connect(dbh,dbu,dbpw))
  {
    
    if(!mysql_select_db(dbn))
      {
	
	trigger_error("Could not select the database");
	
	echo "1";
	exit();
	
      }
    

  }
else
  {
    
    trigger_error("Could not connect");
    
    echo "2";
    exit();
    

  }


function id_by_token($token)
{
  $graph = file_get_contents('https://graph.facebook.com/me?fields=id&access_token='.$token);
  
  $json = json_decode($graph, true);
  
  if (array_key_exists('id', $json)) {
	return $json['id'];
  }
  
  return NULL;

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
  $tmp = openssl_random_pseudo_bytes(150, $cstrong);
  $random_number = bin2hex($tmp);
  
  //Will fail if not unique
  $successful_insertion = mysql_query("INSERT INTO `cla_valid_nums` (valid_num) VALUES ('".$random_number"')");
  
  if ($successful_insertion) {
	return $random_number;
  } else {
	return newValidationNumber();
  }

}


function recordVote()
{
  
  //check if voter voted already

  //add voter and vote to database

  //mark voter as voted

}


function publishResults()
{

  //get total votes for each candidate

  //return

}
