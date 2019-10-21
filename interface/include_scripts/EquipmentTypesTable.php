<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<button style = "margin:25px;float:right;" type = "button" class = "btn btn-success" data-toggle="modal" data-target="#AddEquipmentTypeModal">Create Type</button>';
    echo '<h2 style = "display:inline-block;">Equipment Types</h2>';
    $table = new \bootstrap\table("Equipment_Type_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("Name");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Equipment_Type_Table");
    $equipment_types = new \company_program\All_Equipment_Types;
    ForEach($equipment_types->all_equipment_types as $equipment_type)
    {
        $options = array();
        $options["Edit Type"] = array('href' => '#Edit_Equipment_Type','class' => array('dd-item-grey',"equipment_type_name=".str_replace(" ","{",$equipment_type->Get_Type_Name()),'equipment_type_id='.$equipment_type->Get_Type_Id()));
        $options['Delete Type'] = array('href' => '/scripts/Delete_Equipment_Type.php?equipment_type_id='.$equipment_type->Get_Type_Id(),'class' => array('dd-item-red'));
        $table_row = new \bootstrap\Table_Row(1,array($equipment_type->Get_Type_Name()),array(),$options);
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>
<div class = "modal fade" role = "dialog" id="AddEquipmentTypeModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Add_Equipment_Type.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title">Add Type</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <input type = "text" name = "equipment_type_name" placeholder = "Type Name">
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Create</button>
        </div>
      </form>
    </div>
</div>
</div>

<div class = "modal fade" role = "dialog" id="ChangeEquipmentTypeModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Update_Equipment_Type.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title" id = "update_equipment_type_modal_title"></h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <input type = "hidden" name = "equipment_type_id" id = "update_equipment_type_id">
            <input type = "text" id = "update_equipment_type_name" name="equipment_type_name" class = "form-control" placeholder = "Type Description" required>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Update</button>
        </div>
      </form>
    </div>
</div>
</div>