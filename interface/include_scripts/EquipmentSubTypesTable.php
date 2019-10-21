<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<button style = "margin:25px;float:right;" type = "button" class = "btn btn-success" data-toggle="modal" data-target="#AddEquipmentSubtypeModal">Create Subtype</button>';
    echo '<h2 style = "display:inline-block;">Equipment Sub Types</h2>';
    $table = new \bootstrap\table("Equipment_Subtypes_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("Name");
    $table_headers->Add_Header("Type");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Equipment_Subtypes_Table");
    $equipment_subtypes = new \company_program\All_Equipment_Subtypes;
    ForEach($equipment_subtypes->all_equipment_subtypes as $equipment_subtype)
    {
        $options = array();
        $options["Edit Subtype"] = array('href' => '#Edit_Equipment_Subtype','class' => array('dd-item-grey',"equipment_subtype_name=".str_replace(" ","{",$equipment_subtype->Get_Subtype_Name()),
        'equipment_subtype_id='.$equipment_subtype->Get_Subtype_Id(),
        'equipment_type_id='.$equipment_subtype->Get_Type_Id()));
        $options['Delete Subtype'] = array('href' => '/scripts/Delete_Equipment_Subtype.php?equipment_subtype_id='.$equipment_subtype->Get_Subtype_Id(),'class' => array('dd-item-red'));
        $table_row = new \bootstrap\Table_Row(2,array($equipment_subtype->Get_Subtype_Name(),$equipment_subtype->type_id->Get_Type_Name()),array(),$options);
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>
<div class = "modal fade" role = "dialog" id="AddEquipmentSubtypeModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Add_Equipment_Subtype.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title">Add Subtype</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <input type = "text" name = "equipment_subtype_name" placeholder = "subtype name">
          </div>
          <div class = "form-group">
            <?php include 'include_scripts/Equipment_Type_Dropdown.php';?>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Create</button>
        </div>
      </form>
    </div>
</div>
</div>

<div class = "modal fade" role = "dialog" id="ChangeEquipmentSubtypeModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Update_Equipment_Subtype.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title" id = "update_equipment_subtype_modal_title"></h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <input type = "hidden" name = "equipment_subtype_id" id = "update_equipment_subtype_id">
            <input id = "update_equipment_subtype_name" type = "text" name = "equipment_subtype_name" placeholder = "subtype name">
          </div>
          <div class = "form-group">
            <?php 
              $equipment_type_dropdown_id = 'update_equipment_type_id';
              include 'include_scripts/Equipment_Type_Dropdown.php';
            ?>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Update</button>
        </div>
      </form>
    </div>
</div>
</div>