<?php
$report_post = $this->admin_model->get_all_report_post();
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3>Reported Posts</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');  ?>">Home</a></li>
						<!-- <li class="breadcrumb-item">Pages </li>
                        <li class="breadcrumb-item">ECommerce</li> -->
						<li class="breadcrumb-item active">Reported Posts list</li>
					</ol>
				</div>
				<!-- <?php if (!empty($this->session->flashdata('success_msg'))) : ?>
					<div class="alert alert-success">
						<a href="javascript:void()" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<span> <?php echo $this->session->flashdata('success_msg'); ?> </span>
					</div>
				<?php endif ?>
				<?php if ($this->session->flashdata('error_msg')) : ?>
					<div class="alert alert-danger">
						<a href="javascript:void()" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<span><?php echo $this->session->flashdata('error_msg') ?></span>
					</div>
				<?php endif ?> -->
			</div>
		</div>
	</div>
	<!-- Container-fluid starts-->
	<div class="container-fluid list-products">
		<div class="row">
			<!-- Individual column searching (text inputs) Starts-->
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
						<div class="dt-ext table-responsive">
							<table class="display" id="new-cons">
								<thead>
									<tr>
										<th>Srno</th>
										<th>Report UserName</th>
										<th>Post</th>
										<th>Report Message </th>
										<th>PostUserName</th>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 0;
									foreach ($report_post as $post) : $no = $no + 1; ?>

										<tr>
											<td><?php echo $no; ?></td>
											<?php $user = $this->db->get_where('users', array('id' => $post['blockedByUserId']), 1)->row(); ?>
											<td><?php if (!empty($user)) {
													echo $user->username;
												} ?></td>
											<?php $posts = $this->db->get_where('posts', array('post_id' => $post['blockedPostsId']), 1)->row(); ?>
											<td>
												<?php

												if (!empty($posts->image)) {

													$url = explode(":", $posts->image);
													if ($url[0] == "https" || $url[0] == "http") { ?>
														<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo $posts->image ?>">
													<?php } else {
														$image_arr = explode("::::", $posts->image);
													?>

														<?php
														$image_arr = explode("::::", $posts->image);
														foreach ($image_arr as $image_name) { ?>
															<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url() . 'assets/images/post/' . $image_name ?>"><?php } ?>

													<?php }
												} else { ?>
													<?php if (!empty($posts->video_thumbnail)) { ?>
														<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url() . 'assets/images/post/video_thumbnail/' . $posts->video_thumbnail ?>">
													<?php } ?>

												<?php } ?>

											</td>
											<td><?php echo $post['report_text']; ?></td>
											<td>
												<?php
												if (!empty($posts)) {
													$username = $this->db->get_where('users', array('id' => $posts->user_id), 1)->row();

													if (!empty($username)) {
														echo $username->username;
													}
												}

												?>
											</td>
											<td> <?php $timestamp = $post['created_date'];
													$seconds = round($timestamp / 1000);
													echo date('m-d-Y', $seconds);
													?>
											</td>
											<td><a class="btn btn-danger me-3" href="<?php echo base_url('admin/delete-post/' . $post['blockedPostsId']); ?>" onclick="return confirm('Are you sure you want to delete this?')">Delete</a></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- Individual column searching (text inputs) Ends-->
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>
