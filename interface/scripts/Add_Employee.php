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
if(isset($_POST['first_name']))
{
    $employee = new \company_program\Employee();
    $employee->Set_First_Name($_POST['first_name']);
    $employee->Set_Last_Name($_POST['last_name']);
    $employee->Set_Phone_Number($_POST['phone_number']);
    $employee->Set_Email_Address($_POST['email_address']);
    $employee->Create_Employee();
    ForEach($_POST['employee_skills_id'] as $skill_id)
    {
        $employee->Add_Skill(new \company_program\Employee_Skill($skill_id));
    }
}else
{
    throw new \Exception('$_POST["first_name"] does not exist');
}
header("location: ".$redirect);
?>
