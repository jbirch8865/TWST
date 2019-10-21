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
if(isset($_POST['equipment_subtype_id']))
{
    $equipment_subtype = new \company_program\Equipment_Subtype($_POST['equipment_subtype_id']);
    $equipment_subtype->Set_Subtype_Name($_POST['equipment_subtype_name']);
    $equipment_subtype->Set_Type_Id($_POST['equipment_type_id']);
}else
{
    throw new \Exception('$_POST["equipment_subtype_id"] does not exist');
}
header("location: ".$redirect);
?>