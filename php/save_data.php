<?php
require_once 'db.php';
header("Content-Type: application/json");

// 確保資料庫連接初始化
if (!isset($_SESSION['link'])) {
    echo json_encode(["status" => "error", "message" => "資料庫連接未初始化"]);
    exit;
}
if ($_SESSION['link']->connect_error) {
    echo json_encode(["status" => "error", "message" => "資料庫連接錯誤: " . $_SESSION['link']->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// 從網址中抓取參數
$record_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$record_number = isset($_GET['number']) ? intval($_GET['number']) : null;
$method = isset($_GET['process']) ? $_GET['process'] : null;

// 驗證數據
if (!$data || $record_id === null || $record_number === null || empty($method)) {
    echo json_encode(["status" => "error", "message" => "無效的數據"]);
    exit;
}

try {
    $conn = $_SESSION['link'];

    // 將數據轉換為 JSON 格式
    $jsonData = json_encode($data);
    if ($jsonData === false) {
        echo json_encode(["status" => "error", "message" => "JSON 編碼失敗", "error" => json_last_error_msg()]);
        exit;
    }

    // 插入或更新數據
    $stmt = $conn->prepare("
        INSERT INTO process_data (record_id, record_number, method, data)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        data = VALUES(data)
    ");

    // 綁定參數
    $stmt->bind_param("iiss", $record_id, $record_number, $method, $jsonData);

    // 執行操作
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 1) {
            echo json_encode(["status" => "success", "message" => "數據已更新"]);
        } else {
            echo json_encode(["status" => "success", "message" => "數據已新增"]);
        }
    } else {
        throw new Exception("操作失敗：" . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
