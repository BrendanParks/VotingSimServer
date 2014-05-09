<?php

	if ($argv[1] == "on") {
		
		apc_delete('done_voting');
		

	} else if ($argv[1] == "off") {

		$boolea = true;
		apc_add('done_voting',$boolea);
		
		
	} else {
		echo "USAGE: php ctf_vote_control.php <on or off>";
		echo "\n";
		return;
	}





?>