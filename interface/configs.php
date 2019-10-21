<?php
require 'PageTop.php';
$current_user->current_user->Exit_If_Not_Currently_Authenticated();
if($cConfigs->cConfigs->Is_Dev())
{
    echo '<br>';
    echo '<a type = "button" class = "btn btn-danger" href = "scripts/UpdateServer.php">Update Now</a>';
}
?>
<?php
$table = new \bootstrap\table();
$table_headers = new \bootstrap\Table_Header;
$table_headers->Add_Header("Key");
$table_headers->Add_Header("Value");
$table_headers->Close_Header();
$table_body = new \bootstrap\Table_Body("config_file");
$config_list = $cConfigs->cConfigs->Configurations();
ForEach($config_list as $key => $value)
{
    $table_row = new \bootstrap\Table_Row(2,array($key,$value),array("config_file_row"));
}
$table_body->Close_Body();
$table->Close_Table();
?>
<?php
require 'PageBottom.php';
?>