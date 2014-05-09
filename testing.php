<?php
  //netverify
  
error_reporting(0);  
  
$cla_add_url = 'https://main-securityproject.rhcloud.com/api_cla.php/add_voter';
$cla_add_data = array(
                 'token' => 'CAAFrZAYb2ELcBAJ0R7pFaLsE1zji0mHXTIcNlZBWC8Ax8tD12KGDT8x8geJJoKMjzpZAoOWxKQLrTZChealpWU5mfy1kkkcZANdZAXEj9FDs3BTHQFc1zf4uI62cfbo0L4JEKLkG9rfZCkMAbKDI6dOLBJhV7Xz2Aa1qs5aLiwhvwcnZAY3ZA3iyIvRzVFYKMYpheoaEI4vOnMwZAEp5t9yoSO'  
	      );


$ctf_vote_url = 'https://main2-securityproject.rhcloud.com/api_ctf.php/vote';
$ctf_vote_data = array(
                 'validnum' => '1765d2da363f5e6de6216bc68990b4e410ed938c9b0467cf7adfd4dd2f816bcc028f5a660af654687d23134bdb9ee2a845c3',
                 'voter_id' => 'IamAVoter!$^2^@!7245@372#7',	  
				 'vote' => 'test vote', 
	      );


$ctf_results_url = 'https://main2-securityproject.rhcloud.com/api_ctf.php/testing';

$url = '';
$data = array();

$is_results = 0;

if ($argv[1] == "add_voter") {

$url = $cla_add_url;
$data = $cla_add_data;

} else if ($argv[1] == "vote") {

$url = $ctf_vote_url;
$data = $ctf_vote_data;
$data['validnum'] = $argv[2];
$data['voter_id'] = $argv[3];
$data['vote'] = $argv[4];


} else if ($argv[1] == "results") {

$url = "https://main2-securityproject.rhcloud.com/api_ctf.php/results";
$is_results = 1;

} else if ($argv[1] == "results_tabulated") {
$url = "https://main2-securityproject.rhcloud.com/api_ctf.php/results_tabulated";
$is_results = 2;


} else {
	echo "USAGE: php testing.php <add_voter(PARAMS: token) or vote(PARAMS: validnum, voter_id, vote) or results or results_tabulated>";
	echo "\n";
	return;
}

//add fields to the data array




//$data_string = json_encode($data);

//echo $data_string;

//open connection
$ch = curl_init();


//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);

if ($is_results == 0) {
	curl_setopt($ch,CURLOPT_POST, true);

	curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($data));

	// curl_setopt($ch, CURLOPT_USERPWD, $userid.":".$password); 
	
	// RETURN THE CONTENTS OF THE CALL
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
}

curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);

//execute post
echo curl_exec($ch);
echo "\n\n\n";
//$result = json_decode(curl_exec($ch),true);

//var_dump($result);


//close connection
curl_close($ch);


?>
