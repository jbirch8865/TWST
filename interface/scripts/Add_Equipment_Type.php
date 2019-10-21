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
if(isset($_POST['equipment_type_name']))
{
    $equipment_type = new \company_program\Equipment_Type();
    $equipment_type->Create_Type($_POST['equipment_type_name']);
}else
{
    throw new \Exception('$_POST["equipment_type_name"] does not exist');
}
header("location: ".$redirect);
?>
