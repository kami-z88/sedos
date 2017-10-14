		<div class="container">
			<div class="row">
					<h2><?php ptrans("Add Organiaztion"); ?></h2>
					<form method="post" action="<?php echo get_link('add-organization'); ?>">
						<?php 
							if( $content['oid'] > 0 ){
								echo '<div class="form-group"><label for="org-parent"></label><select class="form-control" disabled="disabled" ><option value="' . $content['parent']['id'] . '">' . $content['parent']['name'] . '</option></select></label></div><input name="organization-id" type="hidden" value="' . $content['parent']['id'] . '"/>';
							}
								
						?>
						<div class="form-group">
						  <div class="cols-sm-10">
							<div class="input-group">
							  <span class="input-group-addon"><i class="fa fa-bank" aria-hidden="true"></i></span>
							  <input type="text" class="form-control" name="name" type="text" placeholder=<?php ptrans("Organization name");?> value="<?php echo $content['form']['name']; ?>" />
							</div>
						  </div>
						</div>
						<button name="do_add_organization" type="submit" class="btn btn-success btn-lg btn-block"><?php ptrans("Add Organiaztion"); ?></button>
					</form>
			</div>
		</div>