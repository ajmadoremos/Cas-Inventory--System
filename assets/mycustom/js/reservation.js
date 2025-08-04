$(document).ready(function () {
    // Load pending reservations
    $('.tbl_pendingres').DataTable({
        ajax: 'fetch_reservation.php?type=pending',
        columns: [
            { title: "Name", data: 0 },
            { title: "Items", data: 1 },
            { title: "Date", data: 2 },
            { title: "Room", data: 3 },
            { title: "Edit", data: 4 },
            { title: "Actions", data: 5 }
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

    // ✅ Cancel Reservation Button Handler
    $(document).on('click', '.btn-cancel', function () {
        const code = $(this).data('id');
        $('input[name="codereserve"]').val(code);
        $('#myModal').modal('show');
    });

    // ✅ Edit Reservation Button Handler
    $(document).on('click', '.btn-edit', function () {
        const code = $(this).data('id');
        const items = $(this).data('items');

        let checklistHtml = '';
        items.split('<br/>').forEach(function (item) {
            if (item.trim()) {
                checklistHtml += `
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="approved_items[]" value="${item.trim()}">
                            ${item.trim()}
                        </label>
                    </div>`;
            }
        });

        $('#itemsChecklist').html(checklistHtml);
        $('#reservation_code').val(code);
        $('#editReservationModal').modal('show');
    });

    // Optional: View handler
    $(document).on('click', '.view-btn', function () {
        const code = $(this).data('id');
        alert('Reservation Code: ' + code);
    });
});

// ✅ Load user-specific reservations (outside of ready if not needed on all pages)
var currentUserId = 21;
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
