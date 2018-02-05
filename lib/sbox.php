<?php

/**
 * @author Michael Akanji <matscode@gmail.com>
 */

/* ----------------------------
	Class/Functions for DPEdit
   ---------------------------- */

	function sanit($strtoclean){
	/* -------- FUNCTION DOC ------------
		function to put stamp on images
		** Argument to be passed to the
		function are listed below and comment
		in front of em
		
		$strtoclean ===
		
		** Values to be return by the function
		$cleanedStr
		---------------------------------- */
		$strtoclean = trim($strtoclean);
		$strtoclean = stripslashes($strtoclean);
		$cleanedStr = htmlspecialchars($strtoclean);
		return $cleanedStr;
	}
	
	
	//function resize resize image
	function resizeImage($path, $resampleWidth){
	/* -------- FUNCTION DOC ------------
		function to resize image using resample image to maintain aspect ratio
		** Argument to be passed to the
		function are listed below and comment
		in front of em
		
		$path === path to image to be resized
		$resampleWidth === desired width of the image to be resized
		
		** Values to be return by the function
		$photo(identity) === the identity of the image resized
		$resampleWidth === the desired width of the image
		$resampleHeight === the height of the image return as the function is trying to maitain aspect ratio
		$photoExt === extention of the photo as jpg|png
		---------------------------------- */
		//find photo's ext to know which photoType to create from e.g. imagecreatefromjpeg|png
		$pathPart = explode('.',$path);
		$photoExt = end($pathPart);
		//check for photos extention
		if ($photoExt == 'jpg' || $photoExt == 'jpeg'){
			// read photos as jpeg
			$photoType = imagecreatefromjpeg($path);
		} else {
			// read photo as png
			$photoType = imagecreatefrompng($path);
		}
		// width & height of images
		list($pWidth, $pHeight) = getimagesize($path);
		// get the ratio of the photo lenght & breath
		$realSizeRatio = ($pWidth/$pHeight);
		// set the height
		$resampleHeight = ($resampleWidth/$realSizeRatio);
		// Resample
		$photo = imagecreatetruecolor($resampleWidth, $resampleHeight);
		// resample == resize the image
		imagecopyresampled($photo, $photoType, 0, 0, 0, 0, $resampleWidth, $resampleHeight, $pWidth, $pHeight);
		// return few of the photo character
		return $phot = array(
								$photo, // resource of photo
								$resampleWidth, // photo width
								$resampleHeight, // photo height
								$photoExt // photo extension
							);
	}
	//draw the canvas before making mix for 1 by 2 mixing
	function drawCanvas($photo1Width, $photo1Height, $photo2Width, $photo2Height, $canvasMargin){
		//return canvas width and height in an array
		$canvasWidth = ($photo1Width + $photo2Width) + ($canvasMargin * 3);
		$canvasHeight = '';
		// tryna manipulate photos w & h to get acuurate canvas height
		if ($photo1Height > $photo2Height){
			// then equate the height of photo2 to photo1
			$photo1Height = $photo2Height;
		// so common photoheight equate to $commonHeight
		$commonPhotoHeight = $photo1Height;
		} else {
			// do other wise
			$photo2Height = $photo1Height;
		// so common photoheight equate to $commonHeight
		$commonPhotoHeight = $photo2Height;
		}
		// now set canvas height
		$canvasHeight = $commonPhotoHeight + ($canvasMargin * 2);
		//draw the canvas
		$canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);
		$canvasBgColor = imagecolorallocate($canvas, 30, 60, 100);
		imagefill($canvas, 0, 0, $canvasBgColor);
		// send out formatted values
		return $canvas = array(
									$canvas, 
									$canvasWidth, 
									$canvasHeight, 
									$commonPhotoHeight
								);
	}
	
	function drawCanvasVert($photo1Width, $photo1Height, $photo2Width, $photo2Height, $canvasMargin){
		//return canvas width and height in an array
		$canvasHeight = ($photo1Height + $photo2Height) + ($canvasMargin * 3);
		$canvasWidth = '';
		// tryna manipulate photos w & h to get acuurate canvas height
		if ($photo1Width > $photo2Width){
			// then equate the height of photo2 to photo1
			$photo1Width = $photo2Width;
		// so common photoheight equate to $commonHeight
		$commonPhotoWidth = $photo1Width;
		} else {
			// do other wise
			$photo2Width = $photo1Width;
		// so common photoheight equate to $commonHeight
		$commonPhotoWidth = $photo2Width;
		}
		// now set canvas height
		$canvasWidth = $commonPhotoWidth + ($canvasMargin * 2);
		//draw the canvas
		$canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);
		$canvasBgColor = imagecolorallocate($canvas, 30, 60, 100);
		imagefill($canvas, 0, 0, $canvasBgColor);
		// send out formatted values
		return $canvas = array(
									$canvas, 
									$canvasWidth, 
									$canvasHeight, 
									$commonPhotoWidth
								);
	}
	
	// function to put stamp on alll image generated
	function stamp($imageIdentity, $stampWidth, $stampPath, $canvasMargin, $transparencyValue){
	/* -------- FUNCTION DOC ------------
		function to put stamp on images
		** Argument to be passed to the
		function are listed below and comment
		in front of em
		
		$imageIdentity === 
		$stampPath === path to where to fetch the stamp to use on the image
		$canvasMargin === offset for where to position the stamp on the pic
		
		** Values to be return by the function
		$imageIdentiy === the resource name of the image
		$imageWidth === width of resource image
		$imageHeight === height of resource image
		---------------------------------- */
		list($imageWidth, $imageHeight) = array (imagesx($imageIdentity), imagesx($imageIdentity)); // width of the imageIdentified
		// intialize the process to create the stamp
		$stamp = resizeImage($stampPath, $stampWidth);
		// get the stamp dimensions
		list($stamp, $stampWidth, $stampHeight) = $stamp;
		// now do some math to get the stamp margin
		$stampPos_x = $imageWidth - $stampWidth - $canvasMargin;
		$stampPos_y = $imageHeight - $stampHeight - $canvasMargin;
		// now merge the stamp on to the imageIdentifier
		imagecopymerge ($imageIdentity, $stamp, $stampPos_x, $stampPos_y, 0, 0, $stampWidth, $stampHeight, $transparencyValue);
		return $stampImage = array (
										$imageIdentity,
										$imageWidth,
										$imageHeight
									);
	}
	
	
	// function to check image type
	function validPhoto($photoType, $photoSize, $photoExt){
	/* -------- FUNCTION DOC ------------
		function to check if the photo extentoin if met requirement
		** Argument to be passed to the
		function are listed below and comment
		in front of em
		$photoName === from an upload form
		
		** Values to be return by the function
		true === if image is valid
		false === only if image is invalid
		---------------------------------- */
		
		$allowedExts = array("jpeg", "jpg", "png");
		if (((($photoType == "image/jpeg")	|| ($photoType == "image/jpg") || 
			($photoType == "image/pjpeg") || ($photoType == "image/x-png") || 
			($photoType == "image/png")) && ($photoSize < 2000000) &&
			in_array($photoExt, $allowedExts))){
				// its a valid photo
				return true;
			} else {
				//photos invalid so opt out
				return false;
			}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>