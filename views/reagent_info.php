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
					Chemical Reagents
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

	<div class="col-sm-9 col-lg-10 col-md-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-3 main">
		
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="dashboard"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class=""><a href="items">Chemical Reagents</a></li>
				<li class="active">Reagent Information</li>
			</ol>
			<div class="breadcrumb">
				<button class="btn btn-primary reagent-add" type="button">
					<i class="fa fa-plus"></i>
					Add Quantity
				</button>
				<button class="btn btn-primary reagent-edit" type="button">
					<i class="fa fa-edit"></i>
					Edit Reagent
				</button>
				<button class="btn btn-primary reagent-change" type="button">
					<i class="fa fa-arrows-alt"></i>
					Change Status
				</button>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<td class="col-sm-6">Name of Reagent</td>
									<td class="r_name"></td>
								</tr>
								<tr>
									<td class="col-sm-6">Quantity</td>
									<td class="r_quantity"></td>
								</tr>
								<tr>
           							<td class="col-sm-6">Unit</td>
           	 						<td class="r_unit"></td> <!-- ✅ new row for unit -->
        						</tr>
								<tr>
									<td class="col-sm-6">Date Received</td>
									<td class="r_date_received"></td>
								</tr>
								<tr>
									<td class="col-sm-6">Date Opened</td>
									<td class="r_date_opened"></td>
								</tr>
								<tr>
									<td class="col-sm-6">Expiration Date</td>
									<td class="r_expiration"></td>
								</tr>
								<tr>
									<td class="col-sm-6">Storage Location</td>
									<td class="r_storage"></td>
								</tr>
								<tr>
									<td class="col-sm-6">Hazard Information</td>
									<td class="r_hazard"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div><!-- panel -->
			</div><!-- col-lg-12 -->
		</div><!--/.row-->
	</div><!--/.main-->

<div class="right-sidebar reagent-info">
	<div class="sidebar-form">
		<div class="container-fluid reagent-forminfo"></div>
	</div>
</div>

<?php include 'footer.php'; ?>

<script type="text/javascript">
	
	var id = '<?php echo $_GET["r_id"]; ?>';


	// Initialize reagent info
	reagent_info(id);

	function getReagentId(){
		return id;
	}

</script>
