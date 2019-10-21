
<?php
  if(isset($equipment_subtype_dropdown_id))
  {
    $id = $equipment_subtype_dropdown_id;
  }else
  {
    $id = "Equipment_Subtype_List";
  }
?>
<label for="<?php echo $id;?>">Subtype:</label>
<select class="form-control" name = "equipment_subtype_id" id="<?php echo $id;?>" required>
<option></option>
<?php
  $subtypes = new \company_program\All_Equipment_Subtypes;
  ForEach($subtypes->all_equipment_subtypes as $subtype_id => $subtype)
  {
      echo '<option value = "'.$subtype_id.'">'.$subtype->Get_Subtype_Name().'</option>';
  }
?>
</select>