<?php
namespace company_program;
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
if(isset($_POST['employee_id']))
{
    $employee = new \company_program\Employee($_POST['employee_id']);
    $employee->Set_First_Name($_POST['first_name']);
    $employee->Set_Last_Name($_POST['last_name']);
    $employee->Set_Phone_Number($_POST['phone_number']);
    $employee->Set_Email_Address($_POST['email_address']);
    $employee->Update_Employee();
    $employee->Delete_Skills();
    ForEach($_POST['employee_skills_id'] as $skill_id)
    {
        try
        {
            $employee->Add_Skill(new \company_program\Employee_Skill($skill_id));
        }catch(\DatabaseLink\DuplicatePrimaryKeyRequest $e)
        {
            $aAlerts->aAlerts->Add_Alert("Duplicate Skill ","This employee already has this skill.");
        }
    }
}else
{
    throw new \Exception('$_POST["employee_id"] does not exist');
}
header("location: ".$redirect);
?>
