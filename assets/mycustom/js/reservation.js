$(document).ready(function () {
    // Load pending reservations
    $('.tbl_pendingres').DataTable({
        ajax: 'fetch_reservation.php?type=pending',
        columns: [
            { data: 'name' },
            { data: 'item' },
            { data: 'date' },
            { data: 'room' },
            { data: 'action' }
        ]
    });

    // Load accepted reservations
    $('.tbl_reserved').DataTable({
        ajax: 'fetch_reservation.php?type=accepted',
        columns: [
            { data: 'name' },
            { data: 'item' },
            { data: 'date' },
            { data: 'room' },
            { data: 'action' }
        ]
    });

    // Optional: Handle "View" or other actions
    $(document).on('click', '.view-btn', function () {
        const code = $(this).data('id');
        alert('Reservation Code: ' + code);
        // You can trigger modals or fetch details with this code
    });
});
var currentUserId = 21; // Example: fetch this from PHP session or HTML

$('.tbl_user_reservation').DataTable({
    ajax: 'fetch_reservation.php?type=accepted&member_id=' + currentUserId,
    columns: [
        { data: 'name' },
        { data: 'item' },
        { data: 'date' },
        { data: 'room' },
        { data: 'action' }
    ]
});