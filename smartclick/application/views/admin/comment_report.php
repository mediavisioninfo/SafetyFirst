<?php
$all_comment = $this->admin_model->get_all_comment_report();
?>
<div id="wrapper">
   <div id="page-wrapper">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h1 class="page-header">Comment Report</h1>
            </div>
            <!-- /.col-lg-12 -->
         </div>
         <!-- /.row -->
         <div class="row">
            <div class="col-lg-12">
               <div class="panel panel-default">
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
                  <div class="panel-heading">
                     Comment
                     <div class="pull-right">
                        <a href="<?= base_url('admin/dashboard') ?>" class="btn m-b-xs btn-sm btn-info btn-addon"><i class="fa fa-backward"></i> Back</a>
                     </div>
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="comment">
                           <thead>
                              <tr>
                                 <th>Srno</th>
                                 <th>UserName</th>
                                 <th>Comment </th>
                                 <th>PostUserName</th>
                                 <th>Date/Time</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $no = 0;
                              foreach ($all_comment as $comment) : $no = $no + 1; ?>

                                 <tr>
                                    <td><?php echo $no; ?></td>
                                    <?php $user = $this->db->get_where('users', array('id' => $comment['user_id']), 1)->row(); ?>
                                    <td><?php echo $user->username; ?></td>
                                    <td><?php echo $comment['text']; ?></td>
                                    <td>
                                       <?php $post = $this->db->get_where('posts', array('post_id' => $comment['post_id']), 1)->row();
                                       if (!empty($post)) {
                                          $username = $this->db->get_where('users', array('id' => $post->user_id), 1)->row();

                                          echo $username->username;
                                       }

                                       ?>
                                    </td>
                                    <td> <?php $timestamp = $comment['create_date'];
                                          $seconds = $timestamp / 1000;
                                          echo date('m/d/Y h:i:sa', $seconds); ?> </td>

                                    <td style="display: inline-flex;">
                                       <!-- <a href="<?php echo base_url('admin/delete-comment/' . $comment['comment_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?')"><span class="glyphicon glyphicon-trash"></span></a> -->

                                       <button data-i="<?php echo $comment['comment_id']; ?>" class="btn btn-danger btn-sm delete">
                                          <i class="fa fa-trash"></i></button>

                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                     </div>
                     <!-- /.table-responsive -->
                  </div>
                  <!-- /.panel-body -->
               </div>
               <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
         </div>

      </div>
      <!-- /.container-fluid -->
   </div>
</div>

<!-- <script src="<?php echo base_url(); ?>bower_components/js/jquery.min.js"></script>
<script>
   $(document).ready(function() {
      $('#comment').DataTable({
         responsive: true
      });
   });
</script> -->


<div class="modal fade in" id="modalDel">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Delete Confirmation</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span></button>
         </div>
         <form method="post" action="<?php echo base_url('admin/trash-user'); ?>" id="frmDel">
            <div class="modal-body">
               <p>Are you sure you want to delete?</p>
            </div>
            <div class="modal-footer">
               <input type="hidden" name="id" value="">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <input type="submit" class="btn btn-primary btnclass" value="Yes Delete!">
            </div>
         </form>
      </div>
   </div>
</div>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
      $(document).on('click', '.delete', function() {
         var i = $(this).data('i');
         $("#frmDel input[name='id']").val(i);
         $("#modalDel").modal('show');
      });
   });
</script>