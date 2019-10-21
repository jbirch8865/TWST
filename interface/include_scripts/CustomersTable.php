<?php
    global $html_green_checkmark;
    global $html_delete;
    global $html_yellow_exclamation;
    echo '<button style = "margin:25px;float:right;" type = "button" class = "btn btn-success" data-toggle="modal" data-target="#AddCustomerModal">New Company</button>';
    echo '<h2 style = "display:inline-block;">Current Companies</h2>';
    $table = new \bootstrap\table("Customer_Table_ID");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("Company Name");
    $table_headers->Add_Header("Can Do Business");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("Customer_Table");
    $customers = new \company_program\Customers;
    ForEach($customers->customers as $customer)
    {
        $options = array();
        if($customer->Get_Can_We_Do_Business() == '1')
        {
            $can_we_do_business = "<div style = 'display:inline-block;' data-toggle = 'tooltip' title = 'We can do business with this company'>".$html_green_checkmark."</div>";
            $options[$html_delete." Do Not Work"] = array('href' => 'scripts/Do_Not_Do_Business.php?customer_id='.$customer->Get_Customer_Id());
            $options[$html_yellow_exclamation." Ask Accounting"] = array('href' => 'scripts/Do_Business.php?check=true&customer_id='.$customer->Get_Customer_Id());
          }elseif($customer->Get_Can_We_Do_Business() == '0')
        {
            $can_we_do_business = "<div style = 'display:inline-block;' data-toggle = 'tooltip' title = 'We do not do business with this company'>".$html_delete.'</div>';
            $options[$html_green_checkmark." Good"] = array('href' => 'scripts/Do_Business.php?customer_id='.$customer->Get_Customer_Id());
            $options[$html_yellow_exclamation." Ask Accounting"] = array('href' => 'scripts/Do_Business.php?check=true&customer_id='.$customer->Get_Customer_Id());
          }else
        {
          $can_we_do_business = "<div style = 'display:inline-block;' data-toggle = 'tooltip' title = 'Prior to agreeing to do business with this customer.  Accounting needs to give permission.'>".$html_yellow_exclamation."</div>";
          $options[$html_green_checkmark." Good"] = array('href' => 'scripts/Do_Business.php?customer_id='.$customer->Get_Customer_Id());
          $options[$html_delete." Do Not Work"] = array('href' => 'scripts/Do_Not_Do_Business.php?customer_id='.$customer->Get_Customer_Id());
        }
        $options["Edit Company"] = array('href' => '#Edit_Customer','class' => array('dd-item-grey',
          "customer_name=".str_replace(" ","{",$customer->Get_Customer_Name()),
          'customer_id='.$customer->Get_Customer_Id(),
          'customer_address='.str_replace("&#10;","~",str_replace(" ","{",str_replace("\r\n","}",$customer->Get_Customer_Address()))),
          'customer_billing_address='.str_replace("&#10;","~",str_replace(" ","{",str_replace("\r\n","}",$customer->Get_Customer_Billing_Address()))),
          'customer_phone_number='.$customer->Get_Phone_Number(),
          'customer_phone_number_extension='.$customer->Get_Phone_Number_Extension(),
          'customer_fax_number='.$customer->Get_Fax_Number(),
          'customer_web_address='.$customer->Get_Web_Address(),
          'customer_CCB='.$customer->Get_CCB(),
          'customer_industry='.$customer->Get_Customer_Industry()));
          $tooltip = "";
          if(!$customer->Get_Customer_Address() == "")
          {
            $tooltip = "Address:<br>".$customer->Get_Customer_Address()."<br>";
          }
          if(!$customer->Get_Customer_Billing_Address() == "")
          {
            $tooltip = $tooltip."Billing Address:<br>".$customer->Get_Customer_Billing_Address()."<br>";
          }
          if(!$customer->Get_Phone_Number() == "")
          {
            $tooltip = $tooltip."Phone_number:<br>".$customer->Get_Phone_Number()."<br>";
          }
          if(!$customer->Get_Phone_Number_Extension() == "")
          {
            $tooltip = $tooltip."Extension:<br>".$customer->Get_Phone_Number_Extension()."<br>";
          }
          if(!$customer->Get_Fax_Number() == "")
          {
            $tooltip = $tooltip."Fax Number:<br>".$customer->Get_Fax_Number()."<br>";                              
          }
          if(!$customer->Get_Web_Address() == "")
          {
            $tooltip = $tooltip."Website:<br><a href = '".$customer->Get_Web_Address()."'>".$customer->Get_Web_Address()."</a><br>";    
          }          
          if(!$customer->Get_CCB() == "")
          {
            $tooltip = $tooltip."CCB#:<br>".$customer->Get_CCB()."<br>";                              
          }          
          if(!$customer->Get_Customer_Industry() == "")
          {
            $tooltip = $tooltip."Industry:<br>".$customer->Get_Customer_Industry()."<br>";                              
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
            <label for="customer_phone_number_extension">Phone Number Extension:</label><input type = "number" max = "999999" min = "0" id = "customer_phone_number_extension" name="phone_number_extension" class = "form-control" placeholder = "4848">
          </div>
          <div class = "form-group">
            <label for="customer_address">Event Address:</label><textarea id = "customer_address" name = "customer_address" class = "form-control" placeholder = "[physical address]&#10;2345 NE Sacramento St.&#10;Portland OR, 97025"></textarea>
          </div>
          <div class = "form-group">
            <label for="customer_billing_address">Mailing Address:</label>
            <div class="checkbox">
            <label><input type="checkbox" id = "address_checkbox">Same as Physical</label>
            </div>
            <textarea id = "customer_billing_address" name = "customer_billing_address" class = "form-control" placeholder = "[billing address]&#10;1234 SE Ferguson Rd&#10;Oregon City OR, 97045"></textarea>
          </div>
          <div class = "form-group">
            <label for="customer_web_address">Website:</label><input type = "text" id = "customer_web_address" name="web_address" class = "form-control" placeholder = "www.google.com">
          </div>
          <div class = "form-group">
            <label for="customer_CCB">CCB:</label><input type = "text" id = "customer_CCB" name="CCB" class = "form-control" placeholder = "#321498245">
          </div>
          <div class = "form-group">
            <label for="customer_industry"> Industry:</label><input type = "text" id = "customer_industry" name="customer_industry" class = "form-control" placeholder = "Communication">
          </div>          
          <div class = "form-group">
            <label for="customer_fax_number">Fax Number:</label><input type = "tel" id = "customer_fax_number" pattern="([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}" name="fax_number" class = "form-control" placeholder = "1-800-456-9785">
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
              <label for="update_customer_phone_number_extension">Phone Number Extension:</label><input type = "number" max = "999999" min = "0" id = "update_customer_phone_number_extension" name="phone_number_extension" class = "form-control" placeholder = "4848">
            </div>
            <div class = "form-group">
              <label for="update_customer_address">Event Address:</label><textarea id = "update_customer_address" name = "customer_address" class = "form-control" placeholder = "2345 NE Sacramento St.&#10;Portland OR, 97025"></textarea>
            </div>
            <div class = "form-group">
              <label for="update_customer_billing_address">Mailing Address:</label><textarea id = "update_customer_billing_address" name = "customer_billing_address" class = "form-control" placeholder = "1234 SE Ferguson Rd&#10;Oregon City OR, 97045"></textarea>
            </div>
            <div class = "form-group">
              <label for="update_customer_web_address">Website:</label><input type = "text" id = "update_customer_web_address" name="web_address" class = "form-control" placeholder = "www.google.com">
            </div>
            <div class = "form-group">
              <label for="update_customer_CCB">CCB:</label><input type = "text" id = "update_customer_CCB" name="CCB" class = "form-control" placeholder = "#321498245">
            </div>
            <div class = "form-group">
              <label for="update_customer_industry"> Industry:</label><input type = "text" id = "update_customer_industry" name="customer_industry" class = "form-control" placeholder = "Communication">
            </div>          
            <div class = "form-group">
              <label for="update_customer_fax_number">Fax Number:</label><input type = "tel" id = "update_customer_fax_number" pattern="([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}" name="fax_number" class = "form-control" placeholder = "1-800-456-9785">
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