<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">

	<title></title>

	<!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="../assets/custom/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/custom/css/bootstrap-table.css">
	<link rel="stylesheet" type="text/css" href="../assets/custom/css/datepicker.css">
	<link rel="stylesheet" type="text/css" href="../assets/custom/css/datepicker3.css">
	<link rel="stylesheet" type="text/css" href="../assets/custom/css/styles.css">


	<!-- datatables -->
	<link rel="stylesheet" type="text/css" href="../assets/datatables/css/jquery.dataTables.min.css">

	<!-- fontawesome -->
	<link rel="stylesheet" type="text/css" href="../assets/fontawesome/css/font-awesome.min.css">

	<!-- custom -->
	<link rel="stylesheet" type="text/css" href="../assets/mycustom/css/styles.css">

	<!-- toastr -->
	<link rel="stylesheet" type="text/css" href="../assets/toastr/css/toastr.css">


</head>

<body class="index-body login">


	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">CAS LABORATORY MANAGEMENT SYSTEM</a>
			</div>
							
		</div><!-- /.container-fluid -->
	</nav>	


<div class="container-fluid">

	<div class="panel">
		<div class="panel-body">
	<div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-4">
		<!-- LOGIN FORM -->
		<div id="login-form">
			<form class="frm_memberlogin">
				<h4 class="alert bg-primary">Borrower Login</h4>
				<div class="form-group">
					<label>ID Number</label>
					<input type="text" name="id_number" class="form-control" placeholder="e.g. 21-1-1-0221" maxlength="14" required autofocus>
				</div>
				<div class="form-group">
					<button class="btn btn-primary btn-block">Log in</button>
				</div>
				<div class="form-group text-center">
					<a href="#" onclick="toggleForms()">Don't have an account? Sign up</a><br>
					<a href="../">Go to Admin Panel</a>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="signup-form" style="display: none;">
			<h4 class="alert bg-success">Borrower Registration</h4>
			<?php include 'signup.php'; ?>
			<div class="form-group text-center">
				<a href="#" onclick="toggleForms()">Already have an account? Log in</a>
			</div>
		</div>
	</div>
	
</div>
<script>
	function toggleForms() {
		const loginForm = document.getElementById('login-form');
		const signupForm = document.getElementById('signup-form');
		const isLoginVisible = loginForm.style.display !== 'none';
		
		loginForm.style.display = isLoginVisible ? 'none' : 'block';
		signupForm.style.display = isLoginVisible ? 'block' : 'none';
	} 
</script>
</body>

<?php include 'footer.php'; ?>