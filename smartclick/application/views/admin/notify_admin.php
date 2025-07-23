<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3>View Notifications</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');  ?>">Home</a></li>
						<!-- <li class="breadcrumb-item">Pages </li>
                        <li class="breadcrumb-item">ECommerce</li> -->
						<li class="breadcrumb-item active">Notifications list</li>
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
										<th>user Name </th>
										<th>Notifications Title</th>
										<th> Message</th>
										<th>Date/Time</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									foreach ($adminnotify as $row) { ?>
										<tr>
											<td><?php echo $no; ?></td>
											<?php $qry = $this->db->get_where("users", array('id' => $row['user_id']))->row(); ?>
											<td><?php echo $qry->username; ?></td>
											<td><?php echo $row['title']; ?></td>
											<td><?php echo $row['message']; ?></td>
											<td><?php echo $row['date']; ?></td>
											<td><a href="<?php echo base_url('admin/delete-notification/' . $row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?')">Delete</a></td>
										</tr>

									<?php $no++;
									} ?>
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
