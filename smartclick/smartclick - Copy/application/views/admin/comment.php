<?php
$all_comment = $this->admin_model->get_all_comment();
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h3>Comments</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');  ?>">Home</a></li>
						<!-- <li class="breadcrumb-item">Pages </li>
                        <li class="breadcrumb-item">ECommerce</li> -->
						<li class="breadcrumb-item active">Comments list</li>
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
					<div class="card-body">
						<div class="dt-ext table-responsive">
							<table class="display" id="new-cons">
								<thead>
									<tr>
										<th>Srno</th>
										<th>UserName</th>
										<th>Comment </th>
										<th>PostUserName</th>
										<th>Date/Time</th>

										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 0;
									foreach ($all_comment as $comment) : $no = $no + 1; ?>

										<tr>
											<td><?php echo $no; ?></td>
											<?php $user = $this->db->get_where('users', array('id' => $comment['user_id']), 1)->row(); ?>
											<td><?php if (!empty($user)) {
														echo $user->username;
													} ?></td>
											<td><?php echo $comment['text']; ?></td>
											<td>
												<?php $post = $this->db->get_where('posts', array('post_id' => $comment['post_id']), 1)->row();
												if (!empty($post)) {
													$username = $this->db->get_where('users', array('id' => $post->user_id), 1)->row();

													if (!empty($username)) {
														echo $username->username;
													}
												}

												?>
											</td>
											<td> <?php $timestamp = $comment['date'];
													$seconds = round($timestamp / 1000);
													echo date('m-d-Y ', $seconds); ?> </td>

											<td style="display: inline-flex;">
												<a href="<?php echo base_url('admin/comment-edit/' . $comment['comment_id']); ?>" class="btn btn-success btn-sm" style="margin-right:5px;">Edit</a>
												<a href="<?php echo base_url('admin/delete-comment/' . $comment['comment_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
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
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<script src="<?php echo base_url(); ?>bower_components/js/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$('#comment').DataTable({
			responsive: true
		});
	});
</script>
