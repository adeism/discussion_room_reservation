<?php
/**
 * Plugin Name: Reservasi Ruang Diskusi
 * Plugin URI: https://github.com/nazaralwi/discussion_room_reservation
 * Description: Plugin untuk Reservasi Ruang Diskusi
 * Version: 1.0.0
 * Author: Nazar Alwi
 * Author URI: https://nazaralwi.com
 */

// Discussion Room Reservation Base
define('DRRB', __DIR__);

require_once DRRB . DS . 'lib/vendor/autoload.php';
require_once DRRB . DS . 'app/helper/common.php';
require_once DRRB . DS . 'app/models/Reservation.php';

use SLiMS\Plugins;

// Get plugin instance
$plugin = Plugins::getInstance();

// Registering menus
$plugin->registerMenu('membership', 'Reservasi Ruang Diskusi', DRRB . DS . 'app/index_admin.php');
$plugin->registerMenu('opac', 'Member', DRRB . DS . 'app/index_member.php');
$plugin->registerMenu('opac', 'Jadwal Ruang Diskusi', DRRB . DS . 'app/index_opac.php');

// For AJAX request
$plugin->registerMenu('opac', 'Populate Schedule', DRRB . DS . 'app/reservation_logic/populate_schedule.php');
$plugin->registerMenu('opac', 'Reservation Calendar', DRRB . DS . 'app/opac/AJAX_reservation_schedule.php');