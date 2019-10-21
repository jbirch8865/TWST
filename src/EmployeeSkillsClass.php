<?php
namespace company_program;
$cConfigs = new \config\ConfigurationFile();
$dblink = new \DatabaseLink\MySQLLink($cConfigs->Configurations()['database_name']);

class Employee_Skill
{
    private $name;
    private $certificate_required;
    private $verified_skill_id;
    public $dblink;

    function __construct($unverified_skill_id = NULL)
    {
        $this->verified_skill_id = null;
        $this->name = null;
        $this->certificate_required = false;
        global $dblink;
        $this->dblink = $dblink;
        if(!empty($unverified_skill_id)){$this->Load_Properties($unverified_skill_id);}
    }

    private function Load_Properties($unverified_skill_id)
    {
        if($this->Verify_Skill_ID($unverified_skill_id))
        {
            $this->Populate_Employee_Skill_Properties();
        }else
        {
            throw new Skill_Does_Not_Exist("The unverified_skill_id does not coorospond to a verified_skill_id");
        }
    }

    private function Populate_Employee_Skill_Properties()
    {
        if(!is_null($this->verified_skill_id))
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Skills` WHERE `Skill_ID` = '".$this->verified_skill_id."'");
            while($row = mysqli_fetch_assoc($results))
            {
                $this->name = $row['Name'];
                $this->certificate_required['Certificate_Required'];
            }
        }else
        {
            throw \Exception("Trying to Load_Properties before giving a unique id");
        }
    }

    public function Verify_Skill_ID($id_to_verify)
    {
        if($this->Does_Skill_Exist($id_to_verify))
        {
            $this->verified_skill_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_skill_id = null;
            return false;
        }
    }

    public function Does_Skill_Exist($unverified_skill_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Skills` WHERE `Skill_ID` = '".$unverified_skill_id."'");
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

    public function Get_Skill_Name()
    {
        return $this->name;
    }

    public function Get_Skill_ID()
    {
        return $this->verified_skill_id;
    }

    public function Get_Certificate_Required()
    {
        return $this->certificate_required;
    }

    public function Set_Skill_Name($name)
    {
        if($name == ""){return false;}
        $this->name = $name;
        $this->Update_Skill();
    }

    public function Set_Certificate_Required($certificate_required)
    {
        if(!is_bool($certificate_required)){return false;}
        $this->certificate_required = $certificate_required;
        $this->Update_Skill();
    }

    public function Assign_Person($unverified_person_id)
    {
        $person = new \company_program\Employee($unverified_person_id);
        
    }

    private function Update_Skill()
    {
        if(is_null($this->verified_skill_id)){ return false;}
        $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Skill_Name());
        $certificate_required = (int) $this->Get_Certificate_Required();
        return $this->dblink->ExecuteSQLQuery("UPDATE `Skills` SET `Name` = '".$name."', `Certificate_Required` = '".$certificate_required."' WHERE `Skill_ID` = '".$this->verified_skill_id."'");
    }

    public function Create_Skill($name = NULL)
    {        
        if(is_null($this->verified_skill_id) && !empty($name))
        {
            $this->Set_Skill_Name($name);
            $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Skill_Name());
            $certificate_required = (int) $this->Get_Certificate_Required();
            if($this->dblink->ExecuteSQLQuery("INSERT INTO `Skills` SET `Name` = '".$name."', `Certificate_Required` = '".$certificate_required."'"))
            {
                return $this->Verify_Skill_ID($this->dblink->GetLastInsertID());
            }else
            {
                return false;
            }
        }else
        {
            return false;
        }
    }

    public function Delete_Skill()
    {
        if(!is_null($this->verified_skill_id))
        {
            if($this->dblink->ExecuteSQLQuery("UPDATE `Skills` SET `Active_Status` = '0' WHERE `Skill_ID` = '".$this->verified_skill_id."'"))
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

class Employee_Skills
{
    public $skills;
    private $dblink;
    
    function __construct()
    {
        $this->skills = array();
        global $dblink;
        $this->dblink = $dblink;
        $this->Load_Skills();
    }

    private function Load_Skills()
    {
        $skills = $this->Get_SQL_Employee_Skills_Search();
        while($row = mysqli_fetch_assoc($skills))
        {
            $this->Load_Skill(new Employee_Skill($row['Skill_ID']));
        }
    }

    private function Get_SQL_Employee_Skills_Search()
    {
        return $this->dblink->ExecuteSQLQuery("SELECT * FROM `Skills` WHERE `Active_Status` = '1'");
    }

    private function Load_Skill($skill)
    {
        if($skill instanceof Employee_Skill)
        {
            if(is_null($skill->Get_Skill_ID()))
            {
                return false;
            }
            $this->skills[$skill->Get_Skill_ID()] = $skill;
        }else
        {
            return false;
        }
    }
}
?>