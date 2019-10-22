<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<button style = "margin:25px;float:right;" type = "button" class = "btn btn-success" data-toggle="modal" data-target="#AddCustomerModal">New Company</button>';
    echo '<h2 style = "display:inline-block;">Current Companies</h2>';
    $table = new \bootstrap\table("Customer_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("Company Name");
    $table_headers->Add_Header("Phone Number");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Customer_Table");
    $customers = new \company_program\Customers;
    ForEach($customers->customers as $customer)
    {
        $options = array();
        $options["Edit Company"] = array('href' => '#Edit_Customer','class' => array('dd-item-grey',
          "customer_name=".str_replace(" ","{",$customer->Get_Customer_Name()),
          'customer_id='.$customer->Get_Customer_Id(),
          'customer_address='.str_replace("&#10;","~",str_replace(" ","{",str_replace("\r\n","}",$customer->Get_Customer_Address()))),
          'customer_phone_number='.$customer->Get_Phone_Number(),
          'customer_web_address='.$customer->Get_Web_Address()));
          $tooltip = "";
          if(!$customer->Get_Customer_Address() == "")
          {
            $tooltip = "Address:<br>".$customer->Get_Customer_Address()."<br>";
          }
          if(!$customer->Get_Phone_Number() == "")
          {
            $tooltip = $tooltip."Phone_number:<br>".$customer->Get_Phone_Number()."<br>";
          }
          if(!$customer->Get_Web_Address() == "")
          {
            $tooltip = $tooltip."Website:<br><a href = '".$customer->Get_Web_Address()."'>".$customer->Get_Web_Address()."</a><br>";    
          }          
          $options["Add Contacts"] = array('href' => 'Contractor_index.php?customer_id='.$customer->Get_Customer_Id(),'class' => array('dd-item-grey'));
          if($current_user->current_user->Is_Management()){$options['Delete Company'] = array('href' => '/scripts/Delete_Customer.php?customer_id='.$customer->Get_Customer_Id(),'class' => array('dd-item-red'));}
        $table_row = new \bootstrap\Table_Row(2,array($customer->Get_Customer_Name(),$can_we_do_business),array(),$options,true,$tooltip);
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>
<div class = "modal fade" role = "dialog" id="AddCustomerModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Add_Customer.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title">Add Company</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <label for="customer_name"><span style="color:red;">*</span>Name:</label><input type = "text" id = "customer_name" name="customer_name" class = "form-control" placeholder = "Company Name" required>
          </div>
          <div class = "form-group">
            <label for="customer_phone_number"><span style="color:red;">*</span>Phone Number:</label><input type = "tel" id = "customer_phone_number" pattern="([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}" name="phone_number" class = "form-control" placeholder = "1-800-456-9785" required>
          </div>
          <div class = "form-group">
            <label for="customer_address">Company Address:</label><textarea id = "customer_address" name = "customer_address" class = "form-control" placeholder = "2345 NE Sacramento St.&#10;Portland OR, 97025"></textarea>
          </div>
            <label for="customer_web_address">Website:</label><input type = "text" id = "customer_web_address" name="web_address" class = "form-control" placeholder = "www.google.com">
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Create</button>
        </div>
      </form>
    </div>
</div>
</div>

<div class = "modal fade" role = "dialog" id="ChangeCustomerModal">
<div class = "modal-dialog">
    <div class = "modal-content">
    <form action = "scripts/Update_Customer.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title" id = "update_customer_modal_title"></h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <input type = "hidden" name = "customer_id" id = "update_customer_id">
            <div class = "form-group">
              <label for="update_customer_name">Name:</label><input type = "text" id = "update_customer_name" name="customer_name" class = "form-control" placeholder = "Company Name" required>
            </div>
            <div class = "form-group">
              <label for="update_customer_phone_number"><span style="color:red;">*</span>Phone Number:</label><input type = "tel" pattern="([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}" id = "update_customer_phone_number" name="phone_number" class = "form-control" placeholder = "1-800-456-9785" required>
            </div>
            <div class = "form-group">
              <label for="update_customer_address">Company Address:</label><textarea id = "update_customer_address" name = "customer_address" class = "form-control" placeholder = "2345 NE Sacramento St.&#10;Portland OR, 97025"></textarea>
            </div>
            <div class = "form-group">
              <label for="update_customer_web_address">Website:</label><input type = "text" id = "update_customer_web_address" name="web_address" class = "form-control" placeholder = "www.google.com">
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