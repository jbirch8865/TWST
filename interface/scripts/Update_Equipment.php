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
if(isset($_POST['equipment_id']))
{
    $equipment = new \company_program\Equipment($_POST['equipment_id']);
    $equipment->Set_Equipment_Name($_POST['equipment_name']);
    $equipment->Set_Equipment_Subtype_From_Id($_POST['equipment_subtype_id']);
    $equipment->Remove_Equipment_Owner();
    $employee = new \company_program\Employee($_POST['employee_id']);
    $employee->Add_Equipment($equipment);
}else
{
    throw new \Exception('$_POST["equipment_id"] does not exist');
}
header("location: ".$redirect);
?>