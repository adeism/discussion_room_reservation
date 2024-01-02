<?php

function formatWhatsAppNumberInto62Format($whatsAppNumber)
{
    $whatsAppNumber = preg_replace('/[^\d]/', '', $whatsAppNumber);  // Remove non-digit characters

    // Phone number validation
    if (!preg_match('/^(\+62|62)?[\s-]?0?8[1-9]{1}\d{1}[\s-]?\d{4}[\s-]?\d{2,5}$/', $whatsAppNumber)) {
        return $whatsAppNumber; // return it as is
    }

    // Check inputted phoneNumber with "8" prefix
    if (strlen($whatsAppNumber) == 9 && $whatsAppNumber[0] == 8) {
        $whatsAppNumber = "62" . $whatsAppNumber;
    } else if (strlen($whatsAppNumber) == 10 && $whatsAppNumber[0] == 8) {
        $whatsAppNumber = "62" . $whatsAppNumber;
    } else if (strlen($whatsAppNumber) == 11 && $whatsAppNumber[0] == 8) {
        $whatsAppNumber = "62" . $whatsAppNumber;
    } else if (strlen($whatsAppNumber) == 12 && $whatsAppNumber[0] == 8) {
        $whatsAppNumber = "62" . $whatsAppNumber;
    }

    // Check inputted phoneNumber with "0" prefix
    if (strlen($whatsAppNumber) == 10 && $whatsAppNumber[0] == 0) {
        $whatsAppNumber = "62" . substr($whatsAppNumber, 1);
    } else if (strlen($whatsAppNumber) == 11 && $whatsAppNumber[0] == 0) {
        $whatsAppNumber = "62" . substr($whatsAppNumber, 1);
    } else if (strlen($whatsAppNumber) >= 12 && $whatsAppNumber[0] == 0) {
        $whatsAppNumber = "62" . substr($whatsAppNumber, 1);
    }

    return $whatsAppNumber;
}

function getMajorFromId($id)
{
    $educationLevelCode = (int) ((string) $id)[2];
    $majorCode = ((string) $id)[3] . ((string) $id)[4];

    $actualMajor = "";

    if ($educationLevelCode == 1) {
        $actualMajor .= "S1";
    } else if ($majorCode == 2) {
        $actualMajor .= "D3";
    } else {
        $actualMajor .= "";
    }

    switch ($majorCode) {
        case "01":
            $actualMajor .= " Teknik Telekomunikasi";
            break;
        case "02":
            $actualMajor .= " Teknik Informatika";
            break;
        case "03":
            $actualMajor .= " Sistem Informasi";
            break;
        case "04":
            $actualMajor .= " Software Engineering";
            break;
        case "05":
            $actualMajor .= " Desain Komunikasi Visual";
            break;
        case "06":
            $actualMajor .= " Teknik Industri";
            break;
        case "07":
            $actualMajor .= " Teknik Elektro";
            break;
        case "08":
            $actualMajor .= " Teknik Biomedis";
            break;
        case "09":
            $actualMajor .= " Teknik Logistik";
            break;
        case "10":
            $actualMajor .= " Sains Data";
            break;
        default:
            $actualMajor .= "";
            break;
    }

    if ($actualMajor === "") {
        return "S1 Teknik Informatika";
    }

    return $actualMajor;
}

function sortByDateField($a, $b, $dateField)
{
    $dateA = strtotime($a->$dateField);
    $dateB = strtotime($b->$dateField);

    return $dateB - $dateA; // Compare in reverse order for descending
}

function getMinutesAndSecond($time)
{
    $timeParts = explode(":", $time);
    $formattedTime = $timeParts[0] . ":" . $timeParts[1];
    return $formattedTime;
}

function convertDate($dateString)
{
    // Convert date string to timestamp
    $timestamp = strtotime($dateString);

    // Format the date as 'd MonthName YYYY'
    $formattedDate = date('j F Y', $timestamp);

    return $formattedDate;
}

function memberSection($section_path = null)
{
    $query = [
        'p' => 'member',
        'sec' => 'discussion_room_reservation_tab'
    ];
    return $_SERVER['PHP_SELF'] . '?' . http_build_query($query);
}

