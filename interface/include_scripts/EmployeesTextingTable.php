<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<h2 style = "display:inline-block;">Weekly Schedule</h2>';
    echo '<form style = "float:right;" action = "../scripts/Send_Texts.php" method = "GET"><input type = "date" name = "date" value = "'.date('Y-m-d',strtotime("+2 days")).'">';
    include 'Employee_Skills_Dropdown.php';
    echo '<input type = "submit" value = "Send Messages">';
    echo '</form>';  
    $table = new \bootstrap\table("Employee_Texts_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("Name");
    $table_headers->Add_Header("Phone Number");
    $table_headers->Add_Header("skills");
    $table_headers->Add_Header(Get_Today("D"));
    $table_headers->Add_Header(Get_Tomorrow("D"));
    $table_headers->Add_Header(Get_Date_After_This_Many_Days(2,"D"));
    $table_headers->Add_Header(Get_Date_After_This_Many_Days(3,"D"));
    $table_headers->Add_Header(Get_Date_After_This_Many_Days(4,"D"));
    $table_headers->Add_Header(Get_Date_After_This_Many_Days(5,"D"));
    $table_headers->Add_Header(Get_Date_After_This_Many_Days(6,"D"));
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Employee_Table");
    $employees = new \company_program\Employees;
    ForEach($employees->employees as $employee)
    {
      $send = "";
      $options = array();
      $status = "";
      $date = date("Y-m-d");
      $end_date = date("Y-m-d",strtotime("+6 days"));
      $column_array = array($employee->Get_First_Name()." ".$employee->Get_Last_Name(),$employee->Get_Phone_Number(),$employee->Get_Skills_String());  
      $date_array_for_td_context = array();
      $column_to_start_td_context = 2;    
      while(strtotime($date) <= strtotime($end_date))
      {
        if($employee->Did_The_Daily_Text_Get_Sent($date))
        {
          if($employee->Am_I_Available_For_Work($date))
          {
            $status = $available_icon;
          }elseif($employee->Am_I_Unavailable_For_Work($date))
          {
            $status = $unavailable_icon;
          }else
          {
            $status = $waiting_for_reply_icon;
          }
        }else
        {
          $status = $green_send_sms_icon;
        }
        $column_array[] = $status;
        $date_array_for_td_context[$column_to_start_td_context] = $date;
        $column_to_start_td_context = $column_to_start_td_context + 1;
        $date = date("Y-m-d",strtotime("+1 day",strtotime($date)));
      }
      $table_row = new \bootstrap\Table_Row(10,$column_array,array('employee_id'=>$employee->Get_Person_ID()),$options,true,"",$date_array_for_td_context);
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>