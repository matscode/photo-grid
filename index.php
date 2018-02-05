<?php
	
/**
 * @author Michael Akanji <matscode@gmail.com>
 */

	include 'inc/head.php';
	include 'lib/session.php';
	// print the path to the picture
	$photoName = "";
	if (isset($_SESSION['photoName50']) && !empty($_SESSION['photoName50']) && isset($_SESSION['photoName100']) && !empty($_SESSION['photoName100'])){
		$photoName50 = 'after_edit/'.$_SESSION['photoName50'];
		$photoName100 = 'after_edit/'.$_SESSION['photoName100'];
	} else {
		$photoName100 = $photoName50 = "img/sam_img.jpg";
	}
?>
		<!-- Site editable content start here -->
			<div class="well well-sm">
				<h1 class="">
					Welcome to Display Picture Editor <small>  (DPEdit). </small>
					<p class="small"> <span class="glyphicon glyphicon-picture"></span> Show the You in You </p>
				</h1>
				<p class="text">
						For any Suggestion or Bug, you can contact the developer here, more features to be added in time keep mixing with no stamp <br /> 
						<a href="http://www.facebook.com/matscode"> Facebook </a> <br/>
						+2348186074929
				</p>
			</div>
			<div class="well">
				<div class="row">
					<div class="col-sm-4">
						<?php
							// see which mixing user want and include its template
							if (isset($_GET['mix_type']) && !empty($_GET['mix_type'])){
								$mixType = htmlspecialchars($_GET['mix_type']);
								if ($mixType == 'mix_two'){
									// follow it
									include 'inc/mixtwo.php';
								} else {
									// do default
									include 'inc/mix.php';
								}
							} else {
								// do default
								include 'inc/mix.php';
							}
						?>
					</div>
					<div class="col-sm-8">
							<fieldset>
								<legend> Mix Preview </legend>
								<div class="row">
									<div class="col-sm-6">
										<div class="panel panel-primary">
											<div class="panel-heading">Medium Quality</div>
											<div class="panel-body text-center">
												<div class="img-thumbnail">
													<img src="<?php echo $photoName50; ?>" class="" alt="mix result" width="100" />
												</div>
												<p class="help-block">
													Result of the Mixed picture in <span class="badge"> 50% </span> Quality
												</p>
											</div>
											<div class="panel-footer">
												<a href="<?php echo $photoName50; ?>" class="btn btn-success"> Download </a>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="panel panel-primary">
											<div class="panel-heading">High Quality</div>
											<div class="panel-body text-center">
												<div class="img-thumbnail">
													<img src="<?php echo $photoName100; ?>" class="" alt="mix result" width="100" />
												</div>
												<p class="help-block">
													Result of the Mixed picture in <span class="badge"> 100% </span> Quality
												</p>
											</div>
											<div class="panel-footer">
												<a href="<?php echo $photoName100; ?>" class="btn btn-success"> Download </a>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					Statistic
				</div>
				<div class="panel-body">
					Total Picture Mixed: <?= $picMixedStats  ? $picMixedStats : null ?>
				</div>
			</div>
		<!-- Site editable content end here -->
<?php
	// destroy session by checking if any imagepath saved in it
	if (isset($_SESSION['photoName50']) && !empty($_SESSION['photoName100'])){
		session_destroy();
	}
	include 'inc/foot.php';
?>