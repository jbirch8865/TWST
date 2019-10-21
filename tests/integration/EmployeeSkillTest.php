<?php
class Employee_Skill_Test extends \PHPUnit\Framework\TestCase
{
    
    function test_Create_Employee_Skill()
    {
        $skill = new \company_program\Employee_Skill();
        $this->assertTrue($skill->Create_Skill('test'));
        $this->assertTrue($skill->Delete_Skill());
    }
}

?>