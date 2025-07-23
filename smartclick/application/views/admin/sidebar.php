<?php $uri = $this->uri->segment(2);
$id = $this->session->userdata('aid');
$profile = $this->admin_model->get_admin($id);

// echo $page;
?>

<!-- Page Body Start-->
<div class="page-body-wrapper sidebar-icon">
	<!-- Page Sidebar Start-->
	<header class="main-nav">
		<div class="sidebar-user text-center">
			<!-- <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a>-->
			<img class="img-90 rounded-circle" src="<?php echo base_url() ?>assets/images/logo/default.png" alt="">
			<!-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div><a href="javascript:void(0)"> -->
			<a href="javascript:void(0)">
				<h6 class="mt-3 f-14 f-w-600"><?php echo $profile['name'] ?></h6>
			</a>
			<ul>
				<li><span class="counter"><?php echo $this->admin_model->get_total_posts(); ?></span>
					<p>Posts</p>
				</li>
				<li><span class="counter"><?php echo $this->admin_model->get_total_story(); ?></span>
					<p>Storys</p>
				</li>
				<li><span class="counter"><?php echo $this->admin_model->get_total_users(); ?></span>
					<p>Users </p>
				</li>
			</ul>
		</div>
		<nav>
			<div class="main-navbar">
				<div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
				<div id="mainnav">
					<ul class="nav-menu custom-scrollbar">
						<li class="back-btn">
							<div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
						</li>
						<!-- <li class="sidebar-main-title">
							<div>
								<h6>General </h6><?php //echo $page 
													?>
							</div>
						</li> -->
						<li class="dropdown"><a class="nav-link menu-title link-nav <?php echo ($page == "index") ? "active" : "" ?>" href="<?php echo base_url('admin/dashboard'); ?>"><i data-feather="home"></i><span> Dashboard</span></a></li>

						<li class="dropdown"><a class="nav-link menu-title link-nav <?php echo ($page == "user") ? "active" : "" ?>" href="<?php echo base_url('admin/user'); ?>"><i data-feather="user"></i><span> Users</span></a></li>

						<li class="dropdown"><a class="nav-link menu-title <?php echo ($page == "all_post" || $page == "trending_post" || $page == "like" || $page == "comment") ? "active" : "" ?>" href="javascript:void(0)"><i data-feather="airplay"></i><span> Posts Details</span></a>
							<ul class="nav-submenu menu-content">
								<li><a class="<?php echo ($page == "all_post") ? "active" : "" ?>" href="<?php echo base_url('admin/all-post'); ?>"> All Posts</a></li>
								<li><a class=" <?php echo ($page == "trending_post") ? "active" : "" ?>" href="<?php echo base_url('admin/trending-post'); ?>"> Trending Posts</a></li>
								<li><a class=" <?php echo ($page == "like") ? "active" : "" ?>" href="<?php echo base_url('admin/like'); ?>"> Likes</a></li>
								<li><a class=" <?php echo ($page == "comment") ? "active" : "" ?>" href="<?php echo base_url('admin/comment'); ?>"> Comments</a></li>
							</ul>
						</li>
						<li class="dropdown"><a class="nav-link menu-title <?php echo ($page == "storys") ? "active" : "" ?>" href="javascript:void(0)"><i data-feather="star"></i><span> Storys</span></a>
							<ul class="nav-submenu menu-content">
								<li><a class="<?php echo ($page == "storys") ? "active" : "" ?>" href="<?php echo base_url('admin/newstorys'); ?>">All New Storys </a></li>
								<li><a class="<?php echo ($page == "storys") ? "active" : "" ?>" href="<?php echo base_url('admin/paststorys'); ?>"> Past Story </a></li>
							</ul>
						</li>
						<li class="dropdown"><a class="nav-link menu-title link-nav <?php echo ($page == "followers_following") ? "active" : "" ?>" href="<?php echo base_url('admin/followers-following'); ?>"><i data-feather="user-check"></i><span>Followers-Following</span></a></li>

						<li class="dropdown"><a class="nav-link menu-title <?php echo ($page == "user_notifications" || $page == "notify_admin") ? "active" : "" ?>" href="javascript:void(0)"><i data-feather="bell"></i><span> Notifications</span></a>
							<ul class="nav-submenu menu-content">
								<li><a class=" <?php echo ($page == "user_notifications") ? "active" : "" ?>" href="<?php echo base_url('admin/push-notifications'); ?>"> Push Notification</a></li>
								<li><a class=" <?php echo ($page == "notify_admin") ? "active" : "" ?>" href="<?php echo base_url('admin/admin-notifications'); ?>"> View Notifications</a></li>
								<!-- <li><a href="<?php echo base_url('admin/user-notifications'); ?>"> User Notifications</a></li> -->
							</ul>
						</li>

						<li class="dropdown"><a class="nav-link menu-title <?php echo ($page == "report_post" || $page == "report_user") ? "active" : "" ?>" href="javascript:void(0)"><i data-feather="flag"></i><span> Reports</span></a>
							<ul class="nav-submenu menu-content">
								<li><a class="<?php echo ($page == "report_post") ? "active" : "" ?>" href="<?php echo base_url('admin/report-post'); ?>"> Posts Reported </a></li>
								<li><a class="<?php echo ($page == "report_user") ? "active" : "" ?>" href="<?php echo base_url('admin/report-users'); ?>"> Users Reported </a></li>
							</ul>
						</li>
						<li class="dropdown"><a class="nav-link menu-title <?php echo ($page == "settings") ? "active" : "" ?>" href="javascript:void(0)"><i data-feather="settings"></i><span> Settings</span></a>
							<ul class="nav-submenu menu-content">
								<li><a class="<?php echo ($page == "settings") ? "active" : "" ?>" href="<?php echo base_url('admin/settings'); ?>"> Change Settings</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
			</div>
		</nav>

	</header>
	<!-- Page Sidebar Ends-->