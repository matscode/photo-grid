<?php

/**
 * @author Michael Akanji <matscode@gmail.com>
 */

/* set db details */
$remoteServer = false; // set to activate the remote server setting

// if setting not available use the default
if ($remoteServer){
/* 
	$db_host = '';
	$db_user = 'a8684805_matmot';
	$db_pwd = '_tomtam4real';
	$db_name = 'a8684805_dpedit';
	 */
	$db_host = '';
	$db_user = '';
	$db_pwd = '';
	$db_name = '';
} else {
	// working on localhost ish
	$db_host = 'localhost';
	$db_user = 'root';
	$db_pwd = '';
	$db_name = 'dpedit';

}

// Create Connection
$db = @new mysqli($db_host,$db_user,$db_pwd,$db_name);

// Check Connection
if ($db->errno){
	// DEBUGGING purpose only
	/* 
	echo "Connection can not be established successfully
		with the error: " . $db->error . " <br /> "; 
	*/
	exit('DPEdit Stopped Unexpectedly');
} else {
	$isCon = true;
}

?>