<?php

require 'calendar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newDate'])) {
  $newDate = $_POST["newDate"];

  displayCalendarForDate($calendar, $newDate);
} else {
  displayCalendarForDate($calendar, date('Y-m-d'));
}
exit;
?>