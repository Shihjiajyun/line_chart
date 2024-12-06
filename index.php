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
        <div class="d-flex justify-content-end mb-3">
            <a href="filter.php" class="btn btn-primary">前往後台資料表</a>
        </div>
        <div class="row" id="card-container">
            <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card text-white status-<?php echo htmlspecialchars($row['status']); ?> shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">
                            <strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?>
                        </h5>
                        <h6 class="card-subtitle mb-2">
                            <strong>Sub title:</strong> <?php echo htmlspecialchars($row['sub_title']); ?>
                        </h6>
                        <p class="card-text">
                            <strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?>
                        </p>
                        <p class="card-text">
                            <strong>Die Number:</strong> <?php echo htmlspecialchars($row['die_number']); ?>
                        </p>
                        <p class="card-text">
                            <strong>備註:</strong> <?php echo htmlspecialchars($row['text']); ?>
                        </p>
                        <!-- 按鈕 -->
                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-light edit-button"
                                data-id="<?php echo htmlspecialchars($row['id']); ?>"
                                data-date="<?php echo htmlspecialchars($row['date']); ?>"
                                data-subtitle="<?php echo htmlspecialchars($row['sub_title']); ?>"
                                data-text="<?php echo htmlspecialchars($row['text']); ?>"
                                data-status="<?php echo htmlspecialchars($row['status']); ?>" data-bs-toggle="modal"
                                data-bs-target="#editModal" onclick="event.stopPropagation();">
                                編輯
                            </button>
                            <a href="number.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-light">
                                查看詳情
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p class="text-center text-muted">目前沒有任何一張卡片</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">編輯卡片</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-card-form">
                        <input type="hidden" id="edit-card-id">
                        <!-- Date Field -->
                        <div class="mb-3">
                            <label for="edit-card-date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit-card-date" required>
                        </div>
                        <!-- Status Field (Select) -->
                        <div class="mb-3">
                            <label for="edit-card-status" class="form-label">Status</label>
                            <select class="form-select" id="edit-card-status" required>
                                <option value="store">store</option>
                                <option value="measure">measure</option>
                                <option value="for-ncu">for-ncu</option>
                                <option value="sem">sem</option>
                                <option value="nuce">nuce</option>
                                <option value="pending">pending</option>
                            </select>
                        </div>
                        <!-- Sub Title Field (Text Input) -->
                        <div class="mb-3">
                            <label for="edit-card-subtitle" class="form-label">Sub Title</label>
                            <input type="text" class="form-control" id="edit-card-subtitle" placeholder="輸入自定義子標題"
                                required>
                        </div>
                        <!-- Card Text Field -->
                        <div class="mb-3">
                            <label for="edit-card-text" class="form-label">Card Text</label>
                            <textarea class="form-control" id="edit-card-text" rows="3"></textarea>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">更新卡片</button>
                        <!-- Delete Button -->
                        <button type="button" class="btn btn-danger" id="delete-card-button">刪除卡片</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button class="floating-button" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>

    <!-- Modal to Add New Card -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">新增卡片</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-card-form">
                        <input type="hidden" id="add-card-id">
                        <!-- Date Field -->
                        <div class="mb-3">
                            <label for="add-card-date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="add-card-date" required>
                        </div>
                        <!-- Status Field (Select) -->
                        <div class="mb-3">
                            <label for="add-card-status" class="form-label">Status</label>
                            <select class="form-select" id="add-card-status" required>
                                <option value="store">store</option>
                                <option value="measure">measure</option>
                                <option value="for-ncu">for-ncu</option>
                                <option value="sem">sem</option>
                                <option value="nuce">nuce</option>
                                <option value="pending">pending</option>
                            </select>
                        </div>
                        <!-- Sub Title Field (Text Input) -->
                        <div class="mb-3">
                            <label for="add-card-subtitle" class="form-label">Sub Title</label>
                            <input type="text" class="form-control" id="add-card-subtitle" placeholder="輸入自定義子標題"
                                required>
                        </div>
                        <!-- Card Text Field -->
                        <div class="mb-3">
                            <label for="add-card-text" class="form-label">Card Text</label>
                            <textarea class="form-control" id="add-card-text" rows="3"></textarea>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">新增卡片</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 編輯卡片 -->
    <script>
    // 刪除按鈕事件
    document.getElementById('delete-card-button').addEventListener('click', function() {
        const cardId = document.getElementById('edit-card-id').value;

        if (confirm('確定要刪除這張卡片嗎？')) {
            fetch('php/delete_card.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${encodeURIComponent(cardId)}`,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('卡片刪除成功');
                        location.reload(); // 刷新頁面以顯示更新後的數據
                    } else {
                        alert(data.message || '刪除失敗，請稍後再試');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('發生錯誤，請稍後再試');
                });
        }
    });

    // 編輯按鈕事件
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function() {
            // 獲取按鈕上的數據屬性
            const cardId = this.getAttribute('data-id');
            const cardDate = this.getAttribute('data-date');
            const cardSubtitle = this.getAttribute('data-subtitle');
            const cardText = this.getAttribute('data-text');
            const cardStatus = this.getAttribute('data-status');

            // 將數據填充到模態框中
            document.getElementById('edit-card-id').value = cardId;
            document.getElementById('edit-card-date').value = cardDate;
            document.getElementById('edit-card-subtitle').value = cardSubtitle;
            document.getElementById('edit-card-text').value = cardText;

            // 將狀態選擇填充
            const statusSelect = document.getElementById('edit-card-status');
            Array.from(statusSelect.options).forEach(option => {
                option.selected = option.value === cardStatus; // 對比選項值與卡片狀態
            });
        });
    });


    document.getElementById('edit-card-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // 获取编辑后的数据
        const id = document.getElementById('edit-card-id').value;
        const date = document.getElementById('edit-card-date').value;
        const subtitle = document.getElementById('edit-card-subtitle').value;
        const text = document.getElementById('edit-card-text').value;
        const status = document.getElementById('edit-card-status').value; // 获取 status 的值

        // 发送更新请求到后端
        fetch('php/edit_card.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${encodeURIComponent(id)}&date=${encodeURIComponent(date)}&sub_title=${encodeURIComponent(subtitle)}&text=${encodeURIComponent(text)}&status=${encodeURIComponent(status)}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 清空表单
                    document.getElementById('edit-card-form').reset();

                    // 关闭模态框
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                    modal.hide();

                    // 提示成功信息
                    alert('卡片更新成功');
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
    </script>

    <!-- 新增卡片 -->
    <script>
    document.getElementById('add-card-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // 獲取表單資料
        const date = document.getElementById('add-card-date').value;
        const subtitle = document.getElementById('add-card-subtitle').value;
        const text = document.getElementById('add-card-text').value;
        const status = document.getElementById('add-card-status').value;

        // 發送資料到後端
        fetch('php/add_card.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `date=${encodeURIComponent(date)}&sub_title=${encodeURIComponent(subtitle)}&status=${encodeURIComponent(status)}&text=${encodeURIComponent(text)}`,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // 清空表單
                    document.getElementById('add-card-form').reset();

                    // 關閉模態框
                    const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                    modal.hide();

                    // 提示成功訊息
                    alert('卡片創建成功');
                    location.reload();
                } else {
                    alert(data.message || '創建失敗，請稍後再試');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('發生錯誤，請稍後再試');
            });
    });
    </script>
</body>

</html>