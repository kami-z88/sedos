<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><?php ptrans("Messages"); ?></div>
				<div class="panel-body">
					<div class="list-group">
						<?php
						foreach ($content["messages"] as $key => $msg) {
							echo '<div class="list-group-item"><h4 class="list-group-item-heading">' . $msg['group_name'] .'</h4>';
							echo '<p class="list-group-item-text">' . nl2br($msg['text']) . '<span class="badge pull">'.trans("Sent by:").' ' .$msg["sender-name"].'<br>'. $msg['date'] .'</span></p></div>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>