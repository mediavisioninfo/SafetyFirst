

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Category
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><i class="fa fa-users"></i> Category</a></li>
        <li class="active">Edit Category</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Category</h3>
            </div>
            <!-- /.box-header -->
			<?php echo validation_errors(); ?>
            <!-- form start -->
            <?php
				  $success_msg= $this->session->flashdata('success_msg');
				  $error_msg= $this->session->flashdata('error_msg');

				  if($success_msg){
					?>
					<div class="alert alert-success alert-dismissible fade in">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Alert!</strong> <?php echo $success_msg; ?>
					</div>
				  <?php
				  }
				  if($error_msg){
					?>
					<div class="alert alert-danger alert-dismissible fade in">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Alert!</strong> <?php echo $error_msg; ?>
					</div>
					<?php
				  }
				?>	
            <form method="POST" enctype="multipart/form-data">
              <div class="box-body">
				<div class="form-group">
                  <label for="exampleInputfnm">Category Icon</label>
                  <input type="file" class="form-control" id="exampleInputfnm" name="icon">
                  <img src="<?php echo base_url('uploads/'.$cat->icon) ?>" style="width: 100px; height: 100px;" alt="">
                </div>
				<div class="form-group">
                  <label for="exampleInputfnm">Category Image</label>
                  <input type="file" class="form-control" id="exampleInputfnm" name="img">
                  <img src="<?php echo base_url('uploads/'.$cat->img) ?>" style="width: 100px; height: 100px;" alt="">
                </div>
			    <div class="form-group">
                  <label for="exampleInputfnm">Category Name</label>
                  <input type="text" class="form-control" id="exampleInputfnm" value="<?php echo $cat->c_name; ?>" placeholder="Enter Category Name" name="category">
                </div>
			
			<!-- 	<div class="form-group">
				<label for="exampleInputfnm">Category Type</label>
				  <div class="radio">
					<label>
					  <input type="radio" id="optionsRadios1" name="type" <?php //echo ($cat->type == "vip" ? "checked" : "") ?> value="vip">
					  VIP
					</label>
				  </div>
				  <div class="radio">
					<label>
					  <input type="radio" id="optionsRadios2" name="type" <?php //echo ($cat->type == "non_vip" ? "checked" : "") ?> value="non_vip">
					  NON-VIP
					</label>
				  </div>
				</div> -->
                </div>
           
              <div class="box-footer">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

        </div>
        <!--/.col (left) -->
       
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 