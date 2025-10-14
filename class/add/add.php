<?php
	require_once "../config/config.php";
	// include "../../views/session.php";

	// 1 == success
	// 2 == exist
	// 0 == failed

	class add {
		
		public function add_room($name)
{
	global $conn;

	session_start();
	$h_desc = 'add new room '. $name;
	$h_tbl = 'room';
	$sessionid = $_SESSION['admin_id'];
	$sessiontype = $_SESSION['admin_type'];

	$select = $conn->prepare("SELECT * FROM room WHERE rm_name = ? "); 
	$select->execute(array($name));
	$row = $select->rowCount();
	if($row <= 0){
		$sql = $conn->prepare("INSERT INTO room(rm_name, rm_status) VALUES(?, ?);
        INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)");
		$sql->execute(array($name, 1, $h_desc, $h_tbl, $sessionid, $sessiontype));
		$count = $sql->rowCount();
		if($count > 0){
			echo "1"; 
		}else{
			echo "0";
		}
	}else{
		echo "2";
	}
}

		public function sign_student($sid_number, $s_fname, $s_lname, $s_gender, $s_contact, $s_department, $s_year, $s_section, $type)
{
    global $conn;

    // Check if student ID exists
    $sql = $conn->prepare('SELECT * FROM member WHERE m_school_id = ?');
    $sql->execute([$sid_number]);
    $sql_count = $sql->rowCount();

    if ($sql_count <= 0) {
        $insert = $conn->prepare('INSERT INTO member (m_school_id, m_fname, m_lname, m_gender, m_contact, m_department, m_year_section, m_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $insert->execute([$sid_number, $s_fname, $s_lname, $s_gender, $s_contact, $s_department, $s_year . ' - ' . $s_section, $type]);
        $insert_count = $insert->rowCount();

        if ($insert_count > 0) {
            echo "1"; // success
        } else {
            echo "0"; // insert failed
        }
    } else {
        echo "2"; // duplicate student ID found
    }
}


		public function sign_faculty($f_id,$f_fname,$f_lname,$f_gender,$f_contact,$f_department,$type)
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM member WHERE m_school_id = ? AND m_fname = ? AND m_lname = ? AND m_type = ?');
			$sql->execute(array($f_id,$f_fname,$f_lname,$type));
			$sql_count = $sql->rowCount();
				if($sql_count <= 0 ){
					
					$insert = $conn->prepare('INSERT INTO  member(m_school_id, m_fname, m_lname, m_gender, m_contact, m_department, m_type) VALUES(?, ?, ?, ?, ?, ?, ?)');
					$insert->execute(array($f_id,$f_fname,$f_lname,$f_gender,$f_contact,$f_department,$type));
					$insert_count = $insert->rowCount();
						
						if($insert_count > 0){
							echo "1";
						}else{
							echo "0";
						}

				}else{
					echo "2";
				}

		}

		public function add_equipment()
{
    global $conn;

    // ✅ Validate required POST fields without warnings
    $required_fields = [
        'e_model', 'e_number', 'e_category', 'e_brand', 
        'e_description', 'e_stock', 'e_type', 'e_status', 
        'e_mr', 'e_price', 'e_assigned'
    ];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || ($_POST[$field] === "" && $_POST[$field] !== "0")) {
            echo "Error: Missing required field - {$field}";
            return; // stop execution
        }
    }

    // ✅ Assign variables safely
    $e_model       = trim($_POST['e_model']);
    $e_number      = trim($_POST['e_number']);
    $e_category    = trim($_POST['e_category']);
    $e_brand       = trim($_POST['e_brand']);
    $e_description = trim($_POST['e_description']);
    $e_stock       = trim($_POST['e_stock']);
    $e_type        = trim($_POST['e_type']);
    $e_status      = trim($_POST['e_status']);
    $e_mr          = trim($_POST['e_mr']);
    $e_price       = trim($_POST['e_price']);
    $e_assigned    = trim($_POST['e_assigned']); // room_id

    session_start();
    $h_desc = 'add new equipment ' . $e_model . ' , ' . $e_category;
    $h_tbl = 'equipment';
    $sessionid = $_SESSION['admin_id'] ?? 0;
    $sessiontype = $_SESSION['admin_type'] ?? '';

    // ✅ Insert into item table
    $sql = $conn->prepare('
        INSERT INTO item(i_deviceID, i_model, i_category, i_brand, i_description, i_type, item_rawstock, i_mr, i_price)
        VALUES(?,?,?,?,?,?,?,?,?)');
    $sql->execute([$e_number, $e_model, $e_category, $e_brand, $e_description, $e_type, $e_stock, $e_mr, $e_price]);
    $row = $sql->rowCount();
    $itemID = $conn->lastInsertId();

    // ✅ Handle photo upload
    if (!empty($_FILES['e_photo']['name'])) {
        $imageName = $_FILES['e_photo']['name'];
        $extension = pathinfo($imageName, PATHINFO_EXTENSION);
        $tmpData   = $_FILES['e_photo']['tmp_name'];
        $fileName  = time();
        $filePath  = '../../uploads/' . $fileName . "." . $extension;

        if (move_uploaded_file($tmpData, $filePath)) {
            $file = $fileName . "." . $extension;
            $update = $conn->prepare('UPDATE item SET i_photo = ? WHERE id = ?');
            $update->execute([$file, $itemID]);
        }
    }

    // ✅ Insert into item_stock if item added successfully 
    if ($row > 0) {
        $item = $conn->prepare('INSERT INTO item_stock (item_id, room_id, items_stock, item_status)
                                VALUES(?,?,?,?)');
        $item->execute([$itemID, $e_assigned, $e_stock, $e_status]);

        if ($item->rowCount() > 0) {
            $history = $conn->prepare('INSERT INTO history_logs(description, table_name, status_name, user_id, user_type)
                                       VALUES(?,?,?,?,?)');
            $history->execute([$h_desc, $h_tbl, 'added', $sessionid, $sessiontype]);
            echo $history->rowCount();
        } else {
            echo '0';
        }
    } else {
        echo '0';
    }
}


		public function add_member($as,$handle){
			global $conn;

			session_start();
			$h_desc = 'add csv file clients';
			$h_tbl = 'client';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			try {	
				$sql = $conn->prepare('INSERT INTO member(m_school_id,m_fname,m_lname,m_gender,m_contact,m_department,m_year_section,m_type	) VALUES(?,?,?,?,?,?,?,?)');
				fgets($handle);
				 while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
		            $sql->execute($data);
		        }   
		        $insert = $conn->prepare('INSERT INTO history_logs(description,table_name,status_name,user_id,user_type) VALUES(?,?,?,?,?)');
		        $insert->execute(array($h_desc,$h_tbl,'imported',$sessionid,$sessiontype));   
			}
			catch(PDOException $e){
				echo 0;
			}
			echo 1;
		}


		public function add_itemqty($id,$item_qty)
		{
			global $conn;

			session_start();
			
			$h_tbl = 'equipment';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('SELECT * FROM item_stock 
									LEFT JOIN item ON item.id = item_stock.item_id
									WHERE item_stock.id = ?');
			
			$sql->execute(array($id));
			$count = $sql->rowCount();
			$fetch = $sql->fetch();
			$itemID = $fetch['item_id'];

			$rawstock = $fetch['item_rawstock'] + $item_qty; 
			$stockleft = $fetch['items_stock'] + $item_qty; 

			$h_desc = 'add '.$item_qty .'items to'.  $fetch['i_model'] .'quantity';

			if($count > 0){

				$addstock = $conn->prepare('UPDATE item SET item_rawstock = (item_rawstock + ?) WHERE id = ?');
				$addstock->execute(array($item_qty,$itemID));
				$addrow = $addstock->rowCount();

				if($addrow > 0){
					$update_stock = $conn->prepare('UPDATE item_stock SET items_stock = (items_stock + ?) WHERE id = ?');
					$update_stock->execute(array($item_qty,$id));
					$updaterow = $update_stock->rowCount();
					if($updaterow > 0){
						$history = $conn->prepare('INSERT INTO history_logs(description,table_name,status_name,user_id,user_type) VALUES(?,?,?,?,?)');
						$history->execute(array($h_desc,$h_tbl,'added qty',$sessionid,$sessiontype));
						$historycount = $history->rowCount();
						
						echo $rawstock.'|'.$stockleft;
					}
				}

			}else{

			}
		}
		public function add_reagentqty($id, $reagent_qty)
{
    global $conn;
    session_start();

    $h_tbl = 'chemical_reagents';
    $sessionid = $_SESSION['admin_id'];
    $sessiontype = $_SESSION['admin_type'];

    $sql = $conn->prepare('SELECT * FROM chemical_reagents WHERE r_id = ?');
    $sql->execute(array($id));
    $count = $sql->rowCount();
    $fetch = $sql->fetch();

    if ($count > 0) {
        $currentQty = $fetch['r_quantity'];
        $newQty = $currentQty + $reagent_qty;

        $update = $conn->prepare('UPDATE chemical_reagents SET r_quantity = ? WHERE r_id = ?');
        $update->execute(array($newQty, $id));
        $updaterow = $update->rowCount();

        if ($updaterow > 0) {
            $h_desc = 'Added ' . $reagent_qty . ' to reagent ' . $fetch['r_name'] . ' (previous: ' . $currentQty . ')';
            $history = $conn->prepare('INSERT INTO history_logs(description, table_name, status_name, user_id, user_type) VALUES(?,?,?,?,?)');
            $history->execute(array($h_desc, $h_tbl, 'added qty', $sessionid, $sessiontype));

            echo $currentQty . '|' . $newQty; // return old and new qty
        }
    } else {
        echo "Error: Reagent not found.";
    }
}
public function add_reagentunitqty($id, $reagent_unitqty)
{
    global $conn;
    session_start();

    $h_tbl = 'chemical_reagents';
    $sessionid = $_SESSION['admin_id'];
    $sessiontype = $_SESSION['admin_type'];

    $sql = $conn->prepare('SELECT * FROM chemical_reagents WHERE r_id = ?');
    $sql->execute(array($id));
    $count = $sql->rowCount();
    $fetch = $sql->fetch();

    if ($count > 0) {
        $currentQty = $fetch['unit'];
        $newQty = $currentQty + $reagent_unitqty;

        $update = $conn->prepare('UPDATE chemical_reagents SET unit = ? WHERE r_id = ?');
        $update->execute(array($newQty, $id));
        $updaterow = $update->rowCount();

        if ($updaterow > 0) {
            $h_desc = 'Added ' . $reagent_unitqty . ' to reagent ' . $fetch['r_name'] . ' (previous: ' . $currentQty . ')';
            $history = $conn->prepare('INSERT INTO history_logs(description, table_name, status_name, user_id, user_type) VALUES(?,?,?,?,?)');
            $history->execute(array($h_desc, $h_tbl, 'added qty', $sessionid, $sessiontype));

            echo $currentQty . '|' . $newQty; // return old and new qty
        }
    } else {
        echo "Error: Reagent not found.";
    }
}

public function add_reagent($r_name, $r_quantity, $unit, $r_date_received, $r_date_opened, $r_expiration, $r_storage, $r_hazard)
{
    global $conn;
    session_start();

    $h_tbl = 'chemical_reagents';
    $sessionid = $_SESSION['admin_id'] ?? 0;
    $sessiontype = $_SESSION['admin_type'] ?? 'admin';

    $sql = $conn->prepare("INSERT INTO chemical_reagents 
        (r_name, r_quantity, unit, r_date_received, r_date_opened, r_expiration, r_storage, r_hazard)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $inserted = $sql->execute([$r_name, $r_quantity, $unit, $r_date_received, $r_date_opened, $r_expiration, $r_storage, $r_hazard]);

    if($inserted){
        // Log history
        $h_desc = "Added new reagent: $r_name ($r_quantity)";
        $history = $conn->prepare('INSERT INTO history_logs(description, table_name, status_name, user_id, user_type) VALUES(?,?,?,?,?)');
        $history->execute([$h_desc, $h_tbl, 'added', $sessionid, $sessiontype]);

        echo 1; // ✅ return plain 1
    } else {
        echo 0; // ✅ return plain 0
    }
}



		public function add_borrower($name, $item, $chemical, $id, $reserve_room, $timeLimit)
{
    global $conn;

    session_start();
    $h_desc = 'create borrow transaction';
    $h_tbl = 'borrow';
    $sessionid = $_SESSION['admin_id'];
    $sessiontype = $_SESSION['admin_type'];

    $code = date('mdYHis') . '' . $id;

    // 🔎 Check if user has 3 active borrows
    $select = $conn->prepare('SELECT * FROM borrow WHERE member_id = ? AND status = ? GROUP BY borrowcode');
    $select->execute([$name, 1]);
    $row = $select->rowCount();

    if ($row >= 3) {
        echo json_encode([
            "response" => 0,
            "message" => 'Unable to process your transaction. Please return first your borrowed items/chemicals'
        ]);
        return;
    }

    $borrowIds = [];

    /** -------------------------
     *  ✅ Process Equipment Items
     * ------------------------- */
    if (!empty($item)) {
        foreach ($item as $key => $items) {
            $itemsArr = explode("||", $items);
            $sql = $conn->prepare('INSERT INTO borrow (borrowcode,member_id,item_id,stock_id,user_id,room_assigned,time_limit) 
                                   VALUES(?,?,?,?,?,?,?)');
            $sql->execute([$code, $name, $itemsArr[0], $itemsArr[1], $id, $reserve_room, $timeLimit]);
            $count = $sql->rowCount();
            $borrowId = $conn->lastInsertId();
            $borrowIds[] = $borrowId;

            if ($count > 0) {
                $update = $conn->prepare('UPDATE item_stock SET items_stock = (items_stock - ?) WHERE id = ?');
                $update->execute([1, $itemsArr[1]]);
            }
        }
    }

    /** -------------------------
     *  ✅ Process Chemicals (unit-based deduction)
     * ------------------------- */
    if (!empty($chemical)) {
        // Create single borrow entry for all chemicals
        $sql = $conn->prepare('INSERT INTO borrow (borrowcode,member_id,user_id,room_assigned,time_limit) 
                               VALUES(?,?,?,?,?)');
        $sql->execute([$code, $name, $id, $reserve_room, $timeLimit]);
        $borrowId = $conn->lastInsertId();
        $borrowIds[] = $borrowId;

        foreach ($chemical as $key => $chemData) {
            $chemArr = explode("||", $chemData);
            $chemId = $chemArr[0];
            $qtyMl  = isset($chemArr[1]) ? (float)$chemArr[1] : 1; // ml borrowed

            // Get current stock info
            $check = $conn->prepare('SELECT r_quantity, unit, r_name FROM chemical_reagents WHERE r_id = ?');
            $check->execute([$chemId]);
            $chem = $check->fetch(PDO::FETCH_ASSOC);

            if (!$chem) continue;

            $currentQty = (int)$chem['r_quantity'];
            $currentUnit = (float)$chem['unit'];

            // If not enough ml left in unit
            if ($qtyMl > $currentUnit && $currentQty <= 0) {
                echo json_encode([
                    "response" => 0,
                    "message" => "Not enough stock for '{$chem['r_name']}'. Only {$currentUnit} ml remaining."
                ]);
                return;
            }

            // Deduct ml from unit
            $newUnit = $currentUnit - $qtyMl;
            $newQty = $currentQty;

            // If unit runs out, deduct 1 from main quantity
            if ($newUnit <= 0) {
                $newQty = max(0, $currentQty - 1);
                $newUnit = 0; // stay zero until admin resets
            }

            // Update stock in database
            $update = $conn->prepare('UPDATE chemical_reagents SET unit = ?, r_quantity = ? WHERE r_id = ?');
            $update->execute([$newUnit, $newQty, $chemId]);

            // Insert borrow_chemical record
            $sqlChem = $conn->prepare('INSERT INTO borrow_chemicals (borrow_id, chemical_id, quantity) VALUES(?,?,?)');
            $sqlChem->execute([$borrowId, $chemId, $qtyMl]);
        }
    }

    echo json_encode([
        "response" => 1,
        "message" => "Successfully Borrowed",
        "borrowIds" => implode("|", $borrowIds)
    ]);
}

		public function add_users($u_fname,$u_username,$u_password,$u_type)
		{
			global $conn;

			session_start();
			$h_desc = 'add user'. $u_fname;
			$h_tbl = 'user';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('SELECT * FROM user WHERE name = ? OR username = ? ');
			$sql->execute(array($u_fname,$u_username));
			$count = $sql->rowCount();
			if($count <= 0){
				$que = $conn->prepare('INSERT INTO user	(name,username,password,type) VALUES(?,?,?,?);
    INSERT INTO history_logs(description,table_name,status_name,user_id,user_type) VALUES(?,?,?,?,?)');
				$que->execute(array($u_fname,$u_username,$u_password,$u_type,$h_desc,$h_tbl,'added',$sessionid,$sessiontype));
				$row = $que->rowCount();
				if($row > 0){
					echo "1";
				}else{
					echo "0";
				}
			}else{
				echo "2";
			}
		}


		public function addclient_reservation($items, $chemicals, $date, $time, $client_id, $assign_room, $timeLimit)
{
    global $conn;
    $code = date('mdYhis') . $client_id;

    if ($client_id == 0) {
        echo '3'; // invalid client
        return;
    }

    try {
        $conn->beginTransaction();

        // ✅ Step 1: Insert reservation
        $sql = $conn->prepare("INSERT INTO reservation 
            (reservation_code, member_id, room_id, reserve_date, reservation_time, assign_room, time_limit) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$code, $client_id, $assign_room, $date, $time, $assign_room, $timeLimit]);

        $reservationId = $conn->lastInsertId();
        $count = 0;

        // ✅ Step 2: Insert items (no deduction)
        if (!empty($items)) {
            foreach ($items as $item) {
                $itemsArr = explode("||", $item);
                $sql1 = $conn->prepare("INSERT INTO reservation_items (reservation_id, item_id, stock_id) 
                                        VALUES (?, ?, ?)");
                $sql1->execute([$reservationId, $itemsArr[0], $itemsArr[1]]);
                if ($sql1->rowCount() > 0) $count++;
            }
        }

        // ✅ Step 3: Insert chemicals (with stock/unit validation)
        $chemicalAmounts = json_decode($_POST['chemical_amounts'] ?? '{}', true);

        if (!empty($chemicals)) {
            foreach ($chemicals as $chemId) {
                $quantity = isset($chemicalAmounts[$chemId]) && is_numeric($chemicalAmounts[$chemId])
                    ? $chemicalAmounts[$chemId]
                    : 1;

                // 🔍 Check chemical stock first
                $check = $conn->prepare("SELECT r_quantity, unit, r_status, r_name FROM chemical_reagents WHERE r_id = ?");
                $check->execute([$chemId]);
                $chem = $check->fetch(PDO::FETCH_ASSOC);

                if (!$chem) {
                    throw new Exception("Chemical not found (ID: $chemId).");
                }

                // 🔒 Strict condition for stock validation
                if ($chem['r_status'] === 'Out of Stock' || ($chem['r_quantity'] <= 0 && $chem['unit'] <= 0)) {
                    throw new Exception("Chemical '{$chem['r_name']}' is out of stock.");
                }

                // 🧮 Optional: Check if enough unit remains for requested ml
                if ($chem['unit'] < $quantity && $chem['r_quantity'] <= 0) {
                    throw new Exception("Not enough stock for '{$chem['r_name']}'. Only {$chem['unit']} ml remaining.");
                }

                // ✅ Insert reservation if available
                $sql2 = $conn->prepare("INSERT INTO reservation_chemicals (reservation_id, chemical_id, quantity) 
                                        VALUES (?, ?, ?)");
                $sql2->execute([$reservationId, $chemId, $quantity]);
                if ($sql2->rowCount() > 0) $count++;
            }
        }

        $conn->commit();
        echo ($count > 0) ? '1' : '0';

    } catch (Exception $e) {
        $conn->rollBack();
        // Instead of generic '0', send specific message for frontend toastr
        echo "error: " . $e->getMessage();
    }
}



		public function add_newstudent($sid_number, $s_fname, $s_lname, $s_gender, $s_contact, $s_department, $s_year, $s_section)
{
    global $conn;

    session_start();
    $h_desc = 'add new student';
    $h_tbl = 'member';
    $sessionid = $_SESSION['admin_id'];
    $sessiontype = $_SESSION['admin_type'];

    $type = 'Student';

    // Check if student with this school id already exists
    $sql = $conn->prepare('SELECT * FROM member WHERE m_school_id = ?');
    $sql->execute([$sid_number]);
    $sql_count = $sql->rowCount();

    if ($sql_count <= 0) {
        // Insert new student record
        $insert = $conn->prepare('INSERT INTO member(m_school_id, m_fname, m_lname, m_gender, m_contact, m_department, m_year_section, m_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $inserted = $insert->execute([$sid_number, $s_fname, $s_lname, $s_gender, $s_contact, $s_department, $s_year . ' - ' . $s_section, $type]);

        if ($inserted) {
            // Insert into history logs
            $log = $conn->prepare('INSERT INTO history_logs(description, table_name, status_name, user_id, user_type) VALUES (?, ?, ?, ?, ?)');
            $log->execute([$h_desc, $h_tbl, 'added', $sessionid, $sessiontype]);

            echo "1"; // success
        } else {
            echo "0"; // failed insert
        }
    } else {
        echo "2"; // duplicate school id
    }
}
 

		public function add_newfaculty($f_id, $f_fname, $f_lname, $f_gender, $f_contact, $f_department, $type)
{
    global $conn;

    session_start();
    $sessionid = $_SESSION['admin_id'];
    $sessiontype = $_SESSION['admin_type'];

    $h_desc = 'Added new faculty: ' . $f_fname . ' ' . $f_lname;
    $h_tbl = 'member';

    // Check if already exists
    $sql = $conn->prepare('SELECT * FROM member WHERE m_school_id = ? AND m_fname = ? AND m_lname = ? AND m_type = ?');
    $sql->execute(array($f_id, $f_fname, $f_lname, $type));
    $sql_count = $sql->rowCount();

    if ($sql_count <= 0) {
        // Insert into member table
        $insert = $conn->prepare('INSERT INTO member (m_school_id, m_fname, m_lname, m_gender, m_contact, m_department, m_type) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $insert->execute(array($f_id, $f_fname, $f_lname, $f_gender, $f_contact, $f_department, $type));
        $insert_count = $insert->rowCount();

        if ($insert_count > 0) {
            // Insert into history_logs table
            $log = $conn->prepare('INSERT INTO history_logs (description, table_name, status_name, user_id, user_type) VALUES (?, ?, ?, ?, ?)');
            $log->execute(array($h_desc, $h_tbl, 'added', $sessionid, $sessiontype));
            
            echo "1"; // success
        } else {
            echo "0"; // failed
        }
    } else {
        echo "2"; // duplicate
    }
}


	}


	$add_function = new add();

	$key = trim($_POST['key']);

	switch ($key){
		case 'add_room';
		$name = strtolower($_POST['name']);
		$add_function->add_room($name);
		break;

		case 'sign_student':
    $sid_number = trim($_POST['sid_number']);
    $s_fname = strtolower(trim($_POST['s_fname']));
    $s_lname = strtolower(trim($_POST['s_lname']));
    $s_gender = trim($_POST['s_gender']);
    $s_contact = trim($_POST['s_contact']);
    $s_department = trim($_POST['s_department']);
    $s_year = trim($_POST['s_year']);
    $s_section = trim($_POST['s_section']);
    $type = trim($_POST['type']); // ✅ use actual string "Student"

    $add_function->sign_student(
        $sid_number, $s_fname, $s_lname, $s_gender, $s_contact,
        $s_department, $s_year, $s_section, $type
    );
    break;


		case 'sign_faculty':
    $f_id = trim($_POST['f_id']);
    $f_fname = strtolower(trim($_POST['f_fname']));
    $f_lname = strtolower(trim($_POST['f_lname']));
    $f_gender = trim($_POST['f_gender']);
    $f_contact = trim($_POST['f_contact']);
    $f_department = trim($_POST['f_department']);
    $type = trim($_POST['type']); // ✅ use actual string "Faculty"

    $add_function->sign_faculty($f_id, $f_fname, $f_lname, $f_gender, $f_contact, $f_department, $type);
    break;

	case 'add_reagent';
    $r_name = trim($_POST['r_name']);
    $r_quantity = trim($_POST['r_quantity']);
	$unit = trim($_POST['unit']);
    $r_date_received = $_POST['r_date_received'] ?? null;
    $r_date_opened = $_POST['r_date_opened'] ?? null;
    $r_expiration = $_POST['r_expiration'] ?? null;
    $r_storage = trim($_POST['r_storage']);
    $r_hazard = trim($_POST['r_hazard']);

    $add_function->add_reagent($r_name, $r_quantity, $unit, $r_date_received, $r_date_opened, $r_expiration, $r_storage, $r_hazard);
    break;

		case 'add_equipment';
		$e_number = trim($_POST['e_number']);
		$e_model = trim($_POST['e_model']);
		$e_category = trim($_POST['e_category']);
		$e_brand = trim($_POST['e_brand']);
		$e_description = trim($_POST['e_description']);
		$e_stock = trim($_POST['e_stock']);
		$e_assigned = trim($_POST['e_assigned']);
		$e_type = trim($_POST['e_type']);
		$e_status = trim($_POST['e_status']);
		$add_function->add_equipment($e_number,$e_model,$e_category,$e_brand,$e_description,$e_stock,$e_assigned,$e_type,$e_status);
		break;

		case 'add_member';
		if($_FILES['file']['name'])  
 		{
 			$filename = explode('.',$_FILES['file']['name']);  
           	if($filename[1] == 'csv')  
       		{
       			$as = 1;
       			$handle = fopen($_FILES['file']['tmp_name'], "r");  
       		}else{
       			$as = 0;
       		} 
 		}
 		$add_function->add_member($as,$handle);
		break;

		case 'add_itemqty';
		$id = trim($_POST['id']);
		$item_qty = trim($_POST['item_qty']);
		$add_function->add_itemqty($id,$item_qty);
		break;

		case 'add_reagentqty':
    	$id = trim($_POST['id']);
    	$reagent_qty = trim($_POST['reagent_qty']);
    	$add_function->add_reagentqty($id, $reagent_qty);
    	break;

		case 'add_reagentunitqty':
    	$id = trim($_POST['id']);
    	$reagent_unitqty = trim($_POST['reagent_unitqty']);
    	$add_function->add_reagentunitqty($id, $reagent_unitqty);
    	break;

		case 'add_borrower';
    $name         = $_POST['borrow_membername'];
    $item         = isset($_POST['borrowitem']) ? $_POST['borrowitem'] : array();
    $chemical     = isset($_POST['borrowchemical']) ? $_POST['borrowchemical'] : array();
    $id           = $_POST['user_id'];
    $reserve_room = $_POST['reserve_room'];
    $timeLimit    = $_POST['expected_time_of_return'];

    $add_function->add_borrower($name, $item, $chemical, $id, $reserve_room, $timeLimit);
    break;


		case 'add_users';
		$u_fname = trim($_POST['u_fname']);
		$u_username = trim($_POST['u_username']);
		$u_password = trim(md5($_POST['u_password']));
		$u_type = trim($_POST['u_type']);
		$add_function->add_users($u_fname,$u_username,$u_password,$u_type);
		break;

		case 'addclient_reservation';
    $items       = isset($_POST['reserve_item']) ? $_POST['reserve_item'] : array();
    $chemicals   = isset($_POST['borrow_chemical']) ? $_POST['borrow_chemical'] : array();
    $date        = $_POST['reserved_date'];
    $time        = $_POST['reserved_time'];
    $client_id   = $_POST['client_id'];
    $assign_room = $_POST['reserve_room'];
    $timeLimit   = $_POST['time_limit'];

    $add_function->addclient_reservation($items, $chemicals, $date, $time, $client_id, $assign_room, $timeLimit);
    break;


		case 'add_newstudent';
		$sid_number = trim($_POST['sid_number']);
 		$s_fname = ucwords(trim($_POST['s_fname']));
 		$s_lname = ucwords(trim($_POST['s_lname']));
 		$s_gender = trim($_POST['s_gender']);
 		$s_contact = trim($_POST['s_contact']);
 		$s_department = trim($_POST['s_department']);
 		$s_year = trim($_POST['s_year']);
 		$s_section = ucwords(trim($_POST['s_section']));
 		$add_function->add_newstudent($sid_number,$s_fname,$s_lname,$s_gender,$s_contact,$s_department,$s_year,$s_section);
		break;

		case 'add_newfaculty';
		$f_id = trim($_POST['f_id']);
		$f_fname = strtolower(trim($_POST['f_fname']));
		$f_lname = strtolower(trim($_POST['f_lname']));
		$f_gender = trim($_POST['f_gender']);
		$f_contact = trim($_POST['f_contact']);
		$f_department = trim($_POST['f_department']);
 		$type = 'Faculty';
		$add_function->add_newfaculty($f_id,$f_fname,$f_lname,$f_gender,$f_contact,$f_department,$type);
		break;
		



	}



	?>


