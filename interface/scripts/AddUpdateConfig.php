<?php
require '../PageTop.php';
$current_user->current_user->Exit_If_Not_Currently_Authenticated();
if(isset($_SERVER['HTTP_REFERER']))
{
    $redirect = $_SERVER['HTTP_REFERER'];
}else
{
    $redirect = "../index.php";
}
if(isset($_POST['key']) && isset($_POST['value']))
{
    $cConfigs->cConfigs->Add_Or_Update_Config($_POST['key'],$_POST['value']);
    $current_user->current_user->Log_Update_Config($_POST['key'],$_POST['value']);
}
header("location: ".$redirect);
?>