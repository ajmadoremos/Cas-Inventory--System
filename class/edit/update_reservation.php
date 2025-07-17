<?php
require_once "../config/config.php";

$response = ['status' => 0, 'message' => 'Something went wrong'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_code = $_POST['reservation_code'] ?? '';
    $admin_feedback = $_POST['admin_feedback'] ?? '';
    $approved_items = $_POST['approved_items'] ?? [];

    if (empty($reservation_code)) {
        $response['message'] = 'Reservation code is required.';
        echo json_encode($response);
        exit;
    }

    try {
        // Step 1: Get all item_ids in this reservation
        $stmt = $conn->prepare("
            SELECT reservation.id as res_id, item.id as item_id
            FROM reservation
            LEFT JOIN item_stock ON item_stock.id = reservation.stock_id
            LEFT JOIN item ON item.id = item_stock.item_id
            WHERE reservation.reservation_code = ?
        ");
        $stmt->execute([$reservation_code]);
        $all_reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $disapproved_items = [];
        foreach ($all_reservations as $res) {
            if (!in_array($res['item_id'], $approved_items)) {
                // Unchecked item - mark for removal
                $disapproved_items[] = $res;
            }
        }

        // Step 2: Delete unchecked items (disapproved)
        foreach ($disapproved_items as $item) {
            $del = $conn->prepare("DELETE FROM reservation WHERE id = ?");
            $del->execute([$item['res_id']]);
        }

        // Step 3: Fetch readable names of disapproved items
        $disapproved_names = [];
        if (!empty($disapproved_items)) {
            $item_ids = array_column($disapproved_items, 'item_id');
            $placeholders = implode(',', array_fill(0, count($item_ids), '?'));
            $query = "SELECT i_deviceID, i_category FROM item WHERE id IN ($placeholders)";
            $stmt = $conn->prepare($query);
            $stmt->execute($item_ids);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $disapproved_names[] = $row['i_deviceID'] . ' - ' . $row['i_category'];
            }
        }

        // Step 4: Create remark message
        if (!empty($disapproved_names)) {
            $item_feedback = implode(", ", $disapproved_names);
            $final_remark = "Not approved: $item_feedback. Reason: $admin_feedback";
        } else {
            $final_remark = "All items approved.";
        }

        // Step 5: Update remarks in reservation_status table
        $update = $conn->prepare("UPDATE reservation_status SET remark = ? WHERE reservation_code = ?");
        $update->execute([$final_remark, $reservation_code]);

        $response['status'] = 1;
        $response['message'] = 'Reservation updated successfully.';
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    echo json_encode($response);
}
