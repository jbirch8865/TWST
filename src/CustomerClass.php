<?php
namespace company_program;
$cConfigs = new \config\ConfigurationFile();
$dblink = new \DatabaseLink\MySQLLink($cConfigs->Configurations()['database_name']);

class Customer
{
    private $customer_name;
    private $verified_customer_id;
    private $dblink;
    private $can_we_do_business;
    private $customer_address;
    private $customer_billing_address;
    private $customer_phone_number_extension;
    private $customer_phone_number;
    private $customer_fax_number;
    private $customer_web_address;
    private $customer_CCB;
    private $customer_industry;

    function __construct($unverified_customer_id=NULL)
    {
        $this->verified_customer_id = null;
        $this->customer_name = null;
        $this->can_we_do_business = 0;
        $this->customer_address = "";
        $this->customer_billing_address = "";
        $this->customer_phone_number_extension = null;
        $this->customer_phone_number = "";
        $this->customer_fax_number = "";
        $this->customer_web_address = "";
        $this->customer_CCB = "";
        $this->customer_industry = "";
        global $dblink;
        $this->dblink = $dblink;
        if(!is_null($unverified_customer_id))
        {
            $this->Load_Customer($unverified_customer_id);
        }
    }

    private function Load_Customer($unverified_customer_id)
    {
        if($this->Verify_Customer_ID($unverified_customer_id))
        {
            $this->Populate_Customer_Properties();
        }else
        {
            throw new Customer_Does_Not_Exist("The unverified_customer_id does not coorospond to a verified_customer_id");
        }
    }

    private function Does_Customer_Exist($unverified_customer_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Customers` WHERE `customer_id` = '".$unverified_customer_id."'");
            if(mysqli_num_rows($results) == 1)
            {
                return true;
            }else
            {
                return false;
            }
        } catch (\Exception $e)
        {
            $log_exception = new \logging\Log_To_Console($e->getMessage());
            return false;
        }               
    }

    private function Verify_Customer_ID($id_to_verify)
    {
        if($this->Does_Customer_Exist($id_to_verify))
        {
            $this->verified_customer_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_customer_id = null;
            return false;
        }
    }

    private function Populate_Customer_Properties()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Customers` WHERE `customer_id` = '".$this->verified_customer_id."'");
        while($row = mysqli_fetch_assoc($results))
        {
            $this->customer_name = $row['customer_name'];
            $this->can_we_do_business = (int) $row['can_we_do_business'];
            $this->customer_address = $row['customer_address'];
            $this->customer_billing_address = $row['customer_billing_address'];
            $this->customer_phone_number = $row['phone_number'];
            $this->customer_phone_number_extension = $row['phone_number_extension'];
            $this->customer_fax_number = $row['fax_number'];
            $this->customer_web_address = $row['Website'];
            $this->customer_CCB = $row['CCB'];
            $this->customer_industry = $row['Industry'];
        }
    }

    public function Get_Web_Address()
    {
        return $this->customer_web_address;
    }

    public function Get_CCB()
    {
        return $this->customer_CCB;
    }

    public function Get_Customer_Industry()
    {
        return $this->customer_industry;
    }

    public function Get_Customer_Name()
    {
        return $this->customer_name;
    }

    public function Get_Customer_Id()
    {
        return $this->verified_customer_id;
    }

    public function Get_Can_We_Do_Business()
    {
        return $this->can_we_do_business;
    }

    public function Get_Customer_Address()
    {
        return $this->customer_address;
    }

    public function Get_Customer_Billing_Address()
    {
        return $this->customer_billing_address;
    }

    public function Get_Phone_Number()
    {
        return $this->customer_phone_number;
    }
    
    public function Get_Fax_Number()
    {
        return $this->customer_fax_number;
    }

    public function Get_Phone_Number_Extension()
    {
        return $this->customer_phone_number_extension;
    }

    public function Set_Customer_Name($customer_name)
    {
        if($customer_name == "" || strtoupper($customer_name) == strtoupper($this->customer_name)){return false;}
        $this->customer_name = ucwords($customer_name);
        if($this->Does_Name_Exist())
        {
            throw new Customer_Does_Exist("This customer already exists");
        }
        $this->Update_Customer();
    }

    public function Set_Customer_Phone_Number($customer_phone_number)
    {
        if(preg_match("/^([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}$/", $customer_phone_number) || $customer_phone_number == "") 
        {
            $this->customer_phone_number = $customer_phone_number;
            $this->Update_Customer();
        }else
        {
            throw new \Exception("Customer phone number is not valid");
        }
    }

    public function Set_Customer_Fax_Number($customer_fax_number)
    {
        if(preg_match("/^([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}$/", $customer_fax_number) || $customer_fax_number == "") 
        {
            $this->customer_fax_number = $customer_fax_number;
            $this->Update_Customer();
        }else
        {
            throw new \Exception("Customer fax number is not valid");
        }
    }

    public function Set_Customer_Phone_Number_Extension($customer_phone_number_extension)
    {
        if(preg_match("/^[0-9]{1}|[0-9]{2}|[0-9]{3}|[0-9]{4}|[0-9]{5}|[0-9]{6}$/", $customer_phone_number_extension) || $customer_phone_number_extension == "") 
        {
            $this->customer_phone_number_extension = $customer_phone_number_extension;
            $this->Update_Customer();
        }else
        {
            throw new \Exception("Customer phone number extension is not valid");
        }
    }

    public function Set_Customer_Address($customer_address)
    {
        if($customer_address == ""){return false;}
        $this->customer_address = $customer_address;
        $this->Update_Customer();
    }

    public function Set_Customer_Billing_Address($customer_billing_address)
    {
        if($customer_billing_address == ""){return false;}
        $this->customer_billing_address = $customer_billing_address;
        $this->Update_Customer();
    }

