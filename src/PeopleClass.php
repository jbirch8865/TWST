<?php
namespace company_program;
$cConfigs = new \config\ConfigurationFile();
$dblink = new \DatabaseLink\MySQLLink($cConfigs->Configurations()['database_name']);

class Employee extends Person
{
    public $skills;
    public $equipment;

    function __construct($unverified_employee_id=NULL)
    {
        parent::__construct('1');
        $this->skills = array();
        if(!empty($unverified_employee_id))
        {
            $this->Load_Employee($unverified_employee_id);
        }
    }

    private function Load_Employee($unverified_employee_id)
    {
        if($this->Verify_Person_ID($unverified_employee_id))
        {
            $this->Populate_Employee_Properties();
            $this->Populate_Skills();
            $this->Populate_Equipment();
        }else
        {
            throw new Person_Does_Not_Exist("The unverified_employee_id does not coorospond to a verified_person_id");
        }
    }

    public function Load_Employee_From_Phone_Number($phonenum)
    {
        if(preg_match("/^[\+]1[0-9]{10}$/", $phonenum) && $phonenum != "")
        {
            $phonenum = substr($phonenum,2,10);
            $number = substr($phonenum,0,3)."-".substr($phonenum,3,3)."-".substr($phonenum,6,4);
            $results = $this->dblink->ExecuteSQLQuery("SELECT `person_id` FROM `People` WHERE `phone_number` = '".$number."' AND `Active_Status` = '1'");
            if(mysqli_num_rows($results) == 1)
            {
                $row = mysqli_fetch_assoc($results);
                $this->Load_Employee($row['person_id']);
            }else
            {
                throw new \Exception("Sorry not a valid person");
            }
        }else
        {
            throw new \Exception("Incorrect number format");
        }
    }

    private function Populate_Employee_Properties()
    {
        $this->Load_Properties();
    }

    public function Update_Employee()
    {
        return $this->Update_Person();
    }

    public function Create_Employee()
    {
        return $this->Verify_Person_ID($this->Create_Person());
    }

    public function Delete_Employee()
    {
        return $this->Delete_Person();
    }

    private function Person_Is_Loaded()
    {
        if(empty($this->verified_person_id))
        {
            return false;
        }else
        {
            return true;
        }
    }

    public function Get_Skills_String()
    {
        $string = "";
        ForEach($this->skills as $skill_id => $skill)
        {
            $string = $string." ".$skill->Get_Skill_Name();
        }
        return $string;
    }

    public function Add_Skill($skill)
    {
        if(empty($this->verified_person_id) || empty($skill->Get_Skill_ID()) || !$skill instanceof \company_program\Employee_Skill){return false;}
        try
        {
            $this->dblink->ExecuteSQLQuery("INSERT INTO `Person_Has_Skills` SET `Person_ID` = '".$this->verified_person_id."', `Skill_ID` = '".$skill->Get_Skill_ID()."'");
            $this->skills[$skill->Get_Skill_ID()] = $skill;
        }catch(\DatabaseLink\DuplicatePrimaryKeyRequest $e)
        {
            throw new \DatabaseLink\DuplicatePrimaryKeyRequest($e->getMessage());
        }
    }

