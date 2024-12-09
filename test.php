<?php
// 引入 db.php 以進行資料庫連線
include 'php/db.php';

// 檢查是否成功連線
if (!isset($_SESSION['link'])) {
    die("資料庫連線失敗！");
}

// 獲取連線物件
$conn = $_SESSION['link'];

// 定義需要查詢的資料表
$tables = ['20Q19C', '5Q4C', '8Q', '6Q'];

// 查詢並印出每個資料表中是否包含指定的 `table_name`
foreach ($tables as $table) {
    $query = "SELECT DISTINCT table_name FROM `$table`";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "資料表 `$table` 存在，名稱如下：<br/>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo $row['table_name'] . "<br/>";
            }
        } else {
            echo "資料表 `$table` 存在，但沒有符合條件的 `table_name` 資料。<br/>";
        }
    } else {
        echo "查詢 `$table` 時發生錯誤：" . mysqli_error($conn) . "<br/>";
    }
}

// 關閉連線
mysqli_close($conn);
?>