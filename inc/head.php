<?php

/**
 * @author Michael Akanji <matscode@gmail.com>
 */

	include 'inc/config.php';
	include 'inc/db_con.php';
	include 'lib/sbox.php';
	include 'lib/rst_handler.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title> Display Picture Editor  - Show the You in You</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Upload your picture and get it edited easily... DPEdit is an Automated Display Picture Editor." />
		<meta name="keywords" content="picture" />
		<meta name="author" content="Aknji Michael" />

		<!-- Bootstrap -->
		<link href="theme/css/bootstrap.css" rel="stylesheet" />
		<!-- <link href="theme/css/bootstrap-theme.css" rel="stylesheet" /> -->
		<!-- Custom CSS -->
		<link href="theme/css/style.css" rel="stylesheet">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<div class="navbar-brand" id="logo">
				<a class="" href="<?php if(isset($config['site_url']) && !empty($config['site_url'])) echo $config['site_url']; ?>">
					<img src="img/dpedit.png" alt="DPEdit" width=""/>
				</a> <!-- end navbar logo -->
			</div>
		</div> <!-- end logo container -->
		<div class="navbar navbar-default" id="navi">
			<div class="container">
				 <ul class="nav navbar-nav">
					<li class="active">
						<a href="<?php if(isset($config['site_url']) && !empty($config['site_url'])) echo $config['site_url']; ?>"> Home </a>
					</li>
					<li>
						<a href="?"> Mix 2 by 1 </a>
					</li>
					<li>
						<a href="?mix_type=mix_two"> Mix 2 by 2 </a>
					</li>
				 </ul>
			</div> <!-- end navbar container -->
		</div>
		<div class="container" id="main">
		<?php
			// check for success result
			if (isset($rst) && !empty($rst)){
				// print the success msg
				echo '<div class="alert alert-dismissable alert-success" id="hideAlert">
							<button type="button" class="close" data-dismiss="alert"> &times; </button>
							<strong> Success: </strong>  '.$rst.'
						</div>';
			} elseif (isset($err) && !empty($err)){
				// print the error msg
				echo '<div class="alert alert-dismissable alert-danger errAlert" id="">
							<button type="button" class="close" data-dismiss="alert"> &times; </button>
							<strong> Error: </strong>  '.$err.'
						</div>';
			}
		?>