<?php
include 'PageTop.php';
?>
<div class = "modal" role = "dialog" id="scheduleModal">
  <div class = "modal-dialog">
    <div class = "modal-content">
      <form action = "scripts/Update_Employee_Schedule.php" method = "POST">
        <div class = "modal-header">
<?php
if(!empty($_GET['employee_id']) && !empty($_GET['date']))
{
    echo '<h3 class = "modal-title">Please let us know your availability for '.date("D",strtotime($_GET['date'])).'</h3>';
    echo '</div>';
    echo '<div class = "modal-body">';
    echo '<input type = "hidden" name = "employee_id" value = "'.$_GET['employee_id'].'">';
    echo '<input type = "hidden" name = "date" value = "'.$_GET['date'].'">';
    echo '<div class="btn-group-vertical btn-group-toggle col-8" style = "margin: auto; display: flex; justify-content: center;" data-toggle="buttons">
    <label class="btn btn-primary btn-block">
        <input type="radio" name="availability" value = "available" autocomplete="off" required> Available
    </label>
    <br>
    <label class="btn btn-primary btn-block">
        <input type="radio" name="availability" value = "unavailable" autocomplete="off" required> Unavailable
    </label>
    </div></div>';
}
?>
<div class = "modal-footer">
    <?php echo '<input type = "submit" class = "btn btn-success" value = "Update Availability">';?>
</div>
</form>
</div></div></div>