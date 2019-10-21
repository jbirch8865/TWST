<?php
class gCurrent_User
{
    public $current_user;

    function __construct()
    {
        $this->current_user = new \company_program\Current_User;
    }
}

class gaAlerts
{
    public $aAlerts;

    function __construct()
    {
        $this->aAlerts = new \company_program\Alerts;
    }
}

class gcConfigs
{
    public $cConfigs;

    function __construct()
    {
        $this->cConfigs = new \config\ConfigurationFile();
    }
}

class gStatic_Variables
{
    public $db_name;

    function __construct()
    {
        $config = new gcConfigs;
        $this->db_name = $config->cConfigs->Name_Of_Project_Database();
    }
}
?>