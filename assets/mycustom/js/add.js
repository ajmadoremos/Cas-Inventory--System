/*
*
*
	all add function
*
*/

$('.frm_addroom').submit(function(e){
	e.preventDefault();
	var a = $('input[name="room_name"]');
	
	$.ajax({
		type: "POST",
		url: "../class/add/add",
		data: {
			name: a.val(),
			key: 'add_room'
		}
	})
	.done(function(data){
		a.val('');
		if(data == 1){
			toastr.success("Room added.");
			table_room.ajax.reload(null,false);
			$('.cancel_room').click();
		}
		else if(data == 2){
			toastr.warning("Room already exist");

		}else if(data == 0){
			toastr.error("Failed to add room");
			$('.cancel_room').click();

		}
	})
	.fail(function(data){
		console.log(data);
	});

});
$(document).on('click', '.delete-room', function(e){
    e.preventDefault();
    
    var room_id = $(this).data('id');

    if (confirm('Are you sure you want to delete this room?')) {

        $.ajax({
            type: "POST",
            url: "../class/add/delete_room ",  // adjust the path accordingly
            data: {
                id: room_id,
                key: 'delete_room'  // (optional: for consistency, like your add_room has key)
            }
        })
        .done(function(data){
            if (data == 1) {
                toastr.success("Room deleted.");
                table_room.ajax.reload(null,false);
            } else if (data == 0) {
                toastr.error("Failed to delete room");
            }
        })
        .fail(function(data){
            console.log(data);
            toastr.error("AJAX error.");
        });

    }
});

$(document).on('click', '.delete-member', function(e){
    e.preventDefault();
    
    var member_id = $(this).data('id');

    if (confirm('Are you sure you want to delete this member?')) {

        $.ajax({
            type: "POST",
            url: "../class/add/delete_member ",  // adjust the path accordingly
            data: {
                id: member_id,
                key: 'delete_member'  // (optional: for consistency, like your add_room has key)
            }
        })
        .done(function(data){
            if (data == 1) {
                toastr.success("Member deleted.");
                table_member .ajax.reload(null,false);
            } else if (data == 0) {
                toastr.error("Failed to delete member");
            }
        })
        .fail(function(data){
            console.log(data);
            toastr.error("AJAX error.");
        });

    }
});

$(document).on('click', '.delete-user', function(e){
    e.preventDefault();
    
    var user_id = $(this).data('id');

    if (confirm('Are you sure you want to delete this member?')) {

        $.ajax({
            type: "POST",
            url: "../class/add/delete_user ",  // adjust the path accordingly
            data: {
                id: user_id,
                key: 'delete_user'  // (optional: for consistency, like your add_room has key)
            }
        })
        .done(function(data){
            if (data == 1) {
                toastr.success("User deleted.");
                table_user.ajax.reload(null,false);
            } else if (data == 0) {
                toastr.error("Failed to delete user");
            }
        })
        .fail(function(data){
            console.log(data);
            toastr.error("AJAX error.");
        });

    }
});


function toggleForms() {
	const loginForm = document.getElementById('login-form');
	const signupForm = document.getElementById('signup-form');

	const isLoginVisible = loginForm.style.display !== 'none';
	loginForm.style.display = isLoginVisible ? 'none' : 'block';
	signupForm.style.display = isLoginVisible ? 'block' : 'none';
}

 $(document).ready(function () {
  $('.student-btn').click(function () {
    $('#student-form').show();
    $('#faculty-form').hide();
    $('input[name="type"]').val('Student'); // ✅ sets hidden type field
  });

  $('.faculty-btn').click(function () {
    $('#student-form').hide();
    $('#faculty-form').show();
    $('input[name="type"]').val('Faculty'); // ✅ sets hidden type field
  });
});




