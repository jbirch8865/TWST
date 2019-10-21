<?php
    namespace company_program;
    ob_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $current_dir = "../";
    require $current_dir.'PageTop.php';
    if(isset($_SERVER['HTTP_REFERER']))
    {
        $redirect = $_SERVER['HTTP_REFERER'];
    }else
    {
        $redirect = "../index.php";
    }
    try
    {
        $current_user->current_user->Set_Username($_POST['username']);
        $current_user->current_user->Set_Password($_POST['password']);
        if(!$current_user->current_user->Authenticate())
        {
            $aAlerts->aAlerts->Add_Alert("Unable to log in","Unable to authenticate with the given username or password.",true);
        }

    } catch (\User_Session\User_Does_Not_Exist $e)
    {
        $aAlerts->aAlerts->Add_Alert("Username doesn't exist","That username does not exist.",false);
        header("location: ".$redirect);
        exit();
    } catch (\Exception $e)
    {
        throw new \Exception($e->getMessage());
    }
    if($current_user->current_user->Currently_Default_Password())
    {
        echo '<script>
                function password_prompt()
                {
                    i = 1;
                    do {
                        password = window.prompt("new password","");
                        conf_password = window.prompt("confirm password","");
                        if(password == conf_password && password != "")
                        {
                            var post = {new_password:password};
                            Post_Ajax("UpdatePassword.php",JSON.stringify(post));
                            return true;
                        }else
                        {
                            alert("failed, please try again");
                        }
                    }
                    while (i == 1);
                }
                if(password_prompt() == true)
                {
                    alert("success!");
                    window.location.href = "'.$redirect.'";
                }
            </script>';
            exit();
    }else
    {
        header("location: ".$redirect);
        exit();
    }

?>