<?php
require '../PageTop.php';
$current_user->current_user->Exit_If_Not_Currently_Authenticated();
print_r($_POST);
if(!empty($_POST['username']) && !empty($_POST['department']) && !empty($_POST['checked']))
{
    $user = new \company_program\User;
    if($user->Set_Username($_POST['username']))
    {
        if($_POST['checked'] == "true")
        {
            $user->Unassign_Department($_POST['department']);
        }else
        {
            $user->Assign_Department($_POST['department']);
        }
    }else
    {
        throw new \Exception("Sorry not a valid user");
    }

}else
{
    throw new \Exception('$_POST["username"] OR $_POST["department"] does not exist');
}
?>
