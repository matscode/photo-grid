
						<form class="form" method="post" action="lib/mix_pic.php" enctype="multipart/form-data">
							<fieldset>
								<legend> Select Photos </legend>
								<div class="form-group">
									<label for="dp2" class="control-label"> 1st Photo </label>
									<input class="form-control fileInput" id="dp2" type="file" name="dp2">
								</div>
								<div class="form-group">
									<label for="dp1" class="control-label"> 2nd Photo </label>
									<input class="form-control fileInput" id="dp1" type="file" name="dp1">
								</div>
								<div class="form-group">
									<div class="form-group">
										<input type="reset" value="Reset" class="btn btn-default" />
										<button type="submit" class="btn btn-primary"> Mix Photo </button>
									</div>
								</div>
							</fieldset>
						</form>