$(document).off('submit', '.frm_student_signs').on('submit', '.frm_student_signs', function(e) {
    e.preventDefault();

    let sid = $('input[name="sid_number"]').val().trim();
    let contact = $('input[name="s_contact"]').val().trim();

    // Validate School ID Format: 21-1-1-0221
    let sidFormat = /^\d{2}-\d{1}-\d{1}-\d{4}$/;
    if (!sidFormat.test(sid)) {
        toastr.warning('School ID must be in the format: 21-1-1-0221');
        return;
    }

    // Validate Contact Number Format: 09XXXXXXXXX
    let contactFormat = /^09\d{9}$/;
    if (!contactFormat.test(contact)) {
        toastr.warning('Contact number must be in the format: 09090909099');
        return;
    }

    let datas = $(this).serialize() + '&key=sign_student';
    let form = this; // cache reference to the form for reset

    $.ajax({
        type: "POST",
        data: datas,
        url: '../class/add/add',
        beforeSend: function() {
            $('.btn_student').attr('disabled', true);
        }
    })
    .done(function(data) {
        $('.btn_student').removeAttr('disabled');
        data = data.trim();

        if (data == "Student") {
            toastr.success('Successfully signup', 'Redirecting page');
            form.reset(); // ✅ Clear the form
            setTimeout(function() {
                window.location = 'login'; // redirect
            }, 3000);
        } else if (data == "2") {
            toastr.warning('Student already exists');
        } else if (data == "0") {
            toastr.error('Failed to signup');
        }
    })
    .fail(function(data) {
        $('.btn_student').removeAttr('disabled');
        console.log(data);
    });
});



$(document).off('submit', '.frm_faculty_sign').on('submit', '.frm_faculty_sign', function(e) {
    e.preventDefault();

    let contact = $('input[name="f_contact"]').val().trim();

    // ✅ Validate Contact Number Format: 09XXXXXXXXX
    let contactFormat = /^09\d{9}$/;
    if (!contactFormat.test(contact)) {
        toastr.warning('Contact number must be in the format: 09090909099');
        return;
    }

    let datas = $(this).serialize() + '&key=sign_faculty';
    let form = this; // ✅ Reference to the form for resetting

    $.ajax({
        type: "POST",
        url: '../class/add/add',
        data: datas,
        beforeSend: function() {
            $('.btn_faculty').attr('disabled', true);
        }
    })
    .done(function(data) {
        $('.btn_faculty').removeAttr('disabled');
        data = data.trim();

        console.log('✅ Server response:', data);

        if (data == "Faculty") {
            toastr.success('Successfully signup', 'Redirecting page');
            form.reset(); // ✅ Clear the form fields
            setTimeout(function() {
                window.location = 'login'; // redirect to login.php
            }, 3000);
        } else if (data == "2") {
            toastr.warning('Faculty already exists');
        } else if (data == "0") {
            toastr.error('Failed to signup');
        } else {
            toastr.error('Unexpected server response: ' + data);
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        $('.btn_faculty').removeAttr('disabled');
        toastr.error('AJAX failed: ' + textStatus);
        console.error('❌ AJAX error:', textStatus, errorThrown);
    });
});




$('.frm_addequipment').submit(function(e){
	e.preventDefault();
	var _this = $(this);
	var formData = new FormData();
	var fileData = _this.find('input[type="file"]').prop('files')[0];
	formData.append("e_photo",fileData);
	var otherFormData = _this.serializeArray();
	$.each(otherFormData,function(key,input){
		formData.append(input.name,input.value);
	});

	$.ajax({
		type: "POST",
		url: "../class/add/add",
		data: formData,
		async: true,
		cache: false,
		contentType: false,
		processData: false 
	})
	.done(function(data){
		console.log(data);
		if(data == 1){
			toastr.success("Item added.");
			table_equipment.ajax.reload(null,false);
			$('.cancel-equipment').click();
			$('.frm_addequipment').find('input').val('');
		}else if(data == 0){
			toastr.error("Failed to add item");
			$('.cancel-equipment').click();
			$('.frm_addequipment').find('input').val('');
		}
	})
	.fail(function(data){
		console.log(data);
	});

});


$(".frm_addmember").submit(function(e){
	e.preventDefault();

	var formData = new FormData($(this)[0]);

	console.log(formData);

	$.ajax({
		type: "POST",
		url: "../class/add/add",
		data: formData,
		contentType: false,
		cache: false,
		processData:false,
	})
	.done(function(data){
		if(data == 1){
			toastr.success("Members added successfully.");
			table_member.ajax.reload(null,false);
			$('.member-side').toggle(effect, options, duration);
		}else if(data == 0){
			toastr.error("Failed to add member");
			$('.member-side').toggle(effect, options, duration);
		}
	});

});


$('.item-add').click(function(){
    $('.equipment-info').toggle(effect, options, duration);
    var id = getequipmentid();
    console.log(id);
    var append = '  <form class="frm_eadditem">\
                        <h4 class="alert bg-success">Add Quantity</h4>\
                        <div class="form-group">\
                            <label>Quantity</label>\
                            <input type="number" name="item_qty" class="form-control" min="1" required autofocus="on">\
                            <input type="hidden" name="id" value="'+id+'">\
                            <input type="hidden" name="key" value="add_itemqty">\
                        </div>\
                        <div class="form-group">\
                            <button class="btn btn-danger cancel-equipmentinfo" type="button">Cancel</button>\
                            <button class="btn btn-primary" type="submit">Add</button>\
                        </div>\
                    </form>';

    $('.equipment-forminfo').html(append);

    $('form.frm_eadditem').submit(function(e){
        e.preventDefault();
        var c = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "../class/add/add",
            data: c
        })
        .done(function(data){
        	var ab = data.split('|');
        	$('.e_stock').html(ab[0]);
        	$('.e_stockleft').html(ab[1]);
        	toastr.success('Successful');
        	$('.equipment-info').toggle(effect, options, duration);
        	$('input[name="item_qty"]').val('');
        });

    });
});


