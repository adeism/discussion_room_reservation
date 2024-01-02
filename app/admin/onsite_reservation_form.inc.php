<?php
$majorList = getMajorList();

// Creating form as in your original code
$form = new simbio_form_table_AJAX('reservationForm', $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'], 'post');
$form->submit_button_attr = 'name="reserve" value="' . __('Reservasi') . '" class="s-btn btn btn-default"';

$form->table_attr = 'id="dataList" cellpadding="0" cellspacing="0"';
$form->table_header_attr = 'class="alterCell"';
$form->table_content_attr = 'class="alterCell2"';

$meta = [];
$str_input = '<div class="container-fluid">';
$str_input .= '<div class="row">';
$str_input .= simbio_form_element::textField('text', 'memberID', $rec_d['member_id'] ?? '', 'id="memberID" onblur="ajaxCheckID(\'' . SWB . 'plugins/reservasi_ruang_diskusi/app/admin/AJAX_check_id.php\', \'member\', \'member_id\', \'msgBox\', \'memberID\')" class="form-control col-6" required');
$str_input .= '<div id="msgBox" class="col mt-2"></div>';
$str_input .= '</div>';
$str_input .= '</div>';
$form->addAnything('NIDN/NIM', $str_input);
// $form->addTextField('text', 'memberId', 'NIDN/NIM', '', 'rows="1" class="form-control col-6"', 'Member ID');
$form->addTextField('text', 'name', 'Nama', '', 'rows="1" class="form-control col-6" required', 'Name');
$form->addSelectList('major', 'Program Studi', $majorList, $meta['major'] ?? '', 'class="form-control col-6" required', 'Major');
$form->addTextField('text', 'whatsAppNumber', 'Nomor WhatsApp', $meta['whatsAppNumber'] ?? '', 'rows="1" class="form-control col-6" required', 'WhatsApp Number');

$str_date = '<input type="date" id="reservationDate" name="reservationDate" class="form-control col-6" value="' . date('Y-m-d') . '" min="' . date('Y-m-d') . '" onchange="populateSubcategories()" required/>';
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
$str_input = '<div id="reservationDocument" class="container-fluid">';
$str_input .= '<div class="row">';
$str_input .= '<div class="custom-file col-6">';
$str_input .= simbio_form_element::textField('file', 'reservationDocumentInput', '', 'class="custom-file-input" required');
$str_input .= '<label class="custom-file-label" for="reservationDocumentInput">Choose file</label>';
$str_input .= '</div>';
$str_input .= '<div class="col-4 mt-2">Maximum ' . $sysconf['max_upload'] . ' KB</div>';
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

echo adminReservationFormScript();

echo $form->printOut();
?>