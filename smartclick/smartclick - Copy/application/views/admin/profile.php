<?php
// $id = $this->uri->segment(3);
$id = $this->session->userdata('aid');
  $profile = $this->admin_model->get_admin($id);
?>
<div id="wrapper">
  <div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Profile</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                Profile Update
                <div class="pull-right">
                  <a href="<?=base_url('admin/dashboard')?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
                </div>
              </div>
            <div class="panel-body">
            <div class="card">
              <?php if(!empty($this->session->flashdata('success'))): ?>
                <div class="alert alert-success">
                  <a href="javascript:void()" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <span> <?php echo $this->session->flashdata('success'); ?> </span>
                </div>
              <?php endif ?>
              <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                  <a href="javascript:void()" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <span><?php echo $this->session->flashdata('error') ?></span>
                </div>
              <?php endif ?>
              <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url('admin/admin-edit'); ?>">
                      <div class="col-md-8">
                        <div class="box-body">
                          <div class="form-group">
                            <label for="exampleInputfnm">Username</label>
                            <input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter Username" name="name" value="<?php echo $profile['name'] ?>">
                          </div>
                          <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" name="email" value="<?php echo $profile['email'] ?>">
                          </div>
                          <div class="form-group">
                            <label for="exampleInputimg">Profile Image</label>
                            <input type="file" class="form-control" id="exampleInputimg"  name="img">
                          </div>
                        </div>
                     
                        <div class="box-footer">
                          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    <div class="col-md-4">
                        <img src="<?php echo base_url();  ?>uploads/profile_pics/<?php echo $profile['img'] ?>" class="img-thumbnail" height="100" width="100">
                    </div>
                </form>
              </div>
            </div>
            </div>
          </div>
        </div>
        </div>

      <div class="row">
            <!-- left column -->
        <div class="col-lg-12">
           <div class="panel panel-default">
              <div class="panel-heading">
                  Change Password
                  <div class="pull-right">
                    <a href="<?=base_url('admin/dashboard')?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
                  </div>
              </div>
            <div class="panel-body">
              <!-- general form elements -->
          <div class="box box-primary col-md-12">
            <div class="box-header with-border">
            </div>
                <!-- /.box-header -->
            <?php if(!empty($this->session->flashdata('msg_success'))): ?>
              <div class="alert alert-success">
                <a href="javascript:void()" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span> <?php echo $this->session->flashdata('msg_success'); ?> </span>
              </div>
            <?php endif ?>
            <?php if($this->session->flashdata('msg_error')): ?>
              <div class="alert alert-danger">
                <a href="javascript:void()" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span><?php echo $this->session->flashdata('msg_error') ?></span>
              </div>
            <?php endif ?>
                <!-- form start -->
            <form method="POST" enctype="multipart/form-data" action="<?php echo base_url('admin/change'); ?>">
              <div class="col-md-8">
                <div class="box-body">
                  <div class="form-group">
                    <label for="exampleInputfnm">Old Password</label>
                    <input type="password" class="form-control" id="exampleInputfnm" placeholder="Old Password" name="password">
                      <?php echo form_error('password'); ?>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputfnm">New Password</label>
                    <input type="password" class="form-control" id="exampleInputfnm" placeholder="New Password" name="npassword">
                      <?php echo form_error('npassword'); ?>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputfnm">Confirm Password</label>
                    <input type="password" class="form-control" id="exampleInputfnm" placeholder="Confirm Password" name="cpassword">
                      <?php echo form_error('cpassword'); ?>
                  </div>
                </div>
                  <div class="box-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                  </div>
              </div>
            </form>
          </div>
              <!-- /.box -->

        </div>
        <!--/.col (left) -->
      </div>

    </div>         <!-- /.row -->
    </div>
      <!-- /.container-fluid -->
  </div>
  <!-- /#page-wrapper -->
</div>