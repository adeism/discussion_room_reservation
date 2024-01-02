<?php
use SLiMS\Filesystems\Storage;

require DRRB . DS . 'app/models/Reservation.php';

function reserveScheduleOnsite($self)
{
    if (isset($_POST['reserve'])) {
        $reservation = new Reservation();

        $memberId = $_POST['memberID'];
        $reservation->name = $_POST['name'];
        $reservation->memberId = $memberId;
        $reservation->major = $_POST['major'];
        $reservation->whatsAppNumber = formatWhatsAppNumberInto62Format($_POST['whatsAppNumber']);
        $reservation->reservedDate = $_POST['reservationDate'];
        $reservation->duration = $_POST['duration'];

        $timeRange = $_POST['availableSchedule'];
        $times = explode(" - ", $timeRange);
        $reservation->startTime = $times[0];
        $reservation->endTime = $times[1];

        $reservation->visitorNumber = $_POST['visitorNumber'];
        $reservation->activity = $_POST['activity'];

        $timestamp = date('Y-m-d H:i:s');
        $reservation->reservation_date = $timestamp;
        $reservation->reservationLastUpdate = $timestamp;

        $isFileUploaded = uploadFile($memberId);
        $reservation->reservationDocumentId = $isFileUploaded['insert_id'];

        $result = $reservation->save();

        if ($isFileUploaded['message'] !== "No insertion is made") {
            $reservation->associateFileWithReservation();
        }

        if ($result['success'] === true) {
            utility::jsToastr('Reservasi onsite', $result['message'], 'success');
            echo '<script>parent.$("#mainContent").simbioAJAX("' . $self . '")</script>';
            exit();
        } else {
            utility::jsToastr('Reservasi onsite', $result['message'], 'error');
            exit();
        }
    }
}

function reserveSchedule($self)
{
    if (isset($_POST['availableSchedule'])) {
        $reservation = new Reservation();

        $memberId = $_SESSION['mid'];
        $reservation->name = $_SESSION['m_name'];
        $reservation->memberId = $memberId;
        $reservation->major = $_POST['major'];
        $reservation->whatsAppNumber = formatWhatsAppNumberInto62Format($_POST['whatsAppNumber']);
        $reservation->reservedDate = $_POST['reservationDate'];
        $reservation->duration = $_POST['duration'];

        $timeRange = $_POST['availableSchedule'];
        $times = explode(" - ", $timeRange);
        $reservation->startTime = $times[0];
        $reservation->endTime = $times[1];

        $reservation->visitorNumber = $_POST['visitorNumber'];
        $reservation->activity = $_POST['activity'];

        $timestamp = date('Y-m-d H:i:s');
        $reservation->reservation_date = $timestamp;
        $reservation->reservationLastUpdate = $timestamp;

        $isFileUploaded = uploadFile($memberId);
        $reservation->reservationDocumentId = $isFileUploaded['insert_id'];

        $result = $reservation->save();

        if ($isFileUploaded['message'] !== "No insertion is made") {
            $reservation->associateFileWithReservation();
        }

        if ($result['success'] === true) {
            echo '<script type="text/javascript">';
            echo 'alert("' . $result['message'] . '");';
            echo 'location.href = \'index.php?p=member&sec=discussion_room_reservation_tab\';';
            echo '</script>';
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("' . $result['message'] . '");';
            echo 'location.href = \'index.php?p=member&sec=discussion_room_reservation_tab\';';
            echo '</script>';
            exit();
        }
    }
}

function updateReservation($self)
{
    if (isset($_POST['updateRecordID']) && isset($_POST['updateReservationData'])) {
        global $dbs;

        $updateRecordID = $dbs->escape_string($_POST['updateRecordID']);
        $reservation = new Reservation();

        // Reservation::getById($updateRecordID);

        if ($reservation) {
            $reservation->reservationId = $updateRecordID;
            $reservation->major = $_POST['major'];
            $reservation->whatsAppNumber = formatWhatsAppNumberInto62Format($_POST['whatsAppNumber']);
            $reservation->reservedDate = $_POST['reservationDate'];
            $reservation->duration = $_POST['duration'];

            $timeRange = $_POST['availableSchedule'];
            $times = explode(" - ", $timeRange);
            $reservation->startTime = $times[0];
            $reservation->endTime = $times[1];

            $reservation->visitorNumber = $_POST['visitorNumber'];
            $reservation->activity = $_POST['activity'];

            $timestamp = date('Y-m-d H:i:s');
            $reservation->reservation_date = $timestamp;
            $reservation->reservationLastUpdate = $timestamp;

            $isFileUploaded = uploadFile($_POST['memberId']);
            $reservation->reservationDocumentId = $isFileUploaded['insert_id'];

            $result = $reservation->updateReservation();

            if ($isFileUploaded['message'] !== "No insertion is made") {
                $reservation->associateFileWithReservation();
            }

            if ($result['success'] === true) {
                utility::jsToastr('Reservasi onsite', $result['message'], 'success');
                echo '<script>parent.$("#mainContent").simbioAJAX("' . $self . '")</script>';
                exit();
            } else {
                utility::jsToastr('Reservasi onsite', $result['message'], 'error');
                exit();
            }
        } else {
            echo "Reservation not found.";
            exit;
        }
    }
}

