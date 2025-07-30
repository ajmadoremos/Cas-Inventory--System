<?php
include_once('../config/config.php');

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $user_id = intval($_POST['id']);

    try {
        // Hard delete the room permanently
        $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
        $result = $stmt->execute([$user_id]);

        if ($result) {
            echo 1;  // Success
        } else {
            echo 0;  // Failed
        }
    } catch (PDOException $e) {
        // Optional: log $e->getMessage() for debugging
        echo 0;
    }
} else {
    echo 0;
}
?>
