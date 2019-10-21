<?php
  if(isset($employee_skills_dropdown_id))
  {
    $id = $employee_skills_dropdown_id;
  }else
  {
    $id = "employee_skills_dropdown_id";
  }
?>
<label for="<?php echo $id;?>">Skills:</label>
<select class="form-control" name = "employee_skills_id" id="<?php echo $id;?>" required>
<option></option>
<?php
  $employee_skills = new \company_program\Employee_Skills;
  ForEach($employee_skills->skills as $skill_id => $skill)
  {
      echo '<option value = "'.$skill_id.'">'.$skill->Get_Skill_Name().'</option>';
  }
?>
</select>