<script>
// https://datatables.net/reference/option/language
$(document).ready(function(){
    if($('.table')){
        $('.table').DataTable({
        	responsive: true,
	        "language": {
	            "lengthMenu": "<?php ptrans("Display _MENU_ records per page"); ?>",
	            "zeroRecords": "<?php ptrans("Nothing found - sorry"); ?>",
	            "info": "<?php ptrans("Showing page _PAGE_ of _PAGES_"); ?>",
	            "infoEmpty": "<?php ptrans("No records available"); ?>",
	            "infoFiltered": "<?php ptrans("(filtered from _MAX_ total records)"); ?>",
	        }
        });
    }
});
</script>

<!-- Invite Member -->
<div class="modal fade" tabindex="-1" role="dialog" id="invite-member">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form  method="post" action="<?php echo get_link('group-members',$content['group']['id']); ?>">
	    	<input type="hidden" name="gid" value="<?php echo $content['group']['id']?>">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans("Invite Member"); ?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans("Please enter phone number"); ?>:</p>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
					<input class="form-control" name="phone" placeholder="<?php ptrans('Phone Number'); ?>" value="" type="text">
				</div>
			</div>
		  </div>
	      <div class="modal-footer ">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans("Close"); ?></button>
	        <button type="submit" class="btn btn-info" name="do-invite-member"><?php ptrans("Invite Member"); ?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>

<!-- Add Admin -->
<div class="modal fade" tabindex="-1" role="dialog" id="add-admin">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form  method="post" action="<?php echo get_link('group-members',$content['group']['id']); ?>">
	    	<input type="hidden" name="gid" value="<?php echo $content['group']['id']?>">
	      <div class="modal-header ">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans("Add Administrator"); ?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans("Please enter phone number"); ?>:</p>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
					<input class="form-control" name="phone" placeholder="<?php ptrans('Phone Number');?>" value="" type="text">
				</div>
				<h4><?php ptrans("Permissions"); ?></h4>
				<ul>
				<?php 
					foreach (GROUP_PERMISSIONS as $key => $value) {
						
						if(in_array($value, $content['group']['permissions']))
							echo '<li><input id="' . $value . '" type="checkbox" name="' . $value . '" checked="checked" /><label for="' . $value . '" class="permission">' . strtolower( str_replace('_',' ',$value)) . '</label></li>';
						else
							echo '<li><input id="' . $value . '" type="checkbox" name="' . $value . '"  disabled="disabled" /><label for="' . $value . '" class="permission">' . strtolower( str_replace('_',' ',$value)) . '</label></li>';

					}
				?>
				</ul>
			</div>
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-info" name="do-add-admin"><?php ptrans("Add Administrator"); ?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>

<!-- Kick Out User -->
<div class="modal fade" tabindex="-1" role="dialog" id="kickout-user">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form  method="post" action="<?php echo get_link('group-members',$content['group']['id']); ?>">
	    	<input type="hidden" name="gid" value="<?php echo $content['group']['id']?>">
	    	<input type="hidden" id="uid-to-kick" name="uid" value="#">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans("Danger"); ?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans("Are you sure you want to force out this user?"); ?></p>
				<hr>
				<div class="checkbox">
				  <label for="block-user-too"><input name="block-user" id="block-user-too" type="checkbox" value="1"><?php ptrans("Block this user too"); ?>.</label>
				</div>
			</div>	      
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans("Close"); ?></button>
	        <button type="submit" class="btn btn-danger" name="do-kickout-user"><?php ptrans("Force Out User"); ?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>
<!-- Remove Membership Invitation -->
<div class="modal fade" tabindex="-1" role="dialog" id="remove-invitation">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form  method="post" action="<?php echo get_link('group-members',$content['group']['id']); ?>">
	    	<input type="hidden" name="gid" value="<?php echo $content['group']['id']?>">
	    	<input type="hidden" id="remove-invitation-phone" name="remove-invitation-phone" value="#">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans("Danger"); ?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans("Are you sure you want to Remove this membership invitation?"); ?></p>
			</div>	      
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans("Close"); ?></button>
	        <button type="submit" class="btn btn-danger" name="do-remove-invitation"><?php ptrans("Remove Invitation"); ?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>
