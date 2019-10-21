<?php
namespace company_program;
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../src/ClassLoader.php';
$cConfigs = new \gcConfigs;
$current_user = new \gCurrent_User;
$aAlerts = new \gaAlerts;
$System = new \system;
$current_user->current_user->Exit_If_Not_Currently_Authenticated("Session expired, please log in.");
$minus_icon_html = Delete_Minus_Icon("Employee_Has_Skills_Table");
if(!empty($_POST['employee_id']))
{
    $string_to_return = "";
    $employee = new Employee($_POST['employee_id']);
    ForEach($employee->skills as $skill)
    {
        $table_row = new \bootstrap\Table_Row(1,array($skill->Get_Skill_Name()."<input type = 'hidden' name = 'employee_skills_id[]' value = '".$skill->Get_Skill_ID()."'>".$minus_icon_html),array(),array(),false);
        $string_to_return = $string_to_return.$table_row->Return_String();
    }
    echo $string_to_return;
}
?>