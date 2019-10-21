
<?php
  if(isset($equipment_type_dropdown_id))
  {
    $id = $equipment_type_dropdown_id;
  }else
  {
    $id = "Equipment_Type_List";
  }
?>
<label for="<?php echo $id;?>">type:</label>
<select class="form-control" name = "equipment_type_id" id="<?php echo $id;?>" required>
<option></option>
<?php
  $types = new \company_program\All_Equipment_Types;
  ForEach($types->all_equipment_types as $type_id => $type)
  {
      echo '<option value = "'.$type_id.'">'.$type->Get_Type_Name().'</option>';
  }
?>
</select>