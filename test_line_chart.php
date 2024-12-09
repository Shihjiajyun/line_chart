<?php
// --- PHP 部分：處理資料庫邏輯 ---
include 'php/db.php'; // 引入資料庫連線

// 檢查資料庫連線
if (!isset($_SESSION['link'])) {
    die("資料庫連線失敗！");
}

// 獲取連線物件
$conn = $_SESSION['link'];

// 定義需要查詢的資料表
$tables = ['20Q19C', '5Q4C', '8Q', '6Q'];

// 初始化變數
$selectedItem = null;
$datasets = []; // 存放多條折線數據集
$chartLabels = [];
$tableHtml = '';
$optionsHtml = ''; // 用於存放表單選項的 HTML

// 查詢每個資料表的 `table_name` 值，生成選項 HTML
foreach ($tables as $table) {
    $query = "SELECT DISTINCT `table_name` FROM `$table`";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $optionsHtml .= "<optgroup label='資料表 $table'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $optionsHtml .= '<option value="' . htmlspecialchars($row['table_name']) . '">' . htmlspecialchars($row['table_name']) . '</option>';
        }
        $optionsHtml .= '</optgroup>';
    }
}

// 處理提交的表單數據
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedItem'])) {
    $selectedItem = $_POST['selectedItem'];
    $foundTable = null;

    // 查找選擇的 table_name 所屬資料表
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

    // 如果找到相關資料表
    if ($foundTable) {
        $query = "SELECT * FROM `$foundTable` WHERE `table_name` = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $selectedItem);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            // 動態生成表格，使用 Bootstrap 樣式
            $tableHtml .= '<table class="table table-bordered table-hover">';
            $fields = mysqli_fetch_fields($result);

            // 表頭
            $tableHtml .= '<thead><tr class="table-primary">';
            foreach ($fields as $field) {
                $tableHtml .= '<th>' . htmlspecialchars($field->name) . '</th>';
            }
            $tableHtml .= '</tr></thead>';

            // 初始化欄位標籤（水平軸）
            foreach ($fields as $field) {
                if (strpos($field->name, 'Q') === 0 || strpos($field->name, 'C') === 0) {
                    $chartLabels[] = $field->name; // 儲存水平軸標籤
                }
            }
            $chartLabels = array_unique($chartLabels);

            // 生成表格數據
            $tableHtml .= '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                $tableHtml .= '<tr>';
                $data = [];
                foreach ($row as $key => $value) {
                    $tableHtml .= '<td>' . htmlspecialchars($value) . '</td>';
                    if (strpos($key, 'Q') === 0 || strpos($key, 'C') === 0) {
                        $data[] = $value;
                    }
                }
                $tableHtml .= '</tr>';

                // 每一筆數據作為一條折線
                $datasets[] = [
                    'label' => 'ID: ' . $row['id'], // 假設有 'id' 欄位
                    'data' => $data,
                    'borderColor' => 'rgba(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ', 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'pointRadius' => 5,
                ];
            }
            $tableHtml .= '</tbody>';
            $tableHtml .= '</table>';
        }
    }
}

// JSON 格式化圖表數據
$chartLabelsJson = json_encode(array_values($chartLabels));
$datasetsJson = json_encode($datasets);

// 關閉連線
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>多折線圖表</title>
    <!-- 引入 Bootstrap 和 Chart.js -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* 限制折線圖的高度 */
        #lineChartContainer {
            max-height: 500px;
        }

        canvas {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container my-4">
        <!-- 標題 -->
        <div class="row">
            <div class="col-12">
                <h1 class="text-center text-primary">多折線圖表</h1>
            </div>
        </div>

        <!-- 下拉表單和折線圖 -->
        <div class="row">
            <!-- 表單部分 -->
            <div class="col-md-4">
                <form action="" method="post" class="p-3 border rounded bg-white shadow">
                    <h5 class="text-center">選擇項目</h5>
                    <div class="mb-3">
                        <label for="selectedItem" class="form-label">選擇數據</label>
                        <select name="selectedItem" id="selectedItem" class="form-select">
                            <?php echo $optionsHtml; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">提交</button>
                </form>
            </div>

            <!-- 折線圖部分 -->
            <div class="col-md-8">
                <div class="p-3 border rounded bg-white shadow" id="lineChartContainer">
                    <h5 class="text-center">折線圖</h5>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 數據表格 -->
        <div class="row mt-4">
            <div class="col-12">
                <?php if (!empty($tableHtml)): ?>
                    <div class="p-3 border rounded bg-white shadow">
                        <h5 class="text-center">數據表格</h5>
                        <div class="table-responsive">
                            <?php echo $tableHtml; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- --- JS 部分：Chart.js 折線圖 --- -->
    <script>
        const ctx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo $chartLabelsJson; ?>, // 水平軸標籤
                datasets: <?php echo $datasetsJson; ?> // 多條數據集
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // 圖表比例可調整
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: '欄位名稱'
                        }
                    },
                    y: {
                        beginAtZero: false, // 關閉從 0 開始
                        min: 1, // 縱軸最小值
                        max: 10, // 縱軸最大值
                        title: {
                            display: true,
                            text: '數值'
                        }
                    }
                },
                plugins: {
                    annotation: {
                        annotations: [
                            // 註記紅色線條
                            {
                                type: 'line',
                                mode: 'vertical',
                                scaleID: 'x',
                                value: 3.5,
                                borderColor: 'red',
                                borderWidth: 2
                            },
                            {
                                type: 'line',
                                mode: 'vertical',
                                scaleID: 'x',
                                value: 8,
                                borderColor: 'red',
                                borderWidth: 2
                            },
                            // 區間背景色
                            {
                                type: 'box',
                                xMin: 2.5,
                                xMax: 3.5,
                                backgroundColor: 'rgba(255, 0, 0, 0.5)',
                            },
                            {
                                type: 'box',
                                xMin: 7,
                                xMax: 9,
                                backgroundColor: 'rgba(0, 0, 255, 0.5)',
                            },
                            // 灰色區間背景色
                            {
                                type: 'box',
                                xMin: 2,
                                xMax: 2.5,
                                backgroundColor: 'rgba(128, 128, 128, 0.5)',
                            },
                            {
                                type: 'box',
                                xMin: 3.5,
                                xMax: 4,
                                backgroundColor: 'rgba(128, 128, 128, 0.5)',
                            },
                            {
                                type: 'box',
                                xMin: 6,
                                xMax: 7,
                                backgroundColor: 'rgba(128, 128, 128, 0.5)',
                            },
                            {
                                type: 'box',
                                xMin: 9,
                                xMax: 12,
                                backgroundColor: 'rgba(128, 128, 128, 0.5)',
                            }
                        ]
                    }
                }
            }
        });
    </script>
</body>

</html>