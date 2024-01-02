<?php

$reservationsList = getReservationByMemberId($_SESSION['mid']);

$reservationsList = array_filter($reservationsList, function ($reservation) {
  return $reservation->status === 'ongoing';
});

usort($reservationsList, 'sortByDate');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['itemID']) && !empty($_POST['itemID']) && isset($_POST['itemAction']) && $_POST['itemAction'] === 'cancel') {
      cancelReservationByMember('index.php?p=member&sec=discussion_room_reservation_tab');
  }
}
?>

<div class="list-group">
  <?php if (count($reservationsList) != 0) : ?>
    <?php foreach ($reservationsList as $reservation): ?>
      <a href="" class="list-group-item list-group-item-action flex-column align-items-start">
        <div class="d-flex w-100 align-items-center justify-content-between">
          <h5 class="mb-1"><?= $reservation->activity ?></h5>
          <?php if (strtotime($reservation->reservedDate.' '.$reservation->startTime) <= date(strtotime(date('Y-m-d H:i')))): ?>
            <small style="color: #FFFFFF;" class="badge bg-primary">In Use</small>
          <?php else: ?>
            <small>
            <form method="post">
                <!-- Add a hidden input field for itemID -->
                <input type="hidden" name="itemID" value="<?= $reservation->reservationId ?>">

                <!-- Add a submit button to trigger the form submission -->
                <button type="submit" class="" name="itemAction" value="cancel">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                  </svg>
                </button>
            </form>
            </small>
          <?php endif; ?>  
        </div>
        <p class="mb-1">Jadwal: <?= convertDate($reservation->reservedDate) . ' <strong>' . getMinutesAndSecond($reservation->startTime) . '</strong>-<strong>' . getMinutesAndSecond($reservation->endTime) . '</strong>'?></p>
        <small>Jumlah anggota: <?= $reservation->visitorNumber ?> orang</small>
      </a>
    <?php endforeach; ?>
  <?php else: ?>
      <div class="alert alert-secondary text-center" role="alert">
        No reservation activity
      </div>
  <?php endif; ?>
</div>