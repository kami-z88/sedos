<!-- Reset Password Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="reset-password">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form  method="post" action="<?php echo get_link('reset-password'); ?>">
	    	<input type="hidden" name="gid" value="<?php echo $content['group']['id']?>">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans("Reset Your Password"); ?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans("Please enter your phone number and click ok"); ?></p>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
					<input class="form-control" name="phone" placeholder="<?php ptrans('Phone Number');?>" value="" type="text">
				</div>
			</div>
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans("Close"); ?></button>
	        <button type="submit" class="btn btn-info" name="do-send-code"><?php ptrans("OK"); ?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>

		<div class="jumbotron">
			<div class="container">
				<h1><?php ptrans("Hello, world"); ?>!</h1>
				<p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
				<p><a class="btn btn-primary btn-lg" href="#" role="button"><?php ptrans("Learn more"); ?> &raquo;</a></p>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h2><?php ptrans("Login"); ?></h2>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<?php 
							if(isset($content['login-error']))
								echo '<p class="alert alert-danger">' . @$content['login-error'] .'</p>';
						?>
						<div class="form-group">
						  <div class="cols-sm-10">
							<div class="input-group">
							  <span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
							  <input type="text" class="form-control" name="phone" type="text" placeholder="<?php ptrans('Cell Phone'); ?>"/>
							</div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="cols-sm-10">
							<div class="input-group">
							  <span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
							  <input class="form-control" name="password" type="password" placeholder="<?php ptrans('Password'); ?>"/>
							</div>
						  </div>
						</div>
						<div class="checkbox">
						  <label class="pull"><input name="remember" type="checkbox"><?php ptrans("Remember me"); ?></label>
						  <a id="forgot-pass"class="pull-right" data-toggle="modal" data-target="#reset-password"><?php ptrans("Forgot Password?"); ?></a>
						</div>
						<button name="do_login" type="submit" class="btn btn-success btn-lg btn-block"><?php ptrans("Sign in"); ?></button>
						<a href="<?php echo get_link('verify'); ?>" class="btn btn-default btn-lg btn-block"><?php ptrans("Verify Phone Number"); ?></a>
					</form>
				</div>
				<div class="col-md-6">
					<h2><?php ptrans("Register"); ?></h2>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<div class="form-group">
							<div class="cols-sm-10">
									<?php
										if(@$content['register-errors']['name1'])
											echo '<p class="alert alert-danger">' .  @$content['register-errors']['name1'] .'</p>';
									?>
									<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="name" id="name" placeholder="<?php ptrans('Enter your Name'); ?>" value="<?php echo $_POST['name']; ?>"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php
								if(@$content['register-errors']['email1'])
									echo '<p class="alert alert-danger">' .  @$content['register-errors']['email1'] .'</p>';
								if(@$content['register-errors']['email2'])
									echo '<p class="alert alert-danger">' .  @$content['register-errors']['email2'] .'</p>';
							?>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope " aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="email" id="email"	placeholder="<?php ptrans('Enter your Email'); ?>" value="<?php echo $_POST['email']; ?>"/>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="cols-sm-10">
								<?php
									if(@$content['register-errors']['phone1'])
										echo '<p class="alert alert-danger">' .  @$content['register-errors']['phone1'] .'</p>';
									if(@$content['register-errors']['phone2'])
										echo '<p class="alert alert-danger">' .  @$content['register-errors']['phone2'] .'</p>';
								?>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="phone" id="phone"	placeholder="<?php ptrans('Enter your cell phone number'); ?>" value="<?php echo $_POST['phone']; ?>"/>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="cols-sm-10">
								<?php
									if(@$content['register-errors']['pass1'])
										echo '<p class="alert alert-danger">' .  @$content['register-errors']['pass1'] .'</p>';
									if(@$content['register-errors']['pass2'])
										echo '<p class="alert alert-danger">' .  @$content['register-errors']['pass2'] .'</p>';
								?>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
									<input type="password" class="form-control" name="password" id="password"	placeholder="<?php ptrans('Enter your Password'); ?>"/>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
									<input type="password" class="form-control" name="confirm" id="confirm"	placeholder="<?php ptrans('Confirm your Password'); ?>"/>
								</div>
							</div>
						</div>

						<div class="form-group ">
							<button name="do_register" type="submit" class="btn btn-primary btn-lg btn-block"><?php ptrans("Register"); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<h2>Heading</h2>
					<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
					<p><a class="btn btn-default" href="#" role="button"><?php ptrans('View details'); ?> &raquo;</a></p>
				</div>
				<div class="col-md-4">
					<h2>Heading</h2>
					<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
					<p><a class="btn btn-default" href="#" role="button"><?php ptrans('View details'); ?> &raquo;</a></p>
			 </div>
				<div class="col-md-4">
					<h2>Heading</h2>
					<p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
					<p><a class="btn btn-default" href="#" role="button"><?php ptrans('View details'); ?> &raquo;</a></p>
				</div>
			</div>
		</div> 