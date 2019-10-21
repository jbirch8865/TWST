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
if(isset($_POST['employee_skill_id']))
{
    $skill = new \company_program\Employee_Skill($_POST['employee_skill_id']);
    $skill->Set_Skill_Name($_POST['name']);
}else
{
    throw new \Exception('$_POST["employee_skill_id"] does not exist');
}
header("location: ".$redirect);
?>
