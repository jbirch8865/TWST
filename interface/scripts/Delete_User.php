<?php
require '../PageTop.php';
$current_user->current_user->Exit_If_Not_Currently_Authenticated();
if(isset($_POST['added_context']['username']))
{
    $current_user->current_user->Delete_User($_POST['added_context']['username']);
}else
{
    throw new \Exception('$_POST["username"] does not exist');
}
?>
