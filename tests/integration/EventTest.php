<?php
class Event_Test extends \PHPUnit\Framework\TestCase
{
    
    function test_Create_Event_Type()
    {
        $event = new \company_program\Event_Type();
        $this->assertTrue($event->Create_Event_Type('test'));
        $this->Create_Event($event);
        $event->Delete_Event_Type();
    }

    function Create_Event($event_type)
    {
        $event = new \company_program\Event();
        $event->Set_Confirmed(true);
        $event->Set_Tentative(false);
        $customer = new \company_program\Customer();
        $customer->Create_Customer('test');
        $event->Set_Customer($customer);
        $event->Set_Start_Time('10/23/2019 13:30');
        $event->Set_End_Time('10/23/2019 15:30');
        $event->Set_Event_Type($event_type);
        $event->Set_Guest_Count(20);
        $event->Create_Event();
        $event->Delete_Event();
        $customer->Delete_Customer();
    }
}

?>