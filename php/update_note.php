<?php
include 'db.php'; // 引入資料庫連線

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => '未知錯誤',
];

// 確保請求是 POST，並解析 JSON
$input = json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['id'], $input['note'], $input['table'])) {
    $id = $input['id'];
    $note = $input['note'];
    $table = $input['table'];

    // 驗證資料
    if (!empty($id) && !empty($table)) {
        $updateQuery = "UPDATE `$table` SET `note` = ? WHERE `id` = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "si", $note, $id);

        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
            $response['message'] = '資料庫更新失敗';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = '無效的資料';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = '非法請求';
}

echo json_encode($response);
exit();
