<?php
require dirname(__FILE__). DIRECTORY_SEPARATOR . '../vendor/autoload.php';  
require dirname(__FILE__). DIRECTORY_SEPARATOR . '../vendor/jbirch8865/PHP_Tools/ClassLoader.php';
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'ExceptionClass.php';
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'BootStrapHTMLHelper.php';
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'PeopleClass.php';
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'EmployeeSkillsClass.php';
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'UserClass.php';
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'EquipmentClass.php';
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'Public_Functions.php';
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'CustomerClass.php';

session_start();
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'StartupClasses.php';
require dirname(__FILE__). DIRECTORY_SEPARATOR . 'StartupVariables.php';
?>