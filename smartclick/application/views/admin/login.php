<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Primocys admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
	<!-- <meta name="description" content="viho admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities."> -->
	<meta name="keywords" content="admin template, viho admin template, dashboard template, flat admin template, responsive admin template, web app">
	<meta name="author" content="pixelstrap">
	<link rel="icon" href="<?php echo base_url(); ?>/assets/images/logo/snapta.jpg" type="image/x-icon" sizes="16x16">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/images/snapta.jpg" type="image/x-icon" sizes="16x16">
	<title>Snapta - Admin Login</title>
	<!-- Google font-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
	<!-- Font Awesome-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/fontawesome.css">
	<!-- ico-font-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/icofont.css">
	<!-- Themify icon-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/themify.css">
	<!-- Flag icon-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/flag-icon.css">
	<!-- Feather icon-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/feather-icon.css">
	<!-- Plugins css start-->
	<!-- Plugins css Ends-->
	<!-- Bootstrap css-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
	<!-- App css-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">
	<link id="color" rel="stylesheet" href="<?= base_url() ?>assets/css/color-1.css" media="screen">
	<!-- Responsive css-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/responsive.css">
	<link href="<?php echo base_url() ?>assets/css/toastr.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<!-- Loader starts-->
	<!-- <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div> -->
	<!-- Loader ends-->
	<!-- page-wrapper Start-->
	<section>
		<div class="container-fluid">
			<div class="row">
				<!-- <div class="col-xl-5"><img class="bg-img-cover bg-center" src="<?= base_url() ?>/assets/images/login/3.jpg" alt="looginpage"></div> -->
				<div class="col-xl-12 p-0">
					<div class="login-card">
						<form class="theme-form login-form" id="loginAjax" role="form" method="post" action="<?php echo base_url('admin/login-admin'); ?>">
							<div class="text-center">
								<a href="<?php echo base_url(); ?>admin/login"><img style="width: 110px; height: 55px; left:125px;" src="<?php echo base_url(); ?>assets/images/logo/write_Snapta.png"></a>
							</div>
							<h4>Login</h4>
							<h6>Welcome back! Log in to your account.</h6>
							<?php if (!empty($this->session->flashdata('success'))) : ?>
								<div class="alert alert-success">
									<a href="javascript:void()" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<span> <?php echo $this->session->flashdata('success'); ?> </span>
								</div>
							<?php endif ?>
							<?php if ($this->session->flashdata('error')) : ?>
								<div class="alert alert-danger">
									<a href="javascript:void()" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<span><?php echo $this->session->flashdata('error') ?></span>
								</div>
							<?php endif ?>
							<div class="form-group">
								<label>Email Address</label>
								<div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
									<input class="form-control" name="email" id="email" type="email" required="" placeholder="Your_Name@gmail.com">
									<!-- <input type="email" class="form-control" placeholder="Email" name="email"> -->
								</div>
							</div>
							<div class="form-group">
								<label>Password</label>
								<div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
									<input class="form-control" type="password" id="password" name="password" required="" placeholder="*********">
									<!-- <input type="password" class="form-control" placeholder="Password" name="password"> -->
									<!-- <div class="show-hide"><span class="show"></span></div> -->
								</div>
							</div>
							<div class="form-group">
								<div class="checkbox">
									<input name="remember_me" id="remember_me" type="checkbox" checked="" />
									<label class="text-muted" for="remember_me">Remember password</label>
								</div>
								<!-- <a class="link" href="#">Forgot password?</a> -->
							</div>
							<div class="form-group">
								<!-- <button type="button" onclick="login_ajax()" class="btn btn-primary waves-effect waves-light">Sign In</button> -->
								<button type="submit" name="submit" class="btn btn-lg btn-success btn-block login-form__btn submit w-100">Sign In</button>
								<!-- <a onclick="login_ajax()" class="btn btn-primary btn-block waves-effect waves-light">Sign in</a> -->
							</div>
							<!-- <div class="login-social-title">
                                <h5>(: OR :)</h5>
                            </div> -->
							<!-- <div class="form-group">
                                <ul class="login-social">
                                    <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="linkedin"></i></a></li>
                                    <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="twitter"></i></a></li>
                                    <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="facebook"></i></a></li>
                                    <li><a href="https://www.instagram.com/login" target="_blank"><i data-feather="instagram"> </i></a></li>
                                </ul>
                            </div> -->
							<!-- <p>Don't have account?<a class="ms-2" href="#">Create Account</a></p> -->
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- page-wrapper end-->
	<!-- latest jquery-->
	<script src="<?= base_url() ?>assets/js/jquery-3.5.1.min.js"></script>
	<!-- feather icon js-->
	<script src="<?= base_url() ?>assets/js/icons/feather-icon/feather.min.js"></script>
	<script src="<?= base_url() ?>assets/js/icons/feather-icon/feather-icon.js"></script>
	<!-- Sidebar jquery-->
	<script src="<?= base_url() ?>assets/js/sidebar-menu.js"></script>
	<script src="<?= base_url() ?>assets/js/config.js"></script>
	<!-- Bootstrap js-->
	<script src="<?= base_url() ?>assets/js/bootstrap/popper.min.js"></script>
	<script src="<?= base_url() ?>assets/js/bootstrap/bootstrap.min.js"></script>
	<!-- Plugins JS start-->
	<!-- Plugins JS Ends-->
	<!-- Theme js-->
	<script src="<?= base_url() ?>/assets/js/script.js"></script>
	<!-- login js-->
	<!-- Plugin used-->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/toastr.min.js"></script>
	<script type="text/javascript">
		function login_ajax() {

			var email = $("#email").val();
			var password = $("#password").val();
			if (email == '') {
				toastr.error('Please enter email address.');
				$("#email").focus();
				return false;
			} else if (!validateEmail($("#email").val())) {
				toastr.error('Please enter valid email address');
				$("#email").focus();
				return false;
			} else if (password == '') {
				toastr.error('Please enter password.');
				$("#password").focus();
				return false;
			} else {
				if ($("#remember_me").is(":checked")) {
					var remember_me = 1;
				} else {
					var remember_me = 0;
				}
				var dataString = "email=" + email + "&password=" + password + "&remember_me=" + remember_me;

				//alert(dataString);

				$.ajax({
					type: 'post',
					url: '<?php echo base_url() ?>index.php/login/login_admin',
					data: dataString,
					success: function(data) {
						var obj = JSON.parse(data);
						console.log(data.status);
						if (obj.status == '200') {
							window.location.replace("<?php echo base_url() ?>index.php/admin_home");
						} else {
							toastr.error(obj.message);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(xhr);
					}
				});
			}
			event.preventDefault();
			return false;
		}

		function validateEmail($email) {
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			return emailReg.test($email);
		}
	</script>
</body>

</html>
<!-- jQuery -->
<script src="<?php echo base_url(); ?>bower_components/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url(); ?>bower_components/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="<?php echo base_url(); ?>bower_components/js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="<?php echo base_url(); ?>bower_components/js/startmin.js"></script>