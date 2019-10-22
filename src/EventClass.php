<?php
namespace company_program;
$cConfigs = new \config\ConfigurationFile();
$dblink = new \DatabaseLink\MySQLLink($cConfigs->Configurations()['database_name']);

class Event_Type
{
    private $verified_event_type_id;
    private $name;
    public $dblink;

    function __construct($unverified_event_type_id = NULL)
    {
        $this->verified_event_type_id = null;
        $this->name = "";
        global $dblink;
        $this->dblink = $dblink;
        if(!empty($unverified_event_type_id)){$this->Load_Properties($unverified_event_type_id);}
    }

    private function Load_Properties($unverified_event_type_id)
    {
        if($this->Verify_Event_Type_ID($unverified_event_type_id))
        {
            $this->Populate_Event_Type_Properties();
        }else
        {
            throw new Event_Type_Does_Not_Exist("The unverified_event_type_id does not coorospond to a verified_event_type_id");
        }
    }

    private function Populate_Event_Type_Properties()
    {
        if(!is_null($this->verified_event_type_id))
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Event_Types` WHERE `type_id` = '".$this->verified_event_type_id."'");
            while($row = mysqli_fetch_assoc($results))
            {
                $this->name = $row['description'];
            }
        }else
        {
            throw \Exception("Trying to Load_Properties before giving a unique id");
        }
    }

    public function Verify_Event_Type_ID($id_to_verify)
    {
        if($this->Does_Event_Type_Exist($id_to_verify))
        {
            $this->verified_event_type_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_event_type_id = null;
            return false;
        }
    }

    public function Does_Event_Type_Exist($unverified_event_type_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Event_Types` WHERE `type_id` = '".$unverified_event_type_id."'");
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

    public function Get_Event_Type_ID()
    {
        return $this->verified_event_type_id;
    }

    public function Get_Event_Description()
    {
        return $this->name;
    }

    public function Set_Event_Type_Description($description)
    {
        if($description == ""){return false;}
        $this->name = $description;
        $this->Update_Event_Type();
    }

    private function Update_Event_Type()
    {
        if(is_null($this->verified_event_type_id))
        {
            return false;
        }
        $description = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Event_Description());
        return $this->dblink->ExecuteSQLQuery("UPDATE `Event_Types` SET `description` = '".$description."' WHERE `type_id` = '".$this->verified_event_type_id."'");
    }

    public function Create_Event_Type($name = "")
    {        
        if(!is_null($this->verified_event_type_id))
        {
            return false;
        }
        if($name != "")
        {
            $this->Set_Event_Type_Description($name);
        }
        $description = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Event_Description());
        if($this->dblink->ExecuteSQLQuery("INSERT INTO `Event_Types` SET `description` = '".$description."'"))
        {
            return $this->Verify_Event_Type_ID($this->dblink->GetLastInsertID());
        }else
        {
            return false;
        }
    }

    public function Delete_Event_Type()
    {
        if(is_null($this->verified_event_type_id))
        {
            return false;
        }
        if($this->dblink->ExecuteSQLQuery("UPDATE `Event_Types` SET `Active_Status` = '0' WHERE `type_id` = '".$this->verified_event_type_id."'"))
        {
            return true;
        }else
        {
            return false;
        }
    }
}

class Event
{
    private $verified_event_id;
    public $customer;
    private $confirmed;
    private $tentative;
    private $start_time;
    private $end_time;
    private $event_location;
    public $event_type;
    private $guest_count;
    private $parking_instructions;
    public $dblink;

    function __construct($unverified_event_id = NULL)
    {
        $this->verified_event_id = null;
        $this->customer = null;
        $this->confirmed = false;
        $this->tentative = false;
        $this->start_time = null;
        $this->end_time = null;
        $this->event_location = "";
        $this->event_type = null;
        $this->guest_count = 0;
        $this->parking_instructions = "";
        global $dblink;
        $this->dblink = $dblink;
        if(!empty($unverified_event_id)){$this->Load_Properties($unverified_event_id);}
    }

    private function Load_Properties($unverified_event_id)
    {
        if($this->Verify_Event_ID($unverified_event_id))
        {
            $this->Populate_Event_Properties();
        }else
        {
            throw new Event_Does_Not_Exist("The unverified_event_id does not coorospond to a verified_event_id");
        }
    }

