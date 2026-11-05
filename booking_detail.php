<?php
require __DIR__ . '/lib/bootstrap.php';
 
// 1) Đọc ref từ URL (?? '' để không bị lỗi khi thiếu ref)
$ref = input_string($_GET, 'ref');
 
// 2) Tìm booking trong Session
$index   = find_booking_index($ref);
$booking = ($index !== null) ? $_SESSION['bookings'][$index] : null;
 
// 3) Tìm workshop trong catalog để lấy đơn giá (unit fee)
$workshop = $booking ? find_workshop_by_title($workshops, (string)$booking['workshop']) : null;
 
// 4) Không tìm thấy → trả mã 404 (vẫn hiện trang thân thiện bên dưới)
if ($booking === null) {
    http_response_code(404);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Booking Details</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="<?= e($theme) ?>">
<main class="wrap">
<header>
<h1>Booking Details</h1>
<nav>
<a href="index.php">Catalog</a>
<a href="dashboard.php">My bookings</a>
</nav>
</header> 
<?php if ($booking === null): ?>
    <div class="panel">
        <p><strong>Booking not found.</strong></p>
        <p class="muted">The booking reference is missing, wrong, or your session has expired.</p>
        <a class="btn btn-light" href="dashboard.php">Back to My Bookings</a>
    </div>
<?php else: ?>
    <div class="panel">
        <dl class="detail">
            <dt>Booking Reference</dt>
            <dd><span class="badge"><?= e($booking['ref']) ?></span></dd>
            <dt>Attendee</dt>
            <dd><?= e($booking['full_name']) ?></dd>
            <dt>Email</dt>
            <dd><?= e($booking['email']) ?></dd>
            <dt>Workshop</dt>
            <dd><?= e($booking['workshop']) ?></dd>
            <dt>Quantity</dt>
            <dd><?= e($booking['quantity']) ?> tickets</dd>
            <dt>Unit Fee</dt>
            <dd><?= $workshop ? number_format($workshop['fee']) . ' VND' : 'N/A' ?></dd>
            <dt>Total Amount</dt>
            <dd><strong><?= number_format($booking['amount']) ?> VND</strong></dd>
        </dl>
        <div class="actions">
            <a class="btn btn-edit" href="booking_edit.php?ref=<?= urlencode($booking['ref']) ?>">Edit this booking</a>
            <a class="btn btn-light" href="dashboard.php">Back to My Bookings</a>
        </div>
    </div>
    <p class="muted">Booking information is read from the current PHP session.</p>
<?php endif; ?>
</main>
</body>
</html>