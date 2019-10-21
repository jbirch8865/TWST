<?php
    namespace company_program;
    ob_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $current_dir = "../";
    require $current_dir.'PageTop.php';
    shell_exec('sh deploy.sh');

?>