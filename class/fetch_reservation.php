<?php
require_once "../config/config.php";

$type = $_GET['type']; // 'pending' or 'accepted'
$status = ($type == 'pending') ? 0 : 1;
$memberId = isset($_GET['member_id']) ? intval($_GET['member_id']) : 0;

$queryStr = "
    SELECT r.reservation_code, m.m_fname, m.m_lname, i.i_model, r.reserve_date, rm.rm_name
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
    $data[] = [
        'name' => ucwords($row['m_fname'] . ' ' . $row['m_lname']),
        'item' => $row['i_model'],
        'date' => $row['reserve_date'],
        'room' => $row['rm_name'],
        'action' => '<button class="btn btn-info btn-xs view-btn" data-id="' . $row['reservation_code'] . '">View</button>'
    ];
}

echo json_encode(['data' => $data]);