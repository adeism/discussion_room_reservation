<?php

require 'calendar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newDate'])) {
  $newDate = $_POST["newDate"];

  $calendar->display($newDate, 'grey');
} else {
  $calendar->display(date('Y-m-d'), 'grey');
}
exit;
?>