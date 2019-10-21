<?php
/*
require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../src/ClassLoader.php';

if(strtoupper(trim($_POST['Body'])) == "YES" || strtoupper(trim($_POST['Body'])) == "Y")
{
  $message = "Thank you for letting us know you will be available tomorrow.";
  $status = '5';
}elseif(strtoupper(trim($_POST['Body'])) == "NO" || strtoupper(trim($_POST['Body'])) == "N")
{
  $message = "We are sorry to hear you will not be available for work tomorrow.";
  $status = '6';
}else
{
  $message = "Sorry I didn't understand that response, please reply with either \"Yes\" or \"No\"";
  $status = '4';
}
$employee = new \company_program\Employee();
$employee->Load_Employee_From_Phone_Number($_POST['From']);
if($status == '6')
{
  $employee->I_Am_Not_Available_To_Word(date("Y-m-d",strtotime("+2 days")));
}
if($status == '5')
{
  $employee->I_Am_Available_To_Work(date("Y-m-d",strtotime("+2 days")));
}
$employee->Log_SMS_Action($_POST['SmsSid'],$_POST['From'],$_POST['To'],$_POST['Body']);
use Twilio\TwiML\MessagingResponse;
$response = new MessagingResponse();
$response->message($message);
print $response;
*/
?>