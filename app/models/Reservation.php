<?php

class Reservation
{
    public $reservationId;
    public $name;
    public $memberId;
    public $major;
    public $whatsAppNumber;
    public $reservedDate;
    public $duration;
    public $startTime;
    public $endTime;
    public $reservationDocumentId;
    public $visitorNumber;
    public $activity;
    public $reservation_date;
    public $status;
    public $reservationLastUpdate;

    public $fileId;
    public $uploaderId;
    public $fileTitle;
    public $fileName;
    public $fileURL;
    public $fileDir;
    public $fileDesc;
    public $fileKey;
    public $mimeType;
    public $inputDate;
    public $lastUpdate;

    public static function getById($reservationId)
    {
        global $dbs;

        $sql = "SELECT * FROM room_reservations WHERE reservation_id = ?";
        $stmt = $dbs->prepare($sql);
        $stmt->bind_param("i", $reservationId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $reservation = $result->fetch_object('Reservation');
            return $reservation;
        } else {
            return null;
        }
    }

    public static function getAll()
    {
        global $dbs;

        $sql = "SELECT * FROM room_reservations";
        $result = $dbs->query($sql);

        $reservations = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object('Reservation')) {
                $reservations[] = $row;
            }
        }

        return $reservations;
    }

    public static function getAllEvents()
    {
        global $dbs;

        $sql = "SELECT name, reserved_date, start_time, end_time, activity FROM room_reservations WHERE status != 'cancelled'";
        $result = $dbs->query($sql);

        $events = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Create Reservation objects manually and populate them with fetched data
                $reservation = new Reservation();
                $reservation->name = $row['name'];
                $reservation->reservedDate = $row['reserved_date'];
                $reservation->startTime = $row['start_time'];
                $reservation->endTime = $row['end_time'];
                $reservation->activity = $row['activity'];

                $events[] = $reservation;
            }
        }

        return $events;
    }

    public static function getBookedSchedules()
    {
        global $dbs;

        $sql = "SELECT reserved_date, start_time, end_time FROM room_reservations WHERE status != 'cancelled' AND status != 'completed'";
        $result = $dbs->query($sql);

        $schedule = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // return as mapped schedule instead of Reservation's object
                $schedule[] = [
                    'start_date' => $row['reserved_date'],
                    'end_date' => $row['reserved_date'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                ];
            }
        }

        return $schedule;
    }

    public static function getBookedSchedules1()
    {
        global $dbs;

        $sql = "SELECT reserved_date, start_time, end_time FROM room_reservations";
        $result = $dbs->query($sql);

        $schedule = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // return as mapped schedule instead of Reservation's object
                $schedule[] = [
                    'start_date' => $row['reserved_date'],
                    'end_date' => $row['reserved_date'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                ];
            }
        }

        return $schedule;
    }

    public function save()
    {
        global $dbs;

        $sql = "INSERT INTO room_reservations (name, member_id, major, whatsapp_number, reserved_date, duration, start_time, end_time, reservation_document_id, visitor_number, activity, reservation_date, last_update) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $dbs->prepare($sql);
        $stmt->bind_param("sissssssiisss", $this->name, $this->memberId, $this->major, $this->whatsAppNumber, $this->reservedDate, $this->duration, $this->startTime, $this->endTime, $this->reservationDocumentId, $this->visitorNumber, $this->activity, $this->reservation_date, $this->reservationLastUpdate);

        // Check if the reservation already exists before inserting
        $existingReservation = $this->checkExistingReservation();

        if ($existingReservation) {
            // A reservation already exists with the same date, start time, and end time
            return ["success" => false, "message" => "Error: This schedule is already reserved."];
        }

        // Proceed with the insertion if no existing reservation is found
        if ($stmt->execute()) {
            $this->reservationId = $stmt->insert_id;
            return ["success" => true, "message" => "Reservation saved successfully."];
        } else {
            return ["success" => false, "message" => "Error: Failed to insert reservation."];
        }
    }

    public function updateReservation()
    {
        global $dbs;

        $sql = "UPDATE room_reservations SET major=?, whatsapp_number=?, reserved_date=?, duration=?, start_time=?, end_time=?, visitor_number=?, activity=?, reservation_document_id=? WHERE reservation_id=?";

        $stmt = $dbs->prepare($sql);
        $stmt->bind_param("ssssssisii", $this->major, $this->whatsAppNumber, $this->reservedDate, $this->duration, $this->startTime, $this->endTime, $this->visitorNumber, $this->activity, $this->reservationDocumentId, $this->reservationId);

        // Check if the reservation already exists before inserting
        $existingReservation = $this->checkExistingReservation();

        if ($existingReservation) {
            // A reservation already exists with the same date, start time, and end time
            return ["success" => false, "message" => "Error: This schedule is already reserved."];
        }

        // Proceed with the insertion if no existing reservation is found
        if ($stmt->execute()) {
            $this->reservationId = $stmt->insert_id;
            return ["success" => true, "message" => "Reservation saved successfully."];
        } else {
            return ["success" => false, "message" => "Error: Failed to update reservation."];
        }
    }

    public function retrieveReservationByMemberId($memberId)
    {
        global $dbs;

        $sql = 'SELECT * FROM room_reservations WHERE member_id = ' . $memberId . ';';
        $result = $dbs->query($sql);

        $reservations = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reservation = new Reservation();
                $reservation->reservationId = $row['reservation_id'];
                $reservation->reservation_date = $row['reservation_date'];
                $reservation->name = $row['name'];
                $reservation->memberId = $row['member_id'];
                $reservation->major = $row['major'];
                $reservation->whatsAppNumber = $row['whatsapp_number'];
                $reservation->reservedDate = $row['reserved_date'];
                $reservation->duration = $row['duration'];
                $reservation->startTime = $row['start_time'];
                $reservation->endTime = $row['end_time'];
                $reservation->reservationDocumentId = $row['reservation_document_id'];
                $reservation->visitorNumber = $row['visitor_number'];
                $reservation->activity = $row['activity'];
                $reservation->status = $row['status'];
                $reservation->reservationLastUpdate = $row['last_update'];
                $reservations[] = $reservation;
            }
        }

        return $reservations;
    }

    public function insertFile()
    {
        global $dbs;

        $sql = "INSERT INTO files (uploader_id, file_title, file_name, file_url, file_dir, file_desc, file_key, mime_type, input_date, last_update) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $dbs->prepare($sql);
        $stmt->bind_param("ssssssssss", $this->uploaderId, $this->fileTitle, $this->fileName, $this->fileURL, $this->fileDir, $this->fileDesc, $this->fileKey, $this->mimeType, $this->inputDate, $this->lastUpdate);

        if ($stmt->execute()) {
            $insertId = $stmt->insert_id;
            $this->fileId = $insertId;
            return ["success" => true, "message" => "File uploaded successfully.", "insert_id" => $insertId];
        } else {
            return ["success" => false, "message" => "Error: Failed to upload file.", "insert_id" => 0];
        }
    }

    public function associateFileWithReservation()
    {
        global $dbs;

        $sql = "UPDATE room_reservations SET reservation_document_id = ? WHERE reservation_id = ?";
        $stmt = $dbs->prepare($sql);
        $stmt->bind_param("ii", $this->reservationDocumentId, $this->reservationId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function checkExistingReservation()
    {
        global $dbs;

        $sql = "SELECT * FROM room_reservations 
        WHERE reserved_date = ? 
        AND (
            (start_time <= ? AND end_time >= ?) OR            -- Case: New reservation overlaps existing
            (start_time >= ? AND end_time <= ?) OR            -- Case: New reservation is within existing
            (start_time < ? AND end_time > ?) OR              -- Case: New reservation's start_time is within existing
            (start_time < ? AND end_time > ?)                 -- Case: New reservation's end_time is within existing
        )
        AND status != 'cancelled' AND status != 'completed'";

        $stmt = $dbs->prepare($sql);
        $stmt->bind_param(
            "sssssssss",
            $this->reservedDate,
            $this->startTime,
            $this->endTime,                  // For overlapping case
            $this->startTime,
            $this->endTime,                  // For new reservation within existing
            $this->startTime,
            $this->startTime,                // For new reservation's start_time within existing
            $this->endTime,
            $this->endTime                     // For new reservation's end_time within existing
        );
        $stmt->execute();

        $result = $stmt->get_result();
        $existingReservations = $result->fetch_all(MYSQLI_ASSOC);

        return $existingReservations; // Returns existing reservation data if found, otherwise returns an empty array
    }

    public static function updateStatusForExpiredReservations()
    {
        global $dbs;

        $sql = "UPDATE room_reservations 
            SET status = 'completed', last_update = NOW()
            WHERE status = 'ongoing' AND 
            (reserved_date < CURRENT_DATE() OR (reserved_date = CURRENT_DATE() AND end_time < CURRENT_TIME()))";

        $dbs->query($sql);
    }

    public static function deleteById($reservationId)
    {
        global $dbs;

        $sql = "UPDATE room_reservations 
        SET status = 'cancelled', last_update = NOW()
        WHERE reservation_id = ?";

        $stmt = $dbs->prepare($sql);
        $stmt->bind_param("i", $reservationId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function getFileNameFromMemberId($reservationId)
    {
        global $dbs;

        $sql = "SELECT files.file_name
        FROM room_reservations
        JOIN files ON room_reservations.reservation_document_id = files.file_id
        WHERE room_reservations.reservation_id = ?";

        $stmt = $dbs->prepare($sql);
        $stmt->bind_param("i", $reservationId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['file_name']; // Return the file_name value    
        } else {
            return null; // Return null if execution fails
        }
    }
}