<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>
				<?php if(in_array('VIEW_ORGANIZTION_TREE', $content['permissions'])){ptrans('Organizations');}?>
				<?php if(in_array('CREATE_ROOT_ORGANIZATION', $content['permissions'])){ ?>
					<a class="btn btn-default pull" href="<?php echo get_link('add-organization'); ?>"><?php ptrans('Add Organization');?></a>
				<?php } ?>
			</h1>
			<?php if(in_array('VIEW_ORGANIZTION_TREE', $content['permissions'])){ ?>
				<div class="tree well">
					<?php print_tree_html($content['user-organizations']); ?>
					<hr>
				</div>			
			<?php } ?>

		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="panel panel-default">
				<div class="panel-heading"><?php ptrans('Messages');?></div>
				<div class="panel-body">
					<div class="list-group">
						<?php
						foreach ($content["messages"] as $key => $msg) {
							echo '<div class="list-group-item"><h4 class="list-group-item-heading">' . $msg['group_name'] .'</h4>';
							echo '<p class="list-group-item-text">' . nl2br($msg['text']) .'</p></div>';
						}
						?>
						<a href="<?php  echo get_link('all-messages', $content['user']['id']); ?>" class="list-group-item">
							<h3 class="list-group-item-heading center"><?php ptrans('All Messages');?></h3>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<div class="groups">
				<?php
				if( $content['raw-groups'] > 0 ){
					foreach ($content['raw-groups'] as $key => $group) { ?>
					<div class="group-content">
						<h4 class="group-name pull-left">
						<?php echo '<a href="' . get_link('group', $group['gid']) . '">' . $group['name'] . '</a>'; ?>
						</h4>
						<span class="badge pull-right"><?php echo $group['users_count']; ?></span>
						<ol class="breadcrumb">
							<?php foreach ($group["organization"] as $key => $organization) {
								echo '<li><a href="' . get_link('organization', $organization['id']) . '">' . $organization["name"] . '</a></li>';
							}
							?>
						</ol>
					</div>
				<?php
					}
				}else{
					?>
						<?php ptrans('You have not signed up to any groups');?>!
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>