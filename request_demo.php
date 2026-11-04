<?php
// 1. Phần xử lý PHP
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$getName = (string)($_GET['name'] ?? '');
$postName = (string)($_POST['name'] ?? '');
$requestName = (string)($_REQUEST['name'] ?? '');
?>

<!-- 2. Phần giao diện HTML để test -->
<!doctype html>
<html>
<body>
    <h2>Kiểm tra biến toàn cục</h2>
    <p><strong>Method hiện tại:</strong> <?= htmlspecialchars($method) ?></p>
    <p><strong>$ _GET['name']:</strong> <?= htmlspecialchars($getName) ?></p>
    <p><strong>$ _POST['name']:</strong> <?= htmlspecialchars($postName) ?></p>
    <p><strong>$ _REQUEST['name']:</strong> <?= htmlspecialchars($requestName) ?></p>

    <hr>
    <!-- Form thử nghiệm GET -->
    <form method="get">
        <input name="name" placeholder="Nhập tên cho GET">
        <button>Gửi bằng GET</button>
    </form>
    <br>
    <!-- Form thử nghiệm POST -->
    <form method="post">
        <input name="name" placeholder="Nhập tên cho POST">
        <button>Gửi bằng POST</button>
    </form>
</body>
</html>