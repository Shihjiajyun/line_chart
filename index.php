<?php
include './php/db.php';

// 獲取資料庫中的所有記錄
$sql = "SELECT * FROM records ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <title>Card Layout with Bootstrap 5</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row" id="card-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <a href="<?php echo htmlspecialchars($row['id']); ?>" class="text-decoration-none">
                            <div class="card <?php echo htmlspecialchars($row['status']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title">Date: <?php echo htmlspecialchars($row['date']); ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Sub title: <?php echo htmlspecialchars($row['sub_title']); ?></h6>
                                    <p class="card-text">Die Number: <?php echo htmlspecialchars($row['die_number']); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>目前沒有任何一張卡片</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Floating Button to Open Modal -->
    <button class="floating-button" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>

    <!-- Modal to Add New Card -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a New Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-card-form">
                        <div class="mb-3">
                            <label for="card-date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="card-date" required>
                        </div>
                        <div class="mb-3">
                            <label for="card-subtitle" class="form-label">Sub Title</label>
                            <select class="form-select" id="card-subtitle" required>
                                <option value="Store">Store</option>
                                <option value="MEASURE">MEASURE</option>
                                <option value="For NCU">For NCU</option>
                                <option value="SEM">SEM</option>
                                <option value="NUCE">NUCE</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="card-text" class="form-label">Card Text</label>
                            <textarea class="form-control" id="card-text" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Card</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('add-card-form').addEventListener('submit', function(event) {
            event.preventDefault();

            // 獲取表單資料
            const date = document.getElementById('card-date').value;
            const subtitle = document.getElementById('card-subtitle').value;
            const text = document.getElementById('card-text').value;

            // 判斷卡片類型
            let cardClass = '';
            switch (subtitle) {
                case 'Store':
                    cardClass = 'status-store';
                    break;
                case 'MEASURE':
                    cardClass = 'status-measure';
                    break;
                case 'For NCU':
                    cardClass = 'status-for-ncu';
                    break;
                case 'SEM':
                    cardClass = 'status-sem';
                    break;
                case 'NUCE':
                    cardClass = 'status-nuce';
                    break;
                case 'Pending':
                    cardClass = 'status-pending';
                    break;
            }

            // 發送資料到後端
            fetch('php/add_card.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `date=${encodeURIComponent(date)}&sub_title=${encodeURIComponent(subtitle)}&status=${encodeURIComponent(cardClass)}&text=${encodeURIComponent(text)}`,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // 清空表單
                        document.getElementById('add-card-form').reset();

                        // 關閉模態框
                        const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                        modal.hide();

                        // 提示成功訊息
                        alert('卡片創建成功，請重新整理來顯示卡片');
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>