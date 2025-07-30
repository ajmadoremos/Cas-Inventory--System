<?php 
	include 'header.php';
	include '../config/config.php'; // make sure config is included for PDO $conn
?>
<div id="sidebar-collapse" class="col-sm-3 col-lg-2 col-md-2 sidebar">
	<ul class="nav menu">
		<li><a href="dashboard"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"/></svg>Dashboard</a></li>
		<li class="parent">
			<a href="#sub-item-1" data-toggle="collapse">
				<span data-toggle="collapse" href="#sub-item-1"><svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"/></svg></span> Transaction 
			</a>
			<ul class="children collapse" id="sub-item-1">
				<li><a href="reservation"><svg class="glyph stroked eye"><use xlink:href="#stroked-eye"/></svg>Reservations</a></li>
				<li><a href="new"><svg class="glyph stroked plus sign"><use xlink:href="#stroked-plus-sign"/></svg>New</a></li>
				<li><a href="borrow"><svg class="glyph stroked download"><use xlink:href="#stroked-download"/></svg>Borrowed Items</a></li>
				<li><a href="return"><svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"/></svg>Returned Items</a></li>
			</ul>
		</li>
		<?php if($_SESSION['admin_type'] == 1){ ?>
		<li><a href="items"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"/></svg>Item</a></li>
		<li><a href="members"><svg class="glyph stroked male user"><use xlink:href="#stroked-male-user"/></svg>Borrower</a></li>
		<li><a href="room"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"/></svg>Room</a></li>
		<li class="active"><a href="#"><svg class="glyph stroked clipboard with paper"><use xlink:href="#stroked-clipboard-with-paper"/></svg>Inventory</a></li>
		<li><a href="report"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"/></svg>Reports</a></li>
		<li><a href="user"><svg class="glyph stroked female user"><use xlink:href="#stroked-female-user"/></svg>Users</a></li>
		<?php } ?>
	</ul>
</div>

<div class="col-sm-9 col-lg-10 col-md-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-3 main">
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="dashboard"><svg class="glyph stroked home"><use xlink:href="#stroked-home"/></svg></a></li>
			<li class="active">Inventory</li>
		</ol>

		<div class="breadcrumb">
			<ul class="nav nav-pills">
				<li class="active"><a href="#new" data-toggle="tab"><i class="fa fa-list"></i> New</a></li>
				<li><a href="#old" data-toggle="tab"><i class="fa fa-question"></i> Old</a></li>
				<li><a href="#lost" data-toggle="tab"><i class="fa fa-question"></i> Lost</a></li>
				<li><a href="#damaged" data-toggle="tab"><i class="fa fa-file-code-o"></i> Damaged</a></li>
				<li><a href="#pulledout" data-toggle="tab"><i class="fa fa-chevron-circle-right"></i> Total Items</a></li>
				<li><a href="#transferred" data-toggle="tab"><i class="fa fa-chevron-circle-right"></i> Transferred</a></li>
				<li><a href="#report2" data-toggle="tab"><i class="fa fa-chevron-circle-right"></i> Borrowed</a></li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="tab-content">

						<!-- Borrowed tab where the old mysql_query was -->
						<div class="tab-pane fade" id="report2">
							<form method="get" action="viewreport.php">
								<div class="form-group">
									<label>Select Month:</label>
									<select name="program" class="form-control" style="width:280px; display:inline-block;">
										<?php
											$stmt = $conn->prepare("SELECT DISTINCT DATE_FORMAT(date_return, '%Y-%m') AS month_year FROM borrow WHERE progid <> 0 ORDER BY month_year ASC");
											$stmt->execute();
											$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
											foreach ($results as $row) {
												$value = $row['month_year'];
												echo "<option value='$value'>" . strtoupper($value) . "</option>";
											}
										?>
									</select>
									<button type="submit" class="btn btn-primary">View Report</button>
								</div>
							</form>
							
							<hr/>

							<table class="table">
								<thead>
									<tr>
										<th>Borrower</th>
										<th>Items</th>
										<th>Borrowed Date</th>
										<th>Returned Date</th>
									</tr>
								</thead>
							</table>
						</div>

						<!-- The rest of your tabs remain -->
						<div class="tab-pane fade in active" id="new">
							<table class="table table_inventory_new">
								<thead>
									<tr>
										<th>Model</th>
										<th>Category</th>
										<th>Brand</th>
										<th>No. of items</th>
										<th>No. of items left</th>
									</tr>
								</thead>
							</table>
						</div>

						<div class="tab-pane fade" id="old">
							<table class="table table_inventory_old">
								<thead>
									<tr>
										<th>Model</th>
										<th>Category</th>
										<th>Brand</th>
										<th>No. of items</th>
										<th>Remarks</th>
									</tr>
								</thead>
							</table>
						</div>

						<div class="tab-pane fade" id="lost">
							<table class="table table_inventory_lost">
								<thead>
									<tr>
										<th>Model</th>
										<th>Category</th>
										<th>Brand</th>
										<th>No. of items</th>
										<th>Remarks</th>
									</tr>
								</thead>
							</table>
						</div>

						<div class="tab-pane fade" id="damaged">
							<table class="table table_inventory_damaged">
								<thead>
									<tr>
										<th>Model</th>
										<th>Category</th>
										<th>Brand</th>
										<th>No. of items</th>
										<th>Remarks</th>
									</tr>
								</thead>
							</table>
						</div>

						<div class="tab-pane fade" id="pulledout">
							<table class="table table_inventory_all">
								<thead>
									<tr>
										<th>Category</th>
										<th>Available</th>
										<th>Unusable (Damage/Lost/Borrowed)</th>
										<th>Total</th>
									</tr>
								</thead>
							</table>
						</div>

						<div class="tab-pane fade" id="transferred">
							<table class="table table_inventory_transfer">
								<thead>
									<tr>
										<th>Model</th>
										<th>Category</th>
										<th>Brand</th>
										<th>No. of items</th>
										<th>Room</th>
									</tr>
								</thead>
							</table>
						</div>

					</div>
				</div>
			</div>
		</div><!-- panel -->
	</div><!-- row -->

</div><!-- main -->

<?php include 'footer.php'; ?>
