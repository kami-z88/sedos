<?php 
if($_GET['context'] == 'group'){
  $gid = get_path(2);
  $group = group($gid);
}
if($_GET['context'] == 'organization'){
  $root_oid = get_path(2);
  $org = org($root_oid);
}


$uid = $content['user']['id'];
$message = new Message();
$message_text = $_POST['message-text'];

$_SESSION['send_sms'] = $send_sms = isset($_POST['send-sms']);
$_SESSION['send_email'] = $send_email = isset($_POST['send-email']);
$_SESSION['send_online'] = $send_online = isset($_POST['send-online']);
$method =(!$send_sms  && !$send_email && !$send_online);

if(isset($_POST['do-send-message'])){

    if($method) {
        $content['errors']['send-method'] = trans("Please Select a sending method");
    }
    elseif (empty(trim($_POST['message-text']))) {
         $content['errors']['send-message-empty'] = trans("Typing message box empty");
     } else {

       $result = $message->create($gid, $uid, $root_oid, $message_text); 
    }

    if($send_sms) {
      if($root_oid){
        $phones = group()->get_groups_phones($root_oid);       
      }else {
        $phones = group()->get_group_phones($gid);
      }
    }

    if($send_email){
      if($root_oid){
          $emails = user()->get_users_emails_by_oid($root_oid);
      }else {

      }

    }

    if($result) {
       $_SESSION['notify-message'] = trans("Message sent successfully");
       //redirect(get_link('send-message', get_path(2),['context' => $_GET['context']]));
    } elseif(!$result && !$content['errors']){
        $content['errors']['send-message-fail'] = trans("Sorry, Sending message faild");
    }
}

$content['group']['id'] = $group->id;
$content['group']['name'] = $group->name;
$content['orgnization']['name'] = $org->name;


require BASE_DIR . 'view/header.php';
require 'view/send-message.php';
require BASE_DIR . 'view/footer.php';

?>