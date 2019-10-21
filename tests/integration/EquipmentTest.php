<?php
class Equipment_Test extends \PHPUnit\Framework\TestCase
{
 
    function test_Create_Equipment_Type()
    {
        $type = new \company_program\Equipment_Type();
        $this->assertTrue($type->Create_Type("test_type"));
        $this->Create_Equipment_Subtype($type);
        $this->assertTrue($type->Delete_Type());
    }

    function Create_Equipment_Subtype($type)
    {
        $subtype = new \company_program\Equipment_SubType();
        $subtype->Set_Subtype_Name('test_subtype');
        $subtype->Set_Type_Id($type->Get_Type_ID());
        $this->assertTrue($subtype->Create_Subtype());
        $this->Create_3_Equipment_Pieces($subtype); 
        $this->assertTrue($subtype->Delete_Subtype());  
    }

    function Create_3_Equipment_Pieces($subtype)
    {
        $equipment1 = new \company_program\Equipment();
        $equipment1->Set_Equipment_Name('test_equipment1');
        $this->assertTrue($equipment1->Set_Equipment_Subtype_From_Id($subtype->Get_Subtype_Id()));
        $equipment1->Set_In_Commission();
        $this->assertTrue($equipment1->Create_Equipment());
       
        $equipment2 = new \company_program\Equipment();
        $equipment2->Set_Equipment_Name('test_equipment2');
        $this->assertTrue($equipment2->Set_Equipment_Subtype_From_Id($subtype->Get_Subtype_Id()));
        $equipment2->Set_Out_Of_Commission();
        $this->assertTrue($equipment2->Create_Equipment());
        
        $equipment3 = new \company_program\Equipment();
        $equipment3->Set_Equipment_Name('test_equipment3');
        $this->assertTrue($equipment3->Set_Equipment_Subtype_From_Id($subtype->Get_Subtype_Id()));
        $equipment3->Set_In_Commission();
        $this->assertTrue($equipment3->Create_Equipment());
        
        $equipment_all = new \company_program\All_Equipment; 
        $this->assertGreaterThanOrEqual(3,count($equipment_all->all_equipment));    

        $this->assertTrue($equipment1->Delete_Equipment());
        $equipment2->Delete_Equipment();
        $equipment3->Delete_Equipment();

        //$this->Create_Shitload_Of_Equipment($subtype);
    }

    function Create_Shitload_Of_Equipment($subtype)
    {
        $i = 0;
        While($i < 750)
        {
            $equipment = new \company_program\Equipment();
            $equipment->Set_Equipment_Name(uniqid());
            $this->assertTrue($equipment->Set_Equipment_Subtype_From_Id($subtype->Get_Subtype_Id()));
            $equipment->Set_In_Commission();
            $this->assertTrue($equipment->Create_Equipment());
            $i = $i+1;
        }

    }
}

?>