    private function Populate_Skills()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT `Skills`.`Skill_ID` FROM `Person_Has_Skills` INNER JOIN `Skills` ON `Skills`.`Skill_ID` = `Person_Has_Skills`.`Skill_ID` WHERE `Person_ID` = '".$this->verified_person_id."' AND `Active_Status` = '1'");
        while($row = mysqli_fetch_assoc($results))
        {
            $this->skills[$row['Skill_ID']] = new \company_program\Employee_Skill($row['Skill_ID']);
        }
    }

    public function Delete_Skills()
    {
        $this->dblink->ExecuteSQLQuery("DELETE FROM `Person_Has_Skills` WHERE `Person_ID` = '".$this->verified_person_id."'");
        $this->skills = array(); 
    }

    private function Populate_Equipment()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT `Equipment_ID` FROM `Person_Has_Equipment` WHERE `Person_ID` = '".$this->verified_person_id."'");
        while($row = mysqli_fetch_assoc($results))
        {
            $this->equipment[$row['Equipment_ID']] = new \company_program\Equipment($row['Equipment_ID']);
        }
    }

    public function Add_Equipment($equipment)
    {
        if(empty($this->verified_person_id) || empty($equipment->Get_Equipment_Id()) || !$equipment instanceof \company_program\Equipment){return false;}
        try
        {
            $this->dblink->ExecuteSQLQuery("INSERT INTO `Person_Has_Equipment` SET `Person_ID` = '".$this->verified_person_id."', `Equipment_ID` = '".$equipment->Get_Equipment_Id()."'");
            $this->skills[$equipment->Get_Equipment_Id()] = $equipment;
        }catch(\DatabaseLink\DuplicatePrimaryKeyRequest $e)
        {
        }
    }

    private function Send_SMS($body)
    {
        if($this->Person_Is_Loaded())
        {
            $sms = new \sms\SMSMessageWithChecks;
            $sms->Set_To_Number($this->Get_Phone_Number());
            $sms->Set_Message_Body($body);
            $sms->Send_Message();
            $cConfigs = new \config\ConfigurationFile();
            $this->Log_SMS_Action($sms->Get_Message_SID(),$cConfigs->Configurations()['Twilio_From_Number'],"1".str_replace("-","",$this->Get_Phone_Number()),$body);
        }
    }

    public function Number_Of_Texts_Sent_Today()
    {
        $phone = "1".str_replace("-","",$this->Get_Phone_Number());
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `SMS_Log` WHERE `to_number` = '".$phone."' AND DATE(`timestamp`) = CURDATE()");
        return mysqli_num_rows($results);
    }

    public function Number_Of_Texts_Received_Today()
    {
        $phone = "1".str_replace("-","",$this->Get_Phone_Number());
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `SMS_Log` WHERE `from_number` = '".$phone."' AND DATE(`timestamp`) = CURDATE()");
        return mysqli_num_rows($results);
    }


    private function Update_Schedule_Status($status,$date)
    {
        $results = $this->dblink->ExecuteSQLQuery("INSERT INTO `Schedule` SET `Date` = '$date', `Person_ID` = '".$this->Get_Person_ID()."', `Status` = '$status' ON DUPLICATE KEY UPDATE `Status` = '$status'");
    }

    public function I_Am_Available_To_Work($date_available)
    {
        $date_available = date("Y-m-d",strtotime($date_available));
        $this->Update_Schedule_Status('5',$date_available);
    }

    public function I_Am_Not_Available_To_Work($date_not_available)
    {
        $date_not_available = date("Y-m-d",strtotime($date_not_available));
        $this->Update_Schedule_Status('6',$date_not_available);
    }

    public function Am_I_Available_For_Work($date_to_check)
    {
        if($results = $this->Get_My_Schedule_Info_For_This_Day($date_to_check))
        {
            if(mysqli_num_rows($results) > 0)
            {
                $results = mysqli_fetch_assoc($results);
                if($results['Status'] == '5'){return true;}else{return false;}
            }else
            {
                return false;
            }    
        }else
        {
            return false;
        }
    }

    public function Am_I_Unavailable_For_Work($date_to_check)
    {
        if($results = $this->Get_My_Schedule_Info_For_This_Day($date_to_check))
        {
            if(mysqli_num_rows($results) > 0)
            {
                $results = mysqli_fetch_assoc($results);
                if($results['Status'] == '6'){return true;}else{return false;}
            }else
            {
                return false;
            }    
        }else
        {
            return false;
        }

    }

    public function Have_I_Decided_If_I_Am_Available_For_Work($date_to_check)
    {
        if($results = $this->Get_My_Schedule_Info_For_This_Day($date_to_check))
        {
            if(mysqli_num_rows($results) > 0)
            {
                $results = mysqli_fetch_assoc($results);
                if($results['Status'] != '4'){return true;}else{return false;}
            }else
            {
                return false;
            }
        }else
        {
            return false;
        }
    }

    public function Get_My_Schedule_Info_For_This_Day($date_to_check)
    {
        $date_to_check = date("Y-m-d",strtotime($date_to_check));
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Schedule` WHERE `Date` = '".$date_to_check."' AND `Person_ID` = '".$this->Get_Person_ID()."'");
        if(mysqli_num_rows($results) > 0)
        {
            return $results;
        }else
        {
            return false;
        }
    }

    public function Log_SMS_Action($twilio_sid,$from_number,$to_number,$body)
    {
        try
        {
            $this->dblink->ExecuteSQLQuery("INSERT INTO `SMS_Log` SET `twilio_sid` = '".$twilio_sid."', `from_number` = '".$from_number."', `to_number` = '".$to_number."', `message_body` = '".$body."'");
        } catch (\Exception $e)
        {
            //don't error out, continue processing
        }
    }
}


class Contractor extends Person
{
    private $customer_id;

    function __construct($unverified_contractor_id=NULL)
    {
        $this->customer_id = null;
        parent::__construct('2');
        if(!is_null($unverified_contractor_id))
        {
            $this->Load_Contractor($unverified_contractor_id);
        }
    }

    private function Load_Contractor($unverified_contractor_id)
    {
        if($this->Verify_Person_ID($unverified_contractor_id))
        {
            $this->Populate_Contractor_Properties();
        }else
        {
            throw new Person_Does_Not_Exist("The unverified_contractor_id does not coorospond to a verified_contractor_id");
        }
    }

    public function Verify_Contractor_ID($id_to_verify)
    {
        if($this->Does_Person_Exist($id_to_verify))
        {
            $this->verified_person_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_person_id = null;
            return false;
        }
    }

    private function Populate_Contractor_Properties()
    {
        $this->Load_Properties();        
    }

    public function Set_Customer_ID($customer_id)
    {
        if($this->Validate_Customer_ID($customer_id))
        {
            $this->customer_id = $customer_id;
            return $this->Assign_Person_To_Company();
        }else
        {
            return false;
        }
    }

    public function Validate_Customer_ID($unverified_customer_id)
    {
        return $customer = new Customer($unverified_customer_id);
    }

    public function Update_Contractor()
    {
        return $this->Update_Person();
    }

    private function Assign_Person_To_Company()
    {
        if(is_null($this->verified_person_id)){ return false;}
        if(is_null($this->customer_id)){ return false;}
        return $this->dblink->ExecuteSQLQuery("INSERT INTO `Person_Belongs_To_Company` SET `person_id` = '".$this->verified_person_id."', `customer_id` = '".$this->customer_id."'");
    }

    public function Create_Contractor()
    {
        return $this->Verify_Person_ID($this->Create_Person());
    }

    public function Delete_Contractor()
    {
        return $this->Delete_Person();
    }
}



Abstract class Person
{
    public $first_name;
    public $last_name;
    public $verified_person_id;
    public $person_type;
    public $phone_number;
    public $phone_number_extension;
    public $email_address;
    public $phone_number_confirmed;
    public $dblink;

    function __construct($person_type)
    {
        $this->verified_person_id = null;
        $this->person_type = $person_type;
        $this->first_name = null;
        $this->last_name = null;
        $this->phone_number = null;
        $this->phone_number_extension = null;
        $this->email_address = null;
        $this->phone_number_confirmed = false;                
        global $dblink;
        $this->dblink = $dblink;
    }

    public function Load_Properties()
    {
        if(!is_null($this->verified_person_id))
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `People` WHERE `person_id` = '".$this->verified_person_id."'");
            while($row = mysqli_fetch_assoc($results))
            {
                $this->first_name = $row['first_name'];
                $this->last_name = $row['last_name'];
                $this->phone_number = $row['phone_number'];
                $this->email_address = $row['email_address'];
                $this->phone_number_extension = $row['phone_number_extension'];
            }
        }else
        {
            throw \Exception("Trying to Load_Properties before giving a unique id");
        }
    }

    public function Verify_Person_ID($id_to_verify)
    {
        if($this->Does_Person_Exist($id_to_verify))
        {
            $this->verified_person_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_person_id = null;
            return false;
        }
    }

    public function Does_Person_Exist($unverified_person_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `People` WHERE `person_id` = '".$unverified_person_id."' AND `person_type` = '".$this->person_type."'");
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

    public function Get_First_Name()
    {
        return $this->first_name;
    }

    public function Get_Person_ID()
    {
        return $this->verified_person_id;
    }

    public function Get_Last_Name()
    {
        return $this->last_name;
    }

    public function Get_Phone_Number()
    {
        return $this->phone_number;
    }    

    public function Get_Phone_Number_Extension()
    {
        return $this->phone_number_extension;
    }

    public function Get_Email_Address()
    {
        return $this->email_address;
    }

    public function Set_Email_Address($email_address)
    {
        $this->email_address = str_replace(" ","_",$email_address);
    }

    public function Set_First_Name($first_name)
    {
        if($first_name == ""){return false;}
        $this->first_name = ucwords($first_name);
    }

    public function Set_Last_Name($last_name)
    {
        if($last_name == ""){return false;}
        $this->last_name = ucwords($last_name);
    }

    public function Set_Phone_Number($phone_number)
    {
        if(preg_match("/^([1]{1}-[0-9]{3}|[0-9]{3})-[0-9]{3}-[0-9]{4}$/", $phone_number) || $phone_number == "") 
        {
            $this->phone_number = $phone_number;
        }else
        {
            throw new \Exception("phone number is not valid");
        }
    }

    public function Set_Phone_Number_Extension($phone_number_extension)
    {
        if(preg_match("/^[0-9]{1}|[0-9]{2}|[0-9]{3}|[0-9]{4}|[0-9]{5}|[0-9]{6}$/", $phone_number_extension) || $phone_number_extension == "") 
        {
            $this->phone_number_extension = $phone_number_extension;
        }else
        {
            throw new \Exception("phone number extension is not valid");
        }
    }

    public function Update_Person()
    {
        if(is_null($this->verified_person_id)){ return false;}
        $first_name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_First_Name());
        $last_name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Last_Name());
        $email_address = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Email_Address());
        return $this->dblink->ExecuteSQLQuery("UPDATE `People` SET `email_address` = '".$email_address."', `first_name` = '".$first_name."', `last_name` = '".$last_name."', `phone_number` = '".$this->Get_Phone_Number()."', `phone_number_extension` = '".$this->Get_Phone_Number_Extension()."' WHERE `person_id` = '".$this->verified_person_id."'");
    }

    public function Create_Person()
    {
        if(is_null($this->verified_person_id))
        {
            $first_name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_First_Name());
            $last_name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Last_Name());
            $email_address = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Email_Address());
            if($this->dblink->ExecuteSQLQuery("INSERT INTO `People` SET `person_type` = '".$this->person_type."', `email_address` = '".$email_address."', `first_name` = '".$first_name."', `last_name` = '".$last_name."', `phone_number` = '".$this->Get_Phone_Number()."', `phone_number_extension` = '".$this->Get_Phone_Number_Extension()."'"))
            {
                return $this->dblink->GetLastInsertID();
            }else
            {
                return false;
            }
        }else
        {
            return false;
        }
    }

    public function Delete_Person()
    {
        if(!is_null($this->verified_person_id))
        {
            if($this->dblink->ExecuteSQLQuery("UPDATE `People` SET `Active_Status` = '0' WHERE `person_id` = '".$this->verified_person_id."'"))
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
    }

}

class Contractors
{
    public $contractors;
    private $customer_id;
    private $db_name;
    
    function __construct($customer_id)
    {
        $this->contractors = array();
        $this->customer_id = $customer_id;
        global $dblink;
        $this->dblink = $dblink;
        $this->Load_Contractors();
    }

    private function Load_Contractors()
    {
        $contractors = $this->Get_SQL_Contractor_Search();
        while($row = mysqli_fetch_assoc($contractors))
        {
            $this->Load_Contractor(new Contractor($row['person_id']));
        }
    }

    private function Get_SQL_Contractor_Search()
    {
        return $this->dblink->ExecuteSQLQuery("SELECT * FROM `People` INNER JOIN `Person_Belongs_To_Company` ON `Person_Belongs_To_Company`.`person_id` = `People`.`person_id` WHERE `Person_Belongs_To_Company`.`customer_id` = '".$this->customer_id."' AND `People`.`Active_Status` = '1'");
    }

    private function Load_Contractor($contractor)
    {
        if($contractor instanceof Contractor)
        {
            if(is_null($contractor->Get_Person_ID()))
            {
                return false;
            }
            $this->contractors[$contractor->Get_Person_ID()] = $contractor;
        }else
        {
            return false;
        }
    }
}

class Employees
{
    public $employees;
    private $dblink;
    
    function __construct()
    {
        $this->employees = array();
        global $dblink;
        $this->dblink = $dblink;
        $this->Load_Employees();
    }

    private function Load_Employees()
    {
        $employees = $this->Get_SQL_Employee_Search();
        while($row = mysqli_fetch_assoc($employees))
        {
            $this->Load_Employee(new Employee($row['person_id']));
        }
    }

    private function Get_SQL_Employee_Search()
    {
        return $this->dblink->ExecuteSQLQuery("SELECT * FROM `People` WHERE `People`.`person_type` = '1' AND `People`.`Active_Status` = '1'");
    }

    private function Load_Employee($employee)
    {
        if($employee instanceof Employee)
        {
            if(is_null($employee->Get_Person_ID()))
            {
                return false;
            }
            $this->employees[$employee->Get_Person_ID()] = $employee;
        }else
        {
            return false;
        }
    }
}
?>