<?php

function getWhatsAppFromPreviousBookIfAny()
{
    $reservations = getReservationByMemberId($_SESSION['mid']);

    if (count($reservations) != 0) {
        return end($reservations)->whatsAppNumber;
    } else {
        return '';
    }
}

function getMajorFromPreviousBookIfAny()
{
    $reservations = getReservationByMemberId($_SESSION['mid']);

    if (count($reservations) != 0) {
        return end($reservations)->major;
    } else {
        return null;
    }
}
// Handle form submissions or other controller logic
reserveSchedule(memberSection()); // Handle reservation schedule logic...

$attr = [
    'id' => 'reservationForm',
    'action' => memberSection(),
    'method' => 'POST',
    'enctype' => 'multipart/form-data'
];

// check dependency
if (!file_exists(DRRB . DS . 'app/helper/formmaker.inc.php')) {
    echo '<div class="bg-danger p-2 text-white">';
    echo 'Folder <b>' . DRRB . DS . 'app/helper/formmaker.inc.php</b> tidak ada. Pastikan folder itu tersedia.';
    echo '</div>';
} else {
    $majorList = [
        ['label' => __('S1 Teknik Informatika'), 'value' => 'S1 Teknik Informatika'],
        ['label' => __('S1 Software Engineering'), 'value' => 'S1 Software Engineering'],
        ['label' => __('S1 Sistem Informasi'), 'value' => 'S1 Sistem Informasi'],
        ['label' => __('S1 Sains Data'), 'value' => 'S1 Sains Data'],
        ['label' => __('S1 Teknik Telekomunikasi'), 'value' => 'S1 Teknik Telekomunikasi'],
        ['label' => __('D3 Teknik Telekomunikasi'), 'value' => 'D3 Teknik Telekomunikasi'],
        ['label' => __('S1 Automation Technology'), 'value' => 'S1 Automation Technology'],
        ['label' => __('S1 Teknik Biomedis'), 'value' => 'S1 Teknik Biomedis'],
        ['label' => __('S1 Teknologi Pangan'), 'value' => 'S1 Teknologi Pangan'],
        ['label' => __('S1 Teknik Industri'), 'value' => 'S1 Teknik Industri'],
        ['label' => __('S1 Desain Komunikasi Visual'), 'value' => 'S1 Desain Komunikasi Visual'],
        ['label' => __('S1 Digital Logistic'), 'value' => 'S1 Digital Logistic'],
        ['label' => __('S1 Bisnis Digital'), 'value' => 'S1 Bisnis Digital'],
        ['label' => __('S1 Teknik Elektro'), 'value' => 'S1 Teknik Elektro'],
        ['label' => __('S1 Product Innovation'), 'value' => 'S1 Product Innovation'],
        ['label' => __('D3  Teknik Digital'), 'value' => 'D3  Teknik Digital'],
        ['label' => __('Lainnya'), 'value' => 'Lainnya']
    ];

    $reservationDuration = [
        ['label' => __('30 menit'), 'value' => 30],
        ['label' => __('1 jam'), 'value' => 60],
        ['label' => __('1,5 jam'), 'value' => 90],
        ['label' => __('2 jam'), 'value' => 120],
        ['label' => __('> 2 jam'), 'value' => '>120']
    ];

    $visitorCount = [
        ['label' => __('5'), 'value' => 5],
        ['label' => __('6'), 'value' => 6],
        ['label' => __('7'), 'value' => 7],
        ['label' => __('8'), 'value' => 8],
        ['label' => __('9'), 'value' => 9],
        ['label' => __('10'), 'value' => 10]
    ];

    // set key
    define('DR_INDEX_AUTH', '1');

    // require helper
    require DRRB . DS . 'app/helper/formmaker.inc.php';

    // create form
    createForm($attr);
    createSelect(__('Program Studi'), 'major', $majorList, '', getMajorFromPreviousBookIfAny() ?? getMajorFromId($_SESSION['mid']));
    createFormContent(__('Nomor WhatsApp'), 'text', 'whatsAppNumber', 'Isikan nomor WhatsApp Anda (gunakan format 62..)', true, getWhatsAppFromPreviousBookIfAny(), true);
    createDate(__('Tanggal Reservasi'), 'reservationDate', 'min="' . date('Y-m-d') . '" onchange="populateSubcategories()"');
    createSelect(__('Durasi Peminjaman'), 'duration', $reservationDuration, 'onchange="populateSubcategories()"');
    createDynamicSelect(__('Jadwal Reservasi yang Tersedia'), 'availableSchedule');
    createUploadArea(__('Upload Surat Peminjaman Ruang'), 'reservationDocument');
    createSelect(__('Jumlah pengguna ruangan'), 'visitorNumber', $visitorCount);
    createFormContent(__('Kegiatan yang Akan Dilakukan'), 'text', 'activity', 'Isikan apa kegiatan Anda', true, '', true);
    createFormButton('Daftar', 'submit', 'reserve');
    closeTag('div');
    closeTag('form');

    echo memberReservationFormScript();
}