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

class ReservationHistory extends \SLiMS\Migration\Migration
{
    public function up()
    {
        DB::getInstance()->query("CREATE TABLE IF NOT EXISTS `reservation_history` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                    `reservation_date` DATETIME DEFAULT NULL,
                                    `name` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
                                    `student_id` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
                                    `major` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
                                    `whatsapp_number` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
                                    `visitor_number` int(1) NOT NULL,
                                    `activity` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
                                    `status` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL
                                ) ENGINE='MyISAM';");
    }

    public function down()
    {
        
    }
}