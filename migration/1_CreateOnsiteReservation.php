<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-03-28 10:28:36
 * @modify date 2022-03-28 10:31:11
 * @license GPLv3
 * @desc [description]
 */

use SLiMS\DB;

class CreateOnsiteReservation extends \SLiMS\Migration\Migration
{
    public function up()
    {
        DB::getInstance()->query("CREATE TABLE IF NOT EXISTS `room_reservations` (
                                    `reservation_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                    `reservation_date` DATETIME DEFAULT NULL,
                                    `name` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
                                    `student_id` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
                                    `major` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
                                    `whatsapp_number` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
                                    `reserved_date` DATE DEFAULT NULL,
                                    `duration` int(1) NULL,
                                    `start_time` TIME DEFAULT NULL,
                                    `end_time` TIME DEFAULT NULL,
                                    `reservation_document_id` int(11) NOT NULL AUTO_INCREMENT,
                                    `visitor_number` int(1) NULL,
                                    `activity` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL
                                ) ENGINE='MyISAM';");
    }

    public function down()
    {
        
    }
}