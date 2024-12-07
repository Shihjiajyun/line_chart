<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    if (($handle = fopen($file, 'r')) !== FALSE) {
        // 讀取標題
        $headers = fgetcsv($handle, 1000, ',');
        echo "<h2>標題</h2>";
        echo "<ul>";
        foreach ($headers as $header) {
            echo "<li>" . htmlspecialchars($header) . "</li>";
        }
        echo "</ul>";

        // 讀取數據
        echo "<h2>數據</h2>";
        echo "<table border='1'>";
        echo "<tr>";
        foreach ($headers as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr>";

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            echo "<tr>";
            foreach ($data as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";

        fclose($handle);
    } else {
        echo "無法打開檔案。";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <title>CSV 上傳</title>
</head>

<body>
    <h1>上傳 CSV 檔案</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <input type="submit" value="上傳">
    </form>
</body>

</html>