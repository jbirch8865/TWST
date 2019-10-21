<?php
namespace company_program;
$cConfigs = new \config\ConfigurationFile();
$dbLink = new \DatabaseLink\MySQLLink($cConfigs->Configurations()['database_name']);
class Equipment_SubType
{
    private $verified_subtype_id;
    private $subtype_name;
    public $type_id;
    private $dblink;

    function __construct($unverified_subtype_id=NULL)
    {
        $this->verified_subtype_id = null;
        $this->subtype_name = null;
        $this->type_id = null;
        global $dbLink;
        $this->dblink = $dbLink;
        if(!is_null($unverified_subtype_id))
        {
            $this->Load_Subtype($unverified_subtype_id);
            return true;
        }else
        {
            return false;
        }
    }

    private function Load_Subtype($unverified_subtype_id)
    {
        if($this->Verify_Subtype_ID($unverified_subtype_id))
        {
            $this->Populate_Subtype_Properties();
        }else
        {
            throw new Equipment_Subtype_Does_Not_Exist("The unverified_subtype_id does not coorospond to a verified_subtype_id");
        }
    }

    private function Does_Subtype_Exist($unverified_subtype_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Equipment_Subtypes` INNER JOIN `Equipment_Types` ON `Equipment_Types`.`Type_ID` = `Equipment_Subtypes`.`Type_ID` WHERE `subtype_id` = '".$unverified_subtype_id."' AND `Equipment_Subtypes`.`Active_Status` = '1' AND `Equipment_Types`.`Active_Status` = '1'");
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

    private function Verify_Subtype_ID($id_to_verify)
    {
        if($this->Does_Subtype_Exist($id_to_verify))
        {
            $this->verified_subtype_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_subtype_id = null;
            return false;
        }
    }

    private function Populate_Subtype_Properties()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Equipment_Subtypes` WHERE `subtype_id` = '".$this->verified_subtype_id."'");
        while($row = mysqli_fetch_assoc($results))
        {
            $this->subtype_name = $row['subtype_name'];
            $type = new Equipment_Type($row['type_id']);
            $this->type_id = $type;
        }
    }

    public function Get_Subtype_Name()
    {
        return $this->subtype_name;
    }

    public function Get_Subtype_Id()
    {
        return $this->verified_subtype_id;
    }

    public function Get_Type_Id()
    {
        return $this->type_id->Get_Type_Id();
    }

    public function Set_Subtype_Name($subtype_name)
    {
        if($subtype_name == ""){return false;}
        $this->subtype_name = ucwords($subtype_name);
        $this->Update_Subtype();
    }

    public function Set_Type_Id($type_id)
    {
        if($this->Validate_Type_Id($type_id))
        {
            $type = new Equipment_Type($type_id);
            $this->type_id = $type;
            return $this->Update_Subtype();
        }else
        {
            return false;
        }
    }

    private function Validate_Type_Id($type_id)
    {
        if($type = new \company_program\Equipment_Type($type_id))
        {
            return true;
        }else
        {
            return false;
        }
    }

    private function Update_Subtype()
    {
        if(is_null($this->verified_subtype_id)){ return false;}
        $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Subtype_Name());
        if($results = $this->dblink->ExecuteSQLQuery("UPDATE `Equipment_Subtypes` SET `subtype_name` = '".$name."', `type_id` = '".$this->Get_Type_Id()."' WHERE `subtype_id` = '".$this->verified_subtype_id."'"))
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function Create_Subtype()
    {
        if(is_null($this->verified_subtype_id))
        {
            $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Subtype_Name());
            if($this->dblink->ExecuteSQLQuery("INSERT INTO `Equipment_Subtypes` SET `subtype_name` = '".$name."', `type_id` = '".$this->Get_Type_Id()."'"))
            {
                return $this->Verify_Subtype_ID($this->dblink->GetLastInsertID());
            }else
            {
                return false;
            }
            
        }else
        {
            return false;
        }
    }

    public function Delete_Subtype()
    {
        if(!is_null($this->verified_subtype_id))
        {
            if($this->dblink->ExecuteSQLQuery("UPDATE `Equipment_Subtypes` SET `Active_Status` = '0' WHERE `subtype_id` = '".$this->verified_subtype_id."'"))
            {
                return true;
            }else{
                return false;
            }
        }
    }
}

