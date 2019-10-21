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
    try
    {
        $customer = new \company_program\Customer($_POST['customer_id']);
        $customer->Set_Customer_Name($_POST['customer_name']);
        $customer->Set_Customer_Address($_POST['customer_address']);
        $customer->Set_Customer_Billing_Address($_POST['customer_billing_address']);
        $customer->Set_Customer_Phone_Number($_POST['phone_number']);
        $customer->Set_Customer_Phone_Number_Extension($_POST['phone_number_extension']);
        $customer->Set_Customer_Fax_Number($_POST['fax_number']);
        $customer->Set_Web_Address($_POST['web_address']);
        $customer->Set_CCB($_POST['CCB']);
        $customer->Set_Customer_Industry($_POST['customer_industry']);
    }catch(Customer_Does_Exist $e)
    {
        $alert = new \gaAlerts;
        $alert->aAlerts->Add_Alert("Company already Exists","  ");
    }
}else
{
    throw new \Exception('$_POST["customer_name"] does not exist');
}
header("location: ".$redirect);
?>
