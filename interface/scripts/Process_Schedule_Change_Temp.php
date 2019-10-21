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
if(isset($_POST['employee_id']) && $_POST['date'] && $_POST['new_status'])
{
    $employee = new \company_program\Employee($_POST['employee_id']);
    if($_POST['new_status'] == 0) //Send SMS
    {
        
    }elseif($_POST['new_status'] == 1) //No Reply
    {
        $employee->Waiting_For_Response_On_Availability($_POST['date']);
    }elseif($_POST['new_status'] == 2) //Available
    {
        $employee->I_Am_Available_To_Work($_POST['date']);
        
    }elseif($_POST['new_status'] == 3) //Not Available
    {
        $employee->I_Am_Not_Available_To_Work($_POST['date']);        
    }
}else
{
    throw new \Exception('$_POST["employee_id"] does not exist');
}
//header("location: ".$redirect);
?>