<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3><?php echo $title; ?></h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');  ?>">Home</a></li>
						<!-- <li class="breadcrumb-item">Pages </li>
                        <li class="breadcrumb-item">ECommerce</li> -->
						<li class="breadcrumb-item active"><?php echo $title; ?></li>
					</ol>
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
					<div class="card-body">
						<div class="dt-ext table-responsive">
							<table class="display" id="new-cons">
								<thead>
									<tr>
										<th>Srno</th>
										<th>UserName</th>
										<th>Image</th>
										<!-- <th>Type</th> -->
										<th>Create Date</th>
										<!-- <th>Is Delete</th> -->
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 0;
									foreach ($storydtl as $story) : $no = $no + 1; ?>

										<tr>
											<td><?php echo $no; ?></td>
											<td><?php
												$username = $this->db->get_where('users', array('id' => $story->user_id), 1)->row();
												if (!empty($username)) {
													echo $username->username;
												} else echo "Not getting user name..!" ?>
											</td>
											<td><?php //echo $story->url; ?>
												<?php
												if (empty($story->url)) { ?>
													<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/user/default.png') ?>" alt="User Image">
													<?php } else {
													$profile = explode(":", $story->url);
													if ($profile[0] == "https" || $profile[0] == "http") { ?>
														<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo $story->url ?>" alt="User Image">
													<?php } else { ?>
														<img class="img-fluid rounded-circle" style="height:65px;width: 65px;" src="<?php echo base_url('assets/images/story/') . $story->url ?>" alt="User Image"> <?php } ?>
												<?php } ?>
											</td>
											<!-- <td><?php //echo $story->type; 
														?></td> -->
											<td><?php echo $story->create_date; ?></td>
											<!-- <td><?php //echo $story->is_delete; 
														?></td> -->
											<td><a href="<?php echo base_url('admin/delete-story/' . $story->story_id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?')">Delete</a></td>
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