<?php
require_once "../../config/config.php";

$response = ['status' => 0, 'message' => 'Something went wrong'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_code = $_POST['reservation_code'];
    $admin_feedback = $_POST['admin_feedback'];
    $approved_items = $_POST['approved_items'] ?? [];

    // ✅ Step 1: Update reservation table with feedback
    $stmt = $conn->prepare("UPDATE reservation SET admin_feedback = ? WHERE reservation_code = ?");
    if (!$stmt) {
        $response['message'] = "Prepare failed (feedback update): " . $conn->error;
        echo json_encode($response);
        exit;
    }
    $stmt->bind_param("ss", $admin_feedback, $reservation_code);
    if (!$stmt->execute()) {
        $response['message'] = "Execute failed (feedback update): " . $stmt->error;
        echo json_encode($response);
        exit;
    }

    // ✅ Step 2: Clear previous reservation items (if any)
    $stmt = $conn->prepare("DELETE FROM reservation_items WHERE reservation_code = ?");
    if (!$stmt) {
        $response['message'] = "Prepare failed (delete items): " . $conn->error;
        echo json_encode($response);
        exit;
    }
    $stmt->bind_param("s", $reservation_code);
    if (!$stmt->execute()) {
        $response['message'] = "Execute failed (delete items): " . $stmt->error;
        echo json_encode($response);
        exit;
    }

    // ✅ Step 3: Re-insert only approved items
    $success = true;
    foreach ($approved_items as $item) {
        $stmt = $conn->prepare("INSERT INTO reservation_items (reservation_code, item_name) VALUES (?, ?)");
        if (!$stmt) {
            $success = false;
            continue;
        }
        $stmt->bind_param("ss", $reservation_code, $item);
        if (!$stmt->execute()) {
            $success = false;
        }
    }

    if ($success) {
        $response = ['status' => 1, 'message' => 'Reservation updated'];
    } else {
        $response['message'] = 'Some items failed to update';
    }
}

echo json_encode($response);
