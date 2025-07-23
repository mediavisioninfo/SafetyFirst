<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h3>Reported Users</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.html">Home</a></li>
						<li class="breadcrumb-item">Reported Users list</li>
					</ol>
				</div>
				<div class="col-sm-6">
					<!-- Bookmark Start-->
					<!-- <div class="bookmark">
						<ul>
							<li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Tables"><i data-feather="inbox"></i></a></li>
							<li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Chat"><i data-feather="message-square"></i></a></li>
							<li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Icons"><i data-feather="command"></i></a></li>
							<li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Learning"><i data-feather="layers"></i></a></li>
							<li><a href="javascript:void(0)"><i class="bookmark-search" data-feather="star"></i></a>
								<form class="form-inline search-form">
									<div class="form-group form-control-search">
										<input type="text" placeholder="Search..">
									</div>
								</form>
							</li>
						</ul>
					</div> -->
					<!-- Bookmark Ends-->
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<!-- <div class="card-header">
						<h5>Custom Button</h5><span>Fundamentally, each button is described by an object - this object is read by Buttons and displayed as appropriate. There are a number of parameters that Buttons will automatically look for in the button description object such as buttons.buttons.text and buttons.buttons.action which are the two fundamental parameters (button text and the action to take when activated).</span>
					</div> -->
					<div class="card-body">
						<div class="dt-ext table-responsive">
							<table class="display" id="new-cons">
								<!--custom-button-->
								<thead>
									<th>Srno</th>
									<th>Repoted User</th>
									<th>Report Date</th>
									<th>Report By User</th>
									<th>Reason</th>
									<th>Action</th>
								</thead>
								<tbody>
									<?php $no = 1;
									foreach ($report_user as $post) {
										$r_user = $this->db->get_where('users', array('id' => $post['reportedUserId']), 1)->row();
										$by_user = $this->db->get_where('users', array('id' => $post['reportByUserId']), 1)->row(); ?>
										<tr>
											<td><?php echo $no; ?></td>
											<td>
												<div class="media">
													<?php
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
														<a href=""><span>
																<?php if (!empty($r_user)) {
																	echo $r_user->username;
																} ?>
															</span></a>
													</div>
												</div>
											</td>
											<td>
												<p><?php $timestamp = $post['created_date'];
													$seconds = round($timestamp / 1000);
													echo date('d/m/Y', $seconds); ?></p>
											</td>
											<td>
												<div class="media">
													<?php
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
														<a href="product-page.html"><span>
																<?php if (!empty($by_user)) {
																	echo $by_user->username;
																} ?>
															</span></a>
													</div>
												</div>
											</td>
											<td>
												<p><?php echo $post['report_text']; ?></p>
											</td>
											<td>
												<a href="<?php echo base_url('admin/delete-user/' . $r_user->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
											</td>
										</tr>
									<?php $no++;
									}; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>