class Equipment_Type
{
    private $verified_type_id;
    private $type_name;
    private $dblink;

    function __construct($unverified_type_id=NULL)
    {
        $this->verified_type_id = null;
        $this->type_name = null;
        global $dbLink;
        $this->dblink = $dbLink;
        if(!is_null($unverified_type_id))
        {
            $this->Load_type($unverified_type_id);
            return true;
        }else
        {
            return false;
        }
    }

    private function Load_type($unverified_type_id)
    {
        if($this->Verify_type_ID($unverified_type_id))
        {
            $this->Populate_type_Properties();
        }else
        {
            throw new Equipment_Type_Does_Not_Exist("The unverified_type_id does not coorospond to a verified_type_id");
        }
    }

    private function Does_type_Exist($unverified_type_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Equipment_Types` WHERE `type_id` = '".$unverified_type_id."' AND `Active_Status` = '1'");
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

    private function Verify_type_ID($id_to_verify)
    {
        if($this->Does_type_Exist($id_to_verify))
        {
            $this->verified_type_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_type_id = null;
            return false;
        }
    }

    private function Populate_type_Properties()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Equipment_Types` WHERE `type_id` = '".$this->verified_type_id."'");
        while($row = mysqli_fetch_assoc($results))
        {
            $this->Set_Type_Name($row['description']);
        }
    }

    public function Get_Type_Name()
    {
        return $this->type_name;
    }

    public function Get_Type_Id()
    {
        return $this->verified_type_id;
    }

    public function Set_Type_Name($type_name)
    {
        if($type_name == ""){return false;}
        $this->type_name = ucwords($type_name);
        $this->Update_Type();
    }

    private function Update_Type()
    {
        if(is_null($this->verified_type_id)){ return false;}
        $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Type_Name());
        if($results = $this->dblink->ExecuteSQLQuery("UPDATE `Equipment_Types` SET `description` = '".$name."' WHERE `type_id` = '".$this->verified_type_id."'"))
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function Create_Type($type_name)
    {
        if(is_null($this->verified_type_id) && !empty($type_name))
        {
            $this->Set_Type_Name($type_name);
            $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Type_Name());
            if($this->dblink->ExecuteSQLQuery("INSERT INTO `Equipment_Types` SET `description` = '".$name."'"))
            {
                return $this->Verify_Type_ID($this->dblink->GetLastInsertID());
            }else
            {
                return false;
            }
            
        }else
        {
            return false;
        }
    }

    public function Delete_Type()
    {
        if(!is_null($this->verified_type_id))
        {
            if($this->dblink->ExecuteSQLQuery("UPDATE `Equipment_Types` SET `Active_Status` = '0' WHERE `type_id` = '".$this->verified_type_id."'"))
            {
                return true;
            }else{
                return false;
            }
        }
    }
}

class Equipment
{
    private $verified_equipment_id;
    private $equipment_title;
    public $equipment_subtype;
    private $ooc;
    private $dblink;

    function __construct($unverified_equipment_id=NULL)
    {
        $this->verified_equipment_id = null;
        $this->equipment_title = null;
        $this->equipment_subtype = null;
        $this->ooc = 0;
        global $dbLink;
        $this->dblink = $dbLink;

        if(!is_null($unverified_equipment_id))
        {
            $this->Load_Equipment($unverified_equipment_id);
            return true;
        }else
        {
            return false;
        }
    }

    private function Load_Equipment($unverified_equipment_id)
    {
        if($this->Verify_Equipment_ID($unverified_equipment_id))
        {
            $this->Populate_Equipment_Properties();
        }else
        {
            throw new Equipment_Does_Not_Exist("The unverified_subtype_id does not coorospond to a verified_subtype_id");
        }
    }

