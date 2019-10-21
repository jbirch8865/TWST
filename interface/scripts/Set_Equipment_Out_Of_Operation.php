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
if(isset($_GET['equipment_id']))
{
    $equipment = new \company_program\Equipment($_GET['equipment_id']);
    $equipment->Set_Out_Of_Commission();
}else
{
    throw new \Exception('$_GET["equipment_id"] does not exist');
}
header("location: ".$redirect);
?>
