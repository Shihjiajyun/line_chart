<?php
require_once 'db.php';
header("Content-Type: application/json");

try {
    $conn = $_SESSION['link'];

    $record_id = $_POST['record_id'];
    $record_number = $_POST['record_number'];
    $method = $_POST['method'];

    // 檢查是否存在相同的記錄
    $checkQuery = "SELECT * FROM process_data WHERE record_id = ? AND record_number = ? AND method = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("iis", $record_id, $record_number, $method);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 更新現有記錄
        $updateQuery = "UPDATE process_data SET mask = ?, chips = ?, uv_o_zone_min = ?, hf_49_min = ?, anneal = ?, coating_spin_617 = ?, coating_spin_6200 = ?, dose_bc_current_1_nA = ?, dose_bc_step_size_1_um = ?, dose_bc_time_1_us = ?, dose_bc_area_dose_1_uC_cm2 = ?, development_bc_546_sec = ?, development_bc_502_ratio = ?, dose_sc_current_2_nA = ?, dose_sc_step_size_2_um = ?, dose_sc_time_2_us = ?, development_sc_546_sec = ?, development_sc_502_ratio = ?, note = ? WHERE record_id = ? AND record_number = ? AND method = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssiiisiiiiisiiisissiis", $_POST['mask'], $_POST['chips'], $_POST['uv_o_zone_min'], $_POST['hf_49_min'], $_POST['anneal'], $_POST['coating_spin_617'], $_POST['coating_spin_6200'], $_POST['dose_bc_current_1_nA'], $_POST['dose_bc_step_size_1_um'], $_POST['dose_bc_time_1_us'], $_POST['dose_bc_area_dose_1_uC_cm2'], $_POST['development_bc_546_sec'], $_POST['development_bc_502_ratio'], $_POST['dose_sc_current_2_nA'], $_POST['dose_sc_step_size_2_um'], $_POST['dose_sc_time_2_us'], $_POST['development_sc_546_sec'], $_POST['development_sc_502_ratio'], $_POST['note'], $record_id, $record_number, $method);
    } else {
        // 插入新記錄
        $insertQuery = "INSERT INTO process_data (record_id, record_number, method, mask, chips, uv_o_zone_min, hf_49_min, anneal, coating_spin_617, coating_spin_6200, dose_bc_current_1_nA, dose_bc_step_size_1_um, dose_bc_time_1_us, dose_bc_area_dose_1_uC_cm2, development_bc_546_sec, development_bc_502_ratio, dose_sc_current_2_nA, dose_sc_step_size_2_um, dose_sc_time_2_us, development_sc_546_sec, development_sc_502_ratio, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iisssiiisiiiiisiiisiss", $record_id, $record_number, $method, $_POST['mask'], $_POST['chips'], $_POST['uv_o_zone_min'], $_POST['hf_49_min'], $_POST['anneal'], $_POST['coating_spin_617'], $_POST['coating_spin_6200'], $_POST['dose_bc_current_1_nA'], $_POST['dose_bc_step_size_1_um'], $_POST['dose_bc_time_1_us'], $_POST['dose_bc_area_dose_1_uC_cm2'], $_POST['development_bc_546_sec'], $_POST['development_bc_502_ratio'], $_POST['dose_sc_current_2_nA'], $_POST['dose_sc_step_size_2_um'], $_POST['dose_sc_time_2_us'], $_POST['development_sc_546_sec'], $_POST['development_sc_502_ratio'], $_POST['note']);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "數據已成功保存"]);
    } else {
        throw new Exception("保存數據失敗：" . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>