<!-- Reset Password Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="reset-password">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form  method="post" action="<?php echo get_link('reset-password'); ?>">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans('Reset Your Password');?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans('Please enter your phone number and click ok');?></p>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
					<input class="form-control" name="phone" placeholder="<?php ptrans('Phone Number');?>" value="" type="text">
				</div>
			</div>
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans('Close');?></button>
	        <button type="submit" class="btn btn-info" name="do-send-code"><?php ptrans('OK');?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>

<div class="container">
	<div class="row">
			<h2><?php ptrans('Reset Password');?></h2>
				<?php 
					if(isset($content['reset-error'])){
						echo '<p class="alert alert-danger">' . $content['reset-error'] .'<button id="retry" class="btn btn-info" data-toggle="modal" data-target="#reset-password"">Retry</button></p>';
					}else
						echo '<P>'.trans('A verificaion code just sent to number').': <b style="color: green;">'.$_SESSION['reset_phone'] .'</b></P>';
				?>
			<form method="post" action="<?php echo get_link('reset-password'); ?>">
				<?php
					if(count($content['error-messages'])>0)
						foreach ($content['error-messages'] as $key => $error) 
							echo '<p class="alert alert-danger">' . @$error .'</p>';
				?>
				<div class="form-group">
				  <div class="cols-sm-10">
					<div class="input-group">
					  <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
					  <input class="form-control" name="code" type="input" placeholder="<?php ptrans('Verification Code');?>"/>
					</div>
				  </div>
				</div>
				<div class="form-group">
				  <div class="cols-sm-10">
					<div class="input-group">
					  <span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
					  <input class="form-control" name="pass1" type="password" placeholder="<?php ptrans('New Password');?>"/>
					</div>
				  </div>
				</div>
				<div class="form-group">
				  <div class="cols-sm-10">
					<div class="input-group">
					  <span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
					  <input class="form-control" name="pass2" type="password" placeholder="<?php ptrans('Confirm Password');?>"/>
					</div>
				  </div>
				</div>												
				<button name="do_reset_password" type="submit" class="btn btn-success btn-lg btn-block"><?php ptrans('Reset Your Password');?></button>
			</form>
	</div>
</div>