$('.frm_borrow').submit(function(e){
	e.preventDefault();
	var data = $(this).serialize()+'&key=add_borrower';
	$.ajax({
		type: "POST",
		url: "../class/add/add",
		data: data,
		dataType: 'json'
	})
	.done(function(data){
		
		if(data.response == 1)
		{
			toastr.success(data.message);
			$(".borrowitem").select2('val', 'All');
			$("select[name='borrow_membername']").select2('val', 'All');
			$.get('../views/printBorrow?borrowIds=' + data.borrowIds, function(htmlData){
	            var w = window.open();
	            w.document.write(htmlData);
	            w.setTimeout(function(){
	                w.print();  
	                w.close();
	            },250);
	        });
		}
		else
		{
			toastr.error(data.message);
		}
	});
});


$('.frmadd_users').submit(function(e){
	e.preventDefault();
	var data = $(this).serialize()+'&key=add_users';

	$.ajax({
		type: "POST",
		url: "../class/add/add",
		data: data
	})
	.done(function(data){
		console.log(data);
		if(data == 1){
			toastr.success('Successfully added.');
			table_user.ajax.reload(null,false);
			$('.user-side').toggle(effect, options, duration);
			$('.frmadd_users').find('input select').val('');
		}else if(data == 2){
			toastr.warning('Name or username already taken');
		}else{
			toastr.error('Failed to add');
		}
	});

});

$('.client_reservation').submit(function(e){
	e.preventDefault();
	var frmdata = $(this).serialize()+'&key=addclient_reservation';

	$.ajax({
		type: "POST",
		url: "../class/add/add",
		data: frmdata
	})
	.done(function(data){
		console.log(data);
		if(data == 1){
			toastr.success('Successful. Check your reservation status if your reservation was accomodated');
			$('input[name="reserved_date"]').val('');
			$('input[name="reserved_time"]').val('');
			$('select[name="reserve_room"]').val('');
			$(".client_reservation").find('select').select2('val', 'All');
			tbl_pendingres.ajax.reload(null,false);

		}else if(data == 2){
			toastr.warning('Your reservation cannot process. You can only make one reservation per day.');
			$('input[name="reserved_date"]').val('');
			$('input[name="reserved_time"]').val('');
			$('select[name="reserve_room"]').val('');
			$(".client_reservation").find('select').select2('val', 'All');
			tbl_pendingres.ajax.reload(null,false);
		}else{
			toastr.error('Your reservation cannot process right now.');
		}
	});

});

