<?php
session_start();
include 'php/db.php'; // 引入資料庫連線

$results = [];
$fields = [
    'mask' => 'Mask',
    'chips' => 'Chips',
    'uv_o_zone_min' => 'UV O Zone Min',
    'hf_49_min' => 'HF 49 Min',
    'anneal' => 'Anneal',
    'coating_spin_617' => 'Coating Spin 617',
    'coating_spin_6200' => 'Coating Spin 6200',
    'dose_bc_current_1_nA' => 'Dose BC Current 1 nA',
    'dose_bc_step_size_1_um' => 'Dose BC Step Size 1 um',
    'dose_bc_time_1_us' => 'Dose BC Time 1 us',
    'dose_bc_area_dose_1_uC_cm2' => 'Dose BC Area Dose 1 uC/cm2',
    'development_bc_546_sec' => 'Development BC 546 Sec',
    'development_bc_502_ratio' => 'Development BC 502 Ratio',
    'dose_sc_current_2_nA' => 'Dose SC Current 2 nA',
    'dose_sc_step_size_2_um' => 'Dose SC Step Size 2 um',
    'dose_sc_time_2_us' => 'Dose SC Time 2 us',
    'development_sc_546_sec' => 'Development SC 546 Sec',
    'development_sc_502_ratio' => 'Development SC 502 Ratio',
    'note' => '備註'
];

$selected_fields = []; // 預設選擇為空

// 處理篩選請求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_fields = $_POST['fields'] ?? []; // 獲取用戶選擇的字段

    // 構建查詢
    $query = "SELECT record_id, record_number, method, date, " . implode(',', $selected_fields) . " FROM process_data INNER JOIN records ON process_data.record_id = records.id";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <title>數據篩選</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        padding: 20px;
        background-color: #f4f4f4;
    }

    h1 {
        color: #333;
    }

    form {
        margin-bottom: 20px;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin: 10px 0 5px;
    }

    input[type="checkbox"] {
        margin-right: 10px;
    }

    button {
        padding: 10px 15px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #218838;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
    </style>
</head>

<body>
    <h1>篩選數據</h1>
    <form method="POST">
        <h3>選擇要查看的數據:</h3>
        <?php foreach ($fields as $field => $label): ?>
        <label>
            <input type="checkbox" name="fields[]" value="<?php echo htmlspecialchars($field); ?>"
                <?php echo in_array($field, $selected_fields) ? 'checked' : ''; ?>>
            <?php echo htmlspecialchars($label); ?>
        </label>
        <?php endforeach; ?>
        <button type="submit">篩選</button>
    </form>

    <?php if (!empty($results)): ?>
    <h2>結果:</h2>
    <table>
        <thead>
            <tr>
                <th>日期</th>
                <th>Die Number</th>
                <th>製程</th>
                <?php foreach ($selected_fields as $field): ?>
                <th><?php echo htmlspecialchars($fields[$field]); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['date']); ?></td>
                <td><?php echo htmlspecialchars($record['record_number']); ?></td>
                <td><?php echo htmlspecialchars($record['method']); ?></td>
                <?php foreach ($selected_fields as $field): ?>
                <td><?php echo htmlspecialchars($record[$field]); ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>

</html>