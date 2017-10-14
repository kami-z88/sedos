<div class="container">
    <div class="row">
        <form class="col-x-6" method="post" action="<?php echo get_link('send-message', get_path(2),['context' => $_GET['context']])?>">        
            <br style="clear:both">
                <div class="form-group col-md-6 "> 
                    <?php 
                        if(count($content['errors']) > 0  && isset($_POST['do-send-message'])){
                            
                            foreach ($content['errors'] as $key => $error) {
                               echo '<p class="alert alert-danger">' . $error .'</p>';
                                
                            }
                        }
                        elseif(isset($_SESSION['notify-message'])){
                            echo '<p class="alert alert-success">'.$_SESSION['notify-message'].'</p>';
                        }
                        $_SESSION['notify-message'] = null;
                    ?>
                    <?php if($_GET['context'] == 'organization' ) { ?>                               
                        <label id="messageLabel" for="message"><h3><?php ptrans('Message for groups under organization:');?> <?php echo $content['orgnization']['name']?> </h3></label>
                    <?php } ?>
                    <?php if($_GET['context'] == 'group' ) { ?>
                        <label id="messageLabel" for="message"><h3><?php ptrans('Message for group:');?> <?php echo $content['group']['name']?> </h3></label>
                    <?php } ?>                  
                    <div id="send-method">
                    <?php ptrans('Select Sending Method');?>:
                        <span><label for="send-sms" class="send-method-checkbox">SMS</label><input id="send-sms" type="checkbox" name="send-sms" <?php if($_SESSION['send_sms']){echo 'checked="checked"';}?> /></span>
                        <span><label for="send-email" class="send-method-checkbox">Email</label><input id="send-email" type="checkbox" name="send-email" <?php if($_SESSION['send_email']){echo 'checked="checked"';}?> /></span>
                        <span><label for="send-online" class="send-method-checkbox">Online</label><input id="send-online" type="checkbox" name="send-online" checked="checked" onclick="return false;"<?php if($_SESSION['send_online']){echo 'checked="checked"';}?> /></span>
                    </div>
                    <textarea name="message-text" class="form-control input-sm " type="textarea" id="message" placeholder="Message" maxlength="140" rows="7"><?php echo $message_text; ?></textarea>
                        <span class="help-block"><p id="characterLeft" class="help-block "><?php ptrans('You have reached the limit');?></p></span>                    
                </div>
            <br style="clear:both">
            <div class="form-group col-md-2">
            <button class="form-control input-sm btn btn-success " id="btnSubmit" name="do-send-message" type="submit" value="" style="height:35px"><?php ptrans('Send'); ?></button>    
        </form>
    </div>
</div> 
