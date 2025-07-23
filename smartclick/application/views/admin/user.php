<?php
$users = $this->admin_model->get_all_users();
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3>Users list</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');  ?>">Home</a></li>
						<!-- <li class="breadcrumb-item">Pages </li>
                        <li class="breadcrumb-item">ECommerce</li> -->
						<li class="breadcrumb-item active">Users list</li>
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
										<th>Sr no</th>
										<th>User Name</th>
										<th>Login with</th>
										<th>Email</th>
										<th>Image</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 0;
									foreach ($users as $user) : $no = $no + 1; ?>
										<tr>
											<td><?php echo $no; ?></td>
											<td><?php echo $user['username']; ?></td>
											<td><?php if(!empty($user['login_type'])){
													echo $user['login_type'];
											}else{
													echo "Not provided ..!";
											} ?></td>
											<td><?php echo $user['email']; ?></td>

											<td><?php
												// echo $row->id;
												if (empty($user['profile_pic'])) { ?>
													<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
													<?php } else {
													$profile = explode(":", $user['profile_pic']);
													if ($profile[0] == "https" || $profile[0] == "http") { ?>
														<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo $user['profile_pic'] ?>" alt="User Image">
													<?php } else { ?>
														<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/user/') . $user['profile_pic'] ?>" alt="User Image"> <?php } ?>
												<?php } ?>
											</td>
											<td style="display: inline-flex;">
												<a href="<?php echo base_url('admin/user-edit/' . $user['id']); ?>" class="btn btn-success btn-sm" style="margin-right:5px;">Edit</a>

												<a href="<?php echo base_url('admin/delete-user/' . $user['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
											</td>

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
<script src="<?php echo base_url(); ?>bower_components/js/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$('#userstables').DataTable({
			responsive: true
		});
	});
</script>
