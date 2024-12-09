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

// 初始化變數
$selectedItem = null;

// 處理提交的表單數據
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedItem'])) {
    $selectedItem = $_POST['selectedItem'];

    // 尋找選擇的 table_name 所屬的資料表
    $foundTable = null;
    foreach ($tables as $table) {
        $query = "SELECT * FROM `$table` WHERE `table_name` = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $selectedItem);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $foundTable = $table;
            break;
        }
    }

    if ($foundTable) {
        echo "<h3>您選擇的項目：$selectedItem'（屬於資料表 `$foundTable`）</h3>";

        // 查詢該資料表中與選擇的項目相關的所有數據
        $query = "SELECT * FROM `$foundTable` WHERE `table_name` = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $selectedItem);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            echo '<table border="1">';
            echo '<tr>';
            $fields = mysqli_fetch_fields($result);
            foreach ($fields as $field) {
                echo '<th>' . htmlspecialchars($field->name) . '</th>';
            }
            echo '</tr>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                foreach ($row as $value) {
                    echo '<td>' . htmlspecialchars($value) . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "<p>無法查詢到相關數據。</p>";
        }
    } else {
        echo "<p>未找到選擇的項目。</p>";
    }
}

// 開始生成 HTML 表單
echo '<form action="" method="post">';
echo '<label for="selectedItem">選擇項目：</label>';
echo '<select name="selectedItem" id="selectedItem">';

// 查詢每個資料表中的 `table_name` 值
foreach ($tables as $table) {
    $query = "SELECT DISTINCT `table_name` FROM `$table`";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "<optgroup label='資料表 `$table`'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . htmlspecialchars($row['table_name']) . '">' . htmlspecialchars($row['table_name']) . '</option>';
            }
            echo "</optgroup>";
        }
    }
}

echo '</select>';
echo '<button type="submit">提交</button>';
echo '</form>';

// 關閉連線
mysqli_close($conn);
?>