    private function Does_Equipment_Exist($unverified_equipment_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Equipment` WHERE `equipment_id` = '".$unverified_equipment_id."'");
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

    private function Verify_Equipment_ID($id_to_verify)
    {
        if($this->Does_Equipment_Exist($id_to_verify))
        {
            $this->verified_equipment_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_equipment_id = null;
            return false;
        }
    }

    private function Populate_Equipment_Properties()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Equipment` WHERE `equipment_id` = '".$this->verified_equipment_id."'");
        while($row = mysqli_fetch_assoc($results))
        {
            $this->equipment_title = $row['title'];
            $subtype = new Equipment_SubType($row['equipment_subtype']);
            $this->equipment_subtype = $subtype;
            $this->ooc = $row['ooc'];
        }
    }

    private function Set_OOC($ooc)
    {
        if(!is_int($ooc)){return false;}
        $this->ooc = $ooc;
        $this->Update_Equipment();
    }

    public function Set_Out_Of_Commission()
    {
        $this->Set_OOC(1);
        return $this->Update_Equipment();
    }

    public function Set_In_Commission()
    {
        $this->Set_OOC(0);
        return $this->Update_Equipment();
    }

    public function Get_OOC()
    {
        return $this->ooc;
    }

    public function Get_Equipment_Name()
    {
        return $this->equipment_title;
    }

    public function Get_Equipment_Id()
    {
        return $this->verified_equipment_id;
    }

    public function Get_Equipment_Subtype_Id()
    {
        return $this->equipment_subtype->Get_Subtype_Id();
    }

    public function Set_Equipment_Name($equipment_name)
    {
        if($equipment_name == ""){return false;}
        $this->equipment_title = ucwords($equipment_name);
        $this->Update_Equipment();
    }

    public function Set_Equipment_Subtype_From_Id($subtype_id)
    {
        if($this->Validate_Equipment_Subtype_Id($subtype_id))
        {
            $subtype = new Equipment_SubType($subtype_id);
            $this->equipment_subtype = $subtype;
            $this->Update_Equipment();
            return true;
        }else
        {
            return false;
        }
    }

    public function Who_Owns_This_Equipment()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT `Person_ID` FROM `Person_Has_Equipment` WHERE `Equipment_ID` = '".$this->verified_equipment_id."'");
        if(mysqli_num_rows($results) == 1)
        {
            $row = mysqli_fetch_assoc($results);
            $person = new \company_program\Employee($row['Person_ID']);
            return $person;
        }else
        {
            return false;
        }
    }

    public function Remove_Equipment_Owner()
    {
        $results = $this->dblink->ExecuteSQLQuery("DELETE FROM `Person_Has_Equipment` WHERE `Equipment_ID` = '".$this->verified_equipment_id."'");
    }

    private function Validate_Equipment_Subtype_Id($subtype_id)
    {
        if($subtype_id = new \company_program\Equipment_SubType($subtype_id))
        {
            return true;
        }else
        {
            return false;
        }
    }

    private function Update_Equipment()
    {
        if(is_null($this->verified_equipment_id)){ return false;}
        $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Equipment_Name());
        if($results = $this->dblink->ExecuteSQLQuery("UPDATE `Equipment` SET `title` = '".$name."', `equipment_subtype` = '".$this->Get_Equipment_Subtype_Id()."', `ooc` = '".$this->Get_OOC()."' WHERE `equipment_id` = '".$this->verified_equipment_id."'"))
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function Create_Equipment()
    {
        if(is_null($this->verified_equipment_id))
        {
            $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Equipment_Name());
            if($this->dblink->ExecuteSQLQuery("INSERT INTO `Equipment` SET `title` = '".$name."', `equipment_subtype` = '".$this->Get_Equipment_Subtype_Id()."', `ooc` = '".$this->Get_OOC()."'"))
            {
                return $this->Verify_Equipment_ID($this->dblink->GetLastInsertID());
            }else
            {
                return false;
            }
            
        }else
        {
            return false;
        }
    }

    public function Delete_Equipment()
    {
        if(!is_null($this->verified_equipment_id))
        {
            if($this->dblink->ExecuteSQLQuery("UPDATE `Equipment` SET `Active_Status` = '0' WHERE `equipment_id` = '".$this->verified_equipment_id."'"))
            {
                return true;
            }else{
                return false;
            }
        }else
        {
            return false;
        }
    }
}

