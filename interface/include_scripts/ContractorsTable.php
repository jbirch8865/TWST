<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<button style = "margin:25px;float:right;" type = "button" class = "btn btn-success" data-toggle="modal" data-target="#AddContractorModal">Create Contact</button>';
    $customer = new \company_program\Customer($_GET['customer_id']);
    echo '<h2 style = "display:inline-block;">'.$customer->Get_Customer_Name()."</h2>";
    $table = new \bootstrap\table("Contractor_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("First Name");
    $table_headers->Add_Header("Last Name");
    $table_headers->Add_Header("Phone Number");
    $table_headers->Add_Header("Email Address");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Contractors_Table");
    $contractor = new \company_program\Contractors($_GET['customer_id']);
    ForEach($contractor->contractors as $contractor)
    {
        $options = array();
        $options["Edit Contact"] = array('href' => '#Edit_Contractor','class' => 
        array('dd-item-grey',
        "contractor_name=".str_replace(" ","{",$contractor->Get_First_Name()." ".$contractor->Get_Last_Name()),
        "contractor_id=".$contractor->Get_Person_ID(),
        "contractor_first_name=".str_replace(" ","{",$contractor->Get_First_Name()),
        "contractor_last_name=".str_replace(" ","{",$contractor->Get_Last_Name()),
        "contractor_phone_number=".$contractor->Get_Phone_Number(),
        "contractor_phone_number_extension=".$contractor->Get_Phone_Number_Extension(),
        "contractor_email=".$contractor->Get_Email_Address()));
        if($contractor->Get_Phone_Number_Extension() != "")
        {
          $tooltip = "phone extension:<br>".$contractor->Get_Phone_Number_Extension();
        }else
        {
          $tooltip = "";
        }
        $options['Delete Contact'] = array('href' => '/scripts/Delete_Contractor.php?contractor_id='.$contractor->Get_Person_ID(),'class' => array('dd-item-red'));
        $table_row = new \bootstrap\Table_Row(4,array($contractor->Get_First_Name(),$contractor->Get_Last_Name(),$contractor->Get_Phone_Number(),$contractor->Get_Email_Address()),array(),$options,true,$tooltip);
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>
<div class = "modal fade" role = "dialog" id="AddContractorModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Add_Contractor.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title">Add Contact</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type = "hidden" name = "customer_id" value = "<?php echo $_GET['customer_id']; ?>">
          <div class = "form-group">
          <label for="contractor_first_name"><span style="color:red;">*</span>First Name:</label><input type = "text" id = "contractor_first_name" name="first_name" class = "form-control" placeholder = "First Name" required>
          </div>
          <div class = "form-group">
          <label for="contractor_last_name">Last Name:</label><input type = "text" id = "contractor_last_name" name="last_name" class = "form-control" placeholder = "Last Name">
          </div>
          <div class = "form-group">
          <label for="contractor_phone_number">Phone:</label><input type="tel" id="contractor_phone_number" class = "form-control" name="phone_number" placeholder = "503-123-4567" pattern="([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}">
          </div>
          <div class = "form-group">
            <label for="contractor_phone_number_extension">Phone Number Extension:</label><input type = "number" max = "999999" min = "0" id = "contractor_phone_number_extension" name="phone_number_extension" class = "form-control" placeholder = "4848">
          </div>
          <div class = "form-group">
          <label for="customer_email">Email Address:</label><input type = "email" id = "contractor_email" name="email_address" class = "form-control" placeholder = "first.last@example.com">
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Create</button>
        </div>
      </form>
    </div>
</div>
</div>

<div class = "modal fade" role = "dialog" id="ChangeContractorModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Update_Contractor.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title" id = "update_contractor_modal_title"></h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <input id = "update_contractor_id" type = "hidden" name = "contractor_id">
            <div class = "form-group">
            <label for="update_contractor_first_name"><span style="color:red;">*</span>First Name:</label><input type = "text" id = "update_contractor_first_name" name="first_name" class = "form-control" placeholder = "First Name" required>
            </div>
            <div class = "form-group">
            <label for="update_contractor_last_name">Last Name:</label><input type = "text" id = "update_contractor_last_name" name="last_name" class = "form-control" placeholder = "Last Name">
            </div>
            <div class = "form-group">
            <label for="update_contractor_phone_number">Phone:</label><input type="tel" id="update_contractor_phone_number" class = "form-control" name="phone_number" placeholder = "503-123-4567" pattern="([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}">
            </div>
            <div class = "form-group">
            <label for="update_contractor_phone_number_extension">Phone Number Extension:</label><input type = "number" max = "999999" min = "0" id = "update_contractor_phone_number_extension" name="phone_number_extension" class = "form-control" placeholder = "4848">
            </div>
            <div class = "form-group">
            <label for="update_customer_email">Email:</label><input type = "email" id = "update_contractor_email" name="email_address" class = "form-control" placeholder = "first.last@example.com">
            </div>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Update</button>
        </div>
      </form>
    </div>
</div>
</div>