<div class="container">
	<div class="row">
			<h2><?php ptrans('Verify Email');?></h2>
				<div class="form-group">
				  <div class="cols-sm-10">
					  <?php 
						if(isset($content['verify-message']))
							echo '<p class="alert alert-success">' . $content['verify-message'] . '</p>';
						else
							echo '<p class="alert alert-danger">' . $content['verify-error'] . '</p>';
						?>
					</div>
				</div>
				<div class="form-group">
				  <div class="cols-sm-10">
					<a href="<?php echo get_link(''); ?>"><?php ptrans('Return to homepage');?></a>
				  </div>
				</div>
			</form>
	</div>
</div>