<?php
class User_Test extends \PHPUnit\Framework\TestCase
{
    private $configs;

	public function setUp() :void
	{
        $this->configs = new \config\ConfigurationFile();
	}
    
    function test_Delete_Department_To_User()
    {
        $user = new \company_program\User;
        $user->Set_Username($this->configs->Configurations()['test_username']);
        if($user->Get_User_ID() == "")
        {
            throw new \Exception("These tests have not processed correctlt");
        }
        $user->Unassign_Department('5');
        $this->assertTrue(empty($user->Get_Array_Of_Departments()));        
    }
    
    function test_Add_Department_To_User()
	{   
        $user = new \company_program\User;
        $user->Set_Username($this->configs->Configurations()['test_username']);
        if($user->Get_User_ID() == "")
        {
            throw new \Exception("These tests have not processed correctlt");
        }
        $user->Assign_Department('5');
        $this->assertTrue(!empty($user->Get_Array_Of_Departments()));
        $this->assertEquals('5',$user->Get_Array_Of_Departments()[0]);
        //$this->Create_Shit_Load_Of_Users();
    }

    function Create_Shit_Load_Of_Users()
    {
        $i = 0;
        While($i < 50)
        {
            $this->Create_User("User_".$i);
            $i = $i + 1;
        }

    }

    function Create_User($username)
    {
        $user = new \company_program\User;
        $user->Set_Username($username);
        $user->Set_Password('Test_A_Password');
        $this->assertTrue($user->Create_User());
    }
    
}

?>