<?php
// $all_post = $this->admin_model->get_all_post();
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h3>Posts</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');  ?>">Home</a></li>
						<li class="breadcrumb-item active">All Posts</li>

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
	</div>
	<!-- Container-fluid starts-->
	<div class="container-fluid product-wrapper">
		<div class="product-grid">
			<div class="product-wrapper-grid">
				<div class="row">
					<?php $no = 0;
					if(!empty($all_post)){
					foreach ($all_post as $post) : $no = $no + 1; ?>
						<div class="col-xl-2 col-sm-6 xl-4">
							<div class="card">
								<div class="product-box">
									<div class="product-img">
										<?php
										if (!empty($post->image)) {
											$url = explode(":", $post->image);
											if ($url[0] == "https" || $url[0] == "http") { ?><img class="img-fluid" style=" height:200px; width: 200px" src="<?php echo $post->image ?>" alt="">
											<?php } else {
												$image_arr = explode("::::", $post->image);
											?>
												<?php
												$image_arr = explode("::::", $post->image);
												//foreach ($image_arr as $image_name) { 
												?>
												<img class="img-fluid" style=" height:200px; width: 200px" src="<?php echo base_url() . 'assets/images/post/' . $image_arr[0] ?>" alt="">
												<?php //} 
												?>
											<?php } ?>

										<?php } else { ?>
											<img class="img-fluid" style=" height:200px; width: 200px" src="<?php echo base_url() . 'assets/images/post/video_thumbnail/' . $post->video_thumbnail; ?>" alt="">
										<?php } ?>
										<div class="product-hover">
											<ul>
												<!-- <li><a href="cart.html"><i class="icon-shopping-cart"></i></a></li> -->
												<li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter<?php echo $post->post_id; ?>"><i class="icon-eye"></i></a></li>
											</ul>
										</div>
									</div>
									<div class="modal fade" id="exampleModalCenter<?php echo $post->post_id; ?>">
										<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<div class="product-box row">
														<div class="product-img col-lg-6">
															<?php
															if (!empty($post->image)) {

																$url = explode(":", $post->image);
																if ($url[0] == "https" || $url[0] == "http") { ?>
																	<img class="img-fluid" style=" height:500px; width: 500px" src="<?php echo $post->image ?>" alt="">
																<?php } else {
																	$image_arr = explode("::::", $post->image);
																?>

																	<?php
																	$image_arr = explode("::::", $post->image);
																	foreach ($image_arr as $image_name) { ?>
																		<img class="img-fluid" style=" height:500px; width: 500px" src="<?php echo base_url() . 'assets/images/post/' . $image_name ?>" alt="">
																	<?php } ?>
																<?php } ?>

															<?php } else { ?>
																<img class="img-fluid" style=" height:500px; width: 500px" src="<?php echo base_url() . 'assets/images/post/video_thumbnail/' . $post->video_thumbnail; ?>" alt="">
															<?php } ?>
														</div>
														<div class="product-details col-lg-6 text-start">
															<h4>Post By:</h4>
															<div class="product-price">
																<?php $user = $this->db->get_where('users', array('id' => $post->user_id), 1)->row();
																if (!empty($user)) {
																	echo $user->username;
																} ?>
															</div>
															<h4>Likes :</h4>
															<div class="product-price">
																<?php $total_likes = $this->db->get_where('likes', array('post_id' => $post->post_id))->num_rows();
																echo $total_likes; ?>
															</div>
															<h4>
																Date : <?php $timestamp = $post->create_date;
																		$seconds = round($timestamp / 1000);
																		echo date('d-m-Y ', $seconds); ?>
															</h4><br>
															<div class="product-view">
																<h6 class="f-w-600">Description :</h6>
																<p class="mb-0"><?php echo $post->text ?></p>
															</div>
															<div class="product-size">
															</div>
															<div class="product-qnty">
																<div class="addcart-btn">
																	<a class="btn btn-danger me-3" href="<?php echo base_url('admin/delete-post/' . $post->post_id); ?>" onclick="return confirm('Are you sure you want to delete this?')">Delete</a><a class="btn btn-primary " data-bs-dismiss="modal">Cancle</a>
																</div>
															</div>
														</div>
													</div>
													<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
											</div>
										</div>
									</div>
									<div class="product-details"><a href="product-page.html">
											<?php $user = $this->db->get_where('users', array('id' => $post->user_id), 1)->row();
											if (!empty($user)) { ?>
												<h4><?php echo $user->username; ?> </h4>
											<?php } ?>
										</a>
										<p>Date : <?php $timestamp = $post->create_date;
													$seconds = round($timestamp / 1000);
													echo date('d-m-Y', $seconds); ?></p>
										<div class="product-price">
											Likes : <?php $total_likes = $this->db->get_where('likes', array('post_id' => $post->post_id))->num_rows();
													echo $total_likes; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; }?>
					<div class="center-content">
						<nav aria-label="...">
							<?php if ($echoho == 1) {
								redirect(base_url('admin/all-post'));
							}
							echo $links;
							?>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>
