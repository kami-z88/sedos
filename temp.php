<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="pull-left"><?php echo $content['group']['name']; ?></h1>
			<div class="pull-right">
				<a href="<?php echo get_link('manage-group', $content['group']['id']); ?>" class="btn btn-default">Manage Group</a>
				<div class="btn-group">
					<button type="button" class="btn btn-default "><?php if($content['group']['default_phone'] and $content['group']['default_phone']!='null') echo $content['group']['default_phone']; else echo 'Muted';	?></button>
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
						<li role="separator" class="divider"></li>
						<?php 
						echo '<li><a href="' . get_link('group', $content['group']['id'], array('set_defalt_phone' => $content["user"]["phone_num"])) . '">' . $content["user"]["phone_num"] . '</a></li>';
						echo '<li><a href="' . get_link('group', $content['group']['id'], array('set_defalt_phone' => 'null')) . '">Mute</a></li>';
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<hr>
			<ul class="list-group">
  			<?php
				foreach ($content['messages'] as $key => $message) {
					echo '<li class="list-group-item">'. $message['text'] . '<span class="badge">'  . $message['date'] .'</span></li>';
				}
			?>
			</ul>
		</div>
	</div>
</div>