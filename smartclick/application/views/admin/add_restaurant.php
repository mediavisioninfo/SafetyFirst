  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Restaurant
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Restaurant</a></li>
        <li class="active">Add Restaurant</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         	<div class="box box-primary">
			   <div class="box-header with-border">
			      <h3 class="box-title">Add Restaurant</h3>
			   </div>
			   <!-- /.box-header -->
			   <!-- form start -->
			   <form role="form" method="post" action="<?php echo base_url('admin/add_restaurant'); ?>" enctype="multipart/form-data">
			      <div class="box-body">
			         <div class="form-group">
			            <label for="exampleInputEmail1">Restaurant Name</label>
			            <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Store Name" required>
			         </div>
					 
					<div class="form-group">
					   <label>Restaurant Collection</label>
					   <select class="form-control" name="cat_id" required>
					   <option value="">Select Collection</option>
					   <?php foreach ($category as $listing): ?>
						<option value="<?php echo $listing['id']; ?>"><?php echo $listing['c_name']; ?></option>
					   <?php endforeach; ?>
					   </select>
					</div>	
					 <div class="form-group">
					<label for="exampleInputfnm">Status</label>
					  <!-- <div class="radio">
						<label>
						  <input type="checkbox" id="optionsRadios1" name="status[]" value="1">
						  Main Screen VIP
						</label>
					  </div>  -->
					  <!-- <div class="radio">
						<label>
						  <input type="checkbox" id="optionsRadios2" name="status[]" value="0">
						  NON-VIP
						</label>
					  </div> -->
					  <div class="radio">
						<label>
						  <input type="checkbox" id="optionsRadios2" name="status[]" value="2">
						  Featured Collection Restaurant
						</label>
					  </div>
					</div>
					<div class="form-group">
					   <label>Restaurant Description</label>
					   <textarea class="form-control" name="description" rows="3" placeholder="Enter ..." required></textarea>
					</div>
					
					<div class="form-group">
						 <label>Restaurant Promo Offer</label>
						 <input class="form-control" type="text" name="promo_offer" placeholder="Enter Promo Offer ..."  />
					</div>
						 <div class="form-group">
						 <label>Phone Number</label>
						 <input class="form-control" type="number" name="phone" placeholder="Enter phone Number ..."  />
					</div>
						 <div class="form-group">
						 <label>Website</label>
						 <input class="form-control" type="text" name="website" placeholder="Enter website ..."  />
					</div>
					
					<div class="form-group">
					   <label>Address</label>
			            <input type="text" id="address" name="address" class="form-control" id="exampleInputEmail1" placeholder="Enter Address" required>
                  
               
					</div>
					
					 <div class="form-group">
			            <label for="exampleInputFile">Restaurant Menu Images (Multiple Images Possible to Upload)</label>
			            <input type="file" name="logo[]" id="exampleInputFile" required multiple>
			            <p class="help-block">Your Store Logo.</p>
			         </div>
					
			         <div class="form-group">
			            <label for="exampleInputFile">Restaurant Images (Multiple Images Possible to Upload)</label>
			            <input type="file" name="res_image[]" id="exampleInputFile" required multiple>
			            <p class="help-block">Your Store Image.</p>
			         </div>
					 
<!-- 					 <div class="form-group">
			            <label for="exampleInputFile">Video File or Youtube Video Link (Embedded)</label>
			            <input type="file" name="res_video" id="exampleInputFile" >
			            <label for="exampleInputEmail1"></label>
			            <p class="help-block">OR</p>
			            <input type="text" name="res_url" class="form-control" id="exampleInputEmail1" placeholder="Youtube Link(Embedded)">
			            <p class="help-block">Your Store Video.</p>
			         </div> -->
					 
<!-- 					 <div class="form-group">
			            <label for="exampleInputEmail1"></label>
			            <input type="text" name="res_url" class="form-control" id="exampleInputEmail1" placeholder="Youtube Link(Embedded)">
			         </div> -->
					 
					 <div class="form-group">
			            <label for="exampleInputFile">Opening Hours</label>
						<div style="margin-bottom: 10px">
							<textarea rows="6" name="otime_mon"  class="form-control" placeholder="Opening Hours"></textarea>
						</div>
						
						<p style="color:red;">* Leave Blank If Its Close</p>
					</div>
			         
					 <div class="form-group">
					 <label>Restaurant Google Address latitude (Show Store on Map)</label>
					 <input class="form-control" type="text" name="lat" placeholder="Enter latitude ..."  />
					</div>
					
					<div class="form-group">
					 <label>Restaurant Google Address Longitude</label>
					 <input class="form-control" type="text" name="lon" placeholder="Enter longitude ..."  />
					</div>
					 
			      </div>
			      <!-- /.box-body -->
			      <div class="box-footer">
			         <button type="submit" class="btn btn-primary">Submit</button>
			      </div>
			   </form>
			</div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
	
