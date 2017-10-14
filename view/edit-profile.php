<div id="delete-account" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php ptrans("Danger"); ?></h4>
      </div>
      <div class="modal-body">
        <p><?php ptrans("Are you sure you want to delete your user account?"); ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans("Close"); ?></button>
        <a href="<?php echo get_link('detete-user', $user['id']); ?>" class="btn btn-danger"><?php ptrans("Delete This Acount"); ?> </a>
      </div>
    </div>
  </div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h2><?php ptrans("Edit Profile"); ?></h2>
			<form method="post" action="<?php echo get_link('edit-profile', $content['user']['id']); ?>">
				<?php 
					if(count($content['errors-profile'])>0)
						foreach ($content['errors-profile'] as $key => $error) 
							echo '<p class="alert alert-danger">' . @$error .'</p>';
					elseif(isset($_POST['do_update_profile']))
						echo '<p class="alert alert-success">'.trans("Your changes done").'</p>';
				?>
				<div class="form-group">
				  <div class="cols-sm-10">
					<div class="input-group">
					  <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
					  <input class="form-control" name="name" type="text" placeholder="Your Name" value="<?php echo $content['user']['name']; ?>" />
					</div>
				  </div>
				</div>
				<div class="form-group">
				  <div class="cols-sm-10">
					<div class="input-group">
					  <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
					  <input class="form-control" name="email" type="text" placeholder="New Email" value="<?php echo $content['user']['email']?>"/>
					</div>
				  </div>
				</div>
					  <?php 
						if(isset($_POST['do_resend_mail_code']))
							echo '<p class="alert alert-info">'.trans("New code is sent!").'</p>';
						elseif ($content['user']['email_verified'] == 0) {
							echo '<p class="alert alert-danger"> '.trans("Email is not verified").' <button name="do_resend_mail_code" type="submit" class="btn btn-danger pull">Resend Link</button></p>';
						}
					  ?>
				<button name="do_update_profile" type="submit" class="btn btn-success btn-lg btn-block"><?php ptrans("Update Profile"); ?></button>
			</form>
			<hr />
			<h2><?php ptrans("Edit Password"); ?></h2>
			<form method="post" action="<?php echo get_link('edit-profile', $content['user']['id']); ?>">
				<?php 
					if(count($content['errors-password'])>0)
						foreach ($content['errors-password'] as $key => $error) 
							echo '<p class="alert alert-danger">' . @$error .'</p>';
					elseif(isset($_POST['do_reset_password']))
						echo '<p class="alert alert-success">'.trans("Your changes done").'</p>';
				?>
				<div class="form-group">
				  <div class="cols-sm-10">
					<div class="input-group">
					  <span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
					  <input class="form-control" autocomplete="off" name="pass1" type="password" placeholder="Enter your new password" value="" />
					</div>
				  </div>
				</div>
				<div class="form-group">
				  <div class="cols-sm-10">
					<div class="input-group">
					  <span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
					  <input class="form-control" autocomplete="off" name="pass2" type="password" placeholder="Repeat password" value=""/>
					</div>
				  </div>
				</div>
				<button name="do_reset_password" type="submit" class="btn btn-success btn-lg btn-block"><?php ptrans("Reset Password"); ?></button>
			</form>
		</div>

		<div class="col-md-6">
			<h2><?php ptrans("Your Phone Numbers"); ?></h2>
			<form method="post" action="<?php echo get_link('edit-profile', $content['user']['id']); ?>">
				<?php 
					if(count($content['errors-phone'])>0)
						foreach ($content['errors-phone'] as $key => $error) 
							echo '<p class="alert alert-danger">' . @$error .'</p>';
					elseif(isset($_POST['do_update_phone'])) 
						echo '<p class="alert alert-success">'.trans("Default phone number was updated").'</p>';
					elseif($_GET["action"]=="del_phone" && $_GET['place'] == "update") 
						echo '<p class="alert alert-success">'.trans("Phone number deleted").'</p>';
				?>
				<ul id="check-list-box" class="list-group checked-list-box">
				 <li class="list-group-item"><input type = "radio" name = "phone_num" id = "default_phone" value = "<?php echo $content['user']['phone_num']; ?>" checked = "checked" /><label for = "default_phone"><?php echo $content['user']['phone_num']; ?></label><span class="label label-primary"><?php ptrans("default login number"); ?></span></li>
				<?php foreach ($content['user-phones'] as $key => $phone) {?>
 					<li class="list-group-item">
 						<input type = "radio" name = "phone_num" id = "phone_<?php echo @$phone; ?>" value = "<?php echo $phone; ?>" /><label for = "phone_<?php echo $phone; ?>"><?php echo @$phone; ?></label>
 						<a type="button" class="btn btn-danger pull-right" href="<?php echo get_link('edit-profile',$content['user']['id'],$arrayName = array('action' => 'del_phone', 'phone' => $phone, 'place' => 'update')); ?>"><i class="fa fa-trash "></i></a>
 					</li>
				<?php  }?>

				</ul>
				<button name="do_update_phone" type="submit" class="btn btn-success btn-lg btn-block"><?php ptrans("Update Default Phone"); ?></button>
			</form>

			<hr />

			<h2><?php ptrans("Add Phone Number"); ?></h2>
			<form method="post" action="<?php echo get_link('edit-profile', $content['user']['id']); ?>">
				<?php 
					if(count($content['error-add-phone'])>0)
							echo '<p class="alert alert-danger">' . @$content['error-add-phone'] .'</p>';
					elseif(isset($_POST['do_add_phone'])) 
						echo '<p class="alert alert-success">'.trans("Your phone number was added successfully").'</p>';
					elseif($_GET["action"]=="del_phone" && $_GET['place'] == "add") 
						echo '<p class="alert alert-success">'.trans("Phone number deleted").'</p>';
				?>
				<ul id="check-list-box" class="list-group checked-list-box">
				<?php foreach (@$content['user-unverified-phones'] as $key => $phone) {?>
 					<li class="list-group-item">
 						<span style="font-weight: bold;" ><?php echo $phone; ?></span>
 						<a type="button" class="btn btn-info pull-right" href="<?php echo get_link('verify',$phone); ?>"><?php ptrans("Verify"); ?></a>
 						<a type="button" class="btn btn-danger pull-right" href="<?php echo get_link('edit-profile',$content['user']['id'],$arrayName = array('action' => 'del_phone', 'phone' => $phone, 'place' => 'add')); ?>"><i class="fa fa-trash "></i></a>
 					</li>
				<?php  }?>
				</ul>
				<div class="form-group">
					<div class="cols-sm-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
							<input type="text" class="form-control" name="new-phone" type="text" placeholder="Add New Phone" value="<?php echo $content['verify']['phone']; ?>" />
						</div>
					</div>
				</div>				
				<button name="do_add_phone" type="submit" class="btn btn-success btn-lg btn-block"><?php ptrans("Add New Phone"); ?></button>
			</form>
			<hr>
			<button type="submit" class="btn btn-danger btn-lg btn-block" data-toggle="modal" data-target="#delete-account"><?php ptrans("Delete This Acount"); ?></button>
		</div>
	</div>
</div>