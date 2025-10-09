var effect = 'slide';
var options = { direction: 'right' };
var duration = 300;

$(".add_room").click(function () {
    $('.room-side').toggle(effect, options, duration);
});

$(".cancel_room").click(function () {
    $('.room-side').toggle(effect, options, duration);
});


$(".add_member").click(function () {
    $('.member-side').toggle(effect, options, duration);
});

$(".cancel_member").click(function () {
    $('.member-side').toggle(effect, options, duration);
});



$(".add_user").click(function () {
    $('.user-side').toggle(effect, options, duration);
});

$(".cancel_user").click(function () {
    $('.user-side').toggle(effect, options, duration);
});


$('select[name="select_membertype"]').on('change keyup', function(){
    var a = $(this).val();
    if(a == 1){
        $('.frm_student_sign').removeClass('hide');
        $('.frm_faculty_sign').addClass('hide');
    }else if(a == 2){
        $('.frm_faculty_sign').removeClass('hide');
        $('.frm_student_sign').addClass('hide');
    }
});



toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "200",
    "hideDuration": "3000",
    "timeOut": "3000",
    "extendedTimeOut": "3000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}


// Toggle Equipment sidebar
$('.add_equipment').click(function(){
    $('.equipment-side').toggle(effect, options, duration);
    $('.reagent-side').hide(); // hide chemical sidebar if open
});

// Toggle Chemical Reagent sidebar
$('.add_reagent').click(function(){
    $('.reagent-side').toggle(effect, options, duration);
    $('.equipment-side').hide(); // hide equipment sidebar if open
});

// Cancel buttons
$('.cancel-equipment').click(function(){
    $('.equipment-side').toggle(effect, options, duration);
});
$('.cancel-reagent').click(function(){
    $('.reagent-side').toggle(effect, options, duration);
});

// Tab click logic: show only relevant Add button
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href"); // get tab ID
    if(target === "#equipment_tab"){
        $('.add_equipment').show();
        $('.add_reagent').hide();
        $('.reagent-side').hide(); // ensure chemical sidebar is hidden
    } else if(target === "#chemical_tab"){
        $('.add_equipment').hide();
        $('.add_reagent').show();
        $('.equipment-side').hide(); // ensure equipment sidebar is hidden
    }
});

// Initial setup: show equipment button, hide chemical button
$('.add_equipment').show();
$('.add_reagent').hide();
$('.equipment-side').hide();
$('.reagent-side').hide();


$('.student-div').click(function(){
    $('.frm_student_sign').removeClass('hide');
    $('.frm_faculty_sign').addClass('hide');
});

$('.faculty-div').click(function(){
    $('.frm_student_sign').addClass('hide');
    $('.frm_faculty_sign').removeClass('hide');
});


$.ajax({
    type: "POST",
    url: "../class/display/display",
    data: {
        key: "display_roomtype"
    }
})
.done(function(data){
    // console.log(data)
   if(data != 0 ){
        var a = JSON.parse(data);
        var select = '';
        $.each(a, function(x,y){
            select += '<option value="'+y[0]+'" selected>'+y[1]+'</option>';
        });
        $('select[name="e_assigned"]').html(select);
        $('body select[name="e_assigned1"]').html(select);
   }else{
        $('select[name="e_assigned"]').html('<option disabled selected>Please select assign room</option>');
   }
})
.fail(function(data){
    console.log(data);
});

