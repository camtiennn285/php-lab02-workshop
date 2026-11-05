<?php
require __DIR__ . '/lib/bootstrap.php';
 
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'GET' && $method !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}
 
// POST thì phải có CSRF token đúng, nếu không dừng ngay
if ($method === 'POST' && !csrf_valid($_POST)) {
    http_response_code(403);
    exit('Invalid CSRF token. Please reload the form and try again.');
}
 
// Lấy ref: lúc mở trang đến từ URL (GET), lúc lưu đến từ ô hidden (POST)
$ref   = input_string($method === 'POST' ? $_POST : $_GET, 'ref');
$index = find_booking_index($ref);
$booking = ($index !== null) ? $_SESSION['bookings'][$index] : null;
 
if ($booking === null) {
    http_response_code(404);
}
 
// Workshop và đơn giá luôn lấy từ catalog trên server
$workshop = $booking ? find_workshop_by_title($workshops, (string)$booking['workshop']) : null;
 
$errors = [];
$old = $booking ? [
    'full_name' => $booking['full_name'],
    'email'     => $booking['email'],
    'quantity'  => $booking['quantity'],
] : [];
 
// Xử lý lưu (chỉ khi POST + tìm thấy booking + tìm thấy workshop)
if ($method === 'POST' && $booking !== null && $workshop !== null) {
    $result = validate_booking([
        'full_name'   => input_string($_POST, 'full_name'),
        'email'       => input_string($_POST, 'email'),
        'quantity'    => input_string($_POST, 'quantity'),
        'workshop_id' => $workshop['id'],      // từ catalog, KHÔNG lấy từ form
    ], $workshops);
 
    $errors = $result['errors'];
    $old    = $result['data'];
 
    if ($errors) {
        // Giữ nguyên chuỗi người dùng đã gõ ở ô Tickets để họ thấy mình nhập sai gì
        $old['quantity'] = input_string($_POST, 'quantity');
    } else {
        // Chỉ cập nhật đúng booking tại $index
        $_SESSION['bookings'][$index]['full_name'] = $old['full_name'];
        $_SESSION['bookings'][$index]['email']     = $old['email'];
        $_SESSION['bookings'][$index]['quantity']  = $old['quantity'];
        // Tính lại tiền từ đơn giá catalog × số vé đã validate
        $_SESSION['bookings'][$index]['amount']    = $workshop['fee'] * $old['quantity'];
 
        $_SESSION['flash'] = 'Booking updated successfully.';
        redirect_to('dashboard.php');          // 303 + exit
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Booking</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="<?= e($theme) ?>">
<main class="wrap">
<header>
<h1>Edit Booking</h1>
<nav>
<a href="index.php">Catalog</a>
<a href="dashboard.php">My bookings</a>
</nav>
</header>
 
<?php if ($booking === null || $workshop === null): ?>
    <div class="panel">
        <p><strong>Booking not found.</strong></p>
        <p class="muted">The booking reference is missing, wrong, or your session has expired.</p>
        <a class="btn btn-light" href="dashboard.php">Back to My Bookings</a>
    </div>
<?php else: ?>
    <form method="post" action="booking_edit.php" class="panel form-stack" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="ref" value="<?= e($ref) ?>">
 
        <label>Booking Reference
            <input value="<?= e($booking['ref']) ?>" readonly>
        </label>
        <label>Workshop
            <input value="<?= e($booking['workshop']) ?>" readonly>
        </label>
 
        <label>Full Name
            <input name="full_name" value="<?= e($old['full_name'] ?? '') ?>">
        </label>
        <small class="error"><?= e($errors['full_name'] ?? '') ?></small>
 
        <label>Email
            <input name="email" value="<?= e($old['email'] ?? '') ?>">
        </label>
        <small class="error"><?= e($errors['email'] ?? '') ?></small>
 
        <label>Tickets (1–4)
            <input name="quantity" type="number" min="1" max="4" value="<?= e($old['quantity'] ?? '') ?>">
        </label>
        <small class="error"><?= e($errors['quantity'] ?? '') ?></small>
 
        <hr>
        <p><strong>Current total: <?= number_format($booking['amount']) ?> VND</strong><br>
        <span class="muted">Unit fee: <?= number_format($workshop['fee']) ?> VND - Total will be recalculated after saving.</span></p>
 
        <div class="actions" style="border:0;padding:0;margin:0">
            <button>Save Changes</button>
            <a class="btn btn-light" href="dashboard.php">Cancel</a>
        </div>
    </form>
    <p class="muted">The server validates name, email and ticket quantity, verifies the CSRF token and updates only this booking in $_SESSION.</p>
<?php endif; ?>
</main>
</body>
</html>