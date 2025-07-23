

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Updates Notes
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><i class="fa fa-users"></i> Notes</a></li>
        <li class="active">Updates Notes</li>
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
              <h3 class="box-title">Updates Notes</h3>
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
                  <label for="text_en">Welcome Text (Eng)</label>
                  <textarea class="form-control" id="text_en" placeholder="Welcome Text (Eng)" name="text_en"><?php echo $text->text_en; ?></textarea>
                </div>
				<div class="form-group">
                  <label for="text_ar">Welcome Text (Arb)</label>
                  <textarea class="form-control" id="text_ar" placeholder="Welcome Text (Arb)" name="text_ar"><?php echo $text->text_ar; ?></textarea>
                </div>
				<div class="form-group">
                  <label for="popup_text">Popup Text (Eng)</label>
                   <textarea class="form-control" id="popup_text" placeholder="Popup Text (Eng)" name="popup_text"><?php echo $text->popup_text; ?></textarea>
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
 