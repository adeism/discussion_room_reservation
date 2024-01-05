<?php

$reservationsList = getReservationByMemberId($_SESSION['mid']);

$reservationsList = array_filter($reservationsList, function ($reservation) {
    return $reservation->status === 'ongoing';
});

usort($reservationsList, function ($a, $b) {
    return sortByDateField($a, $b, 'reservation_date');
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['itemID']) && !empty($_POST['itemID']) && isset($_POST['itemAction']) && $_POST['itemAction'] === 'cancel') {
        cancelReservationByMember('index.php?p=member&sec=discussion_room_reservation_tab');
    }
}
?>

<div class="list-group">
    <?php if (count($reservationsList) != 0): ?>
        <?php foreach ($reservationsList as $reservation): ?>
            <div class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <h5 class="mb-1">
                        <?= $reservation->activity ?>
                    </h5>
                    <?php if (strtotime($reservation->reservedDate . ' ' . $reservation->startTime) <= date(strtotime(date('Y-m-d H:i')))): ?>
                        <small style="color: #FFFFFF;" class="badge bg-primary">In Use</small>
                    <?php else: ?>
                        <small>
                            <form method="post">
                                <!-- Add a hidden input field for itemID -->
                                <input type="hidden" name="itemID" value="<?= $reservation->reservationId ?>">

                                <!-- Add a submit button to trigger the form submission -->
                                <button type="submit" class="btn btn-danger btn-sm" name="itemAction" value="cancel">Cancel</button>
                            </form>
                        </small>
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
            No reservation activity
        </div>
    <?php endif; ?>
</div>