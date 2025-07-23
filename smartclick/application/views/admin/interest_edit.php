<?php
$id = $this->uri->segment(3);
$interest = $this->admin_model->get_interest_by_id($id);
?>
<div id="wrapper">
   <div id="page-wrapper">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h1 class="page-header">Interest</h1>
            </div>
            <!-- /.col-lg-12 -->
         </div>
         <!-- /.row -->
         <div class="row">
            <div class="col-lg-12">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     Interest Update
                     <div class="pull-right">
                        <a href="<?= base_url('admin/interests') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
                     </div>
                  </div>
                  <div class="panel-body">
                     <div class="card">
                        <?php if (!empty($this->session->flashdata('success_msg'))) : ?>
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
                        <?php endif ?>
                        <div class="card-body">
                           <form method="POST" enctype="multipart/form-data" action="<?php echo base_url('admin/update-interest'); ?>">

                              <div class="form-group">
                                 <input type="hidden" name="id" value="<?php echo $id; ?>">
                                 <label for="exampleInputfnm">Interest Type</label>
                                 <input type="text" class="form-control" id="exampleInputfnm" placeholder="Enter Interest Type" name="type" value="<?php echo $interest->type ?>">
                                 <?php echo form_error('type'); ?>
                              </div>

                              <div class="box-footer">
                                 <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- /.container-fluid -->
      </div>
      <!-- /#page-wrapper -->
   </div>