<?php
    namespace company_program;
    ob_start();
    require '../PageTop.php';
    $current_user->current_user->LogOut();
    if(isset($_SERVER['HTTP_REFERER']))
    {
        header("location: ".$_SERVER['HTTP_REFERER']);
    }else
    {
        header("location: ../index.php");
    }
?>