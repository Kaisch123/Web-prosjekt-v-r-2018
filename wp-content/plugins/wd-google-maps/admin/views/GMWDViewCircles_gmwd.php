<?php

class GMWDViewCircles_gmwd extends GMWDView{

	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////

	public function edit($id){
		$row = $this->model->get_row($id);	
        $page = esc_html(stripslashes(GMWDHelper::get("page")));
        $map_id = esc_html(stripslashes(GMWDHelper::get('map_id')));
	
	?>
	
		<div class="pois_wrapper gmwd_edit">
			<form method="post" action="" id="adminForm">
            
				<!-- header -->
				<h2 class="overlay_title wd-clear">
                    <div class="wd-left">
                        <img src="<?php echo GMWD_URL . '/images/css/circle-active-tab.png';?>" width="30" style="vertical-align:middle;">
                        <span><?php _e("Add Circle","gmwd");?></span>
                    </div>
                    <div class="wd-right">
                        <button class="wd-btn wd-btn-secondary" onclick="gmwdAddPoi();return false;"><?php isset($_GET["hiddenName"]) ? _e("Edit Circle","gmwd") : _e("Add Circle","gmwd") ;?></button>
                        <button class="wd-btn wd-btn-secondary" onclick="gmwdClosePoi();return false;"><?php  _e("Cancel","gmwd") ;?></button>
                    </div>
				</h2>
				<!-- data -->
				<div class="wd-clear">
					<div class="wd-left">
						<table class="pois_table">
							<tr>
                                <td><label for="title" title="<?php _e("Create a title for the circle on the map.","gmwd");?>"><?php _e("Title","gmwd");?>:</label></td>
								<td><input type="text" name="title" id="title" value="<?php echo $row->title;?>" class="wd-form-field wd-poi-required"></td>
								<td><label for="line_width" title="<?php _e("Set circle line width.","gmwd");?>"><?php _e("Line Width","gmwd");?>:</label></td>
								<td><input type="text" name="line_width" id="line_width" value="<?php echo $row->line_width;?>"data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="<?php echo implode(",",range(1,50)); ?>" class="wd-form-field"></td>                                
							</tr>
							<tr>
                                <td><label for="circle_address" title="<?php _e("Search for location or right-click on the map to bring circle center address.  Alternatively, add a location manually.","gmwd");?>"><?php _e("Center Address","gmwd");?>:</label></td>
								<td>
                                    <input type="text" name="center_address" id="circle_address" value="<?php echo $row->center_address;?>"autocomplete="off" class="wd-form-field wd-poi-required"><br>
                                    <small><em><?php _e("Or Right Click on the Map","gmwd");?></em></small>
                                </td>			
								<td><label for="line_color" title="<?php _e("Choose circle line color.","gmwd");?>"><?php _e("Line Color","gmwd");?>:</label></td>
								<td><input type="text" name="line_color" id="line_color" value="<?php echo $row->line_color;?>" class="color wd-form-field" ></td>                                
							</tr>
							<tr>
 								<td><label for="lat" title="<?php _e("Set circle center latitude if not specified automatically.","gmwd");?>"><?php _e("Center Lat","gmwd");?>:</label></td>
								<td><input type="text" name="center_lat" id="lat" value="<?php echo $row->center_lat;?>" class="wd-form-field wd-poi-required"></td>                           
				
								<td><label for="line_opacity" title="<?php _e("Set an opacity for circle line.","gmwd");?>"><?php _e("Line Opacity","gmwd");?>:</label></td>
								<td><input type="text" name="line_opacity" id="line_opacity"  value="<?php echo $row->line_opacity;?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1" class="wd-form-field"></td>                                
							</tr>							
							<tr>
                                <td><label for="lng" title="<?php _e("Set circle center longitude if not specified automatically.","gmwd");?>"><?php _e("Center Lng","gmwd");?>:</label></td>
								<td><input type="text" name="center_lng" id="lng" value="<?php echo $row->center_lng;?>" class="wd-form-field wd-poi-required"></td>
								<td><label for="fill_color" title="<?php _e("Set a color for circle.","gmwd");?>"><?php _e("Fill Color","gmwd");?>:</label></td>
								<td><input type="text" name="fill_color" id="fill_color" value="<?php echo $row->fill_color;?>" class="color wd-form-field" ></td>                                
							</tr>
							<tr>
                                <td><label for="link" title="<?php _e("Add a link to circle.","gmwd");?>"><?php _e("Link","gmwd");?>:</label></td>
								<td><input type="text" name="link" id="link" value="<?php echo $row->link;?>" class="wd-form-field"></td>
								<td><label for="fill_opacity" title="<?php _e("Set an opacity for circle.","gmwd");?>"><?php _e("Fill Opacity","gmwd");?>:</label></td>
								<td><input type="text" name="fill_opacity" id="fill_opacity" value="<?php echo $row->fill_opacity;?>"data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1" class="wd-form-field" ></td>                                
							</tr>
							<tr>
       							<td><label for="radius" title="<?php _e("Set the circle radius in meters.","gmwd");?>"><?php _e("Radius (meters)","gmwd");?>:</label></td>
								<td><input type="number" name="radius" id="radius" value="<?php echo $row->radius;?>" class="wd-form-field"></td>
								<td><label for="line_color_hover" title="<?php _e("Set a color for circle line on hover.","gmwd");?>"><?php _e("Line Color Hover","gmwd");?>:</label></td>
								<td><input type="text" name="line_color_hover" id="line_color_hover" value="<?php echo $row->line_color_hover;?>" class="color wd-form-field"></td>                                
							</tr>
							<tr>
                                <td><label title="<?php _e("Choose whether to show circle center marker or not.","gmwd");?>"><?php _e("Show marker:","gmwd"); ?></label></td>
								<td>
								  <input type="radio" class="inputbox wd-form-field" id="show_marker0" name="show_marker" <?php echo (($row->show_marker) ? '' : 'checked="checked"'); ?> value="0" >
								  <label for="show_marker0"><?php _e("No","gmwd"); ?></label>
								  <input type="radio" class="inputbox wd-form-field" id="show_marker1" name="show_marker" <?php echo (($row->show_marker) ? 'checked="checked"' : ''); ?> value="1" >
								  <label for="show_marker1"><?php _e("Yes","gmwd"); ?></label>
								</td>
								<td><label for="line_opacity_hover" title="<?php _e("Set a color opacity for circle line on hover.","gmwd");?>"><?php _e("Line Opacity Hover","gmwd");?>:</label></td>
								<td><input type="text" name="line_opacity_hover" id="line_opacity_hover" value="<?php echo $row->line_opacity_hover;?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1" class="wd-form-field"></td>                                
							</tr>								
							<tr>
								<td><label title="<?php _e("Choose whether to enable info window or not.","gmwd");?>"><?php _e("Enable Info Window","gmwd");?></label>:</td>
								<td>								
									<input type="radio" class="inputbox wd-form-field" id="enable_info_window0" name="enable_info_window" <?php echo (($row->enable_info_window) ? '' : 'checked="checked"'); ?> value="0"  >
									<label for="enable_info_window0"><?php _e("No","gmwd"); ?></label>
									<input type="radio" class="inputbox wd-form-field" id="enable_info_window1" name="enable_info_window" <?php echo (($row->enable_info_window) ? 'checked="checked"' : ''); ?> value="1"  >
									<label for="enable_info_window1"><?php _e("Yes","gmwd"); ?></label>						
								</td> 
								<td><label for="fill_color_hover" title="<?php _e("Set a color for circle on hover.","gmwd");?>"><?php _e("Fill Color Hover","gmwd");?>:</label></td>
								<td><input type="text" name="fill_color_hover" id="fill_color_hover" value="<?php echo $row->fill_color_hover;?>" class="color wd-form-field"></td>
							</tr>							
							<tr>
                                <td></td>
                                <td></td>                            
								<td><label for="fill_opacity_hover" title="<?php _e("Set a color opacity for circle on hover.","gmwd");?>"><?php _e("Fill Opacity Hover","gmwd");?>:</label></td>
								<td><input type="text" name="fill_opacity_hover" id="fill_opacity_hover" value="<?php echo $row->fill_opacity_hover;?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume"data-slider-values="0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1" class="wd-form-field" ></td>
							</tr>															
							<tr>
								<td><label title="<?php _e("Publish your circle.","gmwd");?>"><?php _e("Published:","gmwd"); ?></label></td>
								<td>
								  <input type="radio" class="inputbox wd-form-field" id="publishedc0" name="published" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" >
								  <label for="published0c"><?php _e("No","gmwd"); ?></label>
								  <input type="radio" class="inputbox wd-form-field" id="publishedc1" name="published" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
								  <label for="publishedc1"><?php _e("Yes","gmwd"); ?></label>
								</td>
							</tr>								
							<tr>								
								<td colspan="4">
									<button class="wd-btn wd-btn-primary" onclick="gmwdAddPoi();return false;"><?php isset($_GET["hiddenName"]) ? _e("Edit Circle","gmwd") : _e("Add Circle","gmwd") ;?></button>
									<button class="wd-btn wd-btn-secondary" onclick="gmwdClosePoi();return false;"><?php  _e("Cancel","gmwd") ;?></button>
								</td>                               
							</tr>	
						</table>
					</div>
					<div class="wd-right">
						<div id="wd-map2" class="wd_map gmwd_follow_scroll" style="height:400px;width:472px;"></div>
					</div>					
				</div>					
				<input id="page" name="page" type="hidden" value="<?php echo $page;?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="id" name="id" type="hidden" value="<?php echo $row->id;?>" class="wd-form-field" />
				<input id="map_id" name="map_id" type="hidden" value="<?php echo $map_id;?>" class="wd-form-field" />	
								
			</form>
		</div>	
		<script>
        
		jQuery(".pois_wrapper [data-slider]").each(function () {
		  var input = jQuery(this);
		  jQuery("<span>").addClass("output").insertAfter(jQuery(this));  
		}).bind("slider:ready slider:changed", function (event, data) {   
		  jQuery(this) .nextAll(".output:first").html(data.value.toFixed(1));   
		});
        gmwdSlider(this.jQuery || this.Zepto, jQuery("#wd-overlays"));
		jscolor.init();
		var _type = "circles";
        var _hiddenName = "<?php echo isset($_GET["hiddenName"]) ? esc_html(stripslashes($_GET["hiddenName"])) : ""; ?>";
		var markerDefaultIcon = "<?php echo gmwd_get_option("marker_default_icon");?>";
		</script>
		    			
		<script src="<?php echo GMWD_URL . '/js/simple-slider.js'; ?>" ></script>
		<script src="<?php echo GMWD_URL . '/js/circles_gmwd.js'; ?>"></script> 
		<script src="<?php echo GMWD_URL . '/js/admin_main.js'; ?>" type="text/javascript"></script>
		
	<?php
	 die();
	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}