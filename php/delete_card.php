<?php
include 'db.php'; // 引入資料庫連線

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // 開始事務
    $conn->begin_transaction();

    try {
        // 刪除 process_data 表中所有與該 record_id 相關的記錄
        $stmt = $conn->prepare("DELETE FROM process_data WHERE record_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // 刪除 records 表中的記錄
        $stmt = $conn->prepare("DELETE FROM records WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // 提交事務
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // 回滾事務
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => '刪除失敗: ' . $e->getMessage()]);
    }
}
?>