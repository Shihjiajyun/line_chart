<?php
// 包含数据库连接文件
include 'db.php';

// 检查是否收到 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取 POST 数据
    $id = $_POST['id'] ?? null;
    $date = $_POST['date'] ?? null;
    $sub_title = $_POST['sub_title'] ?? null;
    $text = $_POST['text'] ?? null;
    $status = $_POST['status'] ?? null;

    // 定义合法的 status 选项
    $valid_statuses = ['store', 'measure', 'for-ncu', 'sem', 'nuce', 'pending'];

    // 验证数据完整性
    if (!$id || !$date || !$sub_title || !$status) {
        echo json_encode([
            'success' => false,
            'message' => '所有字段都是必填。'
        ]);
        exit;
    }

    if (!in_array($status, $valid_statuses)) {
        echo json_encode([
            'success' => false,
            'message' => '無效的 status 值。'
        ]);
        exit;
    }

    // 准备 SQL 更新语句
    $sql = "UPDATE records SET date = ?, sub_title = ?, text = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode([
            'success' => false,
            'message' => '准备 SQL 语句失败：' . $conn->error
        ]);
        exit;
    }

    // 绑定参数
    $stmt->bind_param('ssssi', $date, $sub_title, $text, $status, $id);

    // 执行语句
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '卡片已經完成更新。'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '更新失败：' . $stmt->error
        ]);
    }

    // 关闭语句
    $stmt->close();
} else {
    // 非 POST 请求返回错误
    echo json_encode([
        'success' => false,
        'message' => '無效的請求'
    ]);
}

// 关闭数据库连接
$conn->close();
