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
$employees = array();
if(isset($_GET['date']) && isset($_GET['employee_skills_id']))
{
    $employees = new \company_program\Employees;
    ForEach($employees->employees as $employee)
    {
        ForEach($employee->skills as $skill)
        {
            if($skill->Get_Skill_ID() == $_GET['employee_skills_id'])
            {
                $employee->Send_Daily_Text($_GET['date']);
            }
        }
    }    
}else
{
    throw new \Exception('$_GETT["employee_skills_id"] does not exist');
}
header("location: ".$redirect);
?>