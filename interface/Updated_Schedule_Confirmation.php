<?php
if($_GET['status'] == 'available')
{
    echo '<h1>Thank you for confirming you are available to work on '.date("D",strtotime($_GET['date'])).'</h3>';
}elseif($_GET['status'] == 'unavailable')
{
    echo '<h1>Sorry to hear you won\'t be able to work on '.date("D",strtotime($_GET['date'])).'</h3>';
}
?>