<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<button style = "margin:25px;float:right;" type = "button" class = "btn btn-success" data-toggle="modal" data-target="#AddEmployeeModal">New Employee</button>';
    echo '<h2 style = "display:inline-block;">Current Employees</h2>';
    $table = new \bootstrap\table("Employee_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("First Name");
    $table_headers->Add_Header("Last Name");
    $table_headers->Add_Header("Phone Number");
    $table_headers->Add_Header("Skills");
    $table_headers->Add_Header("Email Address");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Employee_Table");
    $employees = new \company_program\Employees;
    ForEach($employees->employees as $employee)
    {
        $options = array();
        $options["Edit Employee"] = array('href' => '#Edit_Employee','class' => array('dd-item-grey',
          "first_name=".str_replace(" ","{",$employee->Get_First_Name()),
          'employee_id='.$employee->Get_Person_ID(),
          'last_name='.str_replace(" ","{",$employee->Get_Last_Name()),
          'email_address='.str_replace(" ","{",$employee->Get_Email_Address()),
          'phone_number='.$employee->Get_Phone_Number()));
        if($current_user->current_user->Is_Management()){$options['Delete Employee'] = array('href' => '/scripts/Delete_Employee.php?employee_id='.$employee->Get_Person_ID(),'class' => array('dd-item-red'));}
        $skills = $employee->Get_Skills_String();
        $table_row = new \bootstrap\Table_Row(5,array($employee->Get_First_Name(),$employee->Get_Last_Name(),$employee->Get_Phone_Number(),$skills,$employee->Get_Email_Address()),array(),$options,true,$skills);
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>
<div class = "modal fade" role = "dialog" id="AddEmployeeModal">
<div class = "modal-dialog">
    <div class = "modal-content">
      <form action = "scripts/Add_Employee.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title">Add Employee</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <label for="employee_first_name"><span style="color:red;">*</span>First Name:</label><input type = "text" id = "employee_first_name" name="first_name" class = "form-control" placeholder = "Henry" required>
          </div>
          <div class = "form-group">
            <label for="employee_last_name"><span style="color:red;">*</span>Last Name:</label><input type = "text" id = "employee_last_name" name="last_name" class = "form-control" placeholder = "Winkler" required>
          </div>
          <div class = "form-group">
            <label for="employee_phone_number"><span style="color:red;">*</span>Phone Number:</label><input type = "tel" id = "employee_phone_number" pattern="([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}" name="phone_number" class = "form-control" placeholder = "360-456-9785" required>
          </div>
          <div class = "form-group">
            <label for="employee_email_address">Email Address:</label><input type = "email" id = "employee_email_address" name="email_address" class = "form-control" placeholder = "first.last@example.com">
          </div>
          <div class = "form-group">
            <?php include 'EmployeeHasSkillsTable.php';?>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Create</button>
        </div>
      </form>
    </div>
</div>
</div>

<div class = "modal fade" role = "dialog" id="ChangeEmployeeModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Update_Employee.php" method = "POST">
    <input type = "hidden" id = "update_employee_id" name = "employee_id">
    <div class = "modal-header">
          <h3 class = "modal-title" id = "update_employee_modal_title"></h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <label for="update_employee_first_name"><span style="color:red;">*</span>First Name:</label><input type = "text" id = "update_employee_first_name" name="first_name" class = "form-control" placeholder = "Henry" required>
          </div>
          <div class = "form-group">
            <label for="update_employee_last_name"><span style="color:red;">*</span>Last Name:</label><input type = "text" id = "update_employee_last_name" name="last_name" class = "form-control" placeholder = "Winkler" required>
          </div>
          <div class = "form-group">
            <label for="update_employee_phone_number"><span style="color:red;">*</span>Phone Number:</label><input type = "tel" id = "update_employee_phone_number" pattern="([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}" name="phone_number" class = "form-control" placeholder = "360-456-9785" required>
          </div>
          <div class = "form-group">
            <label for="update_employee_email_address">Email Address:</label><input type = "email" id = "update_employee_email_address" name="email_address" class = "form-control" placeholder = "first.last@example.com">
          </div>
          <div class = "form-group">
            <?php $employee_skills_id = "EmployeeHasSkills";include 'EmployeeHasSkillsTable.php';?>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Update</button>
        </div>
      </form>
    </div>
</div>
</div>