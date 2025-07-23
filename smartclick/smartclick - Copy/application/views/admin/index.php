<?php
$report_post = $this->admin_model->get_all_details($tablename = 'posts_report');
$report_user = $this->admin_model->get_all_details($tablename = 'users_report');

// $name = "primocys1";
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<!--<h3>General</h3>
           			<ol class="breadcrumb">
           			 <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            		<li class="breadcrumb-item">Widgets</li>
           			<li class="breadcrumb-item active">General</li>
          			</ol> -->
				</div>
				<div class="col-sm-6">
					<!-- Bookmark Start-->
					<div class="bookmark">
						<ul>
							<!-- <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Tables"><i data-feather="inbox"></i></a></li>
              				<li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Chat"><i data-feather="message-square"></i></a></li>
              				<li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Icons"><i data-feather="command"></i></a></li>
             				<li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Learning"><i data-feather="layers"></i></a></li>
              				<li><a href="javascript:void(0)"><i class="bookmark-search" data-feather="star"></i></a>
                			<form class="form-inline search-form">
                  				<div class="form-group form-control-search">
                  			 	 <input type="text" placeholder="Search<?php echo base_url() ?>">
                 				</div>
                			</form>
              				</li> -->
						</ul>
					</div>
					<!-- Bookmark Ends-->
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid starts-->
	<div class="container-fluid general-widget">
		<div class="row">
			<div class="col-sm-6 col-xl-3 col-lg-6">
				<div class="card o-hidden border-0">
					<div class="b-r-4 card-body" style="background-color: #E6A9EC; color:#ffffff">
						<div class="media static-top-widget">
							<div class="align-self-center text-center"><i data-feather="user-plus"></i></div>
							<div class="media-body"><span class="m-0">Total Users</span>
								<h4 class="mb-0 counter"><?php echo $this->admin_model->get_total_users(); ?></h4><i class="icon-bg" data-feather="user-plus"></i>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="col-sm-6 col-xl-3 col-lg-6">
				<div class="card o-hidden border-0">
					<div class="b-r-4 card-body" style="background-color: #736AFF; color:#ffffff">
						<div class="media static-top-widget">
							<div class="align-self-center text-center"><i data-feather="send"></i></div>
							<div class="media-body"><span class="m-0">Total Post</span>
								<h4 class="mb-0 counter"><?php echo $this->admin_model->get_total_posts(); ?></h4><i class="icon-bg" data-feather="send"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xl-3 col-lg-6">
				<div class="card o-hidden border-0">
					<div class=" b-r-4 card-body" style="background-color: #F535AA; color:#ffffff">
						<div class="media static-top-widget">
							<div class="align-self-center text-center"><i data-feather="message-circle"></i></div>
							<div class="media-body"><span class="m-0">Trending Posts</span>
								<h4 class="mb-0 counter"><?php echo $this->admin_model->count_trending_post(); ?></h4><i class="icon-bg" data-feather="message-circle"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xl-3 col-lg-6">
				<div class="card o-hidden border-0">
					<div class="b-r-4 card-body" style="background-color: #E6A9EC; color:#ffffff">
						<div class="media static-top-widget">
							<div class="align-self-center text-center"><i data-feather="user-plus"></i></div>
							<div class="media-body"><span class="m-0">Total Comments</span>
								<h4 class="mb-0 counter"><?php echo $this->admin_model->get_total_comment(); ?></h4><i class="icon-bg" data-feather="user-plus"></i>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- Top user with slider -->
			<div class="col-xl-7 box-col-12 des-xl-100 top-dealer-sec">
				<div class="card">
					<div class="card-header pb-0">
						<div class="header-top d-sm-flex justify-content-between align-items-center">
							<div class="center-content">
								<h5>Top USer</h5>
								<!-- <p class="d-sm-flex align-items-center"><span class="m-r-10">845 Dealer</span><i class="toprightarrow-primary fa fa-arrow-up m-r-10"></i>86% More Than Last Year</p> -->
							</div>
							<div class="setting-list">
								<ul class="list-unstyled setting-option">
									<li>
										<div class="setting-primary"><i class="icon-settings"></i></div>
									</li>
									<li><i class="icofont icofont-maximize full-card font-primary"></i></li>
									<li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
									<li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
									<li><i class="icofont icofont-error close-card font-primary"> </i></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="owl-carousel owl-theme" id="owl-carousel-14">

							<div class="item">
								<div class="row">
									<div class="col-12">

										<div class="owl-carousel-16 owl-carousel owl-theme">
											<?php
											// $trending_post = $this->admin_model->get_trending_post();
											$query = $this->db->query("SELECT * FROM users ORDER BY id DESC LIMIT 4");
											$users = $query->result();
											$no = 1;
											foreach ($users as $row) {
											?>
												<div class="item">
													<div class="card">
														<div class="top-dealerbox text-center">
															<?php
															// echo $row->id;
															if (empty($row->profile_pic)) { ?>
																<img class="card-img-top" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
																<?php } else {
																$profile = explode(":", $row->profile_pic);
																if ($profile[0] == "https" || $profile[0] == "http") { ?>
																	<img class="card-img-top" src="<?php echo $row->profile_pic ?>" alt="User Image">
																<?php } else { ?>
																	<img class="card-img-top" src="<?php echo base_url('assets/images/user/') . $row->profile_pic ?>" alt="User Image"> <?php } ?>
															<?php } ?>
															<h6><?php echo $row->username; ?></h6>
															<p></p><a class="btn btn-rounded" href="<?php echo base_url('admin/user'); ?>">View More</a>
														</div>
													</div>
												</div>
											<?php } ?>
										</div>

									</div>
									<div class="col-12">
										<div class="owl-carousel-16 owl-carousel owl-theme">
											<?php
											// $trending_post = $this->admin_model->get_trending_post();
											$query = $this->db->query("SELECT * FROM users ORDER BY id DESC LIMIT 4,4");
											$users = $query->result();
											$no = 1;
											foreach ($users as $row) {
											?>
												<div class="item">
													<div class="card">
														<div class="top-dealerbox text-center">
															<?php
															// echo $row->id;
															if (empty($row->profile_pic)) { ?>
																<img class="card-img-top" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
																<?php } else {
																$profile = explode(":", $row->profile_pic);
																if ($profile[0] == "https" || $profile[0] == "http") { ?>
																	<img class="card-img-top" src="<?php echo $row->profile_pic ?>" alt="User Image">
																<?php } else { ?>
																	<img class="card-img-top" src="<?php echo base_url('assets/images/user/') . $row->profile_pic ?>" alt="User Image"> <?php } ?>
															<?php } ?>
															<h6><?php echo $row->username; ?></h6>
															<p></p><a class="btn btn-rounded" href="<?php echo base_url('admin/user'); ?>">View More</a>
														</div>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="row">
									<div class="col-12">

										<div class="owl-carousel-16 owl-carousel owl-theme">
											<?php
											// $trending_post = $this->admin_model->get_trending_post();
											$query = $this->db->query("SELECT * FROM users ORDER BY id DESC LIMIT 8,4");
											$users = $query->result();
											$no = 1;
											foreach ($users as $row) {
											?>
												<div class="item">
													<div class="card">
														<div class="top-dealerbox text-center">
															<?php
															// echo $row->id;
															if (empty($row->profile_pic)) { ?>
																<img class="card-img-top" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
																<?php } else {
																$profile = explode(":", $row->profile_pic);
																if ($profile[0] == "https" || $profile[0] == "http") { ?>
																	<img class="card-img-top" src="<?php echo $row->profile_pic ?>" alt="User Image">
																<?php } else { ?>
																	<img class="card-img-top" src="<?php echo base_url('assets/images/user/') . $row->profile_pic ?>" alt="User Image"> <?php } ?>
															<?php } ?>
															<h6><?php echo $row->username; ?></h6>
															<p></p><a class="btn btn-rounded" href="<?php echo base_url('admin/user'); ?>">View More</a>
														</div>
													</div>
												</div>
											<?php } ?>
										</div>

									</div>
									<div class="col-12">
										<div class="owl-carousel-16 owl-carousel owl-theme">
											<?php
											// $trending_post = $this->admin_model->get_trending_post();
											$query = $this->db->query("SELECT * FROM users ORDER BY id DESC LIMIT 12,4");
											$users = $query->result();
											$no = 1;
											foreach ($users as $row) {
											?>
												<div class="item">
													<div class="card">
														<div class="top-dealerbox text-center">
															<?php
															// echo $row->id;
															if (empty($row->profile_pic)) { ?>
																<img class="card-img-top" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
																<?php } else {
																$profile = explode(":", $row->profile_pic);
																if ($profile[0] == "https" || $profile[0] == "http") { ?>
																	<img class="card-img-top" src="<?php echo $row->profile_pic ?>" alt="User Image">
																<?php } else { ?>
																	<img class="card-img-top" src="<?php echo base_url('assets/images/user/') . $row->profile_pic ?>" alt="User Image"> <?php } ?>
															<?php } ?>
															<h6><?php echo $row->username; ?></h6>
															<p></p><a class="btn btn-rounded" href="<?php echo base_url('admin/user'); ?>">View More</a>
														</div>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Chart user join monthaly chart -->

			<div class="col-xl-5 box-col-12 des-xl-100">
				<div class="card">
					<div class="card-header">
						<div class="header-top d-sm-flex align-items-center">

							<div class="center-content">
								<!-- <p>Yearly User 24.65k</p> -->
								<h5>User Join In Months</h5>
							</div>
							<div class="setting-list">
								<ul class="list-unstyled setting-option">
									<li>
										<div class="setting-primary"><i class="icon-settings"></i></div>
									</li>
									<li><i class="icofont icofont-maximize full-card font-primary"></i></li>
									<li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
									<li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
									<li><i class="icofont icofont-error close-card font-primary"></i></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="card-body p-0">
						<div id="user-activation-dash-2"></div>
						<!-- <div class="code-box-copy">
									<button class="code-box-copy__btn btn-clipboard" data-clipboard-target="#user-activations" title="Copy"><i class="icofont icofont-copy-alt"></i></button>
								</div> -->
					</div>
				</div>
			</div>
			<!-- tables list -->
			<div class="container-fluid product-wrapper">
				<div class="product-grid">
					<div class="product-wrapper-grid">
						<div class="row">
							<!-- Recent Post reports -->
							<div class="col-xl-12 col-sm-6 xl-4">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
											<h5>Recent Post Reports</h5>
											<div class="setting-list">
												<ul class="list-unstyled setting-option">
													<li>
														<div class="setting-primary"><i class="icon-settings"></i></div>
													</li>
													<li><i class="icofont icofont-maximize full-card font-primary"></i></li>
													<li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
													<li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
													<li><i class="icofont icofont-error close-card font-primary"></i></li>
												</ul>
											</div>
											<table class="table table-bordernone">
												<thead>
													<tr>
														<th>Post By User</th>
														<th>Post</th>
														<th>Date</th>
														<th>Report By User</th>
														<th>Report Message</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php $no = 1;
													foreach ($report_post as $ruser) {
														if ($no !== 6) {
															$r_user = $this->db->get_where('users', array('id' => $ruser['blockedByUserId']), 1)->row();
															$post_dtl = $this->db->get_where('posts', array('post_id' => $ruser['blockedPostsId']), 1)->row(); ?>
															<tr>
																<td>
																	<?php if (!empty($post_dtl)) {
																		$username = $this->db->get_where('users', array('id' => $post_dtl->user_id), 1)->row();
																		if (!empty($username)) {
																			echo $username->username;
																		}
																	} ?>
																	<?php // echo $post_dtl->post_id; 
																	?>
																</td>
																<td>
																	<div class="media">
																		<?php
																		if (!empty($post_dtl->image)) {
																			$url = explode(":", $post_dtl->image);
																			if ($url[0] == "https" || $url[0] == "http") { ?>
																				<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo $post_dtl->image ?>" alt="" data-original-title="" title="">
																			<?php } else {
																				$image_arr = explode("::::", $post_dtl->image);
																			?>
																				<?php
																				$image_arr = explode("::::", $post_dtl->image);
																				foreach ($image_arr as $image_name) { ?>
																					<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url() . 'assets/images/post/' . $image_name ?>" alt="" data-original-title="" title="">
																				<?php } ?>
																			<?php }
																		} else { ?>
																			<?php if (!empty($post_dtl->video_thumbnail)) { ?>
																				<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url() . 'assets/images/post/video_thumbnail/' . $post_dtl->video_thumbnail ?>" alt="" data-original-title="" title="">

																				<!-- <img style="height:65px;width: 65px;" src="<?php echo base_url() . 'assets/images/post/video_thumbnail/' . $post_dtl->video_thumbnail ?>" class="img-reponsive"> -->
																			<?php } ?>

																		<?php } ?>
																	</div>
																</td>
																<td>
																	<p><?php $timestamp = $ruser['created_date'];
																		$seconds = round(microtime($timestamp) / 1000);
																		echo date('d/m/Y', $seconds);
																		?></p>
																</td>
																<td>
																	<p><?php if (!empty($r_user)) {
																			echo $r_user->username;
																		} ?></p>
																</td>

																<td>
																	<p><?php if (!empty($ruser['report_text'])) {
																			echo $ruser['report_text'];
																		}else{
																			echo "Not provided..!";
																		} ?></p>
																</td>
																<td>
																	<p><?php if (!empty($r_user)) {
																			echo $ruser['status'];
																		} ?></p>
																</td>
																<td style="display: inline-flex;">
																	<a class="btn btn-primary me-3" style="margin-right:5px;" data-bs-toggle="modal" data-bs-target="#exampleModalCenter<?php echo $ruser['id']; ?>">See</a>
																</td>
															</tr>
													<?php $no++;
														}
													}; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="product-box">
										<?php $no = 1;
										foreach ($report_post as $ruser) {
											if ($no !== 6) {
												$r_user = $this->db->get_where('users', array('id' => $ruser['blockedByUserId']), 1)->row();
												$post_dtl = $this->db->get_where('posts', array('post_id' => $ruser['blockedPostsId']), 1)->row(); ?>
												<div class="modal fade" id="exampleModalCenter<?php echo $ruser['id']; ?>">
													<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<div class="product-box row">
																	<div class="product-img col-lg-6">
																		<?php
																		if (!empty($post_dtl->image)) {
																			$url = explode(":", $post_dtl->image);
																			if ($url[0] == "https" || $url[0] == "http") { ?>
																				<img class="img-fluid " style="height:500px;width: 500px;" src="<?php echo $post_dtl->image ?>" alt="" data-original-title="" title="">

																			<?php } else {
																				$image_arr = explode("::::", $post_dtl->image);
																			?>
																				<?php
																				$image_arr = explode("::::", $post_dtl->image);
																				foreach ($image_arr as $image_name) { ?>
																					<img class="img-fluid " style="height:500px;width: 500px;" src="<?php echo base_url() . 'assets/images/post/' . $image_name ?>" alt="" data-original-title="" title="">
																				<?php } ?>
																			<?php }
																		} else { ?>
																			<?php if (!empty($post_dtl->video_thumbnail)) { ?>
																				<img class="img-fluid " style="height:500px;width: 500px;" src="<?php echo base_url() . 'assets/images/post/video_thumbnail/' . $post_dtl->video_thumbnail ?>" alt="" data-original-title="" title="">
																			<?php } ?>

																		<?php } ?>
																	</div>
																	<div class="product-details col-lg-6 text-start">
																		<h4>Post By user:</h4>
																		<div class="product-price">
																			<?php if (!empty($post_dtl)) {
																				$username = $this->db->get_where('users', array('id' => $post_dtl->user_id), 1)->row();
																				if (!empty($username)) {
																					echo $username->username;
																				}
																			} ?>
																		</div>
																		<br>
																		<h4>
																			Date : <?php $timestamp = $ruser['created_date'];
																					$seconds = round($timestamp / 1000);
																					echo date('d/m/Y', $seconds); ?>
																		</h4><br>
																		<h4>Report By User:</h4>
																		<div class="product-price">
																			<?php if (!empty($r_user)) {
																				echo $r_user->username;
																			} ?>
																		</div>
																		<div class="product-view">
																			<h6 class="f-w-600">Report Message :</h6>
																			<p class="mb-0"><?php if (!empty($r_user)) {
																								echo $ruser['report_text'];
																							} ?></p>
																		</div>
																		<div class="product-size">
																		</div>
																		<?php //echo $post_dtl->post_id; 
																		?>
																		<div class="product-qnty">
																			<div class="addcart-btn">
																				<a class="btn btn-danger me-3" href="<?php echo base_url('admin/delete-post/' . $post_dtl->post_id); ?>" onclick="return confirm('Are you sure you want to delete this?')">Delete</a><a class="btn btn-primary " data-bs-dismiss="modal">Cancel</a>
																			</div>
																		</div>
																	</div>
																</div>
																<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
															</div>
														</div>
													</div>
												</div>
										<?php $no++;
											}
										}; ?>
									</div>
								</div>
							</div>
							<!-- Recent User reports -->
							<div class="col-xl-12 col-sm-6 xl-4">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
											<h5>Recent User Reports</h5>
											<div class="setting-list">
												<ul class="list-unstyled setting-option">
													<li>
														<div class="setting-primary"><i class="icon-settings"></i></div>
													</li>
													<li><i class="icofont icofont-maximize full-card font-primary"></i></li>
													<li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
													<li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
													<li><i class="icofont icofont-error close-card font-primary"></i></li>
												</ul>
											</div>

											<table class="table table-bordernone">
												<thead>
													<tr>
														<th>Repoted User</th>
														<th>Report Date</th>
														<th>Report By User</th>
														<th>Reason</th>
														<th>Action</th>
														<th>
															<div class="setting-list">
																<ul class="list-unstyled setting-option">
																	<li>
																		<div class="setting-primary"><i class="icon-settings"> </i></div>
																	</li>
																	<li><i class="icofont icofont-maximize full-card font-primary"></i></li>
																	<li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
																	<li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
																	<li><i class="icofont icofont-error close-card font-primary"></i></li>
																</ul>
															</div>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php $no = 1;
													foreach ($report_user as $post) {;
														if ($no !== 6) {
															$r_user = $this->db->get_where('users', array('id' => $post['reportedUserId']), 1)->row();
															$by_user = $this->db->get_where('users', array('id' => $post['reportByUserId']), 1)->row(); ?>
															<tr>
																<td>
																	<div class="media">
																		<?php
																		// echo $row->id;
																		if (empty($r_user->profile_pic)) { ?>
																			<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
																			<?php } else {
																			$profile = explode(":", $r_user->profile_pic);
																			if ($profile[0] == "https" || $profile[0] == "http") { ?>
																				<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo $r_user->profile_pic ?>" alt="User Image">
																			<?php } else { ?>
																				<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/user/') . $r_user->profile_pic ?>" alt="User Image"> <?php } ?>
																		<?php } ?>
																		<div class="media-body">
																			<span>
																				<?php if (!empty($r_user)) {
																					echo $r_user->username;
																				} ?>
																			</span>
																		</div>
																	</div>
																</td>
																<td>
																	<p><?php $timestamp = $post['created_date'];
																		$seconds = round($timestamp / 1000);
																		//echo $seconds;
																		echo date('d-m-Y', $seconds); ?></p>
																</td>
																<td>
																	<div class="media">
																		<?php
																		// echo $row->id;
																		if (empty($by_user->profile_pic)) { ?>
																			<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
																			<?php } else {
																			$profile = explode(":", $by_user->profile_pic);
																			if ($profile[0] == "https" || $profile[0] == "http") { ?>
																				<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo $by_user->profile_pic ?>" alt="User Image">
																			<?php } else { ?>
																				<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/user/') . $by_user->profile_pic ?>" alt="User Image"> <?php } ?>
																		<?php } ?>
																		<div class="media-body">
																			<span>
																				<?php if (!empty($by_user)) {
																					echo $by_user->username;
																				} ?>
																			</span>
																		</div>
																	</div>
																</td>
																<td>
																	<p><?php echo $post['report_text']; ?></p>
																</td>
																<td>
																	<p><?php echo $post['status']; ?></p>
																</td>
																<td style="display: inline-flex;">
																	<a href="<?php echo base_url('admin/delete-user/' . $r_user->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
																</td>
															</tr>
													<?php $no++;
														}
													}; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!-- New Post list -->
							<div class="col-xl-12 col-sm-6 xl-4">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
											<h5>Latest post</h5>
											<div class="setting-list">
												<ul class="list-unstyled setting-option">
													<li>
														<div class="setting-primary"><i class="icon-settings"></i></div>
													</li>
													<li><i class="icofont icofont-maximize full-card font-primary"></i></li>
													<li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
													<li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
													<li><i class="icofont icofont-error close-card font-primary"></i></li>
												</ul>
											</div>
											<?php
											// $trending_post = $this->admin_model->get_trending_post();
											$query = $this->db->query("SELECT * FROM posts ORDER BY post_id DESC LIMIT 10");
											$posts = $query->result();

											?>

											<table class="table table-bordernone">
												<thead>
													<tr>
														<!-- <th scope="col">Srno</th> -->
														<th scope="col">UserName</th>
														<th scope="col">Post</th>
														<th scope="col">Caption</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$no = 1;
													foreach ($posts as $post) {
														if ($no !== 6) { ?>
															<?php $user = $this->db->get_where('users', array('id' => $post->user_id), 1)->row(); ?>
															<td><?php if (!empty($user)) {
																	echo $user->username;
																} ?></td>
															<td>
																<?php
																// echo $row->id;
																if (empty($post->image)) { ?>
																	<img style="height:65px;width: 65px;" src=" <?php echo base_url() . 'assets/images/post/video_thumbnail/' . $post->video_thumbnail ?>" class="img-fluid rounded-circle">
																	<?php } else {
																	$profile = explode(":", $post->image);
																	if ($profile[0] == "https" || $profile[0] == "http") { ?>
																		<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo $post->image ?>" alt="User Image">
																	<?php } else {
																		$image_arr = explode("::::", $post->image);
																	?>
																		<img style="height:65px;width: 65px;" src="<?php echo base_url() . 'assets/images/post/' . $image_arr[0] ?>" class="img-fluid rounded-circle">
																	<?php } ?>
																<?php } ?>
															</td>
															<td><?php if(!empty($post->text)){
																	echo $post->text;
																	}else{
																	echo "No captions..!";
																	} ?></td>
															<td style="display: inline-flex;">
																<a class="btn btn-primary me-3" style="margin-right:5px;" href="<?php echo base_url('admin/all-post') ?>">See</a>
															</td>
															</tr>
													<?php $no++;
														}
													} ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- New user list -->
			<!-- <div class="col-xl-6 box-col-6">
				<div class="card">
					<div class="card-header pb-0 d-flex justify-content-between align-items-center">
						<h5>New User</h5>
						<div class="setting-list">
							<ul class="list-unstyled setting-option">
								<li>
									<div class="setting-primary"><i class="icon-settings"></i></div>
								</li>
								<li><i class="icofont icofont-maximize full-card font-primary"></i></li>
								<li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
								<li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
							</ul>
						</div>
					</div>
					<?php
					// $trending_post = $this->admin_model->get_trending_post();
					$query = $this->db->query("SELECT * FROM users ORDER BY id DESC LIMIT 5");
					$users = $query->result();
					?>
					<div class="card-body">
						<div class="user-status table-responsive">
							<table class="table table-bordernone">
								<thead>
									<tr>
										<th scope="col">UserName</th>
										<th scope="col">Post</th>
										<th scope="col">Email</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									foreach ($users as $user) {
										if ($no !== 6) { ?>

											<tr>
												<td><?php echo $user->username; ?></td>

												<td>
													<?php
													// echo $row->id;
													if (empty($user->profile_pic)) { ?>
														<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
														<?php } else {
														$profile = explode(":", $user->profile_pic);
														if ($profile[0] == "https" || $profile[0] == "http") { ?>
															<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo $user->profile_pic ?>" alt="User Image">
														<?php } else { ?>
															<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/user/') . $user->profile_pic ?>" alt="User Image"> <?php } ?>
													<?php } ?>
												</td>
												<td><?php echo $user->email; ?></td>
											</tr>
									<?php $no++;
										}
									}; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div> -->
		</div>
		<!-- Container-fluid Ends-->
	</div>
</div>
