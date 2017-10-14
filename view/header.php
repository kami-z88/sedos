<!doctype html>
<html lang="fa_IR">
		<head>
				<meta charset="utf-8">
				<meta http-equiv="x-ua-compatible" content="ie=edge">
				<title><?php echo "sedos" ?></title>
				<meta name="description" content="">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<link  rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>static/css/bootstrap.min.css">
				<link  rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>static/css/bootstrap-theme.min.css">
				<link  rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>static/css/font-awesome.min.css">
				<link  rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>static/css/main.css">

				<?php 
					if(RTL){
						echo '<link  rel="stylesheet" type="text/css" href="' . SITE_URL . 'static/css/rtl.css">';
						echo '<link  rel="stylesheet" type="text/css" href="' . SITE_URL . 'static/css/bootstrap-rtl.css">';
					}
				?>
				<?php foreach ($content['css'] as $key => $file) {
					echo '<link  rel="stylesheet" type="text/css" href="' . $file . '">';
				} ?>
				<script type="text/javascript" language="javascript" src="<?php echo SITE_URL;?>static/js/jquery.min.js"></script>
				<?php foreach ($content['js'] as $key => $file) {
					echo '<script type="text/javascript" language="javascript" src="' . $file . '"></script>';
				} ?>
</head>
<body>
<nav class="navbar navbar-static-top navbar-inverse">
	 <div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo get_link(''); ?>">SEDOS</a>
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
		</div>
		<?php
		$user = user()->logged_in_user();
		if(isset($user['id'])){
			$content['invitations'] = user()->get_group_invitations($user['id']);
			$invit_num = count($content['invitations']);
		?>
			<div class="navbar-collapse collapse navbar-right" id='navbar'>
					<p class="navbar-text"><?php ptrans("Signed in as"); ?> <a href="<?php echo get_link('profile', $user['id']);?>" class="navbar-link"><?php echo $user['name'];?> (<?php echo $user['phone_num'];?>)</a></p>
			    <ul class="nav navbar-nav">
			    	<li class="dropdown">
			    	  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php if($content['invitations']) echo '<div id="green-disk">'.$invit_num.'</div>';?><span class="fa fa-bell"></span><span class="caret"></span></a>
			    	  <ul class="dropdown-menu dropdown-invitation" role="menu">
			    	  	<?php if($content['invitations']) {
			    	  		$rows_to_show = 5;
			    	  		if($invit_num > $rows_to_show){$long_list = true;}
			    	  		$i=1;
				    	  	 foreach($content['invitations'] as $gid => $group_name){ if($i > $rows_to_show){break;}?>
				    	      <li id="notification-list">
				    	          <span class="item">
				    	            <span class="item-left">
				    	                <span class="item-info">
				    	                    <span><?php echo $group_name ?></span>
				    	                </span>
				    	            </span>
				    	            <span class="item-right">
				    	                <a href="<?php echo get_link('invitation', $gid, array('invitation' => 'accept'));?>"><button class="btn btn-xs btn-success pull-right" style="margin-left:2px;">join</button></a>
				    	                <a href="<?php echo get_link('invitation', $gid, array('invitation' => 'reject'));?>"><button class="btn btn-xs btn-danger pull-right"><?php ptrans("reject"); ?></button></a>
				    	            </span>
				    	        </span>
				    	      </li>
				    	    <?php $i++;} ?>
				    	    <?php if($long_list) { ?>
		    		    	   <li id="notification-list">
				    	          <span class="item">
				    	            <span class="item-center">
				    	                <span class="item-info">
				    	                    <p class="text-center"><a href="#"><?php ptrans("See All Invitations"); ?></a></p>
				    	                </span>
				    	            </span>
				    	        </span>
				    	      </li>
				    	    <?php } ?> 
			    	    <?php } ?> 
			    	  </ul>
			    	</li>
			    	<li class=""><a href="<?php echo get_link('logout'); ?>" class="navbar-link"><?php ptrans("Log out"); ?></a></li>
			    </ul>
			</div
		<?php } ?>
	</div>
</nav>