<?php

require_once __DIR__ . '/../app/reservation_logic/populate_schedule.php';
use PHPUnit\Framework\TestCase;


class SchedulesTest extends TestCase {

    public function testPopulateScheduleForPastDate() {
        // Mocking the provided booked schedules
        $bookedSchedules = [
            ['start_date' => '2023-12-13', 'end_date' => '2023-12-13', 'start_time' => '08:30', 'end_time' => '09:30'],
            // Add more booked slots as needed for varied scenarios
        ];

        // Mocking a past date for testing purposes
        $pastDate = strtotime('yesterday');

        // Generate schedules for a past date
        $result = populateSchedule(date('Y-m-d', $pastDate), 60, $bookedSchedules);

        // Define the expected output for past dates
        $expectedOutput = ['Jadwal tidak tersedia'];

        // Assert that the result for past dates matches the expected output
        $this->assertEquals($expectedOutput, $result);
    }
}
