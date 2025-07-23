<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h3>Interest</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url() . 'index.php/admin_home' ?>">Home</a></li>
						<li class="breadcrumb-item">Interest</li>
						<li class="breadcrumb-item active">Add Interest</li>
					</ol>
				</div>
			</div>
		</div>
		<!-- Container-fluid starts-->
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header pb-0">
							<h5>New Main Topic</h5>
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
						<div class="card-body add-post">
							<form method="POST" enctype="multipart/form-data" action="<?php echo base_url('admin/add-interest'); ?>">
								<div class="box-body">

									<div class="form-group">
										<label for="exampleInputfnm">Interest Type</label>
										<input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter Interest Type" name="type">
										<?php echo form_error('text'); ?>
									</div>

								</div>

								<div class="box-footer">
									<button type="submit" name="submit" class="btn btn-primary">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Container-fluid Ends-->
	</div>
</div>
