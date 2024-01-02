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
use SLiMS\Migration\Migration;

class CreateRoomReservations extends Migration
{
    public function up()
    {
        DB::getInstance()->query(<<<SQL
        CREATE TABLE IF NOT EXISTS `room_reservations` (
            `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
            `reservation_date` datetime DEFAULT NULL,
            `name` varchar(100) DEFAULT NULL,
            `member_id` int(11) DEFAULT NULL,
            `major` varchar(100) DEFAULT NULL,
            `whatsapp_number` varchar(100) DEFAULT NULL,
            `reserved_date` date DEFAULT NULL,
            `duration` varchar(100) DEFAULT NULL,
            `start_time` time DEFAULT NULL,
            `end_time` time DEFAULT NULL,
            `reservation_document_id` int(11) DEFAULT NULL,
            `visitor_number` int(1) DEFAULT NULL,
            `activity` varchar(100) DEFAULT NULL,
            `status` enum('ongoing','completed','cancelled') DEFAULT 'ongoing',
            `last_update` datetime DEFAULT NULL,
            PRIMARY KEY (`reservation_id`),
            KEY `fk_room_reservations_member` (`member_id`) USING BTREE
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
        SQL);
    }

    public function down()
    {

    }
}