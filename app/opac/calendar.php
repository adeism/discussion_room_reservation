<?php

require DRRB . DS . 'app/models/Reservation.php';

use benhall14\phpCalendar\Calendar as Calendar;

$calendar = new Calendar;
$reservationEvents = Reservation::getAllEvents();
$events = [];

function displayCalendarForDate($calendar, $date)
{
    $calendar->display($date, 'grey');
}

function decrementEndTimeByOneMinute($endTime) 
{
    $newTime = date('H:i', strtotime($endTime . ' - 1 minute'));
    return $newTime;
}

foreach ($reservationEvents as $event) {
    $events[] = array(
        'start' => $event->reservedDate . ' ' . getMinutesAndSecond($event->startTime),
        'end' => $event->reservedDate . ' ' . getMinutesAndSecond(decrementEndTimeByOneMinute($event->endTime)),
        'summary' => 'Booked',
        'mask' => false,
    );
}

$calendar
    ->hideSaturdays()
    ->hideSundays()
    ->setTimeFormat('08:00', '15:59', 15)
    ->useWeekView()
    ->addEvents($events);