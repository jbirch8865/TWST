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
if(isset($_POST['customer_id']))
{
    $contractor = new \company_program\Contractor();
    $contractor->Set_First_Name($_POST['first_name']);
    $contractor->Set_Last_Name($_POST['last_name']);
    $contractor->Set_Phone_Number($_POST['phone_number']);
    $contractor->Set_Phone_Number_Extension($_POST['phone_number_extension']);
    $contractor->Set_Email_Address($_POST['email_address']);
    $contractor->Create_Contractor();
    $contractor->Set_Customer_ID($_POST['customer_id']);
}else
{
    throw new \Exception('$_POST["customer_id"] does not exist');
}
header("location: ".$redirect);
?>
