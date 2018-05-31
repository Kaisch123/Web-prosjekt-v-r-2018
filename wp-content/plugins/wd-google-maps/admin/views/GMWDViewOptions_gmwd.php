<?php

class GMWDViewOptions_gmwd extends GMWDView{

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

	public function display(){	
		$options = $this->model->get_options();
		$lists = $this->model->get_lists();
        $query_url =  admin_url('admin-ajax.php');
        $query_url_select_icon = add_query_arg(array('action' => 'select_marker_icon', 'page' => 'markers_gmwd', 'task' => 'select_icon', 'width' => '900', 'height' => '600', 'nonce_gmwd' => wp_create_nonce('nonce_gmwd'), 'TB_iframe' => '1' ), $query_url);
        $query_url_generate_key = 'https://console.developers.google.com/henhouse/?pb=["hh-1","maps_backend",null,[],"https://developers.google.com",null,["maps_backend","geocoding_backend","directions_backend","distance_matrix_backend","elevation_backend","places_backend","static_maps_backend","roads","street_view_image_backend","geolocation"],null]&TB_iframe=true&width=600&height=400';
	?>	
		<div class="gmwd_edit">       
			<h2>
				<img src="<?php echo GMWD_URL . '/images/general_options.png';?>" width="30" style="vertical-align:middle;">
				<span><?php _e("General Options","gmwd");?></span>
			</h2>		
			<form method="post" action="" id="adminForm">
            <?php wp_nonce_field('nonce_gmwd', 'nonce_gmwd'); ?>
				<div class="wd-clear wd-row">
                    <div class="wd-left">
                    	 <a class="wd-btn wd-btn-primary" href="<?php echo admin_url( 'index.php?page=gmwd_setup' );?>" style="    background: #0a7393; border: 1px solid;"><?php _e("Run Install Wizard ","gmwd"); ?></a>   
                    </div>
					<div class="wd-right">
                        <button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" onclick="gmwdFormSubmit('apply');" ><?php _e("Apply","gmwd");?></button>                             						
					</div>
				</div>
              <div class="gmwd">
                    <?php if(defined('GMWDUGM_NAME') && is_plugin_active(GMWDUGM_NAME.'/'.GMWDUGM_NAME.'.php') == true){
                    ?>              
					<ul class="wd-options-tabs wd-clear">
						<li><a href="#general" class="wd-btn wd-btn-secondary <?php echo (GMWDHelper::get('active_tab', "general") == "general") ? 'wd-btn-primary' : ''; ?>" ><?php _e("General Options","gmwd");?></a></li>
						<li><a href="#user-gen-markers" class="wd-btn wd-btn-secondary <?php echo (GMWDHelper::get('active_tab') == "user-gen-markers") ? 'wd-btn-primary' : ''; ?>"><?php _e("User Generated Markers","gmwd");?></a></li>
	
					</ul>
                    <?php
                    }
                    ?>                     
					<div class="wd-clear">	
						 <div class="wd-options-tabs-container wd-left"> 
							<div id="general" class="wd-options-container" style="width:500px; <?php echo GMWDHelper::get('active_tab', "general") == "general"  ? '' : 'display:none;'; ?>">
								<table class="gmwd_edit_table" style="width:100%;">	
									<tr>
										<td width="30%"><label for="map_api_key" title="<?php _e("Set your map API key","gmwd");?>"><?php _e("Map API Key","gmwd"); ?>:</label></td>
										<td>
                                            <input type="text" name="map_api_key" id="map_api_key" value="<?php echo $options->map_api_key;?>" style="width:400px" >                     
										</td>
									</tr> 
                                    <tr>
                                        <td colspan="2">
                                            <a class="wd-btn wd-btn-primary thickbox thickbox-preview" name="<?php _e( 'Generate API Key - ( MUST be logged in to your Google account )', 'gmwd' ); ?>" href='<?php echo $query_url_generate_key;?>'>
                                                <?php _e("Generate Key","gmwd");?>
                                            </a>
                                            or <a target="_blank" href='https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend,static_maps_backend,roads,street_view_image_backend,geolocation,places_backend&keyType=CLIENT_SIDE&reusekey=true'>click here</a>
                                            <?php echo _e( ' to Get a Google Maps API KEY', 'gmwd' ) ?>
                                        </td>
                                    </tr>    
									<tr>
										<td width="30%"><label for="map_language" title="<?php _e("Choose Your Map Language","gmwd");?>"><?php _e("Map Language","gmwd"); ?>:</label></td>
										<td>
											<select name="map_language" id="map_language">
												<?php 
													foreach($lists["map_languages"] as $key => $value){
														$selected = $options->map_language ==  $key ? "selected" : "";
														echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
													}
												
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td style="vertical-align:top;"><label for="marker_default_icon"  title="<?php _e("Upload a Custom Map Marker for Your Google Maps ","gmwd");?>"><?php _e("Marker Default Icon","gmwd");?>:</label></td>
										<td> 
                                          <div class="wd-row"> 
                                              <input type="radio" class="inputbox wd-form-field" id="choose_marker_icon1" name="choose_marker_icon" <?php echo ($options->choose_marker_icon == 1  ? 'checked="checked"' : ''); ?> value="1" >
                                              <label for="choose_marker_icon1"><?php _e("Choose from Icons","gmwd"); ?></label>
                                              <input type="radio" class="inputbox wd-form-field" id="choose_marker_icon0" name="choose_marker_icon" <?php echo (($options->choose_marker_icon == 0 ) ? 'checked="checked"' : ''); ?> value="0"  >
                                              <label for="choose_marker_icon0"><?php _e("Upload","gmwd"); ?></label>  
                                          </div>                                      
                                            <div class="from_media_uploader" <?php echo (($options->choose_marker_icon == 0 ) ? '' : 'style="display:none;"'); ?>>
                                                <button class="wd-btn wd-btn-primary" onclick="gmwdOpenMediaUploader(event,'marker_default_icon');return false;"><?php _e("Upload Image","gmwd"); ?></button>
                        
                                            </div>
                                            <div class="from_icons" <?php echo (($options->choose_marker_icon == 1 ) ? '' : 'style="display:none;"'); ?>>
                                                <a class="wd-btn wd-btn-primary thickbox thickbox-preview" href="<?php echo $query_url_select_icon;?>"><?php _e("Choose Marker Image","gmwd"); ?></a>    
                                            </div>
                                            <input type="hidden" name="marker_default_icon" id="marker_default_icon" value="<?php echo $options->marker_default_icon; ?>" class="wd-form-field">  
											 <div class="marker_default_icon_view upload_view">
											   <?php if($options->marker_default_icon){
													echo '<img src="'.$options->marker_default_icon.'" height="25">';
													echo '<span class="marker_default_icon_delete" onclick="jQuery(\'#marker_default_icon\').val(\'\');jQuery(\'.marker_default_icon_view\').html(\'\');">x</span>';
												}
												?>               
											</div> 			
										</td>
									</tr>
									<tr>
										<td style="width:15%;"><label for="address" title="<?php _e("Set Center Address of your Google Map","gmwd");?>"><?php _e("Center address","gmwd");?>:</label></td>
										<td>
                                            <input type="text" name="center_address" id="address" value="<?php echo $options->center_address;?>" autocomplete="off" ><br>
                                             <small><em><?php _e("Or Right Click on the Map.","gmwd");?></em></small>
                                        </td>
									</tr>
									<tr>
										<td><label for="center_lat" title="<?php _e("Google Map's Center Latitude","gmwd");?>"><?php _e("Center Lat","gmwd");?>:</label></td>
										<td><input type="text" name="center_lat" id="center_lat" value="<?php echo $options->center_lat;?>"></td>
									</tr>
									<tr>
										<td><label for="center_lng" title="<?php _e("Google Map's Center Longitude","gmwd");?>"><?php _e("Center Lng","gmwd");?>:</label></td>
										<td><input type="text" name="center_lng" id="center_lng" value="<?php echo $options->center_lng;?>"></td>
									</tr>         					
									<tr>
										<td><label for="zoom_level" title="<?php _e("Choose the Zoom Level of Your Google Maps","gmwd");?>"><?php _e("Zoom Level","gmwd");?>:</label></td>
										<td><input type="text" name="zoom_level" id="zoom_level" value="<?php echo $options->zoom_level;?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="<?php echo implode(",",range(0,22)); ?>"></td>
									</tr> 
									<tr>
										<td><label title="<?php _e("Enable or Disable Mouse Scroll-Wheel Scaling","gmwd");?>"><?php _e("Wheel Scrolling","gmwd"); ?>:</label></td>
										<td>
										  <input type="radio" class="inputbox" id="whell_scrolling0" name="whell_scrolling" <?php echo (($options->whell_scrolling) ? '' : 'checked="checked"'); ?> value="0" >
										  <label for="whell_scrolling0"><?php _e("Off","gmwd"); ?></label>
										  <input type="radio" class="inputbox" id="whell_scrolling1" name="whell_scrolling" <?php echo (($options->whell_scrolling) ? 'checked="checked"' : ''); ?> value="1" >
										  <label for="whell_scrolling1"><?php _e("On","gmwd"); ?></label>
										</td>
									</tr>
									<tr>
										<td ><label title="<?php _e("Enable or Disable Google Maps Dragging","gmwd");?>"><?php _e("Map Draggable","gmwd"); ?>:</label></td>
										<td>
										  <input type="radio" class="inputbox" id="map_draggable0" name="map_draggable" <?php echo (($options->map_draggable) ? '' : 'checked="checked"'); ?> value="0" >
										  <label for="map_draggable0"><?php _e("No","gmwd"); ?></label>
										  <input type="radio" class="inputbox" id="map_draggable1" name="map_draggable" <?php echo (($options->map_draggable) ? 'checked="checked"' : ''); ?> value="1" >
										  <label for="map_draggable1"><?php _e("Yes","gmwd"); ?></label>
										</td>
									</tr>                                                                            
								</table>
							</div>
                            <?php if(defined('GMWDUGM_NAME') && is_plugin_active(GMWDUGM_NAME.'/'.GMWDUGM_NAME.'.php') == true){
                                GMWDUGMAdmin::options_view($options);
                            }
                            ?>                              
						</div>
					
						<div class="wd-right">
							<div id="wd-options-map" style="width:600px; height:300px;"></div>
						</div>	
					</div>	
			  </div>            
				<input id="page" name="page" type="hidden" value="<?php echo GMWDHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="active_tab" name="active_tab" type="hidden" value="<?php echo GMWDHelper::get('active_tab'); ?>" />	
			</form>
		</div>
		<script>
            jQuery(".gmwd_edit_table [data-slider]").each(function () {
              var input = jQuery(this);
              jQuery("<span>").addClass("output").insertAfter(jQuery(this));  
            }).bind("slider:ready slider:changed", function (event, data) {   
              jQuery(this) .nextAll(".output:first").html(data.value.toFixed(1));   
            });
            gmwdSlider(this.jQuery || this.Zepto, jQuery(".gmwd_edit_table"));
            
			var mapWhellScrolling = Number(<?php echo $options->whell_scrolling;?>) == 1 ? true : false;
			var zoom = Number(<?php echo $options->zoom_level;?>);
			var mapDragable = Number(<?php echo $options->map_draggable;?>) == 1 ? true : false;
			var centerLat = Number(<?php echo $options->center_lat;?>);
			var centerLng = Number(<?php echo $options->center_lng;?>);
            var centerAddress = '<?php echo gmwd_get_option("center_address");?>';
			var map;
            var GMWD_URL = "<?php echo GMWD_URL; ?>";
            var GMWD_UPLOAD_URL = "<?php echo GMWD_UPLOAD_URL; ?>";
		</script>	
       
	<?php
	 
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