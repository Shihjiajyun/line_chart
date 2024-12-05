<?php
include 'php/db.php';

// 使用 session 中的連接
$mysqli = $_SESSION['link'];

$record_id = $_GET['id'];
$record_number = $_GET['number'];
$method = $_GET['process'];

// 檢查連接
if (!$mysqli) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

// 查詢資料庫
$sql = "SELECT * FROM process_data WHERE record_id = ? AND record_number = ? AND method = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die("準備語句失敗: " . $mysqli->error);
}

$stmt->bind_param('iis', $record_id, $record_number, $method);

if (!$stmt->execute()) {
    die("執行語句失敗: " . $stmt->error);
}

$result = $stmt->get_result();
$data = $result->fetch_assoc();

// 關閉連接
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <title>Process Data</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f8f9fa;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            box-sizing: border-box;
            resize: horizontal;
            /* 允許水平調整大小 */
        }
    </style>
</head>

<body>
    <div class=" mt-4">
        <form id="dataForm">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="2">Type</th>
                        <th colspan="3">Step A</th>
                        <th colspan="2">Coating Spin</th>
                        <th colspan="4">Dose (BC)</th>
                        <th colspan="2">Development</th>
                        <th colspan="3">Dose (SC)</th>
                        <th colspan="2">Development</th>
                        <th rowspan="2">Note</th>
                    </tr>
                    <tr>
                        <th>Mask</th>
                        <th>Chips</th>
                        <th>UV O zone (min)</th>
                        <th>49% HF (min)</th>
                        <th>Anneal</th>
                        <th>617</th>
                        <th>6200</th>
                        <th>Current_1 (nA)</th>
                        <th>Step size_1 (um)</th>
                        <th>Dose time_1 (us)</th>
                        <th>Area dose_1 (uC/cm2)</th>
                        <th>546 (sec)</th>
                        <th>50/2 (sec)</th>
                        <th>Current_2 (nA)</th>
                        <th>Step size_2 (um)</th>
                        <th>Dose time_2 (us)</th>
                        <th>546 (sec)</th>
                        <th>50/2 (sec)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" class="form-control" name="mask" value="<?php echo htmlspecialchars($data['mask']); ?>"></td>
                        <td><input type="text" class="form-control" name="chips" value="<?php echo htmlspecialchars($data['chips']); ?>"></td>
                        <td><input type="number" class="form-control" name="uv_o_zone_min" value="<?php echo htmlspecialchars($data['uv_o_zone_min']); ?>"></td>
                        <td><input type="number" class="form-control" name="hf_49_min" value="<?php echo htmlspecialchars($data['hf_49_min']); ?>"></td>
                        <td><input type="text" class="form-control" name="anneal" value="<?php echo htmlspecialchars($data['anneal']); ?>"></td>
                        <td><input type="number" class="form-control" name="coating_spin_617" value="<?php echo htmlspecialchars($data['coating_spin_617']); ?>"></td>
                        <td><input type="number" class="form-control" name="coating_spin_6200" value="<?php echo htmlspecialchars($data['coating_spin_6200']); ?>"></td>
                        <td><input type="number" class="form-control" name="dose_bc_current_1_nA" value="<?php echo htmlspecialchars($data['dose_bc_current_1_nA']); ?>"></td>
                        <td><input type="number" class="form-control" name="dose_bc_step_size_1_um" value="<?php echo htmlspecialchars($data['dose_bc_step_size_1_um']); ?>"></td>
                        <td><input type="number" class="form-control" name="dose_bc_time_1_us" value="<?php echo htmlspecialchars($data['dose_bc_time_1_us']); ?>"></td>
                        <td><input type="number" class="form-control" name="dose_bc_area_dose_1_uC_cm2" value="<?php echo htmlspecialchars($data['dose_bc_area_dose_1_uC_cm2']); ?>"></td>
                        <td><input type="number" class="form-control" name="development_bc_546_sec" value="<?php echo htmlspecialchars($data['development_bc_546_sec']); ?>"></td>
                        <td><input type="text" class="form-control" name="development_bc_502_ratio" value="<?php echo htmlspecialchars($data['development_bc_502_ratio']); ?>"></td>
                        <td><input type="number" class="form-control" name="dose_sc_current_2_nA" value="<?php echo htmlspecialchars($data['dose_sc_current_2_nA']); ?>"></td>
                        <td><input type="number" class="form-control" name="dose_sc_step_size_2_um" value="<?php echo htmlspecialchars($data['dose_sc_step_size_2_um']); ?>"></td>
                        <td><input type="number" class="form-control" name="dose_sc_time_2_us" value="<?php echo htmlspecialchars($data['dose_sc_time_2_us']); ?>"></td>
                        <td><input type="number" class="form-control" name="development_sc_546_sec" value="<?php echo htmlspecialchars($data['development_sc_546_sec']); ?>"></td>
                        <td><input type="text" class="form-control" name="development_sc_502_ratio" value="<?php echo htmlspecialchars($data['development_sc_502_ratio']); ?>"></td>
                        <td><input type="text" class="form-control" name="note" value="<?php echo htmlspecialchars($data['note']); ?>"></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" onclick="saveData()">保存</button>
        </form>
    </div>

    <script>
        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                record_id: params.get('id'),
                record_number: params.get('number'),
                method: params.get('process')
            };
        }

        function saveData() {
            const urlParams = getUrlParams();
            const formData = $('#dataForm').serialize() + '&' + $.param(urlParams);

            $.ajax({
                url: 'php/save_data.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                },
                error: function() {
                    alert('保存失敗');
                }
            });
        }
    </script>

</html>