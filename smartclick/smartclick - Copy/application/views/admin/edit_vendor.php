

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Update Vendor
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><i class="fa fa-users"></i> Vendor</a></li>
        <li class="active">Update Vendor</li>
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
              <h3 class="box-title">Update Vendor</h3>
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
                  <label for="exampleInputfnm">First Name</label>
                  <input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter First Name" name="fname" value="<?php echo $cat->fname; ?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputfnm">Last Name</label>
                  <input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter Last Name" name="lname" value="<?php echo $cat->lname; ?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputfnm">User Name</label>
                  <input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter User Name" name="uname" value="<?php echo $cat->uname; ?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputfnm">Email</label>
                  <input type="email" class="form-control" id="exampleInputfnm" placeholder="Enter Email" name="email" value="<?php echo $cat->email; ?>">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputfnm">Password(Leave blank if not changed)</label>
                  <input type="password" class="form-control" id="exampleInputfnm" placeholder="Enter Password" name="password">
                </div>
                
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
 