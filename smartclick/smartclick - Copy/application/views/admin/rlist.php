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
                  <th>Vendor Name</th>
                  <th>Restaurant Name</th>
                  <th>Image</th>
                  <th>Description</th>
                  <th>Category</th>
                  <th>Date</th>
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
                  <td><?php 
                  if($listing['vid'] != 0)
                  {
                  echo $this->db->get_where('vendor', array('id' => $listing['vid']))->row()->fname;
                  }
                  else
                  {
                  echo "";
                  }?></td>
                  <td><?php echo $listing['res_name']; ?></td>
                  <?php if($listing['res_image'] != " ") { ?>
                    <?php $image = explode('::::', $listing['res_image'])[0]; ?>
                    <td><img src="<?php echo base_url(); ?>uploads/<?php echo $image; ?>" height="80" width="80"></td>
                  <?php } else { ?>
                    <td><?php echo "None"; ?></td>
                  <?php } ?>
                  
                  <td><?php echo $listing['res_desc']; ?></td>

                  <?php $cat_name = $this->admin_model->get_cat_details($listing['cat_id']);
                  if(!empty($cat_name))
                  {
                      $c_name=$cat_name->c_name;
                  }
                  else
                  {
                      $c_name="";
                  }
                  ?>
                    <td><?php echo $c_name; ?></td>
                  <td><?php echo gmdate('d M Y', $listing['res_create_date']); ?></td>
                  <td><a href="<?php echo base_url('admin/delete_res/'.$listing['res_id']); ?>" class="btn btn-danger">Delete</a>
                    <a href="<?php echo base_url('admin/edit_res/'.$listing['res_id']); ?>" class="btn btn-primary">Edit</a>
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
