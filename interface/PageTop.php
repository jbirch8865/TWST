<?php 
  namespace company_program;
  require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../src/ClassLoader.php';
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  $cConfigs = new \gcConfigs;
  $current_user = new \gCurrent_User;
  $aAlerts = new \gaAlerts;
  $System = new \system;
  if(!$System->is_connected())
  {
    $aAlerts->aAlerts->Add_Alert("INTERNET OUTAGE"," ");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="shortcut icon" href="http://twst.dsfellowship.com/images/forkknife.png">
  <title>TWST</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php if(isset($current_dir)){echo $current_dir;}?>style.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
  <div style = "display: none" id = "images_location"><?php 
    echo $cConfigs->cConfigs->Get_Images_URL();
  ?></div>
  <script src="<?php if(isset($current_dir)){echo $current_dir;}?>javascriptTop.js"></script>
  <script src="<?php if(isset($current_dir)){echo $current_dir;}?>javascriptBottom.js"></script>
</head>
<body style="background-image: url('images/background.jpg'); background-repeat: no-repeat; background-size: 100%;">

<?php
  if($cConfigs->cConfigs->Is_Dev())
  {
    echo '<nav class = "navbar navbar-expand-md navbar-light bg-warning sticky-top">';
  }else
  {
    echo '<nav class = "navbar navbar-expand-md navbar-dark bg-dark sticky-top">';
  }
  if($current_user->current_user->Am_I_Currently_Authenticated())
  {
    echo '
    <button class = "navbar-toggler" data-toggle="collapse" data-target="#collapse_target">
        <span class = "navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapse_target">
    <ul class = "navbar-nav">
        <li class = "nav-item">
            <a class = "nav-link" href="Events_index.php">Events</a>
        </li>
        <li class = "nav-item">
            <a class = "nav-link" href="Equipment_index.php">Equipment</a>
        </li>
        <li class = "nav-item">
            <a class = "nav-link" href="CRM_index.php">CRM</a>
        </li>
    </ul>
    </div>';
  }
      if($current_user->current_user->Am_I_Currently_Authenticated())
      {
        echo '<a href = "scripts/logout.php" class = "btn btn-warning btn-xs">Logout - '.$current_user->current_user->Get_Username().'</a>';
        if($current_user->current_user->Is_Management()){echo '<a style = "margin-left:15px" class = "btn btn-success btn-xs" data-toggle="modal" data-target="#userModal">Users</a>';}
      }else
      {
        //echo '<button type = "button" class = "btn btn-success" data-toggle="modal" data-target="#loginModal">Login</button>';
      }
?>
</nav>
<div class = "modal fade" role = "dialog" id="userModal">
  <div class = "modal-dialog">
    <div class = "modal-content">
        <div class = "modal-header">
          <h3 class = "modal-title">Users</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <div class="col-lg-8" style = "background-color: grey;">
                <?php include 'include_scripts/User_Modal.php';?>
                <?php new \company_program\Department_Context_Menu;?>
              </div>
            </div>
          </div>
        </div>
        <div class = "modal-footer">
            <?php echo '<button type = "button" class = "btn btn-success" data-toggle="modal" data-target="#registerModal">Create User</button>';?>
        </div>
    </div>
  </div>
</div>
<div class = "modal fade" role = "dialog" id="registerModal">
  <div class = "modal-dialog">
    <div class = "modal-content">
      <form action = "scripts/register_new_user.php" method = "POST">
        <div class = "modal-header">
          <h3 class = "modal-title">Register New User</h3>
          <button type = "buttton" class ="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class = "form-group">
            <input type = "text" name="username" class = "form-control" placeholder = "Username" required>
          </div>
        </div>
        <div class = "modal-footer">
          <button type = "submit" class = "btn btn-success">Register</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
      if(!$current_user->current_user->Am_I_Currently_Authenticated())
      {
          echo '
          <div style = "display:block;" class = "modal fade show" role = "dialog" id="loginModal">
          
          <div class = "modal-dialog">
            <div class = "modal-content" style = "background-color:rgba(0,0,0,0.5);">
              <form id = "login_user_form" action = "/scripts/login.php" method = "POST">
                <div class = "modal-header">
                  <h3 class = "modal-title text-white">Login</h3>
                </div>
                <div class="modal-body">
                  <div class = "form-group">
                    <input type = "text" name="username" class = "form-control" placeholder = "Username" required>
                  </div>
                  <div class = "form-group">
                    <input type = "password" name="password" class = "form-control" placeholder = "Password" required>
                  </div>
                </div>
                <div class = "modal-footer">
                  <button type = "submit" class = "btn btn-success">Sign In</button>
                </div>
              </form>
            </div>
          </div>
        
          </div>
        ';
      }

?>
<?php
      $aAlerts->aAlerts->Process_Alerts();
?>