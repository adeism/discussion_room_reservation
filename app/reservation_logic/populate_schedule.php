<?php
require DRRB . DS . 'app/models/Reservation.php';

define('START_TIME', '08:00');
define('END_TIME', '16:00');

date_default_timezone_set('Asia/Jakarta'); // Set the timezone to Jakarta/Western Indonesia Time (WIB)

function generateAvailableSchedules($startDate, $durationInMinutes, $bookedSchedules)
{
    $currentTime = strtotime('now'); // Current date and time 2023-12-26 13:45

    // Set the start date to the current hour if it's in the past
    if ($startDate < $currentTime) {
        $currentMinute = date('i', $currentTime); // Get the current minute

        $hour = date('H', $currentTime);
        $min = 0;

        if ($currentMinute >= 0 && $currentMinute < 15) {
            $min = 15;
        } else if ($currentMinute >= 15 && $currentMinute < 30) {
            $min = 30;
        } else if ($currentMinute >= 30 && $currentMinute < 45) {
            $min = 45;
        } else if ($currentMinute >= 45 && $currentMinute <= 59) {
            $hour = date('H', strtotime('+1 hour', $currentTime));
            $min = 0;
        }

        $startDate = strtotime(date('Y-m-d H:i', mktime($hour, $min, 0, date('m'), date('d'), date('Y'))));
    }

    // Check if the provided start date falls on a weekend (Saturday or Sunday)
    $dayOfWeek = date('w', $startDate);
    if ($dayOfWeek == 0 || $dayOfWeek == 6) {
        return []; // Return an empty array for weekends
    }

    $endDate = strtotime(date('Y-m-d', $startDate) . ' ' . END_TIME); // End date for the given day

    $currentTime = $startDate;
    $availableSchedules = [];

    // Loop through the dates and times to generate available schedules
    while ($currentTime + $durationInMinutes * 60 <= $endDate) {
        // Calculate the end time for the current schedule
        $endTimeForSchedule = $currentTime + $durationInMinutes * 60;

        // Check if the current slot is available
        $slotIsAvailable = true;
        foreach ($bookedSchedules as $bookedSlot) {
            $bookedStartTime = strtotime($bookedSlot['start_date'] . ' ' . $bookedSlot['start_time']);
            $bookedEndTime = strtotime($bookedSlot['end_date'] . ' ' . $bookedSlot['end_time']);

            // Check for overlap with booked slots
            if (!($endTimeForSchedule <= $bookedStartTime || $currentTime >= $bookedEndTime)) {
                $slotIsAvailable = false;
                break;
            }
        }

        // If the slot is available, add it to the list of available schedules
        if ($slotIsAvailable) {
            $formattedStartDate = date('H:i', $currentTime);
            $formattedEndDate = date('H:i', $endTimeForSchedule);
            $availableSchedules[] = "{$formattedStartDate} - {$formattedEndDate}";
        }

        // Move to the next time slot (increment by 30 minutes)
        $currentTime = strtotime('+15 minutes', $currentTime);
        // If the current time exceeds the end time of the day, exit the loop
        if ($currentTime >= strtotime(date('Y-m-d', $startDate + 86400) . ' ' . START_TIME)) {
            break;
        }
    }

    return $availableSchedules;
}

function generateLongestAvailableSchedules($startDate, $durationOptions, $bookedSchedules)
{
    $availableSchedules = [];

    rsort($durationOptions); // Sort durations in descending order

    foreach ($durationOptions as $duration) {
        $available = generateAvailableSchedules($startDate, $duration, $bookedSchedules);
        if (!empty($available)) {
            $availableSchedules[] = $available;
        }
    }

    $timeSlots = array_reduce($availableSchedules, 'array_merge', []);
    usort($timeSlots, function ($a, $b) {
        // Split the time slots into start and end times
        list($startA, $endA) = explode('-', $a);
        list($startB, $endB) = explode('-', $b);

        // Compare start times first
        if ($startA !== $startB) {
            return strcmp($startA, $startB); // Sort alphabetically
        }

        // If start times are equal, compare end times
        return strcmp($endB, $endA); // Sort alphabetically
    });
    return $timeSlots;
}

function populateSchedule($startDateParam, $durationInMinutes, $bookedSchedules = [])
{
    $startDate = strtotime($startDateParam . ' ' . START_TIME); // Provide the desired date here

    // Check if the provided start date is not in the past for today's schedule before generating schedules
    if ($startDate >= strtotime('today ' . START_TIME)) {
        if (is_array($durationInMinutes)) {
            return generateLongestAvailableSchedules($startDate, $durationInMinutes, $bookedSchedules);
        } else {
            return generateAvailableSchedules($startDate, $durationInMinutes, $bookedSchedules);
        }
    } else {
        return ['Jadwal tidak tersedia']; // Return an array with an unavailable message if it's in the past
    }
}

$bookedSchedules = Reservation::getBookedSchedules();

// Check if the selectedDate is received from the POST request
if (isset($_POST['selectedDate'])) {
    error_log('Selected Date:' . $_POST['selectedDate']);
    $selectedDate = $_POST['selectedDate'];
    $schedules = [
        '30' => populateSchedule($selectedDate, 30, $bookedSchedules),
        '60' => populateSchedule($selectedDate, 60, $bookedSchedules),
        '90' => populateSchedule($selectedDate, 90, $bookedSchedules),
        '120' => populateSchedule($selectedDate, 120, $bookedSchedules),
        '>120' => populateSchedule($selectedDate, range(480, 150, 30), $bookedSchedules),
    ];
    echo json_encode($schedules);
} else {
    // Handle the case when no date is provided
    echo json_encode(['error' => 'No date provided']);
}

exit;