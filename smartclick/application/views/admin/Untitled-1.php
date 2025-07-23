<div class="">
	<nav>
		<!-- <nav aria-label="Contacts Page Navigation"> -->
		<div class="main-navbar">
			<div id="mainnav">
				<?php if ($echoho == 1) {
					redirect(base_url('admin/all-post'));
				}
				echo $links;
				?>
			</div>
		</div>
	</nav>
</div>