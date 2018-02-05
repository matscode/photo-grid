<?php

/**
 * @author Michael Akanji <matscode@gmail.com>
 */
/*
*
* This is my result handler, for any error or success report been passed tru the url
*
*/
//initialize var rst
$rst = "";
$err = "";
// check whether there is any rst in the url at all.....
if (isset($_GET['rst']) && !empty($_GET['rst'])){
	// start to do some manipulations but first needs to be cleaned
	$rst_clean = sanit($_GET['rst']);
	// now tryna fetch the data oouta it
	$rst = str_replace('_', ' ', $rst_clean);
	// capitalize the first caractet
	$rst = ucfirst($rst);
}

/// check if any error msg is passed tru the url
if (isset($_GET['err']) && !empty($_GET['err'])){
	// start to do some manipulations but first needs to be cleaned
	$err_clean = sanit($_GET['err']);
	// now tryna fetch the data oouta it
	$err = str_replace('_', ' ', $err_clean);
	// capitalize the first caractet
	$err = ucfirst($err);
}

?>