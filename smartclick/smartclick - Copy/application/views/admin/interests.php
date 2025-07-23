<?php
$interests = $this->admin_model->get_all_interests();
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3>Interests</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');  ?>">Home</a></li>
						<!-- <li class="breadcrumb-item">Pages </li>
                        <li class="breadcrumb-item">ECommerce</li> -->
						<li class="breadcrumb-item active">Interests list</li>
					</ol>
					<!-- <div class="pull-right">
						<a href="<?= base_url('admin/dashboard') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
					</div> -->
					<div class="pull-right" style="margin-right:5px;">
						<a href="<?= base_url('admin/create-interest') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-plus"></i> Add Interests</a>
					</div>
				</div>
				<?php if (!empty($this->session->flashdata('success_msg'))) : ?>
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
				<?php endif ?>
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
										<th>Type</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 0;
									foreach ($interests as $list) : $no = $no + 1; ?>
										<tr>
											<td><?php echo $no; ?></td>
											<td><?php echo $list['type']; ?></td>
											<td style="display: inline-flex;">
												<a href="<?php echo base_url('admin/interest-edit/' . $list['id']); ?>" class="btn btn-success btn-sm" style="margin-right:5px;"><i class="fa fa-edit"></i></a>
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
<div id="wrapper">
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Interests</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<?php if (!empty($this->session->flashdata('success_msg'))) : ?>
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
						<?php endif ?>
						<div class="panel-heading">
							Interests
							<div class="pull-right">
								<a href="<?= base_url('admin/dashboard') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
							</div>
							<div class="pull-right" style="margin-right:5px;">
								<a href="<?= base_url('admin/create-interest') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-plus"></i> Add Interests</a>
							</div>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="interests">
									<thead>
										<tr>
											<th>Sr no</th>
											<th>Type</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$no = 0;
										foreach ($interests as $list) : $no = $no + 1; ?>
											<tr>
												<td><?php echo $no; ?></td>
												<td><?php echo $list['type']; ?></td>

												<td style="display: inline-flex;">
													<a href="<?php echo base_url('admin/interest-edit/' . $list['id']); ?>" class="btn btn-success btn-sm" style="margin-right:5px;"><i class="fa fa-edit"></i></a>
												</td>

											</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
				</div>
				<!-- /.col-lg-12 -->
			</div>

		</div>
		<!-- /.container-fluid -->
	</div>
</div>
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<script src="<?php echo base_url(); ?>bower_components/js/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$('#interests').DataTable({
			responsive: true
		});
	});
</script>