$.ajax({
    type: "POST",
    url: "../class/display/display",
    data: {
        key: "display_item_borrow",
        }
    })
    .done(function(data){
        if(data != ''){
            var ndata = JSON.parse(data);
            
            var select = "";
            $.each(ndata, function(a,x){
                // console.log(x);
                select += "<option value='"+x.id+"'>"
                            + x.deviceid +" - "
                            + x.category +" -  "
                            + x.brand +" -  ["
                            + x.stockleft+" in stock]  - "
                            + x.status
                            + "</option>";
            });
            // console.log(select);
            $('.borrowitem').html(select).select2({
                placeholder: "Select item to borrow",
                maximumSelectionLength: 5,
                tokenSeparators: [',', ' ']
            });
        }else{

        }
})
.fail(function(data){
    console.log(data.statusText);
});
// ✅ Fetch and display available chemicals for borrowing
$.ajax({
    type: "POST",
    url: "../class/display/display", // your PHP handler
    data: { key: "display_chemical_borrow" },
    success: function(data) {
        if (data != '') {
            try {
                var ndata = JSON.parse(data); // parse JSON
                var select = "";

                $.each(ndata, function(i, x) {
                    select += "<option value='" + x.id + "'>"
                        + x.name + " - "
                        + x.quantity + " - "
                        + x.storage + " - "
                        + x.hazard + " - [" + x.status + "]"
                        + "</option>";
                });

                // Destroy old Select2 if already initialized
                if ($('.borrowchemical').hasClass("select2-hidden-accessible")) {
                    $('.borrowchemical').select2('destroy');
                }

                // Fill select and reinitialize Select2
                $('.borrowchemical').html(select).select2({
                    placeholder: "Select chemical to borrow",
                    maximumSelectionLength: 5,
                    tokenSeparators: [',', ' ']
                });

            } catch (e) {
                console.error("JSON Parse Error:", e, data);
            }

        } else {
            // No data case
            if ($('.borrowchemical').hasClass("select2-hidden-accessible")) {
                $('.borrowchemical').select2('destroy');
            }
            $('.borrowchemical').html('').select2({
                placeholder: "No chemicals available"
            });
        }
    },
    error: function(xhr, status, error) {
        console.log("Chemical AJAX error:", xhr.responseText);
    }
});

//borrow transaction
$.ajax({
 type: "POST",
 url: "../class/display/display",
 data: {
        key: "display_memberselect"
     }
 })
 .done(function(data){
    var a = JSON.parse(data);
    var b = "";
    $.each(a, function(x,y){
        b += "<option value="+y[2]+">"+y[1]+" -- "+y[0]+"</option>";
    });
    $('select[name="borrow_membername"]').html("<option disabled selected></option>"+b).select2({
        placeholder: 'Please select borrower'
    });
});


 function equipment_info(id){
    $.ajax({
        type: "POST",
        url: "../class/display/display",
        data: {
            key: "display_equipmentinfo",
            id: id
        }
    })
    .done(function(data){
        var a = JSON.parse(data);
      
        $('.e_id').html(a[0]['e_deviceid']);
        $('.e_category').html(a[0]['e_category']);
        $('.e_brand').html(a[0]['e_brand']);
        $('.e_description').html(a[0]['e_description']);
        $('.e_stock').html(a[0]['e_stock']);
        $('.e_stockleft').html(a[0]['e_stockleft']);
        $('.e_assign').html(a[0]['room_name']);
        $('.e_type').html(a[0]['e_type']);
        $('.e_status').html(a[0]['e_status']);
        $('.e_model').html(a[0]['e_model']);
        $('.e_mr').html(a[0]['e_mr']);
        $('.e_price').html(a[0]['e_price']);
        $('.e_photo').html("<img src='../uploads/"+a[0]['e_photo']+"' style='width:400px; height:250px;' />");

        function getequipmentstat(){
            return a[0]['e_status'];
        }
    });
 }
function reagent_info(reagentId){
    $.ajax({
        url: "../class/display/display",
        method: 'POST',
        data: { key: 'display_reagentinfo', id: reagentId },
        dataType: 'json',
        success: function(data){
            if(data && data.length > 0){
                let reagent = data[0]; // first (and only) reagent object

                // Populate table cells
                $('.r_name').text(reagent.r_name);
                $('.r_quantity').text(reagent.r_quantity);
                $('.unit').text(reagent.unit + ' ml');
                $('.r_date_received').text(reagent.r_date_received);
                $('.r_date_opened').text(reagent.r_date_opened);
                $('.r_expiration').text(reagent.r_expiration);
                $('.r_storage').text(reagent.r_storage);
                $('.r_hazard').text(reagent.r_hazard);
                $('.r_status').text(reagent.r_status);

                // If r_status exists, add a color-coded badge
                if(reagent.r_status){
                    let statusLabel = '';
                    switch(reagent.r_status){
                        case 'Available':
                            statusLabel = '<span class="badge badge-success">Available</span>';
                            break;
                        case 'Out of Stock':
                            statusLabel = '<span class="badge badge-warning">Out of Stock</span>';
                            break;
                        case 'Expired':
                            statusLabel = '<span class="badge badge-danger">Expired</span>';
                            break;
                        default:
                            statusLabel = '<span class="badge badge-secondary">'+reagent.r_status+'</span>';
                    }
                    $('.r_status').html(statusLabel);
                }

            } else {
                console.log("No data returned for reagent ID:", reagentId);
                $('.table tbody td').text('N/A'); // fallback if no data
            }
        },
        error: function(xhr, status, error){
            console.error("AJAX error:", xhr.responseText);
            $('.table tbody td').text('Error loading data'); // fallback
        }
    });
}

