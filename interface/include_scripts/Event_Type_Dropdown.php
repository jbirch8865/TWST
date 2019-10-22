<?php
  if(isset($event_type_dropdown_id))
  {
    $id = $event_type_dropdown_id;
  }else
  {
    $id = "event_type_dropdown_id";
  }
?>
<label for="<?php echo $id;?>">Event Type:</label>
<select class="form-control" name = "event_type_id" id="<?php echo $id;?>" required>
<option></option>
<?php
  $event_types = new \company_program\Event_Types;
  ForEach($event_types->event_types as $event_type_id => $event_type)
  {
      echo '<option value = "'.$event_type_id.'">'.$event_type->Get_Event_Description().'</option>';
  }
?>
</select>