class All_Equipment
{
    public $all_equipment;
    private $db_name;
    private $dbLink;
    
    function __construct()
    {
        $this->all_equipment = array();
        $dbname = new \gStatic_Variables;
        $this->db_name = $dbname->db_name;
        $this->Load_All_Equipment();
    }

    private function Load_All_Equipment()
    {
        $equipment = $this->Get_SQL_Equipment_Search();
        while($row = mysqli_fetch_assoc($equipment))
        {
            $this->Load_Equipment(new Equipment($row['equipment_id']));
        }
    }

    private function Get_SQL_Equipment_Search()
    {
        global $dbLink;
        $results = $dbLink->ExecuteSQLQuery("SELECT * FROM `Equipment` INNER JOIN `Equipment_Subtypes` ON `Equipment_Subtypes`.`Subtype_ID` = `Equipment`.`equipment_subtype` INNER JOIN `Equipment_Types` ON `Equipment_Types`.`Type_ID` = `Equipment_Subtypes`.`Type_ID` WHERE `Equipment`.`Active_Status` = '1' AND `Equipment_Subtypes`.`Active_Status` = '1' AND `Equipment_Types`.`Active_Status` = '1'");
        return $results;
    }

    private function Load_Equipment($equipment)
    {
        if($equipment instanceof Equipment)
        {
            if(is_null($equipment->Get_Equipment_Id()))
            {
                return false;
            }
            $this->all_equipment[$equipment->Get_Equipment_Id()] = $equipment;
        }else
        {
            return false;
        }
    }
}

class All_Equipment_Subtypes
{
    public $all_equipment_subtypes;
    private $db_name;
    
    function __construct()
    {
        $this->all_equipment_subtypes = array();
        $dbname = new \gStatic_Variables;
        $this->db_name = $dbname->db_name;
        $this->Load_All_Equipment_Subtypes();
    }

    private function Load_All_Equipment_Subtypes()
    {
        $subtypes = $this->Get_SQL_Equipment_Search();
        while($row = mysqli_fetch_assoc($subtypes))
        {
            $this->Load_Equipment_Subtypes(new Equipment_SubType($row['subtype_id']));
        }
    }

    private function Get_SQL_Equipment_Search()
    {
        global $dbLink;
        $results = $dbLink->ExecuteSQLQuery("SELECT * FROM `Equipment_Subtypes` INNER JOIN `Equipment_Types` ON `Equipment_Types`.`Type_ID` = `Equipment_Subtypes`.`Type_ID` WHERE `Equipment_Subtypes`.`Active_Status` = '1' AND `Equipment_Types`.`Active_Status` = '1'");
        return $results;
    }

    private function Load_Equipment_Subtypes($subtype)
    {
        if($subtype instanceof Equipment_SubType)
        {
            if(is_null($subtype->Get_Subtype_Id()))
            {
                return false;
            }
            $this->all_equipment_subtypes[$subtype->Get_Subtype_Id()] = $subtype;
        }else
        {
            return false;
        }
    }
}

class All_Equipment_Types
{
    public $all_equipment_types;
    private $db_name;
    
    function __construct()
    {
        $this->all_equipment_types = array();
        $dbname = new \gStatic_Variables;
        $this->db_name = $dbname->db_name;
        $this->Load_All_Equipment_Types();
    }

    private function Load_All_Equipment_Types()
    {
        $types = $this->Get_SQL_Equipment_Search();
        while($row = mysqli_fetch_assoc($types))
        {
            $this->Load_Equipment_Types(new Equipment_Type($row['type_id']));
        }
    }

    private function Get_SQL_Equipment_Search()
    {
        global $dbLink;
        $results = $dbLink->ExecuteSQLQuery("SELECT * FROM `Equipment_Types` WHERE `Active_Status` = '1'");
        return $results;
    }

    private function Load_Equipment_Types($type)
    {
        if($type instanceof Equipment_Type)
        {
            if(is_null($type->Get_Type_Id()))
            {
                return false;
            }
            $this->all_equipment_types[$type->Get_Type_Id()] = $type;
        }else
        {
            return false;
        }
    }
}
?>