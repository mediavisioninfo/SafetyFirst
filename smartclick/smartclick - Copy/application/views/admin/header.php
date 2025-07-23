<?php
$id = $this->session->userdata('aid');
$profile = $this->admin_model->get_admin($id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Primocys admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
	<meta name="keywords" content=" primocys admin template, Primocys admin template, primocys dashboard template, primocys flat admin template, primocys responsive admin template,primocys web app">
	<meta name="author" content="Primocys">
	<link rel="icon" href="<?php echo base_url(); ?>assets/images/logo/snapta.jpg" type="image/x-icon" sizes="16x16">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/logo/snapta.jpg" type="image/x-icon" sizes="16x16">
	<title>Snapta - Admin</title>
	<!-- Google font-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" />
	<!-- Font Awesome-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/fontawesome.css">
	<!-- ico-font-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/icofont.css">
	<!-- Themify icon-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/themify.css">
	<!-- Flag icon-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/flag-icon.css">
	<!-- Feather icon-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/feather-icon.css">
	<!-- Plugins css start-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/datatables.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/datatable-extension.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/prism.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/animate.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/chartist.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/owlcarousel.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/prism.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/date-picker.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/vector-map.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/photoswipe.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/tour.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/dropzone.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/toastr.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/range-slider.css">
	<!-- Plugins css Ends-->
	<!-- Bootstrap css-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap.css">
	<!-- App css-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/style.css">
	<link id="color" rel="stylesheet" href="<?php echo base_url() ?>assets/css/color-1.css" media="screen">
	<!-- Responsive css-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/responsive.css">
</head>

<body>
	<!-- Loader starts-->
	<div class="loader-wrapper">
		<div class="theme-loader">
			<div class="loader-p"></div>
		</div>
	</div>
	<!-- Loader ends-->
	<!-- page-wrapper Start-->
	<div class="page-wrapper" id="pageWrapper">
		<!-- Page Header Start-->
		<div class="page-main-header">
			<div class="main-header-right row m-0">
				<div class="main-header-left">
					<div class="logo-wrapper"><a href=""><img class="img-fluid" src="<?php echo base_url(); ?>assets/images/logo/write_Snapta.png" alt=""></a></div>
					<div class="dark-logo-wrapper"><a href=""><img class="img-fluid" src="<?php echo base_url(); ?>assets/images/logo/snapta_logo.jpg" alt=""></a></div>
					<div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle"></i></div>
				</div>
				<div class="nav-right col pull-right right-menu p-0">
					<ul class="nav-menus">
						<li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
						<li>
							<div class="mode"><i class="fa fa-moon-o"></i></div>
						</li>
						<li class="onhover-dropdown p-0">
							<button class="btn btn-primary-light" type="button"><a href="<?php echo base_url('admin/logout'); ?>"><i data-feather="log-out"></i>Logout</a></button>
						</li>
					</ul>
				</div>
				<div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
			</div>
		</div>
		<!-- Page Header Ends-->