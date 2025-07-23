<?php
$id = $this->uri->segment(3);
$get_comment = $this->admin_model->get_comment_by_id($id);
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h3>Comment</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url() . 'index.php/admin_home' ?>">Home</a></li>
						<li class="breadcrumb-item">Edit Comment</li>
					</ol>
				</div>
				<div class="col-sm-6">
					<!-- Bookmark Start-->
					<div class="bookmark">
					</div>
					<!-- Bookmark Ends-->
				</div>
			</div>
		</div>
		<!-- Container-fluid starts-->
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Comment Update
							<div class="pull-right">
								<a href="<?= base_url('admin/comment') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
							</div>
						</div>
						<div class="panel-body">
							<div class="card">
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
								<div class="card-body">
									<form method="POST" enctype="multipart/form-data" action="<?php echo base_url('admin/update-comment'); ?>">
										<div class="box-body">
											<input type="hidden" name="id" value="<?php echo $id; ?>">
											<div class="form-group">
												<label for="exampleInputfnm">Comment</label>
												<textarea class="form-control" name="text" placeholder="Enter Comment"><?php echo $get_comment->text ?></textarea>
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
			</div>
		</div>
		<!-- Container-fluid Ends-->
	</div>
</div>
<div id="wrapper">
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Comment</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Comment Update
							<div class="pull-right">
								<a href="<?= base_url('admin/comment') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
							</div>
						</div>
						<div class="panel-body">
							<div class="card">
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
								<div class="card-body">
									<form method="POST" enctype="multipart/form-data" action="<?php echo base_url('admin/update-comment'); ?>">
										<div class="box-body">
											<input type="hidden" name="id" value="<?php echo $id; ?>">
											<div class="form-group">
												<label for="exampleInputfnm">Comment</label>
												<textarea class="form-control" name="text" placeholder="Enter Comment"><?php echo $get_comment->text ?></textarea>
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
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
	</div>
