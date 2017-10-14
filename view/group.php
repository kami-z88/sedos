<!-- Edit Group Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit-group">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form method="post" action="<?php echo get_link('group', $content['group']['id']); ?>">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans("Update group"); ?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
			  <span class="input-group-addon"><i class="fa fa-bank" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name="group-name" type="text" placeholder="Group Name" value="<?php echo $content['group']['name']; ?>" />
			</div>	      
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans("Close"); ?></button>
	        <button type="submit" class="btn btn-primary" name="do-update-group" value=""><?php ptrans("Update"); ?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>
<!-- Remove Goup Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="remove-group">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form method="post" action="<?php echo get_link('group', $content['group']['id']); ?>">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans("Danger"); ?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans("Are you sure you want to remove this group?"); ?></p>
			</div>	      
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans("Close"); ?></button>
	        <button type="submit" class="btn btn-danger" name="do-remove-group"><?php ptrans("Remove Group"); ?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>
<!-- Leave Goup Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="leave-group">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form method="post" action="<?php echo get_link('group', $content['group']['id']); ?>">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><?php ptrans("Danger"); ?></h4>
	      </div>
	      <div class="modal-body">
			<div class="input-group">
				<p><?php ptrans("Are you sure you want to remove this group?"); ?></p>
			</div>	      
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"><?php ptrans("Close"); ?></button>
	        <button type="submit" class="btn btn-danger" name="do-leave-group"><?php ptrans("Leave Group"); ?></button>
	      </div>
	    </form>
    </div>
  </div>
</div>




<div class="container">
	<h1>
		<i class="fa fa-university" aria-hidden="true"></i>
		<?php echo $content['group']['name']; ?>
		<button class="btn btn-info" id="edit-group" data-toggle="modal" data-target="#edit-group"><i class="fa fa-edit "></i></button>
		<button class="btn btn-danger" id="remove-group" data-toggle="modal" data-target="#remove-group"><i class="fa fa-trash "></i></button>
		<a class="btn btn-default manage-members" href="<?php echo get_link('group-members', $content['group']['id']); ?>"><i class="fa fa-users "></i> <?php ptrans("Manage Members"); ?> </a>
<!--............................................................................................................................................-->
		<div class="btn-group pull">
			<button type="button" class="btn btn-default"><?php if($content['group']['default_phone'] and $content['group']['default_phone']!='null') echo $content['group']['default_phone']; else echo 'Muted';	?></button>
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			</button>
			<ul class="dropdown-menu">
				<?php 
				foreach ($content['phones'] as $key => $phone) {
					echo '<li><a href="'. get_link('group', $content['group']['id'], array('set_defalt_phone' => $phone)) . '">' .  $phone . '</a></li>';
				}
				?>
				
				<?php 
				echo '<li><a href="' . get_link('group', $content['group']['id'], array('set_defalt_phone' => $content["user"]["phone_num"])) . '">' . $content["user"]["phone_num"] . '</a></li>';
				
				echo '<li role="separator" class="divider"></li>';
				echo '<li><a href="' . get_link('group', $content['group']['id'], array('set_defalt_phone' => 'null')) . '">Mute</a></li>';
				?>
				<li role="separator" class="divider"></li>
				<li class="btn btn-default" id="leave-group" data-toggle="modal" data-target="#leave-group"><a><?php ptrans("Leave Group"); ?></a></li>
			</ul>
		</div>
<!--........................................................................................................................-->
	</h1>
	<div class="row">
		<div class="col-md-12">
			<h3>
				<?php ptrans("Messages"); ?>
				<a class="btn btn-default pull" href="<?php echo get_link('send-message', $content['group']['id'], ['context'=>'group']); ?>"><?php ptrans("Send Message"); ?></a>
			</h3>
			<hr>
			<ul class="list-group">
  			<?php
  			if($content['messages']){
				foreach ($content['messages'] as $key => $message) {
					echo '<li class="list-group-item">'. nl2br($message['text']) . '<span class="badge">'.trans("Sent by:").' '. $message['sender-name'].'<br>'. $message['date'] .'</span></li>';
				}  				
  			} else
  				echo '<span class="list-group-item">'.trans("No Messages").'!</span>';

			?>
			</ul>
		</div>
	</div>
</div>