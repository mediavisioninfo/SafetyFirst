

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Tables
        <small>User tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">User</a></li>
        <li class="active">User tables</li>
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
                  <th>Title</th>
                  <th>Image</th>
                  <th>Video</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>By User</th>
                  <th>Email</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 
				$no=0;
				foreach ($listings as $listing):
				$no=$no+1;
				?>
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $listing['title']; ?></td>
                  <td><img src="<?php echo base_url(); ?><?php echo $listing['img']; ?>" height="80" width="80"></td>
                  <td><a target="_blank" href="<?php echo base_url(); ?><?php echo $listing['video']; ?>"><?php echo $listing['video']; ?></a></td>
                  <td><?php echo $listing['des']; ?></td>
                  <td><?php echo $listing['price']; ?></td>
                  <td><?php echo $listing['fname']." ".$listing['lname']; ?></td>
                  <td><?php echo $listing['email']; ?></td>
                  <td><a title="Unapproved" href="<?php echo base_url('admin/delete_listing_mail/'.$listing['cid']); ?>" class="btn btn-danger" style="margin-right:5px;"><i class="fa fa-trash-o"></i></a>
				  
				  <a title="Approved" href="<?php echo base_url('admin/app_listing/'.$listing['cid']); ?>" class="btn btn-success" style="margin-right:5px;"><i class="fa fa-check"></i></a>

				  </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Sr no</th>
                  <th>Title</th>
                  <th>Image</th>
                  <th>Video</th>
                  <th>Price</th>
                  <th>By User</th>
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
