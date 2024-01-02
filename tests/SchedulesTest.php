<?php

require_once DRRB . DS . 'app/reservation_logic/populate_schedule.php';
use PHPUnit\Framework\TestCase;

class SchedulesTest extends TestCase
{

    public function testPopulateScheduleForPastDate()
    {
        $bookedSchedules = [
            ['start_date' => '2023-12-13', 'end_date' => '2023-12-13', 'start_time' => '08:30', 'end_time' => '09:30'],
        ];

        $pastDate = strtotime('yesterday');

        $result = populateSchedule(date('Y-m-d', $pastDate), 60, $bookedSchedules);

        $expectedOutput = ['Jadwal tidak tersedia'];

        $this->assertEquals($expectedOutput, $result);
    }
}
