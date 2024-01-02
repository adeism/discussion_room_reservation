<?php

use DiscussionRoomReservation\Lib\Url;

// Handle form submissions or other controller logic
reserveSchedule(Url::memberSection()); // Handle reservation schedule logic...

$attr = [
    'id' => 'reservationForm',
    'action' => Url::memberSection(),
    'method' => 'POST',
    'enctype' => 'multipart/form-data'
];

function getMajorFromId($id) {
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

function getWhatsAppFromPreviousBookIfAny() {
    $reservations = getReservationByMemberId($_SESSION['mid']);

    if (count($reservations) != 0) {
        return end($reservations)->whatsAppNumber;
    } else {
        return '';
    }
}

function getMajorFromPreviousBookIfAny() {
    $reservations = getReservationByMemberId($_SESSION['mid']);

    if (count($reservations) != 0) {
        return end($reservations)->major;
    } else {
        return null;
    }
}


// check dependency
if (!file_exists(DRRB.DS.'app/helper/formmaker.inc.php'))
{
    echo '<div class="bg-danger p-2 text-white">';
    echo 'Folder <b>'.DRRB.DS.'app/helper/formmaker.inc.php</b> tidak ada. Pastikan folder itu tersedia.';
    echo '</div>';
}
else
{
    $majorList = [['label' => __('S1 Teknik Informatika'), 'value' => 'S1 Teknik Informatika'], ['label' => __('S1 Software Engineering'), 'value' => 'S1 Software Engineering'],
    ['label' => __('S1 Sistem Informasi'), 'value' => 'S1 Sistem Informasi'], ['label' => __('S1 Sains Data'), 'value' => 'S1 Sains Data'],
    ['label' => __('S1 Teknik Telekomunikasi'), 'value' => 'S1 Teknik Telekomunikasi'], ['label' => __('D3 Teknik Telekomunikasi'), 'value' => 'D3 Teknik Telekomunikasi'],
    ['label' => __('S1 Automation Technology'), 'value' => 'S1 Automation Technology'], ['label' => __('S1 Teknik Biomedis'), 'value' => 'S1 Teknik Biomedis'],
    ['label' => __('S1 Teknologi Pangan'), 'value' => 'S1 Teknologi Pangan'], ['label' => __('S1 Teknik Industri'), 'value' => 'S1 Teknik Industri'],
    ['label' => __('S1 Desain Komunikasi Visual'), 'value' => 'S1 Desain Komunikasi Visual'], ['label' => __('S1 Digital Logistic'), 'value' => 'S1 Digital Logistic'],
    ['label' => __('S1 Bisnis Digital'), 'value' => 'S1 Bisnis Digital'], ['label' => __('S1 Teknik Elektro'), 'value' => 'S1 Teknik Elektro'],
    ['label' => __('S1 Product Innovation'), 'value' => 'S1 Product Innovation'], ['label' => __('D3  Teknik Digital'), 'value' => 'D3  Teknik Digital'], ['label' => __('Lainnya'), 'value' => 'Lainnya']];

    $reservationDuration = [['label' => __('30 menit'), 'value' => 30],['label' => __('1 jam'), 'value' => 60],
    ['label' => __('1,5 jam'), 'value' => 90],['label' => __('2 jam'), 'value' => 120], ['label' => __('> 2 jam'), 'value' => '>120']];

    $visitorCount = [['label' => __('5'), 'value' => 5],['label' => __('6'), 'value' => 6],
    ['label' => __('7'), 'value' => 7],['label' => __('8'), 'value' => 8],
    ['label' => __('9'), 'value' => 9],['label' => __('10'), 'value' => 10]];

    // set key
    define('DR_INDEX_AUTH', '1');

    // require helper
    require DRRB.DS.'app/helper/formmaker.inc.php';

    // create form
    createForm($attr);
    createSelect(__('Program Studi'), 'major', $majorList, '', getMajorFromPreviousBookIfAny() ?? getMajorFromId($_SESSION['mid']));
    createFormContent(__('Nomor WhatsApp'), 'text', 'whatsAppNumber', 'Isikan nomor WhatsApp Anda (gunakan format 62..)', true, getWhatsAppFromPreviousBookIfAny(), true);
    createDate(__('Tanggal Reservasi'), 'reservationDate', 'min="'.date('Y-m-d').'" onchange="populateSubcategories()"');
    createSelect(__('Durasi Peminjaman'), 'duration', $reservationDuration, 'onchange="populateSubcategories()"');
    createDynamicSelect(__('Jadwal Reservasi yang Tersedia'), 'availableSchedule');
    createUploadArea(__('Upload Surat Peminjaman Ruang'), 'reservationDocument');
    createSelect(__('Jumlah pengguna ruangan'), 'visitorNumber', $visitorCount);
    createFormContent(__('Kegiatan yang Akan Dilakukan'), 'text', 'activity', 'Isikan apa kegiatan Anda', true, '', true);
    createFormButton('Daftar', 'submit', 'register');
    closeTag('div');
    closeTag('form');

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
    echo '<script>
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

    echo '<script>
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
}