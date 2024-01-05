<?php

$reservationsHistory = getReservationByMemberId($_SESSION['mid']);

$reservationsHistory = array_filter($reservationsHistory, function ($reservation) {
    return $reservation->status !== 'ongoing';
});

usort($reservationsHistory, function ($a, $b) {
    return sortByDateField($a, $b, 'reservationLastUpdate');
});

if (count($reservationsHistory) > 10) {
    $reservationsHistory = array_slice($reservationsHistory, 0, 10);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['itemID']) && !empty($_POST['itemID']) && isset($_POST['itemAction']) && $_POST['itemAction'] === 'cancel') {
        cancelReservationByMember('index.php?p=member&sec=discussion_room_reservation_tab');
    }
}
?>

<div class="list-group">
    <?php if (count($reservationsHistory) != 0): ?>
        <?php foreach ($reservationsHistory as $reservation): ?>
            <div class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <h5 class="mb-1">
                        <?= $reservation->activity ?>
                    </h5>
                    <?php if ($reservation->status == 'cancelled'): ?>
                        <small class="badge badge-danger">Cancelled</small>
                    <?php elseif (($reservation->status == 'completed')): ?>
                        <small class="badge badge-success">Completed</small>
                    <?php endif; ?>
                </div>
                <p class="mb-1">Jadwal:
                    <?= convertDate($reservation->reservedDate) . ' <strong>' . getMinutesAndSecond($reservation->startTime) . '</strong>-<strong>' . getMinutesAndSecond($reservation->endTime) . '</strong>' ?>
                </p>
                <small>Jumlah anggota:
                    <?= $reservation->visitorNumber ?> orang
                </small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-secondary text-center" role="alert">
            No reservation history
        </div>
    <?php endif; ?>
</div>