// Automatically call reagent_info if a global ID is set
if(typeof id !== 'undefined' && id !== ''){
    $(document).ready(function(){
        reagent_info(id);
    });
}



$('body').on('click', '.equipment-forminfo .cancel-equipmentinfo', function(e){
    e.preventDefault();
    $('.equipment-info').toggle(effect, options, duration);
});

$('body').on('click', '.reagent-forminfo .cancel-reagentinfo', function(e){
    e.preventDefault();
    $('.reagent-info').toggle(effect, options, duration);
});




$.ajax({
 type: "POST",
 url: "../class/display/display",
 data: {
        key: "count_pendingres"
     }
 })
 .done(function(data){
   // console.log(data);
   $('.peding_val').text(data);
});

$.ajax({
 type: "POST",
 url: "../class/display/display",
 data: {
        key: "count_acceptres"
     }
 })
 .done(function(data){
   // console.log(data); 
   $('.accept_val').text(data);
});

$.ajax({
 type: "POST",
 url: "../class/display/display",
 data: {
        key: "count_cancelres"
     }
 })
 .done(function(data){
   // console.log(data); 
   $('.cancel_val').text(data);
});

$.ajax({
 type: "POST",
 url: "../class/display/display",
 data: {
        key: "count_client"
     }
 })
 .done(function(data){
   // console.log(data); 
   $('.active_user').text(data);
});


$.ajax({
    type: "POST",
    url: "../class/display/display",
    data: {
        key: "dashboard_history"
    }
})
.done(function(data){
    // console.log(data);
    var nn = JSON.parse(data);
    if(data != 0){
        var htm = "";
        $.each(nn, function(x,y){
            htm += '<li class="todo-list-item">\
                        <div class="checkbox">\
                            <label for="checkbox">'+y[0]+'</label>\
                        </div>\
                    </li>'; 
        });
        $('.todo-list').html(htm);
    }

});


$.ajax({
    type: "POST",
    url: "../class/display/display",
    data: {
        key: "display_room_reserve"
    }
})
.done(function(data){
    var a = JSON.parse(data);
    var b = '';
    $.each(a, function(x,y){
        b += '<option value="'+y[1]+'">'+y[0]+'</option>';
    });
    $('select[name="reserve_room"]').html('<option disabled selected>Please select room</option>'+b);

    // reserve_room
});


$.ajax({
    type: "POST",
    url: "../class/display/display",
    data: {
        key: "display_rooms"
    }
})
.done(function(data){
    var a = JSON.parse(data);
    // console.log(a)
    var b = '';
    $.each(a, function(x,y){
        b += '<option value="'+y[0]+'">'+y[1]+'</option>';
    });
    $('select[name="transfer_room"]').html(b);

    // reserve_room
});

$('.cancel-transfer').click(function(){
   window.location.reload();
});


$.ajax({
    type: "POST",
    url: "../class/display/display",
    data: {
        key: "count_reservation"
    }
})
.done(function(data){
    $('.today_reservation').text(data);
    $('#reserveBadge').text(data);
});

$(document).ready( function(){
    $.ajax({
        type: "POST",
        url: "../class/display/display",
        data: {
            key: "count_due_borrow"
        }
    })
    .done(function(data){
        $('#dueBorrow').text(data);
        $('#dueBadge').text(data);
    });
});

// $.ajax({
//     type: "POST",
//     url: "../class/display/display",
//     data: {
//         key: "display_equipment_all",
//     }
// })
// .done(function(data){
//    console.log(data);
// });