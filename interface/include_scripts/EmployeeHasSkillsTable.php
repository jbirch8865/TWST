<?php
    $add_icon_html = White_Plus_Icon("Employee_Has_Skills_Table");
    echo '<p style = "text-align:center;">Skills Assigned:</p>';
    $table = new \bootstrap\table("Table_ID_Employee_Has_Skills");
    $table_headers = new \bootstrap\Table_Header;
    $table_headers->Add_Header('Skill Name '.$add_icon_html);
    $table_headers->Close_Header();
    if(!empty($employee_skills_id))
    {
        $table_body = new \bootstrap\Table_Body($employee_skills_id);
    }else
    {
        $table_body = new \bootstrap\Table_Body("Employee_Has_Skills_Table");
    }
    $table_body->Close_Body();
    $table->Close_Table();
?>