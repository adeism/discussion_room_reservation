<?php
require 'calendar.php';
?>

<div class="text-right mb-2">
    <button id="prevWeekBtn" class="btn btn-secondary">Previous Week</button>
    <button id="todayWeekBtn" class="btn btn-secondary">Today's Week</button>
    <button id="nextWeekBtn" class="btn btn-secondary">Next Week</button>
</div>

<div id="calendarContainer">
    <?php
    if (isset($_POST["weekSelection"])) {
        $selectedWeek = $_POST["weekSelection"];
        $currentDate = date('Y-m-d');

        if ($selectedWeek === 'prevWeek') {
            $calendar->display(date('Y-m-d', strtotime("-1 week", strtotime($currentDate))), 'grey');
        } elseif ($selectedWeek === 'nextWeek') {
            $calendar->display(date('Y-m-d', strtotime("+1 week", strtotime($currentDate))), 'grey');
        } else {
            $calendar->display($currentDate, 'grey');
        }
    } else {
        $calendar->display(date('Y-m-d'), 'grey');
    }
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentDisplayedDate = '<?php echo date('Y-m-d'); ?>';

    $(document).ready(function() {
        // Button click events
        $('#prevWeekBtn').on('click', function() {
            changeCalendar('prev');
        });
        
        $('#todayWeekBtn').on('click', function() {
            changeCalendar('today');
        });
        
        $('#nextWeekBtn').on('click', function() {
            changeCalendar('next');
        });
    });

    function changeCalendar(action) {
        let currentDate = currentDisplayedDate;

        if (action === 'prev') {
            currentDate = new Date(currentDisplayedDate);
            currentDate.setDate(currentDate.getDate() - 7); // Subtract 7 days for the previous week
        } else if (action === 'next') {
            currentDate = new Date(currentDisplayedDate);
            currentDate.setDate(currentDate.getDate() + 7); // Add 7 days for the next week
        } else {
            currentDate = new Date(); // Today's date
        }

        // Format currentDate to 'YYYY-MM-DD' string
        let formattedDate = currentDate.toISOString().split('T')[0];
        loadCalendar(formattedDate);
        currentDisplayedDate = formattedDate; // Update currentDisplayedDate after loading new data
    }

    function loadCalendar(date) {
        $.ajax({
            url: 'index.php?p=reservation_calendar', // Replace with your calendar rendering script
            method: 'POST', // Use POST method
            data: { newDate: date }, // Send newDate as POST data
            success: function(response) {
                $('#calendarContainer').html(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
</script>