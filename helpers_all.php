<?php

function curl_get_contents($url)
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  $data = curl_exec($curl);
  curl_close($curl);
  return $data;
}

function check_https() {

	if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https'  ){
		return false;
	} else {
		return true;
	}
		
	if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'on' ){
		return false;
	} else {
		return true;
	}

	if (empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && empty($_SERVER['HTTPS']) ){
		return false;
	}
	
	return true;

}




?>