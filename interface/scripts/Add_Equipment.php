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
if(!empty($_POST['equipment_subtype_id']))
{
    try
    {
        $subtype = new \company_program\Equipment_SubType($_POST['equipment_subtype_id']);
        $equipment = new \company_program\Equipment();
        $equipment->Set_Equipment_Name($_POST['equipment_title']);
        $equipment->Set_Equipment_Subtype_From_Id($subtype->Get_Subtype_Id());
        $equipment->Set_In_Commission();
        $equipment->Create_Equipment();
        $employee = new \company_program\Employee($_POST['employee_id']);
        $employee->Add_Equipment($equipment);
        }catch(\Exception $e)
    {
        $alert = new \gaAlerts;
        $alert->aAlerts->Add_Alert("Adding Equipment  ","Not a valid subtype");
    }
}else
{
    throw new \Exception('$_POST["equipment_subtype_id"] does not exist');
}
header("location: ".$redirect);
?>
