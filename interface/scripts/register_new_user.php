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
    try
    {
        $current_user->current_user->Create_User($_POST['username']);
    }catch (\User_Session\User_Already_Exists $e)
    {
        $aAlerts->aAlerts->Add_Alert("Duplicate User ","This user has already been created.",false);
    }catch (\Exception $e)
    {
        throw new \Exception($e->getMessage());
    }
    header("location: ".$redirect);


?>