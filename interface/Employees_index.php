<?php
require 'PageTop.php';
$current_user->current_user->Exit_If_Not_Currently_Authenticated();
include 'include_scripts/CRM_navbar.php';
?>
<div class="container">
  <div class="row">
    <div class="col-lg-10" style = "background-color: grey;">
      <?php include 'include_scripts/EmployeesTable.php';?>
    </div>
  </div>
</div>
<?php
require 'PageBottom.php';
?>