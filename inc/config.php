<?php

/**
 * @author Michael Akanji <matscode@gmail.com>
 */
   
/* ----------------------------------
	App default setting below as constant
   ---------------------------------- */
   // set the development environment
   define('APP', 'DP Editor');
   define('ENVIRON', 'development'); // Options are DEVELOPMENT or PRODUCTION or TEST


/* ---------------------------
	Lite vars.
   --------------------------- */
   
   define('NL', '<br />'); //defines an html line break
   
/* ---------------------------
	Heavy vars.
   --------------------------- */
	// some values are only to be set when going to host it online
	$config = array(
				"site_name"=> "DPEdit", // full name of the site
				"site_abr_name"=> "DPE", // site short name
				"site_root_dir"=> "dl", // name of site root directory
				"edit_dir"=> "before_edit/", // directory of to be edited photos
				"edited_dir"=> "after_edit/", // directory of edited photos
				"site_url"=> "", //set the site baseurl for easy use of ui/ template
				"site_path"=> "", // defines the root directory path of the dpedit app -- should be $_SERVER['DOCUMENT_ROOT']
				"error"=> "DPEdit Configuration Error, Contact Developer. . . . . . .",
				"dev_dtl"=> array( // Developers info starts here
								"name" => "Akanji Michael",
								"email" => "promatmot@gmail.com",
								"phone" => "23486074929",
								"site" => "http://www.datalodge.net"
								)  // Developers info end here
				);
		
	// check if no value are set and reset all for localhost as default server
	if (empty($config['base_url']) || empty($config['site_root'])){
		$config['site_url'] = 'http://localhost/dl'; // needed some fixing with this
		$config['site_path'] = $_SERVER['DOCUMENT_ROOT'].'/'.$config['site_root_dir'].'/';
	}
	// see which environment am developing
	if (defined('ENVIRON')){
		if (ENVIRON == 'development'){
			// work with error_reporting on
			error_reporting(E_ALL|E_STRICT);
		} elseif (ENVIRON == 'production'){
			// work with all error msg turned of
			error_reporting(0);
		} else {
			// testing mode, just log debugins to file|email
			//later ish
		}
	} else {
		// script tampered with, terminate
		exit($config['error']);
	}
	
