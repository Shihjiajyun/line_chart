<?php
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$number = isset($_GET['number']) ? $_GET['number'] : 0;

?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>製程卡片展示</title>
    <!-- 引入 Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/method.css">
</head>

<body>

    <div class="container mt-4">
        <div class="row">
            <?php
            // 定義製程卡片的名稱
            $processes = [
                'lithography',
                'AD clean',
                'Deposition',
                'Liftoff',
                'Measurement (as fab.)',
                'T1'
            ];

            // 迭代每個製程並生成卡片
            foreach ($processes as $process) {
                // 將製程名稱轉換為 URL 安全的格式
                $processTag = urlencode($process);
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='card' onclick=\"location.href='data.php?id=$id&number=$number&process=$processTag'\">"; // 點擊卡片可跳轉
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>$process</h5>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- 引入 Bootstrap JS 和 jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>