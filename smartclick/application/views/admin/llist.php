

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
                  <th>Restaurant Name</th>
                  <th>Count</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 
				$no=0;
				foreach ($restaurants as $listing):
				$no=$no+1;
				?>
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $listing['res_name']; ?></td>
                  <td><span class="btn btn-info"><?php 
                  $lid=$listing['res_id'];
                  
                  $this->db->select('*');
                  $this->db->from('likes');
                  $this->db->where('res_id',$lid);
                  $query = $this->db->get();
                  echo $query->num_rows();
                  
                  ?></span></td>
                  <td><a href="<?php echo base_url('admin/likeview/'.$listing['res_id']); ?>" class="btn btn-success">View</a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Sr no</th>
                  <th>Restaurant Name</th>
                  <th>Count</th>
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
