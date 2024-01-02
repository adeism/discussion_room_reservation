<?php
defined('INDEX_AUTH') OR die('Direct access not allowed!');

// IP based access limitation
require LIB . 'ip_based_access.inc.php';
do_checkIP('smc');
do_checkIP('smc-membership');

// start the session
// Set dependencies
require SB . 'admin/default/session.inc.php';
require SIMBIO . 'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO . 'simbio_GUI/form_maker/simbio_form_table_AJAX.inc.php';
require SIMBIO . 'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO . 'simbio_DB/datagrid/simbio_dbgrid.inc.php';
require DRRB . DS . 'app/helper/reservation_utils.php';
// End dependencies

// privileges checking
$can_read = utility::havePrivilege('membership', 'r');

if (!$can_read) {
    die('<div class="errorBox">' . __('You are not authorized to view this section') . '</div>');
}

use DiscussionRoomReservation\Lib\Url;

$page_title = 'Reservasi Ruang Diskusi';

updateStatusForExpiredReservations();

updateReservation(getCurrentUrl(['reservationScheduleList' => 1]));
reserveScheduleOnsite(getCurrentUrl(['reservationScheduleList' => 1]));
cancelReservation(getCurrentUrl(['reservationScheduleList' => 1]));
?>

<div class="menuBox">
    <div class="menuBoxInner memberIcon">
        <div class="per_title">
            <h2><?= $page_title ?></h2>
        </div>
        <div class="sub_section">
            <div class="btn-group">
                <a href="<?= getCurrentUrl(['reservationScheduleList' => 1]) ?>" class="btn btn-primary">Daftar Jadwal Reservasi</a>
                <a href="<?= getCurrentUrl(['reservationScheduleHistoryList' => 1]) ?>" class="btn btn-primary">Daftar Riwayat Reservasi</a>
                <a href="<?= getCurrentUrl(['onsiteReservation' => 1]) ?>" class="btn btn-success">Reservasi Onsite</a>
            </div>
        </div>
    </div>
</div>

<?php
switch (true) {
    case isset($_GET['onsiteReservation']):
        include __DIR__ . '/admin/onsite_reservation_form.inc.php';
        break;

    case isset($_GET['reservationScheduleList']):
        include __DIR__ . '/admin/onsite_reservation_grid.inc.php';
        break;

    case isset($_GET['reservationScheduleHistoryList']):
        include __DIR__ . '/admin/onsite_reservation_history_grid.inc.php';
        break;

    case (isset($_POST['detail']) || (isset($_GET['editReservation']))):
        include __DIR__ . '/admin/onsite_reservation_edit.inc.php';
        break;
    
    default:
        include __DIR__ . '/admin/onsite_reservation_grid.inc.php';
        break;
}
?>
<script>
    document.querySelectorAll('.editLink').forEach((el) => {
        el.href = el.getAttribute('href').replace(/(\&reservationScheduleList=1)/g, '');
    });

    const form = document.querySelector('.simbio_form_maker');
    if (form !== null) {
        form.setAttribute('action', '<?= getCurrentUrl() ?>');
    }
</script>