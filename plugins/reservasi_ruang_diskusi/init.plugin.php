<?php
/**
 * Plugin Name: Reservasi Ruang Diskusi
 * Plugin URI: https://github.com/nazaralwi
 * Description: Plugin untuk Reservasi Ruang Diskusi
 * Version: 1.0.0
 * Author: Nazar Alwi
 * Author URI: https://nazaralwi.com
 */

// Discussion Room Reservation Base
define('DRRB', __DIR__);
require_once DRRB. '/lib/vendor/autoload.php';
// require_once DRRB . DS . 'app/helper/reservation_utils.php';

// get plugin instance
$plugin = \SLiMS\Plugins::getInstance();

// updateStatusForExpiredReservations();

// registering menus
$plugin->registerMenu('membership', 'Reservasi Ruang Diskusi', DRRB . DS . 'app/index_admin.php');
$plugin->registerMenu('opac', 'Member', DRRB . DS . 'app/index_member.php');
$plugin->registerMenu('opac', 'Jadwal Ruang Diskusi', DRRB . DS . 'app/index_opac.php');

// For AJAX request
$plugin->registerMenu('opac', 'Populate Schedule', DRRB . DS . 'app/reservation_logic/populate_schedule.php');
$plugin->registerMenu('opac', 'Reservation Calendar', DRRB . DS . 'app/opac/reservation_schedule_for_ajax.php');

// Hook
// $plugins->register('membership_init', function() {
    
// });