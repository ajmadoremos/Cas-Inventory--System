<?php 
	include 'header.php';
?>
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 col-md-2 sidebar">
		<form role="search">
			<div class="form-group">
				<!-- <input type="text" class="form-control" placeholder="Search"> -->
			</div>
		</form>
		<ul class="nav menu">
			<li class="">
				<a href="dashboard">
					<svg class="glyph stroked dashboard-dial">
						<use xlink:href="#stroked-dashboard-dial"></use>
					</svg>
					Dashboard
				</a>
			</li>
			<li class="parent ">
				<a href="#sub-item-1" data-toggle="collapse">
					<span data-toggle="collapse" href="#sub-item-1"><svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></use></svg></span> Transaction 
				</a>
				<ul class="children collapse" id="sub-item-1">
					
<li>
						<a class="" href="reservation">
							<svg class="glyph stroked eye">
								<use xlink:href="#stroked-eye"/>
							</svg>
							Reservations
						</a>
					</li>


					<li>
						<a class="" href="new">
							<svg class="glyph stroked plus sign">
								<use xlink:href="#stroked-plus-sign"/>
							</svg>
							New
						</a>
					</li>
					<li>
						<a class="" href="borrow">
							<svg class="glyph stroked download">
								<use xlink:href="#stroked-download"/>
							</svg>
							Borrowed Items
						</a>
					</li>
					<li>
						<a class="" href="return">
							<svg class="glyph stroked checkmark">
								<use xlink:href="#stroked-checkmark"/>
							</svg>
							Returned Items
						</a>
					</li>
				</ul>
			</li>
			<?php if($_SESSION['admin_type'] == 1){ ?>
			<li class="active">
				<a href="#">
					<svg class="glyph stroked desktop">
						<use xlink:href="#stroked-desktop"/>
					</svg>
					Item
				</a>
			</li>
			
			
			<li>
				<a href="members">
					<svg class="glyph stroked male user ">
						<use xlink:href="#stroked-male-user"/>
					</svg>
					Borrower
				</a>
			</li>
			<li>
				<a href="room">
					<svg class="glyph stroked app-window">
						<use xlink:href="#stroked-app-window"></use>
					</svg>
					Room
				</a>
			</li>
			<li>
				<a href="inventory">
					<svg class="glyph stroked clipboard with paper">
						<use xlink:href="#stroked-clipboard-with-paper"/>
					</svg>
					Inventory
				</a>
			</li>
			
			<li>
				<a href="user">
					<svg class="glyph stroked female user">
						<use xlink:href="#stroked-female-user"/>
					</svg>
					User
				</a>
			</li>
			<?php 
				}
				($_SESSION['admin_type'] == 1) ? include('include_history.php') : false;
			 ?>
		</ul>
	</div><!--/.sidebar-->


	<div class="main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="dashboard"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Item</li>
			</ol>
			<div class="breadcrumb">
				<button class="btn btn-primary col-sm-offset-10 add_equipment">
					<svg class="glyph stroked plus sign">
						<use xlink:href="#stroked-plus-sign"/>
					</svg> &nbsp;
					Add Item
				</button>
				<button class="btn btn-primary add_reagent">
					<svg class="glyph stroked plus sign">
						<use xlink:href="#stroked-plus-sign"/>
					</svg> &nbsp;
					Add Chemical Reagent
				</button>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">

						<!-- Tabs for Equipment & Chemical Reagents -->
						<ul class="nav nav-tabs" role="tablist">
							<li class="active"><a href="#equipment_tab" role="tab" data-toggle="tab">Equipment</a></li>
							<li><a href="#chemical_tab" role="tab" data-toggle="tab">Chemical Reagents</a></li>
						</ul>

						<div class="tab-content">
							<!-- Equipment Tab -->
							<div class="tab-pane fade in active" id="equipment_tab">
								<br>
								<table class="table table-bordered table_equipment">
									<thead>
										<tr>
											<th>Image</th>
											<th>Model</th>
											<th>Category</th>
											<th>Brand</th>
											<th>Description</th>
											<th>Quantity</th>
											<th>Quantity Left</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
								</table>
							</div>

							<!-- Chemical Tab -->
							<div class="tab-pane fade" id="chemical_tab">
								<br>
								<table class="table table-bordered table_reagent">
									<thead>
										<tr>
											<th>Name of Reagent</th>
											<th>Quantity</th>
											<th>Quantity(ml)</th>
											<th>Date Received</th>
											<th>Date Opened</th>
											<th>Expiration Date</th>
											<th>Storage Location</th>
											<th>Hazard Information</th>
											<th>Status</th> <!-- ✅ Added -->
											<th>Action</th>
										</tr>
									</thead>
								</table>
							</div>

						</div>

					</div>
				</div><!-- panel -->
			</div><!-- panel -->
		</div>
	</div>


