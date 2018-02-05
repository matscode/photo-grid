<?php
/**
 * @author Michael Akanji <matscode@gmail.com>
 */

// include config file
include '../inc/config.php';
include '../inc/db_con.php';
include '../lib/sbox.php';
include '../lib/session.php';

if ($_SERVER['REQUEST_METHOD'] == "POST"){
	if (!empty($_FILES['dp1']['name']) && !empty($_FILES['dp2']['name'])){
		//set file prop vars
		$photo1 = array(
					'type'=>$_FILES['dp1']['type'],
					'size'=>$_FILES['dp1']['size'],
					'name'=>$_FILES['dp1']['name'],
					'error'=>$_FILES['dp1']['error'],
					'tmp_name'=>$_FILES['dp1']['tmp_name']
					);
		
		$photo2 = array(
					'type'=>$_FILES['dp2']['type'],
					'size'=>$_FILES['dp2']['size'],
					'name'=>$_FILES['dp2']['name'],
					'error'=>$_FILES['dp2']['error'],
					'tmp_name'=>$_FILES['dp2']['tmp_name']
					);
		$photo_time = time();
		// before edit directory path
		$edit_dir = '../' . $config['edit_dir'];
		// after edit directory path
		$edited_dir = '../' . $config['edited_dir'];
		
		// split up photo name to valid with its extension
		$photo1Ext = pathinfo($photo1['name'], PATHINFO_EXTENSION);
		$photo2Ext = pathinfo($photo2['name'], PATHINFO_EXTENSION);
		
		if (validPhoto($photo1["type"], $photo1['size'], $photo1Ext) &&
			validPhoto($photo2["type"], $photo2['size'], $photo2Ext)) {
		  if ($photo1["error"] || $photo2["error"]) {
				header('location: ../?err=error_handling_picture_please_try_again');
			} else {
				//rename and move photos by joining - time & bf_edit
				
	
				$photo1_name = "photo" . '_' . $photo_time .  '_' . $_SESSION['uid'] . '.' . htmlspecialchars($photo1Ext);
				$photo2_name = "photo2" . '_' . $photo_time .  '_' . $_SESSION['uid'] . '.' . htmlspecialchars($photo2Ext);
				$photo1_mv = move_uploaded_file($photo1['tmp_name'], $edit_dir . $photo1_name);
				$photo2_mv = move_uploaded_file($photo2['tmp_name'], $edit_dir . $photo2_name);
				// if photo mv is successful, keep its details
				if ($photo1_mv == true && $photo2_mv == true){
					//store each photo details to the database, in colummns name & time but not really neccesary
					/* 
					$photo_sql = "INSERT INTO photos (photo_name, photo_time) VALUES ('".$photo1_name."', '".$photo_time."');";
					$photo_sql .= "INSERT INTO photos (photo_name, photo_time) VALUES ('".$photo2_name."', '".$photo_time."')";
					$rst = $db->multi_query($photo_sql);
					//check for success
					if($rst == false){
						//store error msg and redirect
						header('location: ../rst_error_simulating_mixing_procedure_please_try_again');
						die('Error: ' . $db->error); //debugin purpose only
					}
					 */
					/* ---------------------------
						Start to process the photo
						--------------------------- */
						
					//set the default values for mixing
					$default = array(
										'mixWidth' => 320,
										'mixHeight' => '', // do not set the height unless you know what you are doing
										'stamp' => null
									);

					// my define function, with returns of $photo['name'], ['width'], ['height'], ['ext']
					$photoResized_1 = resizeImage ($edit_dir . $photo1_name, $default['mixWidth']);
					$photoResized_2 = resizeImage ($edit_dir . $photo2_name, $default['mixWidth']);
					//fetch the sizes of the photos
					list($photoResizedId1,$photo1Width,$photo1Height) = $photoResized_1;
					list($photoResizedId2,$photo2Width,$photo2Height) = $photoResized_2;
					/* draw the canvas and return its properties */
					$canvasMargin = 5;
					$canvas = drawCanvas ($photo1Width,$photo1Height,$photo2Width,$photo2Height,$canvasMargin);
					// mixing ish start here
					list($canvasName, $canvasWidth, $canvasHeight, $commonPhotoHeight) = $canvas;
					// define photo 2 position
					$photo2Pos = $photo1Width + ($canvasMargin * 2);
					// Copy and merge
					imagecopy($canvasName, $photoResizedId1, $canvasMargin, $canvasMargin, 0, 0, $photo1Width, $commonPhotoHeight);
					imagecopy($canvasName, $photoResizedId2, $photo2Pos, $canvasMargin, 0, 0, $photo2Width, $commonPhotoHeight);
					// stamp the image but not for now
					
					// filter the image but not working on PHP Version 5.2
					//imagefilter($canvasName, IMG_FILTER_SMOOTH, 50);
					// location to store the image created
					$photoName50 = 'photo' . $photo_time .   '_' . $_SESSION['uid'] . '_50.jpg';
					$photoName100 = 'photo' . $photo_time .  '_' .  $_SESSION['uid'] . '_100.jpg';
					$mixed_50percent = $edited_dir . $photoName50;
					$mixed_100percent = $edited_dir . $photoName100;
					// Output and free from memory but draw the canvas before hand
					imagejpeg($canvasName, $mixed_50percent , 50);
					imagejpeg($canvasName, $mixed_100percent , 100);
					$imgDestroyed = imagedestroy($canvasName);
					// store img name to session and redirect user
					if ($imgDestroyed && $isCon){
						$_SESSION['photoName50'] = $photoName50;
						$_SESSION['photoName100'] = $photoName100;
						// now add to the pic_mix stats as pple mix.
						$newStats = (int)($picMixedStats + 1);
						$newStatsSql = "UPDATE statistic SET pic_mixed = '".$newStats."'";
						$newStatsRst = $db->query($newStatsSql);
						// check for success
						if (!$newStatsRst){
							// means error as occur
							echo 'Error: ' . $db->error; // debugin ish only
						} else {
							// store success msg and redirect user
							header('location: ../?rst=picture_mixed_successful');
						}
					}
				}
			}
		} else {
			header('location: ../?err=invalid_file_or_size_more_than_2MB');
		}
	} else {
		// send an empty error msg to the user
		header('location: ../?err=inputs_can_not_be_empty_please_select_a_file');
	}
	
} else {
	// send an empty error msg to the user
		header('location: ../');
}

?> 