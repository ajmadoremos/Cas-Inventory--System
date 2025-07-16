<?php
require_once "../config/config.php";

$type = $_GET['type']; // 'pending' or 'accepted'
$status = ($type == 'pending') ? 0 : 1;
$memberId = isset($_GET['member_id']) ? intval($_GET['member_id']) : 0;

$queryStr = "
    SELECT r.reservation_code, m.m_fname, m.m_lname, i.i_model, r.reserve_date, rm.rm_name,
        GROUP_CONCAT(CONCAT(i.i_model, ' - ', i.i_category) SEPARATOR '<br/>') AS item_borrow
    FROM reservation r
    LEFT JOIN member m ON m.id = r.member_id
    LEFT JOIN item i ON i.id = r.item_id
    LEFT JOIN room rm ON rm.id = r.assign_room
    WHERE r.status = ?
";
$params = [$status];

if ($memberId > 0) {
    $queryStr .= " AND r.member_id = ?";
    $params[] = $memberId;
}

$queryStr .= " GROUP BY r.reservation_code ORDER BY r.reserve_date DESC";

$query = $conn->prepare($queryStr);
$query->execute($params);

$data = [];

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    if ($type === 'pending') {
        $data[] = [
            ucwords($row['m_fname'] . ' ' . $row['m_lname']),                 // Name
            $row['item_borrow'],                                              // Items (HTML)
            $row['reserve_date'],                                             // Date
            $row['rm_name'],                                                  // Room

            // Edit button
            '<button class="btn btn-dark btn-edit"
                     data-id="' . $row['reservation_code'] . '"
                     data-items="' . htmlspecialchars($row['item_borrow']) . '">
                     Edit</button>',

            // Accept/Cancel buttons
            '<button class="btn btn-success btn-accept" data-id="' . $row['reservation_code'] . '">Accept</button>
             <button class="btn btn-danger btn-cancel" data-id="' . $row['reservation_code'] . '">Cancel</button>'
        ];
    } else {
        // For accepted or user-specific views
        $data[] = [
            'name'   => ucwords($row['m_fname'] . ' ' . $row['m_lname']),
            'item'   => $row['item_borrow'],
            'date'   => $row['reserve_date'],
            'room'   => $row['rm_name'],
            'action' => '<button class="btn btn-info btn-xs view-btn" data-id="' . $row['reservation_code'] . '">View</button>'
        ];
    }
}

echo json_encode(['data' => $data]);