function memberReservationFormScript()
{
    // Change labelUpload with uploaded file name
    $script = '<script>
    $(document).on("change", ".custom-file-input", function () {
        let fileName = $(this).val().replace(/\\\\/g, "/").replace(/.*\\//, "");
        $(this).parent(".custom-file").find(".custom-file-label").text(fileName);
    });
    </script>';

    // Populate available schedule based on selected date
    $script .= '<script>
    const today = new Date().toISOString().substr(0, 10);
    document.getElementById(\'reservationDate\').value = today;

    function populateSubcategories() {
        // reset all state
        hideErrorMessage()

        const duration = document.getElementById(\'duration\').value;
        const availableSchedule = document.getElementById(\'availableSchedule\');
        availableSchedule.innerHTML = \'\';

        const selectedDate = document.getElementById(\'reservationDate\').value;

        fetch(\'index.php?p=populate_schedule\', {
            method: \'POST\',
            headers: {
                \'Content-Type\': \'application/x-www-form-urlencoded\',
            },
            body: `selectedDate=${selectedDate}`,
        })
            .then(response => response.json())
            .then(data => {
                const options = data[duration];
                if (options && options.length > 0 && options[0] !== \'Jadwal tidak tersedia\') {
                    options.forEach(option => {
                        const newOption = document.createElement(\'option\');
                        newOption.value = option;
                        newOption.text = option;
                        availableSchedule.appendChild(newOption);
                    });
                } else {
                    const newOption = document.createElement(\'option\');
                    newOption.value = \'Jadwal tidak tersedia\';
                    newOption.text = \'Tidak ada jadwal\';
                    availableSchedule.appendChild(newOption);
                }
                // After populating the options, manually trigger the change event
                // Since the AJAX call doesnt explicitly trigger the change event for the newly populated options
                $(\'#availableSchedule\').trigger(\'change\');
            })
            .catch(error => console.error(\'Error:\', error));
    }

    window.onload = populateSubcategories;
    </script>';

    // Prevent form submission when schedule isn't available ("Tidak ada jadwal")
    $script .= '<script>
    document.getElementById("reservationForm").addEventListener("submit", function(event) {
        var availableSchedule = document.getElementById(\'availableSchedule\');
        var selectedValue = availableSchedule.value;

        if (selectedValue === "Jadwal tidak tersedia") { // Replace "requiredValue" with the desired value
            event.preventDefault(); // Prevent form submission
            showInlineErrorMessage("Jadwal tidak tersedia. Silahkan pilih jadwal yang lain.");
        }
    });
    function showInlineErrorMessage(message) {
        var errorContainer = document.getElementById("error-container");
        
        // Clear any existing error messages:
        errorContainer.innerHTML = "";
        
        var errorMessage = document.createElement("p");
        errorMessage.classList.add("error-message"); // Add a class for styling
        errorMessage.textContent = message;
        
        errorContainer.appendChild(errorMessage);
    }
    function hideErrorMessage() {
        var errorContainer = document.getElementById("error-container");
        errorContainer.innerHTML = ""; // Clear the contents of the error container
    }      
    </script>';

    $script .= '<script>
    $(document).ready(function() {
        // Initially hide the conditional field and remove required attribute
        $(\'#reservationDocument\').hide();
        $(\'#reservationDocumentInput\').prop(\'required\', false);
    
        // Use event delegation on the form container
        $(\'#reservationForm > div\').on(\'change\', \'#duration, #availableSchedule\', function() {
            var selectedDuration = $(\'#duration\').val(); // Get the selected duration
            var selectedSchedule = $(\'#availableSchedule\').val(); // Get the selected schedule
            
            // Show/hide the conditional field based on the selected duration and available schedule
            if (selectedDuration === \'>120\' && selectedSchedule !== \'Jadwal tidak tersedia\') {
                $(\'#reservationDocument\').show(); // Show the conditional field
                $(\'#reservationDocumentInput\').prop(\'required\', true); // Add required
            } else {
                $(\'#reservationDocument\').hide(); // Hide the conditional field
                $(\'#reservationDocumentInput\').prop(\'required\', false); // remove required
            }
        });
    });

    // Call populateSubcategories after the initial load
    populateSubcategories();
    </script>';

    return $script;
}

function adminReservationFormScript()
{
    // Change labelUpload with uploaded file name
    $script = '<script>
    $(document).on("change", ".custom-file-input", function () {
        let fileName = $(this).val().replace(/\\\\/g, "/").replace(/.*\\//, "");
        $(this).parent(".custom-file").find(".custom-file-label").text(fileName);
    });
    </script>';

    // Populate available schedule based on selected date
    $script .= '<script>
    const today = new Date().toISOString().substr(0, 10);
    document.getElementById(\'reservationDate\').value = today;

    function populateSubcategories() {
        // reset all state
        hideErrorMessage()

        const duration = document.getElementById(\'duration\').value;
        const availableSchedule = document.getElementById(\'availableSchedule\');
        availableSchedule.innerHTML = \'\';

        const selectedDate = document.getElementById(\'reservationDate\').value;

        const mainPath = window.location.origin + window.location.pathname.substring(0, window.location.pathname.lastIndexOf(\'/admin\'));
        fetch(mainPath + \'/index.php?p=populate_schedule\', {
            method: \'POST\',
            headers: {
                \'Content-Type\': \'application/x-www-form-urlencoded\',
            },
            body: `selectedDate=${selectedDate}`,
        })
            .then(response => response.json())
            .then(data => {
                const options = data[duration];
                if (options && options.length > 0 && options[0] !== \'Jadwal tidak tersedia\') {
                    options.forEach(option => {
                        const newOption = document.createElement(\'option\');
                        newOption.value = option;
                        newOption.text = option;
                        availableSchedule.appendChild(newOption);
                    });
                } else {
                    const newOption = document.createElement(\'option\');
                    newOption.value = \'Jadwal tidak tersedia\';
                    newOption.text = \'Tidak ada jadwal\';
                    availableSchedule.appendChild(newOption);
                }
                // After populating the options, manually trigger the change event
                // Since the AJAX call doesnt explicitly trigger the change event for the newly populated options
                $(\'#availableSchedule\').trigger(\'change\');
            })
            .catch(error => console.error(\'Error:\', error));
    }

    window.onload = populateSubcategories;
    </script>';

    // Prevent form submission when schedule isn't available ("Tidak ada jadwal")
    $script .= '<script>
    document.getElementById("reservationForm").addEventListener("submit", function(event) {
        var availableSchedule = document.getElementById(\'availableSchedule\');
        var selectedValue = availableSchedule.value;

        if (selectedValue === "Jadwal tidak tersedia") { // Replace "requiredValue" with the desired value
            event.preventDefault(); // Prevent form submission
            showInlineErrorMessage("Jadwal tidak tersedia. Silahkan pilih jadwal yang lain.");
        }
    });
    function showInlineErrorMessage(message) {
        var errorContainer = document.getElementById("error-container");
        
        // Clear any existing error messages:
        errorContainer.innerHTML = "";
        
        var errorMessage = document.createElement("p");
        errorMessage.classList.add("error-message"); // Add a class for styling
        errorMessage.textContent = message;
        
        errorContainer.appendChild(errorMessage);
    }
    function hideErrorMessage() {
        var errorContainer = document.getElementById("error-container");
        errorContainer.innerHTML = ""; // Clear the contents of the error container
    }      
    </script>';

    $script .= '<script>
    $(document).ready(function() {
        // Initially hide the conditional field and remove required attribute
        $(\'#reservationDocument\').hide();
        $(\'#reservationDocumentInput\').prop(\'required\', false);

        // Use event delegation on the form container
        $(\'#dataList\').on(\'change\', \'#duration, #availableSchedule\', function() {
            var selectedDuration = $(\'#duration\').val(); // Get the selected duration
            var selectedSchedule = $(\'#availableSchedule\').val(); // Get the selected schedule
            
            // Show/hide the conditional field based on the selected duration and available schedule
            if (selectedDuration === \'>120\' && selectedSchedule !== \'Jadwal tidak tersedia\') {
                $(\'#reservationDocument\').show(); // Show the conditional field
                $(\'#reservationDocumentInput\').prop(\'required\', true); // Add required
            } else {
                $(\'#reservationDocument\').hide(); // Hide the conditional field
                $(\'#reservationDocumentInput\').prop(\'required\', false); // remove required
            }
        });
    });

    // Call populateSubcategories after the initial load
    populateSubcategories();
    </script>';

    return $script;
}