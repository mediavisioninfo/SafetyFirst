<?php
$id = $this->uri->segment(3);
$users = $this->admin_model->get_user_by_id($id);
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h3>Profile</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url() . 'index.php/admin_home' ?>">Home</a></li>
						<li class="breadcrumb-item">Update Profile</li>
					</ol>
				</div>
				<div class="col-sm-6">
					<!-- Bookmark Start-->
					<div class="bookmark">
						<!-- <ul>
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
                        </ul> -->
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
							<h5>Profile Update</h5>
							<div class="pull-right">
								<a href="<?= base_url('admin/user') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
							</div>
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
						<div class="card-body add-post">
							<form method="POST" id="category_form" class="row needs-validation" novalidate="" enctype="multipart/form-data" action="<?php echo base_url('admin/update-user'); ?>">
								<div class="box-body">
									<input type="hidden" name="id" value="<?php echo $id; ?>">
									<div class="form-group">
										<label for="exampleInputfnm">User Name</label>
										<input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter First Name" name="username" value="<?php echo $users->username ?>" autocomplete="off">
										<?php echo form_error('username'); ?>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Phone Number</label>
										<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Phone Number" name="phone" value="<?php echo $users->phone ?>" autocomplete="off">
										<?php echo form_error('phone'); ?>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Email address</label>
										<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter Email" name="email" value="<?php echo $users->email ?>" autocomplete="off">
										<?php echo form_error('email'); ?>
									</div>

									<div class="form-group">
										<label class="control-label">Profile Image</label>
										<input type="file" class="form-control" name="profile_pic">
										<?php if ($this->session->flashdata('profile_pic')) { ?>
											<div class="alert alert-danger">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
												<span><?php echo $this->session->flashdata('profile_pic') ?></span>
											</div>
										<?php } ?>

										<?php
										$profile = explode(":", $users->profile_pic);
										if ($profile[0] == "https" || $profile[0] == "http") { ?>
											<img src="<?php echo $users->profile_pic ?>" alt="User Image" height="80" width="80">
										<?php } else { ?>
											<?php if (empty($users->profile_pic)) { ?>
												<img src="<?php echo base_url('uploads/profile_pics/user.png') ?>" alt="User Image" height="80" width="80">
											<?php } else { ?>
												<img src="<?php echo base_url('uploads/profile_pics/') . $users->profile_pic ?>" alt="User Image" height="80" width="80"> <?php } ?>
										<?php } ?>

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