    public function Set_Web_Address($customer_web_address)
    {
        if($customer_web_address == ""){return false;}
        $this->customer_web_address = $customer_web_address;
        $this->Update_Customer();
    }   
    
    public function Set_CCB($CCB)
    {
        if($CCB == ""){return false;}
        $this->customer_CCB = $CCB;
        $this->Update_Customer();
    }   
    
    public function Set_Customer_Industry($customer_industy)
    {
        if($customer_industy == ""){return false;}
        $this->customer_industry = $customer_industy;
        $this->Update_Customer();
    }
    
    private function Set_Can_We_Do_Business($can_we_do_business)
    {
        if(!is_int($can_we_do_business)){return false;}
        $this->can_we_do_business = $can_we_do_business;
        return $this->Update_Customer();
    }

    public function We_Can_Do_Business()
    {
        return $this->Set_Can_We_Do_Business(1);
    }

    public function We_Can_Not_Do_Business()
    {
        return $this->Set_Can_We_Do_Business(0);
    }

    public function Check_Before_Doing_Business()
    {
        return $this->Set_Can_We_Do_Business(2);
    }

    private function Update_Customer()
    {
        if(is_null($this->verified_customer_id)){ return false;}
        $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Customer_Name());
        $can_we_do_business = (int) $this->can_we_do_business;
        $customer_address = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Customer_Address());
        $customer_billing_address = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Customer_Billing_Address());
        $web_address = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Web_Address());
        $CCB = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_CCB());
        $customer_industry = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Customer_Industry());
        if($results = $this->dblink->ExecuteSQLQuery("UPDATE `Customers` SET `customer_name` = '".$name."', `Can_We_Do_Business` = '".$can_we_do_business."', `customer_billing_address` = '".$customer_billing_address."', `customer_address` = '".$customer_address."', `phone_number` = '".$this->Get_Phone_Number()."', `phone_number_extension` = '".$this->Get_Phone_Number_Extension()."', `fax_number` = '".$this->Get_Fax_Number()."', `Website` = '".$web_address."', `CCB` = '".$CCB."', `Industry` = '".$customer_industry."' WHERE `customer_id` = '".$this->verified_customer_id."'"))
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function Create_Customer($customer_name)
    {
        if(is_null($this->verified_customer_id) && !empty($customer_name))
        {
            $this->Set_Customer_Name($customer_name);
            $this->We_Can_Do_Business();
            $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Customer_Name());
            $customer_address = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Customer_Address());
            $customer_billing_address = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Customer_Billing_Address());
            $web_address = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Web_Address());
            $CCB = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_CCB());
            $customer_industry = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Customer_Industry());
            if($this->dblink->ExecuteSQLQuery("INSERT INTO `Customers` SET `customer_name` = '".$name."', `customer_billing_address` = '".$customer_billing_address."', `customer_address` = '".$customer_address."', `phone_number` = '".$this->Get_Phone_Number()."', `phone_number_extension` = '".$this->Get_Phone_Number_Extension()."', `fax_number` = '".$this->Get_Fax_Number()."', `Website` = '".$web_address."', `CCB` = '".$CCB."', `Industry` = '".$customer_industry."'"))
            {
                return $this->Verify_Customer_ID($this->dblink->GetLastInsertID());
            }else
            {
                return false;
            }
            
        }else
        {
            return false;
        }
    }

    public function Delete_Customer()
    {
        if(!is_null($this->verified_customer_id))
        {
            $this->Delete_Contractors();
            if($this->dblink->ExecuteSQLQuery("UPDATE `Customers` SET `Active_Status` = '0' WHERE `customer_id` = '".$this->verified_customer_id."'"))
            {
                return true;
            }else{
                return false;
            }
        }
    }

    private function Delete_Contractors()
    {
        if(!is_null($this->verified_customer_id))
        {
            if($this->dblink->ExecuteSQLQuery("UPDATE `People` INNER JOIN `Person_Belongs_To_Company` on `Person_Belongs_To_Company`.`person_id` = `People`.`person_id` SET `People`.`Active_Status` = '0' WHERE `Person_Belongs_To_Company`.`customer_id` = '".$this->verified_customer_id."'"))
            {
                return true;
            }else{
                return false;
            }
        }

    }

    private function Does_Name_Exist()
    {
        if(!is_null($this->Get_Customer_Name()))
        {
            if($results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Customers` WHERE `customer_name` = '".$this->Get_Customer_Name()."' AND `Active_Status` = '1'"))
            {
                if(mysqli_num_rows($results) > 0)
                {
                    return true;
                }else
                {
                    return false;
                }
            }else
            {
                return false;
            }
        }else
        {
            return false;
        }
    }
}

class Customers
{
    public $customers;
    private $db_name;

    function __construct()
    {
        $this->customers = array();
        $dbname = new \gStatic_Variables;
        $this->db_name = $dbname->db_name;
        $this->Load_Customers();
    }

    private function Load_Customers()
    {
        $customers = $this->Get_SQL_Customer_Search();
        while($row = mysqli_fetch_assoc($customers))
        {
            $this->Load_Customer(new Customer($row['customer_id']));
        }
    }

    private function Get_SQL_Customer_Search()
    {
        $dblink = new \DatabaseLink\MySQLLink($this->db_name);
        $results = $dblink->ExecuteSQLQuery("SELECT * FROM `Customers` WHERE `Active_Status` = '1'");
        return $results;
    }

    private function Load_Customer($customer)
    {
        if($customer instanceof Customer)
        {
            if(is_null($customer->Get_Customer_Id()))
            {
                return false;
            }
            $this->customers[$customer->Get_Customer_Id()] = $customer;
        }else
        {
            return false;
        }
    }
}
?>