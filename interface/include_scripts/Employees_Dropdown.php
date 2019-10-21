<?php
  if(isset($employees_dropdown_id))
  {
    $id = $employees_dropdown_id;
  }else
  {
    $id = "employees_dropdown_id";
  }
?>
<label for="<?php echo $id;?>">Employee:</label>
<select class="form-control" name = "employee_id" id="<?php echo $id;?>">
<option></option>
<?php
  $employees = new \company_program\Employees;
  ForEach($employees->employees as $employee)
  {
      echo '<option value = "'.$employee->Get_Person_ID().'">'.$employee->Get_First_Name().' '.$employee->Get_Last_Name().'</option>';
  }
?>
</select>