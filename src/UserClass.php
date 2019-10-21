<?php
namespace company_program;

class Current_User extends \User_Session\Current_User
{
    function __construct()
    {
        parent::__construct();        
    }

    function Create_User($username)
    {
        $user_to_create = new \User_Session\User_Session;
        $user_to_create->Set_Username($username);
        $cConfigs = new \config\ConfigurationFile();
        $user_to_create->Set_Password($cConfigs->Configurations()['default_password']);
        try
        {
            $user_to_create->Create_User();
        } catch (\User_Session\User_Already_Exists $e)
        {
            throw new \User_Session\User_Already_Exists($e->getMessage());
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
        $this->Log_Create_User($username);
    }

    function Delete_User($username)
    {
        $user = new \User_Session\User_Session;
        $user->Set_Username($username);
        if($user->Get_User_ID() == "")
        {
            throw new \Exception("Sorry this user doesn't exist");
        }else
        {
            $user->Delete_User();
            $this->Log_Delete_User($username);
        }
    }

    function LogOut()
    {
        $this->Log_Logout();
        parent::LogOut();    
    }

    function Change_Password($password)
    {
        parent::Change_Password($password);
        $this->Log_Change_Password();
    }

    function Exit_If_Not_Currently_Authenticated($message = "",$throw_exception = true)
    {
        try
        {
            parent::Exit_If_Not_Currently_Authenticated($message,true);
        } catch (\User_Session\User_Session_Expired $e)
        {
            $aAlerts = new \gaAlerts;
            $aAlerts->aAlerts->Add_Alert("Login Expired ","Your session has expired, please log back in.");
            parent::Exit_If_Not_Currently_Authenticated($message,false);
        }
    }
    
    private function Log_Action($message, $type)
    {
        try
        {
            $userid = $this->Get_User_ID();
            $this->Get_DBLink()->ExecuteSQLQuery("INSERT INTO `Log` SET `person_id` = '".$userid."', `log_entry` = '".$message."', `log_type` = '".$type."'");
            $this->Log_To_Console($message);
        } catch (\Exception $e)
        {
            //don't error out, continue processing
        }
    }

    public function Authenticate()
    {
        $this->Log_Login();
        return parent::Authenticate();
    }

    private function Log_Login()
    {
        $this->Log_Action($this->Get_Username()." logged in", 4);
    }

    public function Log_Logout()
    {
        $this->Log_Action($this->Get_Username()." logged out", 5);
    }

    private function Log_Delete_User($username)
    {
        $this->Log_Action($this->Get_Username()." Deleted User ".$username,3);
    }

    public function Log_Change_Password()
    {
        $this->Log_Action($this->Get_Username()." Updated Password",2);
    }
    
    private function Log_Create_User($username)
    {
        $this->Log_Action($this->Get_Username()." created user - ".$username,1);
    }

    public function Log_Update_Config($key,$value)
    {
        $this->Log_Action($this->Get_Username()." updated config key ".$key." with the value ".$value,6);
    }

    private function Log_To_Console($message)
    {
        $log = new \logging\Log_To_Console($message);
    }

    public function Is_Management()
    {
        $sql = $this->Get_DBLink()->ExecuteSQLQuery("SELECT * FROM `User_Belongs_To_Department` WHERE `person_id` = '".$this->Get_User_ID()."' AND `department_id` = '5'");
        if(mysqli_num_rows($sql) == 1)
        {
            return true;
        }else
        {
            return false;
        }
    }
}

class User extends \User_Session\User_Session
{

    function __construct()
    {
        parent::__construct();
    }

    public function Get_Array_Of_Departments()
    {
        if($this->Get_User_ID() == "")
        {
            throw new \Exception("Sorry person_id hasn't been set");
        }
        $departments = array();
        $department = $this->Get_DBLink()->ExecuteSQLQuery("SELECT `Departments`.`department_id`, `department_name` FROM `User_Belongs_To_Department` INNER JOIN `Departments` on `Departments`.`department_id` = `User_Belongs_To_Department`.`department_id` WHERE `person_id` = '".$this->Get_User_ID()."'");
        while($row = mysqli_fetch_assoc($department))
        {
            $departments[] = $row['department_id'];
        }
        return $departments;
    }

    public function Assign_Department($department_id)
    {
        if($this->Get_User_ID() == "")
        {
            throw new \Exception("Sorry person_id hasn't been set");
        }
        $this->Get_DBLink()->ExecuteSQLQuery("INSERT INTO `User_Belongs_To_Department` SET person_id = '".$this->Get_User_ID()."', department_id = '".$department_id."'");        
    }

    public function Unassign_Department($department_id)
    {
        if($this->Get_User_ID() == "")
        {
            throw new \Exception("Sorry person_id hasn't been set");
        }
        $this->Get_DBLink()->ExecuteSQLQuery("DELETE FROM `User_Belongs_To_Department` WHERE person_id = '".$this->Get_User_ID()."' AND department_id = '".$department_id."'");        
    }
}

class All_Users
{
    private $users;
    private $dblink;

    function __construct()
    {
        $configs = new \config\ConfigurationFile;
        $this->dblink = new \DatabaseLink\MySQLLink($configs->Configurations()["database_name"]);    
    }

    public function Load_All_Users()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Users` WHERE `Active_Status` = '1'");
        While($row = mysqli_fetch_assoc($results))
        {
            $this->users[] = new \company_program\User;
            $this->users[count($this->users) - 1]->Set_Username($row['username']);
            $this->users[count($this->users) - 1]->Set_User_ID($row['person_id']);
        }
    }
    public function Get_All_Users()
    {
        if(is_null($this->users))
        {
            return array();
        }else
        {
            return $this->users;
        }
    }
}
?>