    private function Populate_Event_Properties()
    {
        if(!is_null($this->verified_event_id))
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Events` WHERE `Event_ID` = '".$this->verified_event_id."'");
            while($row = mysqli_fetch_assoc($results))
            {
                $this->customer = new \company_program\Customer($row['customer_id']);
                $this->confirmed = $row['confirmed'];
                $this->tentative = $row['tentative'];
                $this->start_time = $row['start_time'];
                $this->end_time = $row['end_time'];
                $this->event_location = $row['event_location'];
                $this->event_type = new \company_program\Event_Type($row['event_type']);
                $this->guest_count = $row['guest_count'];
                $this->parking_instructions = $row['parking_instructions'];
            }
        }else
        {
            throw \Exception("Trying to Load_Properties before giving a unique id");
        }
    }

    public function Verify_Event_ID($id_to_verify)
    {
        if($this->Does_Event_Exist($id_to_verify))
        {
            $this->verified_event_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_event_id = null;
            return false;
        }
    }

    public function Does_Event_Exist($unverified_event_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Events` WHERE `Event_ID` = '".$unverified_event_id."'");
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

    public function Get_Event_ID()
    {
        return $this->verified_event_id;
    }

    public function Get_Customer()
    {
        return $this->customer;
    }

    public function Get_Confirmed()
    {
        return $this->confirmed;
    }

    public function Get_Tentative()
    {
        return $this->tentative;
    }

    public function Get_Start_Time()
    {
        return $this->start_time;
    }

    public function Get_End_Time()
    {
        return $this->end_time;
    }

    public function Get_Event_Location()
    {
        return $this->event_location;
    }

    public function Get_Guest_Count()
    {
        return $this->guest_count;
    }

    public function Get_Parking_Instructions()
    {
        return $this->parking_instructions;
    }

    public function Set_Customer($customer)
    {
        if(!$customer instanceof Customer){return false;}
        $this->customer = $customer;
        $this->Update_Event();
    }

    public function Set_Confirmed($confirmed)
    {
        $this->confirmed = (int) $confirmed;
        $this->Update_Event();
    }

    public function Set_Tentative($tentative)
    {
        $this->tentative = (int) $tentative;
        $this->Update_Event();
    }

    public function Set_Start_Time($start_time)
    {
        $this->start_time = date("Y-m-d H:i:s",strtotime($start_time));
        $this->Update_Event();
    }

    public function Set_End_Time($end_time)
    {
        $this->end_time = date("Y-m-d H:i:s",strtotime($end_time));
        $this->Update_Event();
    }

    public function Set_Event_Location($event_location)
    {
        $this->event_location = $event_location;
        $this->Update_Event();
    }

    public function Set_Event_Type($event_type)
    {
        if(!$event_type instanceof Event_Type){return false;}
        $this->event_type = $event_type;
        $this->Update_Event();
    }

    public function Set_Guest_Count($guest_count)
    {
        $this->guest_count = (int) $guest_count;
        $this->Update_Event();
    }

    public function Set_Parking_Instructions($parking_instructions)
    {
        $this->parking_instructions = $parking_instructions;
        $this->Update_Event();
    }

    private function Update_Event()
    {
        if(is_null($this->verified_event_id)){ return false;}
        $event_location = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Event_Location());
        $parking_instructions = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Parking_Instructions());
        return $this->dblink->ExecuteSQLQuery("UPDATE `Events` SET `customer_id` = '".$this->customer->Get_Customer_ID()."', `confirmed` = '".$this->Get_Confirmed()."', `tentative` = '".$this->Get_Tentative()."', `start_time` = '".$this->Get_Start_Time()."', `end_time` = '".$this->Get_End_Time()."', `event_location` = '".$event_location."', `event_type` = '".$this->event_type->Get_Event_Type_ID()."', `guest_count` = '".$this->Get_Guest_Count()."', `parking_instructions` = '".$parking_instructions."' WHERE `event_id` = '".$this->verified_event_id."'");
    }

    public function Create_Event()
    {        
        if(is_null($this->verified_event_id))
        {
            $event_location = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Event_Location());
            $parking_instructions = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Parking_Instructions());
                if($this->dblink->ExecuteSQLQuery("INSERT INTO `Events` SET `customer_id` = '".$this->customer->Get_Customer_ID()."', `confirmed` = '".$this->Get_Confirmed()."', `tentative` = '".$this->Get_Tentative()."', `start_time` = '".$this->Get_Start_Time()."', `end_time` = '".$this->Get_End_Time()."', `event_location` = '".$event_location."', `event_type` = '".$this->event_type->Get_Event_Type_ID()."', `guest_count` = '".$this->Get_Guest_Count()."', `parking_instructions` = '".$parking_instructions."'"))
            {
                return $this->Verify_Event_ID($this->dblink->GetLastInsertID());
            }else
            {
                return false;
            }
        }else
        {
            return false;
        }
    }

    public function Delete_Event()
    {
        if(!is_null($this->verified_event_id))
        {
            if($this->dblink->ExecuteSQLQuery("UPDATE `Events` SET `Active_Status` = '0' WHERE `event_id` = '".$this->verified_event_id."'"))
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

class Events
{
    public $events;
    private $dblink;
    
    function __construct()
    {
        $this->events = array();
        global $dblink;
        $this->dblink = $dblink;
        $this->Load_Events();
    }

    private function Load_Events()
    {
        $events = $this->Get_SQL_Events_Search();
        while($row = mysqli_fetch_assoc($events))
        {
            $this->Load_Event(new Event($row['event_id']));
        }
    }

    private function Get_SQL_Events_Search()
    {
        return $this->dblink->ExecuteSQLQuery("SELECT * FROM `Events` WHERE `Active_Status` = '1'");
    }

    private function Load_Event($event)
    {
        if($event instanceof Event)
        {
            if(is_null($event->Get_Event_ID()))
            {
                return false;
            }
            $this->events[$event->Get_Event_ID()] = $event;
        }else
        {
            return false;
        }
    }
}

class Event_Types
{
    public $event_types;
    private $dblink;
    
    function __construct()
    {
        $this->event_types = array();
        global $dblink;
        $this->dblink = $dblink;
        $this->Load_Event_Types();
    }

    private function Load_Event_Types()
    {
        $event_types = $this->Get_SQL_Event_Types_Search();
        while($row = mysqli_fetch_assoc($event_types))
        {
            $this->Load_Event_Type(new Event_Type($row['type_id']));
        }
    }

    private function Get_SQL_Event_Types_Search()
    {
        return $this->dblink->ExecuteSQLQuery("SELECT * FROM `Event_Types` WHERE `Active_Status` = '1'");
    }

    private function Load_Event_Type($event_type)
    {
        if($event_type instanceof Event_Type)
        {
            if(is_null($event_type->Get_Event_Type_ID()))
            {
                return false;
            }
            $this->event_types[$event_type->Get_Event_Type_ID()] = $event_type;
        }else
        {
            return false;
        }
    }
}
?>