<?php
namespace company_program;
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../PageTop.php';
if(!empty($_POST['employee_id']) && !empty($_POST['date']))
{
    $employee = new \company_program\Employee($_POST['employee_id']);
    if($_POST['availability'] == "available")
    {
        $employee->I_Am_Available_To_Work($_POST['date']);
        header('location: ../Updated_Schedule_Confirmation.php?status=available&date='.$_POST['date']);
    }elseif($_POST['availability'] == "unavailable")
    {
        $employee->I_Am_Not_Available_To_Word($_POST['date']);
        header('location: ../Updated_Schedule_Confirmation.php?status=unavailable&date='.$_POST['date']);
    }
}else
{
    throw new \Exception('$_POST["employee_id"] does not exist');
}
?>