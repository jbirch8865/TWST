<?php
    namespace company_program;
    ob_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $current_dir = "../";
    require $current_dir.'PageTop.php';
    $current_user->current_user->Exit_If_Not_Currently_Authenticated();
    if(isset($_SERVER['HTTP_REFERER']))
    {
        $redirect = $_SERVER['HTTP_REFERER'];
    }else
    {
        $redirect = "../index.php";
    }

    if(isset($_POST['new_password']))
    {
        $current_user->current_user->Change_Password($_POST['new_password']);
    }
    session_destroy();
    header("location: ".$redirect);
?>