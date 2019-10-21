<?php
namespace company_program;

class Department_Context_Menu extends \bootstrap\context_menu
{
    function __construct()
    {
        parent::__construct("department_context_menu");
        $this->Build_Departments();
        $this->Close_Context_Menu();    
    }

    private function Build_Departments()
    {
        $dbname = new \gStatic_Variables;
        $dblink = new \DatabaseLink\MySQLLink($dbname->db_name);
        $departments = $dblink->ExecuteSQLQuery("SELECT * FROM `Departments`");
        While($row = mysqli_fetch_assoc($departments))
        {
            $this->Add_Action($row['department_name'],array("unique_id" => "Change_User_Department".$row['department_id'],"checked" => false,"department_id" => $row['department_id']));
        }
         $this->Add_Divider();
         $this->Add_Action('Delete',array("delete" => true,"unique_id" => "delete_user_now"),true);
    }  
}

class Customer_Context_Menu extends \bootstrap\context_menu
{
    function __construct()
    {
        parent::__construct("Customer_Context_Menu","Customer_Table_ID");
        $this->Add_Action('Delete',array("delete" => true));
        $this->Close_Context_Menu();    
    }  
}

class Alerts extends \bootstrap\Alerts
{
    function __construct()
    {
        parent::__construct();
    }

    function Add_Alert($strong_text = "Unknown Error ",$error_message = false,$hault_execution = false)
    {
        $cConfigs = new \gcConfigs;
        if(!$error_message){$error_message = "Terribly sorry, we have experienced an unknown error, please log out and log back in.  If the problem persists please submit a bug report to ".$cConfigs->cConfigs->Configurations()['onsite_technician'];}
        parent::Add_Alert($strong_text,$error_message,$hault_execution);
    }
}
?>