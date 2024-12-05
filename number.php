<?php
include './php/db.php';

// 驗證是否有 `id` 並從資料庫獲取對應的 `die_number`
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT die_number FROM records WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($die_number);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Invalid ID");
}

// 增加卡片功能
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $stmt = $conn->prepare("UPDATE records SET die_number = die_number + 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<script>alert('新增卡片成功！'); window.location.href='number.php?id=$id';</script>";
        } else {
            echo "<script>alert('新增卡片失敗！');</script>";
        }
        $stmt->close();
    }

    // 刪除卡片功能
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        if ($die_number > 0) {
            $stmt = $conn->prepare("UPDATE records SET die_number = die_number - 1 WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>alert('刪除卡片成功！'); window.location.href='number.php?id=$id';</script>";
            } else {
                echo "<script>alert('刪除卡片失敗！');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('無法刪除卡片，已經沒有卡片了！');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/number.css">
    <title>Filtered Card Layout</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row" id="card-container">
            <?php if ($die_number > 0): ?>
                <?php for ($i = 1; $i <= $die_number; $i++): ?>
                    <div class="col-md-4 mb-4">
                        <!-- 卡片可點擊 -->
                        <a href="method.php?id=<?php echo htmlspecialchars($id); ?>&number=<?php echo $i; ?>"
                            class="text-decoration-none card-link">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Card Number: <?php echo $i; ?></h5>
                                    <p class="card-text">This is card number <?php echo $i; ?> out of
                                        <?php echo $die_number; ?>.
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endfor; ?>
            <?php else: ?>
                <p>No cards to display for this ID.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 顯示錯誤訊息 -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <!-- 增加卡片按鈕 -->
    <form method="POST" class="mt-4 d-flex gap-2">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <button type="submit" name="action" value="add" class="btn btn-primary floating-button">+</button>
        <button type="submit" name="action" value="delete"
            class="btn btn-danger floating-button delete-button">-</button>
    </form>
</body>

</html>