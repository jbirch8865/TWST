<?php

function Get_Today($format = "Y-m-d")
{
    return date($format);
}

function Get_Tomorrow($format = "Y-m-d")
{
    return date($format,strtotime("+1 days"));
}

function Get_Date_After_This_Many_Days($days, $format = "Y-m-d")
{
    return date($format,strtotime("+".$days." days"));
}

function Assign_Person_With_Skill($employee, $skill)
{
    if($employee instanceof \company_program\Employee && $skill instanceof \company_program\Employee_Skill)
    {
        $employee_id = $employee->Get_Employee_ID();
        $skill_id = $skill->Get_Skill_ID();
        if(!empty($employee_id) && !empty($skill_id))
        { 
            try
            {
                if($employee->dblink->ExecuteSQLQuery("INSERT INTO `Person_Has_Skill` SET `Person_ID` = '".$employee_id."', `Skill_ID` = '".$skill_id."'"))
                {
                    return true;
                }else
                {
                    return false;
                }
            }catch(\DatabaseLink\DuplicatePrimaryKeyRequest $e)
            {
                return true;
            }
        }else
        {
            throw new \Exception("Skill or Employee class not properly loaded, invalid id's");
        }
    }else
    {
        throw new \Exception('$employee or $skill not proper class instances');
    }
}

function White_Plus_Icon($data_target)
{
    return '<img class = "add_icon" src = "../images/add.png" height = "35px;" width = "35px;" data-target="'.$data_target.'">';
}

function White_Minus_Icon($data_target)
{
    return '<img class = "minus_icon" src = "../images/minus.png" height = "35px;" width = "35px;" data-target="'.$data_target.'">';
}

function Delete_Minus_Icon($data_target)
{
    return '<img class = "minus_icon" src = "../images/delete.png" height = "35px;" width = "35px;" data-target="'.$data_target.'">';
}

function Send_Icon()
{
    return '<img class = "send_icon" src = "../images/send_sms.jpg" height = "35px;" width = "35px;">';

}
?>