<!-- Edit Organization Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit-organization">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form method="post" action="<?php echo get_link('organization', $content['organization']['id']); ?>">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans('Update organization');?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
			  <span class="input-group-addon"><i class="fa fa-bank" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name="organization-name" type="text" placeholder="Organization Name" value="<?php echo $content['organization']['name']; ?>" />
			</div>	      
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans('Close');?></button>
	        <button type="submit" class="btn btn-primary" name="do-update-organization"><?php ptrans('Update');?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>
<!-- Remove Organization Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="remove-organization">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form method="post" action="<?php echo get_link('organization', $content['organization']['id']); ?>">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans('Danger');?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans('Are you sure you want to remove this organization?');?></p>
				<hr>
				<div class="checkbox">
				  <label for="cascade-remove"><input name="remove-all-subs" id="cascade-remove" type="checkbox" value="1"><?php ptrans('Remove all groups and sub-organizations that belong to this organization');?>!</label>
				</div>
			</div>	      
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans('Close');?></button>
	        <button type="submit" class="btn btn-danger" name="do-remove-organization"><?php ptrans('Remove Organization');?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>




<div class="container">
	<h1>
		<i class="fa fa-university" aria-hidden="true"></i>
		<?php echo $content['organization']['name']; ?>
		<button class="btn btn-info" id="edit-organization" data-toggle="modal" data-target="#edit-organization"><i class="fa fa-edit "></i></button>
		<button class="btn btn-danger" id="remove-organization" data-toggle="modal" data-target="#remove-organization"><i class="fa fa-trash "></i></button>
		<a class="btn btn-default" href="<?php echo get_link('send-message', $content['organization']['id'], ['context'=>'organization']); ?>"><?php ptrans("Send Message"); ?></a>
		<a class="btn btn-default pull" href="<?php echo get_link('organization-members', $content['organization']['id']); ?>"><i class="fa fa-users "></i> <?php ptrans('Manage Members');?> </a>
	</h1>
	<div class="row">
		<div class="col-md-6">
			<h3>
				<?php ptrans('Groups');?>
				<?php if(in_array('CREATE_GROUP', $content['permissions'])) {?>
					<a class="btn btn-default pull" href="<?php echo get_link('add-group', $content['organization']['id']); ?>"><?php ptrans('Add Group');?></a>
				<?php } ?>	
			</h3>
			<div class="list-group">
				<?php 
				if($content['organization']['groups']){
					foreach ($content['organization']['groups'] as $key => $group) {
						
						if(in_array($group['id'], $user_groupIDs)){
							echo '<a href="' . get_link('group' , $group['id']) . '" type="button" class="list-group-item">' . $group['name'] . '</a>';
						}else {
							echo '<span class="list-group-item">'.$group['name'].'<a href="' . get_link('group' , $group['id'], array('join'=>'YES')) . '" type="button" ><i id="join_group" class="btn btn-info pull">'.trans("Join Group").'</i></a></span>';						
						}	
					}						
				} else
				echo '<span class="list-group-item">'.trans("No groups directly associated with").' <b style="color:red;">'.$content['organization']['name'].'</b>.</span>';

					
				?>
			</div>
		</div>
		<div class="col-md-6">
			<h3>
				<?php ptrans('Sub-Organizations');?>
				<?php if(in_array('CREATE_SUB_ORGANIZATION', $content['permissions'])) {?>
					<a class="btn btn-default pull" href="<?php echo get_link('add-organization', $content['organization']['id']); ?>"><?php ptrans('Add Sub-Organization');?></a>
				<?php } ?>
			</h3>
			<div class="list-group">
				<?php 
				if($content['organization']['sub-organizations']){
					foreach ($content['organization']['sub-organizations'] as $key => $organization) {
						echo '<a href="' . get_link('organization' , $organization['id']) . '" type="button" class="list-group-item">' . $organization['name'] . '</a>';
					}					
				} else 
					echo '<span class="list-group-item">'.trans("No Sub-Organization associated with").' <b style="color:red;">'.$content['organization']['name'].'</b>.</span>';


				?>
			</div>
		</div>
	</div>
</div>