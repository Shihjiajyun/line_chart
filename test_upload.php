<?php
session_start(); // 啟用 Session

$uploadedData = [];
$process = '';
$table_name = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    include 'php/db.php'; // 引入資料庫連線

    $process = $_POST['process'];
    $file = $_FILES['csv_file']['tmp_name'];
    $uploadedData = [];
    $table_name = '';

    if (($handle = fopen($file, 'r')) !== FALSE) {
        // 讀取標題
        $headers = fgetcsv($handle, 1000, ',');

        // 去除可能的 BOM
        $headers[0] = preg_replace('/[\x{FEFF}]/u', '', $headers[0]);

        // 移除 'id' 列
        if (($key = array_search('id', $headers)) !== false) {
            unset($headers[$key]);
        }

        // 計算 Q 和 C 的數量
        $q_count = 0;
        $c_count = 0;
        foreach ($headers as $header) {
            if (preg_match('/^Q\d+$/', $header)) {
                $q_count++;
            } elseif (preg_match('/^C\d+$/', $header)) {
                $c_count++;
            }
        }

        // 構建資料表名稱
        $table_name = "{$q_count}Q";
        if ($c_count > 0) {
            $table_name .= "{$c_count}C";
        }

        // 檢查資料表是否存在
        $valid_tables = ['5Q4C', '20Q19C', '6Q', '8Q'];
        if (!in_array($table_name, $valid_tables)) {
            echo "<script>alert('請確認CSV檔案的數據完整性');</script>";
            exit;
        }

        // 讀取數據
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (isset($key)) {
                unset($data[$key]); // 移除 'id' 值
            }
            $uploadedData[] = $data;
        }

        fclose($handle);

        // 將數據存入 Session
        $_SESSION['uploadedData'] = $uploadedData;
        $_SESSION['process'] = $process;
        $_SESSION['table_name'] = $table_name;
        $_SESSION['headers'] = $headers;
    } else {
        echo "<script>alert('無法打開檔案。');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_data'])) {
    include 'php/db.php'; // 引入資料庫連線

    // 確保變數存在
    $table_name = $_SESSION['table_name'] ?? '';
    $uploadedData = $_SESSION['uploadedData'] ?? [];
    $process = $_SESSION['process'] ?? '';
    $headers = $_SESSION['headers'] ?? [];

    // 如果數據缺失，提示錯誤
    if (empty($table_name) || empty($uploadedData) || empty($process) || empty($headers)) {
        echo "<script>alert('缺少必要的資料，請重新上傳 CSV 檔案。');</script>";
        header("Location: " . $_SERVER['PHP_SELF']); // 重定向避免重複提交
        exit;
    }

    foreach ($uploadedData as $data) {
        $data[] = $process;

        $placeholders = implode(',', array_fill(0, count($data), '?'));

        $columns = $headers;
        $columns[] = 'method'; // 確保與資料表欄位名稱一致

        $columnsList = '`' . implode('`, `', $columns) . '`';
        $query = "INSERT INTO `$table_name` ($columnsList) VALUES ($placeholders)";

        $stmt = $conn->prepare($query);

        $typeString = str_repeat('d', count($data) - 1) . 's';
        $stmt->bind_param($typeString, ...$data);

        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error . "<br>";
            echo "Query: " . $query . "<br>";
            echo "Data: " . implode(", ", $data) . "<br>";
            $success = false;
            break;
        } else {
            $success = true;
        }
    }

    if ($success) {
        // 使用 JavaScript 彈出 alert 並進行重定向
        echo "<script>
            alert('數據已成功存入資料表 $table_name');
            window.location.href = '" . $_SERVER['PHP_SELF'] . "';
        </script>";

        // 清空 Session
        unset($_SESSION['uploadedData'], $_SESSION['process'], $_SESSION['table_name'], $_SESSION['headers']);
        exit; // 確保腳本停止執行
    } else {
        echo "<script>alert('數據存入失敗，請檢查輸入或資料庫設置。');</script>";
    }
    
}
?>





<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <title>CSV 上傳</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">上傳 CSV 檔案</h1>
        <form id="upload-form" action="" method="post" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="process" class="form-label">選擇製程:</label>
                <select id="process" name="process" class="form-select" required>
                    <option value="">-- 請選擇製程 --</option>
                    <option value="lithography">Lithography</option>
                    <option value="AD clean">AD Clean</option>
                    <option value="Deposition">Deposition</option>
                    <option value="Liftoff">Liftoff</option>
                    <option value="Measurement (as fab.)">Measurement (as fab.)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="csv_file" class="form-label">上傳 CSV 檔案:</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">上傳</button>
        </form>

        <?php if (!empty($uploadedData)): ?>
            <h2 class="mt-5">上傳的數據</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                    <?php foreach ($headers as $header): ?>
                        <th><?php echo htmlspecialchars($header); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uploadedData as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td><?php echo htmlspecialchars($value); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><strong>製程:</strong> <?php echo htmlspecialchars($process); ?></p>
        <form method="post">
            <input type="hidden" name="process" value="<?php echo htmlspecialchars($process); ?>">
            <input type="hidden" name="table_name" value="<?php echo htmlspecialchars($table_name); ?>">
            <button type="submit" name="save_data" class="btn btn-success">儲存</button>
        </form>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>