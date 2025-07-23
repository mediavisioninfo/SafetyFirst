<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h3>Settings</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url() . 'index.php/admin_home' ?>">Home</a></li>
						<li class="breadcrumb-item">Chenge Settings</li>
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
					<div class="card">
						<div class="card-header pb-0">
							<h5>Setting Details</h5>
							<!-- <div class="pull-right">
								<a href="<?= base_url('admin/user') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
							</div> -->
						</div>
						<div class="card-body add-post">
							<form method="POST" id="" class="row needs-validation" novalidate="" enctype="multipart/form-data" action="<?php echo base_url('admin/chenge-settings'); ?>">
								<div class="box-body col-sm-6">
									<input type="hidden" name="id" value="<?php if (!empty($setting_dtl->id)) {
																				echo $setting_dtl->id;
																			} ?>">
									<div class="form-group">
										<label for="exampleInputfnm">Notification key</label>
										<input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter Notification key" name="noti_key" value="<?php if (!empty($setting_dtl->notify_key)) {
																																										echo $setting_dtl->notify_key;
																																									}   ?>" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="exampleInputfnm">Privacy Policy URL</label>
										<input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter Privacy Policy URL" name="pnp" value="<?php if (!empty($setting_dtl->prv_pol_url)) {
																																									echo $setting_dtl->prv_pol_url;
																																								} ?>" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="exampleInputfnm">Term Condition URL</label>
										<input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter Term Condition URL" name="tnc" value="<?php if (!empty($setting_dtl->tnc_url)) {
																																									echo $setting_dtl->tnc_url;
																																								}  ?>" autocomplete="off">
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
