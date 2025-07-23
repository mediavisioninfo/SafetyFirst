<?php
$users = $this->admin_model->get_all_users();
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3>Followers-Following</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');  ?>">Home</a></li>
						<!-- <li class="breadcrumb-item">Pages </li>
                        <li class="breadcrumb-item">ECommerce</li> -->
						<li class="breadcrumb-item active">Followers-Following list</li>
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
										<th>Image</th>
										<th>UserName</th>
										<!-- <th>Phone Number</th> -->
										<th>Followers</th>
										<th>Following</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 0;
									foreach ($users as $user) : $no = $no + 1; ?>
										<tr>
											<td><?php echo $no; ?></td>
											<td>
												<?php
												// echo $row->id;
												if (empty($user['profile_pic'])) { ?>
													<img class="img-fluid rounded-circle" style="height:60px;width: 60px;" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
													<?php } else {
													$profile = explode(":", $user['profile_pic']);
													if ($profile[0] == "https" || $profile[0] == "http") { ?>
														<img class="img-fluid rounded-circle" style="height:60px;width: 60px;" src="<?php echo $user['profile_pic'] ?>" alt="User Image">
													<?php } else { ?>
														<img class="img-fluid rounded-circle" style="height:60px;width: 60px;" src="<?php echo base_url('assets/images/user/') . $user['profile_pic'] ?>" alt="User Image"> <?php } ?>
												<?php } ?>
											</td>
											<td><?php echo $user['username']; ?></td>
											<!-- <td><?php echo $user['phone']; ?></td> -->
											<td><?php
												// $followers = $this->db->get_where('follow', array('to_user' => $user['id']))->num_rows(); 
												$followers = $this->db->get_where('follow', array('to_user' => $user['id'], 'from_user !=' => $user['id']))->num_rows();
												echo $followers; ?>
											</td>
											<td><?php
												// $following = $this->db->get_where('follow', array('from_user' => $user['id']))->num_rows(); 
												$following = $this->db->get_where('follow', array('from_user' => $user['id'], 'to_user !=' => $user['id']))->num_rows();
												echo $following; ?>
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
		$('#trendingpost').DataTable({
			responsive: true
		});
	});
</script>
