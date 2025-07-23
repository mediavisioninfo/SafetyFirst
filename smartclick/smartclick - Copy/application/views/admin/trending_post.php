<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h3>Trending Post</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');  ?>">Home</a></li>
						<!-- <li class="breadcrumb-item">Pages </li>
                        <li class="breadcrumb-item">ECommerce</li> -->
						<li class="breadcrumb-item active">Trending Post list</li>
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
	<div class="container-fluid list-products">
		<div class="row">
			<!-- Individual column searching (text inputs) Starts-->
			<div class="col-sm-12">
				<div class="card">
					<!-- <div class="card-header pb-0">
						User List
						<div class="pull-right">
							<a href="<? // base_url('admin/dashboard') 
										?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
						</div>
					</div> -->
					<div class="card-body">
						<div class="dt-ext table-responsive">
							<table class="display" id="new-cons">
								<thead>
									<tr>
										<th>Srno</th>
										<th>UserName</th>
										<!-- <th>Phone Number</th> -->
										<th>Email</th>
										<th>Post</th>
										<th>Like</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 0;
									foreach ($trending_post as $post) : $no = $no + 1; ?>

										<tr>
											<td><?php echo $no; ?></td>
											<?php $user = $this->db->get_where('users', array('id' => $post['user_id']), 1)->row(); ?>
											<td><?php
												if (!empty($user->username)) {
													echo $user->username;
												} ?></td>
											<!-- <td><?php
												if (!empty($user->phone)) {
													//echo $user->phone;
												} ?></td> -->
											<td><?php
												if (!empty($user->email)) {
													echo $user->email;
												} ?></td>

											<!-- <td>
                                       <?php
										$image_arr = explode("::::", $post['image']);
										foreach ($image_arr as $image_name) { ?>
                                          <img style="height:50px;width: 50px;" src="<?php echo base_url() . 'uploads/' . $image_name ?>" class="img-reponsive"><?php } ?>
                                    </td> -->

											<td>
												<?php
												// echo $row->id;
												if (empty($post['image'])) { ?>
													<img class="img-fluid rounded-circle" style="height:80px;width: 80px;" src="<?php echo base_url('uploads/profile_pics/user.png') ?>">
													<?php } else {
													$profile = explode(":", $post['image']);
													if ($profile[0] == "https" || $profile[0] == "http") { ?>
														<img class="img-fluid rounded-circle" style="height:80px;width: 80px;" src="<?php echo $post['image'] ?>">
													<?php } else { ?>
														<img class="img-fluid rounded-circle" style="height:80px;width: 80px;" src="<?php echo base_url() . 'uploads/' . $image_name ?>"> <?php } ?>
												<?php } ?>
											</td>

											<td><?php
												$total_likes = $this->db->get_where('likes', array('post_id' => $post['post_id']),)->num_rows();
												echo $total_likes; ?>
											</td>

											<td> <?php $timestamp = $post['create_date'];
													$seconds = round($timestamp / 1000);
													echo date('d-m-Y ', $seconds	); ?>
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
