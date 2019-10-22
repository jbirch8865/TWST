<?php
class Customer_Test extends \PHPUnit\Framework\TestCase
{
    
    function test_Create_Customer()
    {
        $customer = new \company_program\Customer();
        $this->assertTrue($customer->Create_Customer(uniqid()));
        $customer->Set_Customer_Address('1234 S Road St.&#10;Portland, OR 97215');        
        $this->Create_Contractor($customer);
        $this->assertTrue($customer->Delete_Customer($customer));

        //$this->Create_Shitload_Of_Customers();
    }


    function Create_Shitload_Of_Customers()
    {
        $i = 1;
        while($i < 800)
        {
            $customer = new \company_program\Customer();
            $this->assertTrue($customer->Create_Customer("customer".$i));
            $customer->Set_Customer_Address('1234 S Road St.&#10;Portland, OR 97215');        
            $this->Create_Contractor($customer);
            $i = $i + 1;
        }
    }

    function Create_Contractor($customer)
    {
        $contractor = new \company_program\Contractor();
        $contractor->Set_First_Name('first_name');
        $contractor->Set_Last_Name('last_name');
        $contractor->Set_Phone_Number('503-828-7180');
        $contractor->Set_Email_Address('email_address');
        $this->assertTrue($contractor->Create_Contractor());
        $contractor->Set_Customer_ID($customer->Get_Customer_Id());
        $this->assertTrue($contractor->Delete_Contractor());
        
        
        //$this->Create_Shitload_Of_Contractors($customer);
    }

    function Create_Shitload_Of_Contractors($customer)
    {
        $i = 0;
        While($i < 5)
        {
            $contractor = new \company_program\Contractor();
            $contractor->Set_First_Name($i);
            $contractor->Set_Last_Name('last_name');
            $contractor->Set_Phone_Number('503-828-7180');
            $contractor->Set_Email_Address('email_address');
            $this->assertTrue($contractor->Create_Contractor());
            $this->assertTrue($contractor->Set_Customer_ID($customer->Get_Customer_Id()));    
            $i = $i + 1;
        }
    }

}

?>