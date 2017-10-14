<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 center">
            <div class="well well-lg">
                <div class="row">
                    <h4><?php echo $content['user']['name']; ?></h4>
                    <p><?php echo $content['user']['phone_num']; ?></p>
                    <a href="mailto:"><?php echo $content['user']['email']; ?></a>
                </div>
                <a href="<?php echo get_link('edit-profile',$content['user']['id']); ?>"><?php ptrans('Edit Profile');?></a>
            </div>
        </div>
    </div>
</div>
