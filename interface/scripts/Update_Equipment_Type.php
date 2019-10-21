<?php
namespace company_program;

use gaAlerts;

ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../PageTop.php';
$current_user->current_user->Exit_If_Not_Currently_Authenticated();
if(isset($_SERVER['HTTP_REFERER']))
{
    $redirect = $_SERVER['HTTP_REFERER'];
}else
{
    $redirect = "../index.php";
}
if(isset($_POST['equipment_type_id']))
{
    $equipment_type = new \company_program\Equipment_Type($_POST['equipment_type_id']);
    $equipment_type->Set_Type_Name($_POST['equipment_type_name']);
}else
{
    throw new \Exception('$_POST["equipment_type_id"] does not exist');
}
header("location: ".$redirect);
?>
