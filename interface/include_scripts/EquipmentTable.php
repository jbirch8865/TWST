<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<button style = "margin:25px;float:right;" type = "button" class = "btn btn-success" data-toggle="modal" data-target="#AddEquipmentModal">Create Equipment</button>';
    echo '<h2 style = "display:inline-block;">Equipment</h2>';
    $table = new \bootstrap\table("Equipment_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("Title");
    $table_headers->Add_Header("Type");
    $table_headers->Add_Header("Sub Type");
    $table_headers->Add_Header("Status");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Equipment_Table");
    $all_equipment = new \company_program\All_Equipment;
    ForEach($all_equipment->all_equipment as $equipment)
    {
        $options = array();
        if($equipment->Get_OOC())
        {
          $options['is not OOC'] = array('href' => '/scripts/Set_Equipment_In_Operation.php?equipment_id='.$equipment->Get_Equipment_Id(),'class' => array());
          $status = $html_delete;
        }else
        {
          $options['is OOC'] = array('href' => '/scripts/Set_Equipment_Out_Of_Operation.php?equipment_id='.$equipment->Get_Equipment_Id(),'class' => array());
          $status = $html_green_checkmark;
        }
        if($who_owns_equipment = $equipment->Who_Owns_This_Equipment())
        {
          $who_owns_equipment = $who_owns_equipment->Get_Person_ID();
          $employee = new \company_program\Employee($who_owns_equipment);
          $tooltip = "Owner:<br>".$employee->Get_First_Name()." ".$employee->Get_Last_Name();
        }else
        {
          $who_owns_equipment = false;
          $tooltip = "";
        }
        $options["Edit Equipment"] = array('href' => '#Edit_Equipment','class' => array('dd-item-grey',"equipment_name=".str_replace(" ","{",$equipment->Get_Equipment_Name()),
        'equipment_id='.$equipment->Get_Equipment_Id(),
        'equipment_subtype_id='.$equipment->Get_Equipment_Subtype_Id(),
        'person_who_owns_equipment='.$who_owns_equipment));
        $options['Delete Equipment'] = array('href' => '/scripts/Delete_Equipment.php?equipment_id='.$equipment->Get_Equipment_Id(),'class' => array('dd-item-red'));
        $table_row = new \bootstrap\Table_Row(4,array($equipment->Get_Equipment_Name(),$equipment->equipment_subtype->type_id->Get_Type_Name(),$equipment->equipment_subtype->Get_Subtype_Name(),$status),array(),$options,true,$tooltip);
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>
<div class = "modal fade" role = "dialog" id="AddEquipmentModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Add_Equipment.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title">Add Equipment</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <input type = "text" name = "equipment_title" placeholder = "equipment title">
          </div>
          <div class = "form-group">
            <?php include 'include_scripts/Equipment_Subtype_Dropdown.php';?>
          </div>
          <div class = "form-group">
            <?php include 'include_scripts/Employees_Dropdown.php';?>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Create</button>
        </div>
      </form>
    </div>
</div>
</div>


<div class = "modal fade" role = "dialog" id="ChangeEquipmentModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Update_Equipment.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title" id = "update_equipment_modal_title"></h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <input type = "hidden" name = "equipment_id" id = "update_equipment_id">
            <input id = "update_equipment_name" type = "text" name = "equipment_name" placeholder = "equipment title">
          </div>
          <div class = "form-group">
            <?php 
              $equipment_subtype_dropdown_id = 'update_equipment_subtype_id';
              include 'include_scripts/Equipment_Subtype_Dropdown.php';
            ?>
          </div>
          <div class = "form-group">
            <?php $employees_dropdown_id = 'update_employees_dropdown_id';?>
            <?php include 'include_scripts/Employees_Dropdown.php';?>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Update</button>
        </div>
      </form>
    </div>
</div>
</div>