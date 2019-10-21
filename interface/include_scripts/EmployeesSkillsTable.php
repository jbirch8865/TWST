<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<button style = "margin:25px;float:right;" type = "button" class = "btn btn-success" data-toggle="modal" data-target="#AddEmployeeSkillModal">New Skill</button>';
    echo '<h2 style = "display:inline-block;">Skills</h2>';
    $table = new \bootstrap\table("Employee_Skills_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("Skill Name");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Employee_Skills_Table");
    $skills = new \company_program\Employee_Skills;
    ForEach($skills->skills as $skill)
    {
        $options = array();
        $options["Edit Skill"] = array('href' => '#Edit_Employee_Skill','class' => array('dd-item-grey',
          "name=".str_replace(" ","{",$skill->Get_Skill_Name()),
          'skill_id='.$skill->Get_Skill_ID()));
        $options['Delete Skill'] = array('href' => '/scripts/Delete_Employee_Skill.php?employee_skill_id='.$skill->Get_Skill_ID(),'class' => array('dd-item-red'));
        $table_row = new \bootstrap\Table_Row(1,array($skill->Get_Skill_Name()),array(),$options);
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>
<div class = "modal fade" role = "dialog" id="AddEmployeeSkillModal">
<div class = "modal-dialog">
    <div class = "modal-content">
      <form action = "scripts/Add_Employee_Skill.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title">Add Skill</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <label for="employee_skill_name"><span style="color:red;">*</span>Name:</label><input type = "text" id = "employee_skill_name" name="name" class = "form-control" placeholder = "TCS" required>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Create</button>
        </div>
      </form>
    </div>
</div>
</div>

<div class = "modal fade" role = "dialog" id="ChangeEmployeeSkillModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Update_Employee_Skill.php" method = "POST">
    <input type = "hidden" id = "update_employee_skill_id" name = "employee_skill_id">
    <div class = "modal-header">
          <h3 class = "modal-title" id = "update_employee_skill_modal_title"></h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <label for="update_employee_skill_name"><span style="color:red;">*</span>Name:</label><input type = "text" id = "update_employee_skill_name" name="name" class = "form-control" placeholder = "TCS" required>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Update</button>
        </div>
      </form>
    </div>
</div>
</div>