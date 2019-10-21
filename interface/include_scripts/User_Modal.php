
<?php
    $table = new \bootstrap\table();
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header("Username");
    $table_headers->Close_Header();
    $table_body = new \bootstrap\Table_Body("modalmt");
    $users = new \company_program\All_Users;
    $users->Load_All_Users();
    $loop_through_Users = $users->Get_All_Users();
    ForEach($loop_through_Users as $user)
    {
        $departments = $user->Get_Array_Of_Departments();
        $table_row = new \bootstrap\Table_Row(1,array($user->Get_Username()),array("username" => $user->Get_Username(),"departments" => $departments));
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>