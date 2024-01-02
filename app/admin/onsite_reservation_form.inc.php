<?php
$majorList = getMajorList();

// Creating form as in your original code
$form = new simbio_form_table_AJAX('onsiteReservationForm', $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'], 'post');
$form->submit_button_attr = 'name="onsiteReservation" value="' . __('Reservasi') . '" class="s-btn btn btn-default"';

$form->table_attr = 'id="dataList" cellpadding="0" cellspacing="0"';
$form->table_header_attr = 'class="alterCell"';
$form->table_content_attr = 'class="alterCell2"';

$meta = [];
$str_input  = '<div class="container-fluid">';
$str_input .= '<div class="row">';
$str_input .= simbio_form_element::textField('text', 'memberID', $rec_d['member_id']??'', 'id="memberID" onblur="ajaxCheckID(\''.SWB.'plugins/reservasi_ruang_diskusi/app/admin/AJAX_check_id.php\', \'member\', \'member_id\', \'msgBox\', \'memberID\')" class="form-control col-6" required');
$str_input .= '<div id="msgBox" class="col mt-2"></div>';
$str_input .= '</div>';
$str_input .= '</div>';
$form->addAnything('NIDN/NIM', $str_input);
// $form->addTextField('text', 'memberId', 'NIDN/NIM', '', 'rows="1" class="form-control col-6"', 'Member ID');
$form->addTextField('text', 'name', 'Nama', '', 'rows="1" class="form-control col-6" required', 'Name');
$form->addSelectList('major', 'Program Studi', $majorList, $meta['major'] ?? '', 'class="form-control col-6" required', 'Major');
$form->addTextField('text', 'whatsAppNumber', 'Nomor WhatsApp', $meta['whatsAppNumber'] ?? '', 'rows="1" class="form-control col-6" required', 'WhatsApp Number');

$str_date = '<input type="date" id="reservationDate" name="reservationDate" class="form-control col-6" value="'.date('Y-m-d').'" min="'.date('Y-m-d').'" onchange="populateSubcategories()" required/>';
$form->addAnything('Tanggal Reservasi', $str_date);

// $reservationDuration = ['30' => '30 menit', '60' => '1 jam', '90' => '1,5 jam', '120' => '2 jam', '>120' => '> 2 jam'];
$reservationDuration = [['30', '30 menit'], ['60', '1 jam'], ['90', '1,5 jam'], ['120', '2 jam'], ['>120', '> 2 jam']];
$form->addSelectList('duration', 'Durasi Peminjaman', $reservationDuration, $meta['duration'] ?? '', 'onchange="populateSubcategories()" class="form-control col-6" required', 'Duration');
// $form->addSelectList('availableSchedule', 'Jadwal Reservasi yang Tersedia', [], $meta['availableSchedule'] ?? '', 'class="form-control col-6" required', 'Available Schedule');

$str_available_schedule = '<select id="availableSchedule" class="form-control col-6" name="availableSchedule" required></select>';
$str_available_schedule .= '<div id="error-container" aria-live="polite"; class="col-6"></div>';
$form->addAnything('Jadwal Reservasi yang Tersedia', $str_available_schedule);

// required (> 2 hours)
// md5 
$str_input  = '<div id="reservationDocument" class="container-fluid">';
$str_input .= '<div class="row">';
$str_input .= '<div class="custom-file col-6">';
$str_input .= simbio_form_element::textField('file', 'reservationDocumentInput','','class="custom-file-input" required');
$str_input .= '<label class="custom-file-label" for="reservationDocumentInput">Choose file</label>';
$str_input .= '</div>';
$str_input .= '<div class="col-4 mt-2">Maximum '.$sysconf['max_upload'].' KB</div>';
$str_input .= '</div>';
$str_input .= '</div>';
$form->addAnything('File To Attach', $str_input);

$form->addSelectList('visitorNumber', 'Jumlah pengguna ruangan', ['5', '6', '7', '8', '9', '10'], $meta['visitorNumber'] ?? '', 'class="form-control col-6" required', 'Visitor Number');
$form->addTextField('text', 'activity', 'Kegiatan yang Akan Dilakukan', $meta['activity'] ?? '', 'rows="1" class="form-control col-6" required', 'Activity');

echo '<style>
.error-message {
    color: #b9191b; /* Red color */
    font-size: 0.8rem; /* Slightly smaller than your form controls */
    margin-bottom: 0.5rem;
    margin-top: 0.5rem;
    padding: 5px;
    border: 1px solid #e74c3c; /* Light red border */
    border-radius: 4px;
    background-color: #fcf8f6; /* Lighten background for contrast */
}

#error-container {
    display: block; /* Ensure container is visible */
}

.hidden {
    display: none;
}
</style>';

// Change labelUpload with uploaded file name
echo '<script>
$(document).on("change", ".custom-file-input", function () {
    let fileName = $(this).val().replace(/\\\\/g, "/").replace(/.*\\//, "");
    $(this).parent(".custom-file").find(".custom-file-label").text(fileName);
});
</script>';

// Populate available schedule based on selected date
echo '<script>
    const today = new Date().toISOString().substr(0, 10);
    document.getElementById(\'reservationDate\').value = today;

    function populateSubcategories() {
        hideErrorMessage()

        const category = document.getElementById(\'duration\').value;
        const availableSchedule = document.getElementById(\'availableSchedule\');
        availableSchedule.innerHTML = \'\';

        const selectedDate = document.getElementById(\'reservationDate\').value;

        fetch(\'http://localhost/slims9_bulian/index.php?p=populate_schedule\', {
            method: \'POST\',
            headers: {
                \'Content-Type\': \'application/x-www-form-urlencoded\',
            },
            body: `selectedDate=${selectedDate}`,
        })
            .then(response => response.json())
            .then(data => {
                const options = data[category];
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
                $(\'#availableSchedule\').trigger(\'change\');
            })
            .catch(error => console.error(\'Error:\', error));
    }

    window.onload = populateSubcategories;
</script>';

// Prevent form submission when schedule isn't available ("Tidak ada jadwal")
echo '<script>
document.getElementById("onsiteReservationForm").addEventListener("submit", function(event) {
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

echo '<script>
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


echo $form->printOut();
?>