<!-- Unblock Member -->
<div class="modal fade" tabindex="-1" role="dialog" id="unblock-membership">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form  method="post" action="<?php echo get_link('group-members',$content['group']['id']); ?>">
	    	<input type="hidden" name="gid" value="<?php echo $content['group']['id']?>">
	    	<input type="hidden" id="unblock-user-id" name="unblock-user-id" value="#">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans("Unblock Member"); ?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans("Are you sure you want to unblock this member?"); ?></p>
			</div>	      
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans("Close"); ?></button>
	        <button type="submit" class="btn btn-danger" name="do-unblock-membership"><?php ptrans("Unblock Member"); ?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>



<div class="container">
	<h1>
		<i class="fa fa-university" aria-hidden="true"></i>
		<?php echo $content['group']['name']; ?>
	</h1>
	<div class="row">
		<div class="col-md-12">
			<h3>
				<?php ptrans("Members List"); ?>
				<button class="btn btn-default pull" data-toggle="modal" data-target="#invite-member"><?php ptrans("Invite Member"); ?></button>
				<button class="btn btn-default pull" data-toggle="modal" data-target="#add-admin"><?php ptrans("Add Administrator"); ?></button>
				<div class="btn-group" role="group" aria-label="...">
				  <a href="<?php echo get_link('group-members', $content['group']['id'] , array('status' => 'member')) ?>" class="btn btn-default"><?php ptrans("Members"); ?></a>
				  <a href="<?php echo get_link('group-members', $content['group']['id'] , array('status' => 'invited')) ?>" class="btn btn-default"><?php ptrans("Invitations"); ?></a>
				  <a href="<?php echo get_link('group-members', $content['group']['id'] , array('status' => 'blocked')) ?>" class="btn btn-default"><?php ptrans("Blocked"); ?></a>
				  <a href="<?php echo get_link('group-members', $content['group']['id'] , array('status' => 'admin')) ?>" class="btn btn-default pull"><?php ptrans("Administrators"); ?></a>
				</div>
			</h3>
			<?php 
				if(count($content['messages']))
					foreach($content['messages'] as $message) {
						echo '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message . '</div>';
					}
			?>
	        <div class="list-right col-md-12 pull-left">
				<table class="table table-striped table-bordered" width="100%" cellspacing="0">
					<thead>
					    <tr>
					        <th><?php ptrans("Name"); ?></th>
					        <th><?php ptrans("Phone"); ?></th>
					        <th><?php ptrans("Actions"); ?></th>
					    </tr>
					</thead>
					<tfoot>
					    <tr>
					        <th><?php ptrans("Name"); ?></th>
					        <th><?php ptrans("Phone"); ?></th>
					        <th><?php ptrans("Actions"); ?></th>
					    </tr>
					</tfoot>
					<tbody>
						<?php foreach($content['users'] as $user) {?>
							<tr>
							    <td><?php echo $user['name']; ?></td>
							    <td><?php echo $user['phone']; ?></td>
							    <td>
							    	<?php if($user['id'] == $content['user']['id'])
							    		echo '<span style="color:red;">Me</span>';
							    	 else
							    	 	if($user['status'] == 'member')
							    	 		echo '<button class="btn btn-danger btn-kickout-user" data-toggle="modal" data-target="#kickout-user" value="'.$user['id'].'">Remove Membership</button>';
							    	 	elseif($user['status'] == 'invited')
							    	 		echo '<button class="btn btn-danger btn-remove-invitation" data-toggle="modal" data-target="#remove-invitation" value="'.$user['phone'].'">Remove Invitation</button>';
							    	 	if($user['status']=='blocked')
							    	 		echo '<button class="btn btn-info btn-unblock-member" data-toggle="modal" data-target="#unblock-membership" value="'.$user['id'].'">Unblock</button>';
							    	?>
							    </td>
						    </tr>
						<?php }?>
					</tbody>
			    </table>
	        </div>
		</div>
	</div>
</div>