function cancelReservation($self)
{
    if (isset($_POST['itemID']) && !empty($_POST['itemID']) && isset($_POST['itemAction'])) {
        $fail = 0;

        foreach ($_POST['itemID'] as $itemID) {
            $delete = Reservation::deleteById($itemID);

            if (!$delete) {
                $fail++;
            }
        }

        if (!$fail) {
            utility::jsToastr('Onsite Reservation', 'Berhasil membatalkan reservasi', 'success');
            echo '<script>parent.$("#mainContent").simbioAJAX("' . $self . '")</script>';
            exit;
        } else {
            utility::jsToastr('Onsite Reservation', 'Gagal membatalkan reservasi', 'error');
            exit;
        }
    }
}

function cancelReservationByMember($self)
{
    if (isset($_POST['itemID'])) {
        $delete = Reservation::deleteById($_POST['itemID']);
        $deleteDocument = deleteReservationDocument($_POST['itemID']); // Remove this if you wanna keep the reservation document

        if ($delete) {
            echo '<script type="text/javascript">';
            echo 'alert("' . 'Berhasil' . '");';
            echo 'location.href = \'index.php?p=member&sec=discussion_room_reservation_tab\';';
            echo '</script>';
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("' . 'Gagal' . '");';
            echo 'location.href = \'index.php?p=member&sec=discussion_room_reservation_tab\';';
            echo '</script>';
            exit;
        }
    }
}

function getReservationByMemberId($memberId)
{
    $reservation = new Reservation();

    return $reservation->retrieveReservationByMemberId($memberId);
}

function uploadFile($memberId)
{
    global $sysconf;

    ob_start();
    if (isset($_FILES['reservationDocumentInput'])) {
        $uploaded_file_id = 0;
        $title = 'spr_' . $memberId;
        $url = '';
        $fileDesc = '';
        $fileKey = '';

        // FILE UPLOADING
        if ($_FILES['reservationDocumentInput']['error'] == 1) {
            return ["success" => false, "message" => "Invalid attachment, make sure your file is not exceeded system max upload", "insert_id" => 0];
        }

        if (isset($_FILES['reservationDocumentInput']) and $_FILES['reservationDocumentInput']['size']) {
            $file_dir = '';
            // create upload object
            $file_upload = Storage::repository()->upload('reservationDocumentInput', function ($repository) use ($sysconf) {

                // Extension check
                $repository->isExtensionAllowed();

                // File size check
                $repository->isLimitExceeded($sysconf['max_upload'] * 1024);

                // destroy it if failed
                if (!empty($repository->getError()))
                    $repository->destroyIfFailed();
            })->as(md5(date('Y-m-d H:i:s'))); // set new name

            if ($file_upload->getUploadStatus()) {
                $file_ext = $file_upload->getExt($file_upload->getUploadedFileName());

                $reservation = new Reservation();
                $reservation->uploaderId = $uploaded_file_id;
                $reservation->fileTitle = $title;
                $reservation->fileName = $file_upload->getUploadedFileName();
                $reservation->fileURL = $url;
                $reservation->fileDir = $file_dir;
                $reservation->fileDesc = $fileDesc;
                $reservation->fileKey = $fileKey;
                $reservation->mimeType = $sysconf['mimetype'][trim($file_ext, '.')] ?? '';
                $inputDate = date('Y-m-d H:i:s');
                $reservation->inputDate = $inputDate;
                $reservation->lastUpdate = $inputDate;

                $result = $reservation->insertFile();
                $insertId = $result['insert_id'];

                if ($result['success'] === true && $insertId !== 0) {
                    $uploaded_file_id = $insertId;
                    return ["success" => $result['success'], "message" => $result['message'], "insert_id" => $insertId];
                }
            } else {
                return ["success" => false, "message" => "Upload FAILED! Forbidden file type or file size too big!", "insert_id" => 0];
            }
        }
    }

    return ["success" => false, "message" => "No insertion is made", "insert_id" => 0];
}

function deleteReservationDocument($reservationId)
{
    $fileName = Reservation::getFileNameFromMemberId($reservationId);

    if ($fileName) {
        $repository = Storage::repository();

        $repository->delete('/' . $fileName);
        return true; // Return true if deletion is successful
    } else {
        return false; // Return false if file name retrieval fails
    }

}

function updateStatusForExpiredReservations()
{
    Reservation::updateStatusForExpiredReservations();
}

function dirCheckPermission()
{
    $msg = '';
    if (!is_writable(SB . 'lib' . DS . 'contents' . DS)) {
        $msg = 'Direktori : <b>' . SB . 'lib' . DS . 'contents' . DS . '</b> tidak dapat ditulis!. Harap merubah permission pada folder tersebut.';
    }

    return $msg;
}

function getCurrentUrl($query = [])
{

    return $_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge(['mod' => $_GET['mod'], 'id' => $_GET['id']], $query));
}

function getMajorList()
{
    return [
        'S1 Teknik Informatika',
        'S1 Software Engineering',
        'S1 Sistem Informasi',
        'S1 Sains Data',
        'S1 Teknik Telekomunikasi',
        'D3 Teknik Telekomunikasi',
        'S1 Automation Technology',
        'S1 Teknik Biomedis',
        'S1 Teknologi Pangan',
        'S1 Teknik Industri',
        'S1 Desain Komunikasi Visual',
        'S1 Digital Logistic',
        'S1 Bisnis Digital',
        'S1 Product Innovation',
        'D3  Teknik Digital',
        'Lainnya'
    ];
}