$('.add_student').click(function () {
  $('.divedit-member').toggle(effect, options, duration);

  var form = `
    <form class="frm_add_student">
      <br/><br/><h4></h4>
      <hr>
      <div class="form-group">
        <label>School ID Number</label>
        <input type="text" name="sid_number" class="form-control" required autocomplete="off" placeholder="e.g. 21-1-1-0221" maxlength="11" />
      </div>
      <div class="form-group">
        <label>First Name</label>
        <input type="text" name="s_fname" class="form-control text-capitalize" required autocomplete="off" />
      </div>
      <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="s_lname" class="form-control text-capitalize" required autocomplete="off" />
      </div>
      <div class="form-group">
        <label>Gender</label>
        <select name="s_gender" class="form-control" required>
          <option disabled selected>Your gender</option>
          <option>Male</option>
          <option>Female</option>
        </select>
      </div>
      <div class="form-group">
        <label>Contact Number</label>
        <input type="tel" name="s_contact" class="form-control" required autocomplete="off" placeholder="e.g. 09123456789" maxlength="11" />
      </div>
      <div class="form-group">
        <label>Department</label>
        <select name="s_department" class="form-control" required>
          <option disabled selected>Select department</option>
          <option>AB</option>
          <option>BEED</option>
          <option>BSED</option>
          <option>BSCE</option>
          <option>BSHRM</option>
          <option>BSIS</option>
          <option>BSIT</option>
        </select>
      </div>
      
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label>Year</label>
            <select name="s_year" class="form-control" required>
              <option selected disabled>Select year</option>
              <option>1st</option>
              <option>2nd</option>
              <option>3rd</option>
              <option>4th</option>
              <option>5th</option>
            </select>
          </div>
          <div class="col-md-6">
            <label>Section</label>
            <input type="text" name="s_section" class="form-control text-capitalize" required autocomplete="off" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <button class="btn btn-primary btn_faculty" type="submit"><i class="fa fa-plus"></i> Add</button>
        <button class="btn btn-danger btn_frm_add" type="reset"><i class="fa fa-remove"></i> Cancel</button>
      </div>
    </form>`;

  $('.member-form').html(form);

  // Cancel button hides form
  $('.btn_frm_add').click(function () {
    $('.divedit-member').toggle(effect, options, duration);
  });

  // Format School ID to digits and dashes only, max 13 characters
  $(document).on('input', 'input[name="sid_number"]', function () {
    let val = $(this).val().replace(/[^0-9\-]/g, '');
    val = val.replace(/-+/g, '-');
    if (val.length > 11) {
      val = val.slice(0, 11);
    }
    $(this).val(val);
  });

  // Allow only digits in contact number
  $(document).on('input', 'input[name="s_contact"]', function () {
    let val = $(this).val().replace(/\D/g, ''); // remove all non-numeric characters
    $(this).val(val);
  });

  // Submit form
  $('.frm_add_student').submit(function (e) {
    e.preventDefault();

    let sid = $('input[name="sid_number"]').val();
    let contact = $('input[name="s_contact"]').val();

    let validSID = /^\d{2}-\d{1}-\d{1}-\d{4}$/;
    let validContact = /^09\d{9}$/;

    if (!validSID.test(sid)) {
      toastr.error("❌ Invalid Student ID format. Use: YY-C-Y-XXXX (e.g. 21-1-1-1111)");
      return;
    }

    if (!validContact.test(contact)) {
      toastr.error("❌ Invalid Contact Number. Use: 11-digit number (e.g. 09123456789)");
      return;
    }

    let frmdata = $(this).serialize() + '&key=add_newstudent';
    console.log('Sending:', frmdata);

    $.ajax({
      type: "POST",
      url: "../class/add/add",
      data: frmdata
    }).done(function (data) {
      if (data == 1) {
        toastr.success('Student successfully added.');
        $('.btn_frm_add').click();
        table_member.ajax.reload(null, false);
      } else if (data == 2) {
        toastr.warning('Student already exists.');
      } else {
        toastr.error('Failed to add student.');
      }
    });
  });
});