<div class="right-sidebar equipment-side">
    <div class="sidebar-form">
        <div class="container-fluid">
            <h4 class="alert bg-success">
                <svg class="glyph stroked plus sign">
                    <use xlink:href="#stroked-plus-sign"/>
                </svg>
                Add Item
            </h4>
            <form class="frm_addequipment" enctype="multipart/form-data">
                <input type="hidden" name="key" value="add_equipment" />
                
                <div class="form-group">
                    <label>Device ID</label>
                    <input type="text" name="e_number" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Model</label>
                    <input type="text" name="e_model" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Category</label>
                    <select name="e_category" class="form-control" required>
                        <option selected disabled>Please select category</option>
                        <option>Mouse</option>
                        <option>Keyboard</option>
                        <option>Monitor</option>
                        <option>Projector</option>
                        <option>Remote</option>
                        <option>DLP Screen</option>
                        <option>Aircon</option>
                        <option>TV</option>
                        <option>AVR</option>
                        <option>Extension</option>
                        <option>UPS</option>
                        <option>Router</option>
                        <option>Table</option>
                        <option>Chair</option>
                        <option>Switch Hub</option>
                    </select> 
                </div>
                
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" name="e_brand" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="e_description" class="form-control" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="e_stock" class="form-control" min="1" required>
                </div>
                
                <!-- ✅ Hidden default room assignment to prevent missing e_assigned -->
                <input type="hidden" name="e_assigned" value="1"> <!-- Change 1 to your default room ID -->
                
                <div class="form-group">
                    <label>Type</label>
                    <select name="e_type" class="form-control" required>
                        <option disabled selected>Please select type</option>
                        <option>Consumable</option>
                        <option>Non-consumable</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="e_status" class="form-control" required>
                        <option disabled selected>Please select status</option>
                        <option value="1">New</option>
                        <option value="2">Old</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>MR</label>
                    <input type="text" name="e_mr" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" name="e_price" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Photo</label>
                    <input type="file" name="e_photo" class="form-control" required />
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-danger btn-block cancel-equipment" type="button">
                                <i class="fa fa-remove"></i>
                                CANCEL
                            </button>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-primary btn-block" type="submit">
                                ADD
                                <i class="fa fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <br><br><br>
            </form>
        </div>
    </div>
</div>

<!-- Chemical Reagent Sidebar -->
<div class="right-sidebar reagent-side">
    <div class="sidebar-form">
        <div class="container-fluid">
            <h4 class="alert bg-success">
                <svg class="glyph stroked plus sign">
                    <use xlink:href="#stroked-plus-sign"/>
                </svg>
                Add Chemical Reagent
            </h4>
            <form class="frm_addreagent">
                <input type="hidden" name="key" value="add_reagent">
                
                <div class="form-group">
                    <label>Name of Reagent</label>
                    <input type="text" name="r_name" placeholder="Enter reagent name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="r_quantity" placeholder="Enter quantity" class="form-control" min="1" required>
                </div>

				<div class="form-group">
    				<label>Quantity (ml)</label>
    				<input type="number" name="unit" placeholder="Enter quantity" class="form-control" min="1" required>
				</div>

                <div class="form-group">
                    <label>Date Received</label>
                    <input type="date" name="r_date_received" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Date Opened</label>
                    <input type="date" name="r_date_opened" class="form-control">
                </div>

                <div class="form-group">
                    <label>Expiration Date</label>
                    <input type="date" name="r_expiration" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Storage Location</label>
                    <input type="text" name="r_storage" placeholder="Enter storage location" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Hazard Information</label>
                    <textarea name="r_hazard" placeholder="Enter hazard info" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-danger btn-block cancel-reagent">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-block">Add Reagent</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="right-sidebar equipment-view">
	<div class="sidebar-form equipment-form">
		
	</div>
</div>



<?php include 'footer.php'; ?>