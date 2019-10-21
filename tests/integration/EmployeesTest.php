<?php
class Employee_Test extends \PHPUnit\Framework\TestCase
{
    
    function test_Create_Employee()
    {
        $employee = new \company_program\Employee();
        $this->assertTrue($employee->Create_Employee());
        $employee->Delete_Employee();
        //$this->Create_Shitload_Of_Employees();
    }


    function Create_Shitload_Of_Employees()
    {
        $i = 1;
        while($i < 275)
        {
            $employee = new \company_program\Employee();
            $employee->Set_First_Name('Person');
            $employee->Set_Last_Name("$i");
            $employee->Set_Phone_Number('503-828-9847');
            $employee->Set_Email_Address('something@something.com');
            $this->assertTrue($employee->Create_Employee());
            $i = $i + 1;
        }
    }
}

?>