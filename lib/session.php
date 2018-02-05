<?php
	
/**
 * @author Michael Akanji <matscode@gmail.com>
 */

	// my temporary session manager
	session_start();
	if (!isset($_SESSION['uid']) && empty($_SESSION['uid'])){
		// assign a session id from a random number
		$sessionIdMin = 10000; $sessionIdMax = 10000000000;
		$session_id = mt_rand($sessionIdMin,$sessionIdMax);
		$session_hash = md5($session_id);
		//set session id
		$_SESSION['uid'] = $session_hash;
	}
	
	// check if connection is created at all
	if ($isCon){
		// Always fetch the last set value for the number of picture mixed
		$pic_mixed_value_sql = "SELECT pic_mixed FROM statistic LIMIT 1";
		$pic_mixed_value_rst = $db->query($pic_mixed_value_sql);
		// check for success
		if (!$pic_mixed_value_rst){
			//debugin ish follows
			echo 	"ERROR: " . $db->error;
		} else {
			// fetch the data to a row..
			if ($pic_mixed_value_rst->num_rows == 1){
				$picMixedStatsRow = $pic_mixed_value_rst->fetch_assoc();
				$picMixedStats = $picMixedStatsRow['pic_mixed'];
			} else {
				// initialize some kind of alternative value for $picMixedStats
				$picMixedStats = 1;
				$picMixedStatsSql = "INSERT INTO statistic (pic_mixed) VALUE ('".$picMixedStats."')";
				$picMixedStatsRst = $db->query($picMixedStatsSql);
				// check for success
				if (!$picMixedStatsRst){
					// debugin ish
					//echo 'ERROR : ' . $db->error;
				}
			}
		}
	}
	

?>