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
$minus_icon_html = Delete_Minus_Icon("Employee_Has_Skills_Table");
$current_user->current_user->Exit_If_Not_Currently_Authenticated("Session expired, please log in.");

  if(isset($employee_skills_dropdown_id))
  {
    $id = $employee_skills_dropdown_id;
  }else
  {
    $id = "employee_skills_dropdown_id";
  }
$string = '
<div class = "input-group">
<select class="form-control" name = "employee_skills_id[]" id="'.$id.'" required>
<option></option>';
  $employee_skills = new \company_program\Employee_Skills;
  ForEach($employee_skills->skills as $skill_id => $skill)
  {
      $string = $string.'<option value = "'.$skill_id.'">'.$skill->Get_Skill_Name().'</option>';
  }
$string = $string.'</select><span class="input-group-addon">'.$minus_icon_html.'</span></div>';
echo $string;
?>