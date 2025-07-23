

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Restaurant
        <small><?php echo $restaurant->res_name; ?></small>
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         	<div class="box box-primary">
			   <div class="box-header with-border">
			      <h3 class="box-title">Edit Restaurant</h3>
			      <?php echo validation_errors(); ?>
			   </div>
			   <!-- /.box-header -->
			   <!-- form start -->
			   <form role="form" method="post" action="<?php echo base_url('admin/edit_res/'.$restaurant->res_id); ?>" enctype="multipart/form-data">
			      <div class="box-body">
			         <div class="form-group">
			            <label for="exampleInputEmail1">Restaurant Name</label>
			            <input type="text" name="name" class="form-control" id="exampleInputEmail1" value="<?php echo $restaurant->res_name; ?>" required>
			         </div>
				
					 <div class="form-group">
					   <label>Restaurant Collection</label>
					   <select class="form-control" name="cat_id" required>
					   <option value="">Select Collection</option>
					   <?php foreach ($category as $listing): ?>
						<option value="<?php echo $listing['id']; ?>" <?php if($listing['id']==$restaurant->cat_id) echo "selected='selected'"; ?>><?php echo $listing['c_name']; ?></option>
					   <?php endforeach; ?>
					   </select>
					</div>	
					<div class="form-group">
					<?php
					$chk=explode(",",$restaurant->status);
					?>
					<label for="exampleInputfnm">Status</label>
					  <!-- <div class="radio">
						<label>
						  <input type="checkbox" id="optionsRadios1" name="status[]" value="1" <?php //if(in_array("1",$chk)) echo "checked='checked'"; ?>>
						  Main Screen VIP
						</label>
					  </div>
					  <div class="radio">
						<label>
						  <input type="checkbox" id="optionsRadios2" name="status[]" value="0" <?php //if(in_array("0",$chk)) echo "checked='checked'"; ?>>
						  NON-VIP
						</label>
					  </div> -->
					  <div class="radio">
						<label>
						  <input type="checkbox" id="optionsRadios2" name="status[]" value="2" <?php if(in_array("2",$chk)) echo "checked='checked'"; ?>>
						  Featured Category Store
						</label>
					  </div>
					</div>
			         <div class="form-group">
					   <label>Restaurant Description</label>
					   <textarea class="form-control" name="description" rows="3" required><?php echo $restaurant->res_desc; ?></textarea>
					</div>
					
			        <div class="form-group">
						 <label>Restaurant Promo Offer</label>
						 <input class="form-control" type="text" name="promo_offer" placeholder="Enter Promo Offer ..."  value="<?php echo $restaurant->promo_offer; ?>"/>
					</div>    
			         <div class="form-group">
					 <label>Website</label>
					 <input class="form-control" type="text" name="website" placeholder="Enter website ..." value="<?php echo $restaurant->res_website; ?>"  />
					</div>
					 <div class="form-group">
					 <label>Phone</label>
					 <input class="form-control" type="number" name="phone" placeholder="Enter website ..." value="<?php echo $restaurant->res_phone; ?>"  />
					</div>
					
					<div class="form-group">
					   <label>Restaurant Address</label>
			            <input type="text" id="address" name="address" class="form-control" id="exampleInputEmail1" value="<?php echo $restaurant->res_address; ?>" required>
					</div>
					
					<div class="form-group">
			            <label for="exampleInputFile">Restaurant Menu Images (Multiple Images Possible to Upload)</label>
			            <input type="file" name="logo[]"  id="exampleInputFile" multiple>
			            <p class="help-block">Your Store Video.</p>
						<?php $imagesa = explode("::::", $restaurant->logo); ?>
						<?php foreach ($imagesa as $key => $imagea) { ?>
							<img src="<?php echo base_url('uploads/').$imagea ?>" class="res_image" height="200" width="200" style="border: 2px solid #000;padding: 5px;">
						<?php } ?>
			         </div>
					
			         <div class="form-group">
			            <label for="exampleInputFile">Restaurant Images (Multiple Images Possible to Upload)</label>
			            <input type="file" name="res_image[]" multiple id="exampleInputFile" >
			            <p class="help-block">Your Store Image.</p>
						<?php $images = explode("::::", $restaurant->res_image); ?>
						<?php foreach ($images as $key => $image) { ?>
							<img src="<?php echo base_url('uploads/').$image ?>" class="res_image" height="200" width="200" style="border: 2px solid #000;padding: 5px;">
						<?php } ?>
			         </div>
					
<!-- 					 <div class="form-group">
			            <label for="exampleInputFile">Video File or Youtube Video Link (Embedded)</label>
			            <input type="file" name="res_video"  id="exampleInputFile" >
			            <p class="help-block">OR </p>
			            <label for="exampleInputEmail1">Youtube Link(Embedded)</label>
			            <input type="text" name="res_url" class="form-control" id="exampleInputEmail1" placeholder="Youtube Link(Embedded)" value="<?php //echo $restaurant->res_url; ?>">
			            <p class="help-block">Your Store Video.</p>
						<?php //if($restaurant->res_video != "") { ?>
			            <video width="320" height="240" controls>
							<source src="<?php //echo base_url('uploads/').$restaurant->res_video; ?>" type="video/mp4">
							Your browser does not support the video tag.
						</video>
						<?php // } ?>
			         </div> -->
                     
<!--                      <div class="form-group">
			            <label for="exampleInputEmail1">Youtube Link(Embedded)</label>
			            <input type="text" name="res_url" class="form-control" id="exampleInputEmail1" placeholder="Youtube Link(Embedded)" value="<?php //echo $restaurant->res_url; ?>">
			         </div> -->
                        
					 <div class="form-group">
			            <label for="exampleInputFile">Opening Hours</label>
						<div style="margin-bottom: 10px">
							<textarea rows="6" name="otime_mon" class="form-control"  placeholder="Opening Hours"><?php echo $restaurant->mfo; ?></textarea>
						</div>
						<p style="color:red;">* Leave Blank If Its Close</p>
					</div>
			
					<div class="form-group">
					 <label>Restaurant Google Address latitude (Show Store on Map)</label>
					 <input class="form-control" type="text" name="lat" placeholder="Enter latitude ..."  value="<?php echo $restaurant->lat; ?>"/>
					</div>
					
					<div class="form-group">
					 <label>Restaurant Google Address Longitude</label>
					 <input class="form-control" type="text" name="lon" placeholder="Enter longitude ..."  value="<?php echo $restaurant->lon; ?>"/>
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