$('.add_faculty').click(function () {
	$('.divedit-member').toggle(effect, options, duration);

	var mform = '<form class="add_frm_faculty">\
					<br/><br/><h4 class=""></h4>\
					<hr>\
					<div class="form-group">\
						<label>School ID Number</label>\
						<input type="number" name="f_id" class="form-control" required autocomplete="off">\
					</div>\
					<div class="form-group">\
						<label>First Name</label>\
						<input type="text" name="f_fname" class="form-control" required autocomplete="off">\
					</div>\
					<div class="form-group">\
						<label>Last Name</label>\
						<input type="text" name="f_lname" class="form-control" required autocomplete="off">\
					</div>\
					<div class="form-group">\
						<label>Gender</label>\
						<select name="f_gender" class="form-control" required>\
							<option disabled selected>Your gender</option>\
							<option>Male</option>\
							<option>Female</option>\
						</select>\
					</div>\
					<div class="form-group">\
						<label>Contact Number</label>\
						<input type="tel" name="f_contact" class="form-control" required autocomplete="off" placeholder="e.g. 09123456789" maxlength="11">\
					</div>\
					<div class="form-group">\
						<label>Department</label>\
						<select name="f_department" class="form-control" required>\
							<option disabled selected>Select department</option>\
							<option>CIT</option>\
							<option>COED</option>\
							<option>SAS</option>\
						</select>\
					</div>\
					<div class="form-group">\
						<button class="btn btn-primary btn_faculty" type="submit"><i class="fa fa-plus"></i> Add</button>\
						<button class="btn btn-danger btn_frm_add" type="reset"><i class="fa fa-remove"></i> Cancel</button>\
					</div>\
				</form>';

	$('.member-form').html(mform);

	// Cancel button hides form
	$('.btn_frm_add').click(function () {
		$('.divedit-member').toggle(effect, options, duration);
	});

	// Accept only numbers in contact input (no formatting with dashes)
	$(document).on('input', 'input[name="f_contact"]', function () {
		let val = $(this).val().replace(/\D/g, ''); // remove non-digits
		if (val.length > 11) val = val.slice(0, 11); // max 11 digits
		$(this).val(val);
	});

	// Submit handler with plain number validation
	$('.add_frm_faculty').submit(function (e) {
		e.preventDefault();

		let contact = $('input[name="f_contact"]').val();
		let validContact = /^09\d{9}$/; // 11-digit format without dashes

		if (!validContact.test(contact)) {
			toastr.error("❌ Invalid Contact Number. Use: 11-digit number (e.g. 09123456789)");
			return;
		}

		var frmdata = $(this).serialize() + '&key=add_newfaculty';

		$.ajax({
			type: "POST",
			url: "../class/add/add",
			data: frmdata
		})
		.done(function (data) {
			if (data == 1) {
				toastr.success('Faculty successfully added.');
				$('.btn_frm_add').click();
				table_member.ajax.reload(null, false);
			} else if (data == 2) {
				toastr.warning('Faculty already exists.');
			} else {
				toastr.error('Failed to add.');
			}
		});
	});
});


// Submit form
$('#frm_edit_reservation').submit(function (e) {
  e.preventDefault();

  const reservation_code = $('#reservation_code').val();
  const admin_feedback = $('textarea[name="admin_feedback"]').val();
  let approved_items = [];

  $('input[name="approved_items[]"]:checked').each(function () {
    approved_items.push($(this).val());
  });

  $.ajax({
    url: 'update_reservation.php',
    method: 'POST',
    data: {
      reservation_code: reservation_code,
      admin_feedback: admin_feedback,
      approved_items: approved_items
    },
    dataType: 'json',
    success: function (response) {
      if (response.status === 1) {
        toastr.success(response.message);
        $('#editReservationModal').modal('hide');
        $('.tbl_pendingres').DataTable().ajax.reload(null, false);
      } else {
        toastr.error(response.message || 'Update failed');
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", xhr.responseText);
      toastr.error("Server error. Check console.");
    }
  });
});
$(document).ready(function () {

	// Initialize the DataTable
	$('.tbl_pendingres').DataTable({
		ajax: 'fetch_reservation.php?type=pending',
		columns: [
			{ data: 0 },
			{ data: 1 },
			{ data: 2 },
			{ data: 3 },
			{ data: 4 }, // Edit
			{ data: 5 }  // Accept/Cancel
		]
	});

	// ✅ Add Cancel handler
	$(document).on('click', '.btn-cancel', function () {
		const code = $(this).data('id');
		$('input[name="codereserve"]').val(code);
		$('#myModal').modal('show');
	});

	// ✅ Add Edit handler
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

});

