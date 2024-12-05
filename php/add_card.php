<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 接收表單資料
    $date = $_POST['date'] ?? '';
    $subTitle = $_POST['sub_title'] ?? '';
    $status = $_POST['status'] ?? '';
    $dieNumber = 1; // 預設為 1

    // 驗證資料
    if (empty($date) || empty($subTitle) || empty($status)) {
        echo json_encode(['success' => false, 'message' => '資料不完整！']);
        exit;
    }

    // 插入資料
    $sql = "INSERT INTO records (status, date, sub_title, die_number) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssi', $status, $date, $subTitle, $dieNumber);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => '資料已新增！']);
    } else {
        echo json_encode(['success' => false, 'message' => '資料新增失敗！']);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo json_encode(['success' => false, 'message' => '請使用 POST 方法提交資料！']);
}
