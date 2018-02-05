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
					
		$photo3 = array(
					'type'=>$_FILES['dp3']['type'],
					'size'=>$_FILES['dp3']['size'],
					'name'=>$_FILES['dp3']['name'],
					'error'=>$_FILES['dp3']['error'],
					'tmp_name'=>$_FILES['dp3']['tmp_name']
					);
					
		$photo4 = array(
					'type'=>$_FILES['dp4']['type'],
					'size'=>$_FILES['dp4']['size'],
					'name'=>$_FILES['dp4']['name'],
					'error'=>$_FILES['dp4']['error'],
					'tmp_name'=>$_FILES['dp4']['tmp_name']
					);
		$photo_time = time();
		// before edit directory path
		$edit_dir = '../' . $config['edit_dir'];
		// after edit directory path
		$edited_dir = '../' . $config['edited_dir'];
		// split up photo name to valid with its extension
		$photo1Ext = pathinfo($photo1['name'], PATHINFO_EXTENSION);
		$photo2Ext = pathinfo($photo2['name'], PATHINFO_EXTENSION);
		$photo3Ext = pathinfo($photo3['name'], PATHINFO_EXTENSION);
		$photo4Ext = pathinfo($photo4['name'], PATHINFO_EXTENSION);
		
		if (validPhoto($photo1["type"], $photo1['size'], $photo1Ext) && validPhoto($photo2["type"], $photo2['size'], $photo2Ext) && 
			validPhoto($photo3["type"], $photo3['size'], $photo3Ext) && validPhoto($photo4["type"], $photo4['size'], $photo4Ext)) {
		  if ($photo1["error"] || $photo2["error"] || $photo3["error"] || $photo4["error"]) {
				header('location: ../?err=error_handling_picture_please_try_again');
			} else {
				//rename and move photos by joining - time & bf_edit
				$photo1_name = "photo" . '_' . $photo_time .  '_' . $_SESSION['uid'] . '.' . htmlspecialchars($photo1Ext);
				$photo2_name = "photo2" . '_' . $photo_time . '_' .  $_SESSION['uid'] . '.' . htmlspecialchars($photo2Ext);
				$photo3_name = "photo3" . '_' . $photo_time . '_' .  $_SESSION['uid'] . '.' . htmlspecialchars($photo3Ext);
				$photo4_name = "photo4" . '_' . $photo_time . '_' .   $_SESSION['uid'] . '.' . htmlspecialchars($photo4Ext);
				// now copy/move it
				$photo1_mv = move_uploaded_file($photo1['tmp_name'], $edit_dir . $photo1_name);
				$photo2_mv = move_uploaded_file($photo2['tmp_name'], $edit_dir . $photo2_name);
				$photo3_mv = move_uploaded_file($photo3['tmp_name'], $edit_dir . $photo3_name);
				$photo4_mv = move_uploaded_file($photo4['tmp_name'], $edit_dir . $photo4_name);
				// if photo mv is successful, keep its details
				if ($photo1_mv && $photo2_mv && $photo3_mv && $photo4_mv ){
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
										'canvasMargin' => 8,
										'stamp' => null
									);

					// my define function, with returns of $photo['name'], ['width'], ['height'], ['ext']
					$photoResized_1 = resizeImage ($edit_dir . $photo1_name, $default['mixWidth']);
					$photoResized_2 = resizeImage ($edit_dir . $photo2_name, $default['mixWidth']);
					$photoResized_3 = resizeImage ($edit_dir . $photo3_name, $default['mixWidth']);
					$photoResized_4 = resizeImage ($edit_dir . $photo4_name, $default['mixWidth']);
					//fetch the sizes of the photos
					list($photoResizedId1,$photo1Width,$photo1Height) = $photoResized_1;
					list($photoResizedId2,$photo2Width,$photo2Height) = $photoResized_2;
					list($photoResizedId3,$photo3Width,$photo3Height) = $photoResized_3;
					list($photoResizedId4,$photo4Width,$photo4Height) = $photoResized_4;
					
					$canvas = drawCanvas ($photo1Width,$photo1Height,$photo2Width,$photo2Height,$default['canvasMargin']);
					$canvas2 = drawCanvas ($photo3Width,$photo3Height,$photo4Width,$photo4Height,$default['canvasMargin']);
					// mixing ish start here
					list($canvasName, $canvasWidth, $canvasHeight, $commonPhotoHeight) = $canvas;
					list($canvas2Name, $canvas2Width, $canvas2Height, $commonPhoto2Height) = $canvas2;
					// get the new margin for the y-axis of the canvas
					$mainMargin = ($default['canvasMargin'] * 4);
					$newMargin = ceil($mainMargin / 3);
					// define photo 2 position
					$photo2Pos = $photo1Width + ($default['canvasMargin'] * 2);
					$photo2Pos_y = $commonPhotoHeight + $newMargin;
					
					// Copy and merge fisrt pair of images
					imagecopy($canvasName, $photoResizedId1, $default['canvasMargin'], $newMargin, 0, 0, $photo1Width, $commonPhotoHeight);
					imagecopy($canvasName, $photoResizedId2, $photo2Pos, $newMargin, 0, 0, $photo2Width, $commonPhotoHeight);
					// Copy and merge 2nd pair of images
					imagecopy($canvas2Name, $photoResizedId3, $default['canvasMargin'], $newMargin, 0, 0, $photo3Width, $commonPhoto2Height);
					imagecopy($canvas2Name, $photoResizedId4, $photo2Pos, $newMargin, 0, 0, $photo4Width, $commonPhoto2Height);
					
					/* ---------------------------------------------------------------
						Time to join both canvas together on new canvas as a template 
						--------------------------------------------------------------- */
					// gatto first get some dimensions
					list($canvas1Width,$canvas1Height) = array(imagesx($canvasName),imagesy($canvasName));
					list($canvas2Width,$canvas2Height) = array(imagesx($canvas2Name),imagesy($canvas2Name));
					$newCanvas = drawCanvasVert ($canvas1Width,$canvas1Height,$canvas2Width,$canvas2Height,0); // first drawing the common canvas
					$newCanvas = $newCanvas[0];
					// now merge
					imagecopy($newCanvas, $canvasName, 0, 0, 0, 0, $canvas1Width, $canvas1Height);
					imagecopy($newCanvas, $canvas2Name, 0, $photo2Pos_y, 0, 0, $canvas2Width, $canvas2Height);
					
					// stamp the image but not for now
					
					// filter the image but not working on PHP Version 5.2
					//imagefilter($canvasName, IMG_FILTER_SMOOTH, 50);
					// location to store the image created
					$photoName50 = 'photo' . $photo_time .   '_' .  '_' . $_SESSION['uid'] . '_50.jpg';
					$photoName100 = 'photo' . $photo_time .   '_' .  '_' . $_SESSION['uid'] . '_100.jpg';
					$mixed_50percent = $edited_dir . $photoName50;
					$mixed_100percent = $edited_dir . $photoName100;
					// Output and free from memory but draw the canvas before hand
					imagejpeg($newCanvas, $mixed_50percent , 50);
					imagejpeg($newCanvas, $mixed_100percent , 100);
					$imgDestroyed = imagedestroy($newCanvas);
					// store img name to session and redirect user
					if ($imgDestroyed && $isCon == true){
						$_SESSION['photoName50'] = $photoName50;
						$_SESSION['photoName100'] = $photoName100;
						// now add to the pic_mix stats as pple mix.
						$newStats = (int)($picMixedStats + 1);
						$newStatsSql = "UPDATE statistic SET pic_mixed = '".$newStats."'";
						$newStatsRst = $db->query($newStatsSql);
						// check for success
						if (!$newStatsRst){
							// means error as occur
							//echo 'Error: ' . $db->error; // debugin ish only
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