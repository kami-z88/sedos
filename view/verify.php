		<div class="container">
			<div class="row">
					<h2><?php ptrans('Verify');?></h2>
					<form method="post" action="<?php echo get_link('verify', $content['verify']['phone']); ?>">
						<?php 
							if(isset($content['verify-error']))
								echo '<p class="alert alert-danger">' . $content['verify-error'] .'</p>';
						?>
						<div class="form-group">
						  <div class="cols-sm-10">
							<div class="input-group">
							  <span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
							  <input type="text" class="form-control" name="phone" type="text" placeholder="<?php ptrans('Cell Phone');?>" value="<?php echo $content['verify']['phone']; ?>" />
							</div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="cols-sm-10">
							<div class="input-group">
							  <span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
							  <input class="form-control" name="code" type="input" placeholder="<?php ptrans('Verification Code');?>"/>
							</div>
						  </div>
						</div>
						<button name="do_verify" type="submit" class="btn btn-success btn-lg btn-block"><?php ptrans('Verify Phone Number');?></button>
					</form>
			</div>
		</div>