

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Restaurant Tables
        <small>Restaurant tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Restaurant</a></li>
        <li class="active">Restaurant tables</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">All Users</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
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
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr no</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>User Name</th>
                  <th>Email</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 
				$no=0;
				foreach ($vendor as $listing):
				$no=$no+1;
				?>
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $listing['fname']; ?></td>
                  <td><?php echo $listing['lname']; ?></td>
                  <td><?php echo $listing['uname']; ?></td>
                  <td><?php echo $listing['email']; ?></td>
                  <td><a href="<?php echo base_url('admin/delete_vendor/'.$listing['id']); ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                  <a href="<?php echo base_url('admin/edit_vendor/'.$listing['id']); ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Sr no</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
