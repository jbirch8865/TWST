<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<button style = "margin:25px;float:right;" type = "button" class = "btn btn-success" data-toggle="modal" data-target="#AddEventModal">New Event</button>';
    echo '<h2 style = "display:inline-block;">Current Events</h2>';
    $table = new \bootstrap\table("Event_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("Customer Name");
    $table_headers->Add_Header("Date");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Event_Table");
    $events = new \company_program\Events;
    ForEach($events->events as $event)
    {
        $options = array();
        $options["Edit Event"] = array('href' => '#Edit_Event','class' => array('dd-item-grey',
          'event_id='.$event->Get_Event_ID(),
          "customer_name=".str_replace(" ","{",$event->customer->Get_Customer_Name()),
          'confirmed='.$event->Get_Confirmed(),
          'tentative='.$event->Get_Tentative(),
          'start_time='.$event->Get_Start_Time(),
          'end_time='.$event->Get_End_Time(),
          'event_location='.str_replace(" ","{",$event->Get_Event_Location()),
          'event_type_id='.$event->event_type->Get_Type_ID(),
          'guest_count='.$event->Get_Guest_Count(),
          'parking_instructions='.str_replace(" ","{",$event->Get_Parking_Instructions()),
          ));
        if($current_user->current_user->Is_Management()){$options['Delete Event'] = array('href' => '/scripts/Delete_Event.php?event_id='.$event->Get_Event_ID(),'class' => array('dd-item-red'));}
        $table_row = new \bootstrap\Table_Row(2,array($event->customer->Get_Customer_Name(),date('m-d',strtotime($event->Get_Start_Time()))),array(),$options);
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>

<div class = "modal fade" role = "dialog" id="AddEventModal">
<div class = "modal-dialog">
    <div class = "modal-content">
      <form action = "scripts/Add_Event.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title">Add Event</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <label for="customer_name"><span style="color:red;">*</span>Customer Name:</label><input type = "text" id = "customer_name" name="customer_name" class = "form-control" placeholder = "Qwan Family" required>
          </div>
          <div class = "form-group">
            <label for="start_time"><span style="color:red;">*</span>Start Time:</label><input type = "datetime-local" id = "start_time" name="start_time" class = "form-control" required>
          </div>
          <div class = "form-group">
            <label for="end_time">End Time:</label><input type = "datetime-local" id = "end_time" name="end_time" class = "form-control">
          </div>
          <div class = "form-group">
            <label for="event_location">Event Location:</label><textarea id = "event_location" name = "event_location" class = "form-control" placeholder = "2345 NE Sacramento St.&#10;Portland OR, 97025"></textarea>
          </div>
          <div class = "form-group">
            <?php include 'Event_Type_Dropdown.php';?>
          </div>
          <div class = "form-group">
            <label for="guest_count">Guest Count:</label><input type = "num" min = "0" step = "1" id = "guest_count" name="guest_count" class = "form-control">
          </div>
          <div class = "form-group">
            <label for="parking instructions">Parking Instructions:</label><input type = "text" id = "parking_instructions" name="parking_instructions" class = "form-control">
          </div>
          <div class = "modal-footer">
            <button type = "submit" class = "btn btn-success">Create</button>
          </div>
        </div>
      </form>
    </div>
</div>
</div>

<div class = "modal fade" role = "dialog" id="EditEventModal">
<div class = "modal-dialog">
    <div class = "modal-content">
      <form action = "scripts/Update_Event.php" method = "POST">
        <input type = "hidden" name = "event_id" id = "update_event_id">
        <div class = "modal-header">
          <h3 class = "modal-title" id = "update_event_modal_title"></h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <label for="update_customer_name"><span style="color:red;">*</span>Customer Name:</label><input type = "text" id = "update_customer_name" name="customer_name" class = "form-control" placeholder = "Qwan Family" required>
          </div>
          <div class = "form-group">
            <label for="update_start_time"><span style="color:red;">*</span>Start Time:</label><input type = "datetime-local" id = "update_start_time" name="start_time" class = "form-control" required>
          </div>
          <div class = "form-group">
            <label for="update_end_time">End Time:</label><input type = "datetime-local" id = "update_end_time" name="end_time" class = "form-control">
          </div>
          <div class = "form-group">
            <label for="update_event_location">Event Location:</label><textarea id = "update_event_location" name = "event_location" class = "form-control" placeholder = "2345 NE Sacramento St.&#10;Portland OR, 97025"></textarea>
          </div>
          <div class = "form-group">
            <?php $event_type_dropdown_id = "update_event_type_id"; include 'Event_Type_Dropdown.php';?>
          </div>
          <div class = "form-group">
            <label for="update_guest_count">Guest Count:</label><input type = "num" min = "0" step = "1" id = "update_guest_count" name="guest_count" class = "form-control">
          </div>
          <div class = "form-group">
            <label for="update_parking instructions">Parking Instructions:</label><input type = "text" id = "update_parking_instructions" name="parking_instructions" class = "form-control">
          </div>
          <div class = "modal-footer">
            <button type = "submit" class = "btn btn-success">Update</button>
          </div>
        </div>
      </form>
    </div>
</div>
</div>