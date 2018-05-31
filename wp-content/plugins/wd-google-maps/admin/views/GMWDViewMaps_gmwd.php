<?php

class GMWDViewMaps_gmwd extends GMWDView{

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
		$rows = $this->model->get_rows();
		$page_nav = $this->model->page_nav();
		$search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'id');
		$order_class = 'manage-column column-title sorted ' . $asc_or_desc;
		
		$per_page = $this->model->per_page();
		$pager = 0;
      
	?>	
        
		<div class="gmwd">	      
			<form method="post" action="" id="adminForm">
            <?php wp_nonce_field('nonce_gmwd', 'nonce_gmwd'); ?>            
				<!-- header -->
				<h2>
					<img src="<?php echo GMWD_URL . '/images/icon-map.png';?>" width="30" style="vertical-align: middle;">
					<span><?php _e("Maps","gmwd");?></span>
					<a class="wd-btn wd-btn-primary wd-btn-icon wd-btn-addnew" href="admin.php?page=maps_gmwd&task=edit"><?php _e("Add new","gmwd");?></a>
				</h2>
				<!-- filters and actions -->
				<div class="wd_filters_actions wd-row wd-clear">
					<!-- filters-->
					<div class="wd-left">
						<?php echo GMWDHelper::search(__('Title',"gmwd"), $search_value, 'adminForm'); ?>
					</div>
					<!-- actions-->
					<div class="wd-right">
						<div class="wd-table gmwd_btns">
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-publish" onclick="gmwdFormInputSet('task', 'publish');gmwdFormInputSet('publish_unpublish', '1')"><?php _e("Publish","gmwd");?></button>
							</div>
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-unpublish" onclick="gmwdFormInputSet('task', 'publish');gmwdFormInputSet('publish_unpublish', '0')"><?php _e("Unpublish","gmwd");?></button>
							</div>	
                            <div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-dublicate" onclick="gmwdFormInputSet('task', 'dublicate');"><?php _e("Duplicate","gmwd");?></button>
							</div>
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary-red wd-btn-icon wd-btn-delete" onclick="if (confirm('<?php _e("Do you want to delete selected items?","gmwd"); ?>')) { gmwdFormInputSet('task', 'remove');} else { return false;}"><?php _e("Delete","gmwd");?></button>										  
							</div>							
						</div>
					</div>
				</div>
				<!-- pagination-->
				<div class="wd-right wd-clear">
					<?php GMWDHelper::html_page_nav($page_nav['total'], $pager++, $page_nav['limit'], 'adminForm', $per_page);?>
				</div>
				<!-- rows-->
				<table class="wp-list-table widefat fixed pages gmwd_list_table">
					<thead>
						<tr class="gmwd_alternate">
							<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
								<label class="screen-reader-text" for="cb-select-all-1"><?php _e("Select All","gmwd"); ?></label>
								<input id="cb-select-all-1" type="checkbox">
							</th>
							<th class="col <?php if ($order_by == 'id') {echo $order_class;} ?>" width="8%">
								<a onclick="gmwdFormInputSet('order_by', 'id');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span>ID</span><span class="sorting-indicator"></span>
								</a>
							</th>							

							<th class="col <?php if ($order_by == 'title') {echo $order_class;} ?>">
								<a onclick="gmwdFormInputSet('order_by', 'title');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'title') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Title","gmwd"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>	
							<th class="col">
                              <span><?php _e("Shortcode","gmwd"); ?></span><span class="sorting-indicator"></span>                           
							</th>
							<th class="col">                            
                                <span><?php _e("PHP Function","gmwd"); ?></span><span class="sorting-indicator"></span> 
                            </th>                          
							<th class="col <?php if ($order_by == 'published') {echo $order_class;} ?>" width="10%">
								<a onclick="gmwdFormInputSet('order_by', 'published');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'published') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Published","gmwd"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>								
						</tr>					
					</thead>

					<tbody>
					<?php 
						if(empty($rows ) == false){
							$iterator = 0;
							foreach($rows as $row){
								$alternate = $iterator%2 != 0 ? "class='gmwd_alternate'" : "";
								$published_image = (($row->published) ? 'publish-blue' : 'unpublish-blue');
								$published = (($row->published) ? 0 : 1);
						?>
								<tr id="tr_<?php echo $iterator; ?>" <?php echo $alternate; ?>>
									<th scope="row" class="check-column">
										<input type="checkbox" name="ids[]" value="<?php echo $row->id; ?>">
									</th>
									<td class="id column-id">
										<?php echo $row->id;?>
									</td>
									<td class="title column-title">
										<a href="admin.php?page=maps_gmwd&task=edit&id=<?php echo $row->id;?>">
											<?php echo $row->title;?>
										</a>
									</td>           
                                    <td class="shortcode column-shortcode">
                                        <input type="text" style="border: 1px solid #eee; padding: 1px 4px;background: rgba(208, 203, 203, 0.1);width: 240px;" value="<?php echo '[Google_Maps_WD id='.$row->shortcode_id.' map='.$row->id.']';?>" readonly onclick="jQuery(this).focus();jQuery(this).select();">
									</td>
                                    <td class="php_function column-php_function">
                                        <input type="text" style="border: 1px solid #eee; padding: 1px 4px;background: rgba(208, 203, 203, 0.1);" value="&#60;?php gmwd_map( <?php echo $row->shortcode_id; ?>, <?php echo $row->id; ?>); ?&#62;" readonly onclick="jQuery(this).focus();jQuery(this).select();">
									</td>                                    
														
									<td class="table_big_col" align="center">
										<a onclick="gmwdFormInputSet('task', 'publish');gmwdFormInputSet('publish_unpublish', '<?php echo $published ; ?>');gmwdFormInputSet('current_id', '<?php echo $row->id; ?>');document.getElementById('adminForm').submit();return false;" href="">
											<img src="<?php echo GMWD_URL . '/images/css/' . $published_image . '.png'; ?>"></img>
										</a>
									</td>
									
								</tr>
						<?php
								$iterator++;
								}
							}	
						?>
					</tbody>
				</table>
				
				<input id="page" name="page" type="hidden" value="<?php echo GMWDHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
				<input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />
				<input id="current_id" name="current_id" type="hidden" value="" />
				<input id="publish_unpublish" name="publish_unpublish" type="hidden" value="" />
			</form>
		</div>	

	<?php
	 
	}
	
	public function edit($id){
		
		$row = $this->model->get_row($id);
		$urls = $this->model->get_query_urls($id);
		
		$query_url_marker = $urls["marker"] ;
		$query_url_polygon = $urls["polygon"] ;
		$query_url_polyline = $urls["polyline"] ;
		$query_url_circle = $urls["circle"] ;
		$query_url_rectangle = $urls["rectangle"] ;
        $main_tabs = array("map" => __("Map","gmwd"), "settings" => __("Settings","gmwd"), "theme_preview" => __("Theme / Preview","gmwd"));
        $whitelist = array( '127.0.0.1', '::1' );
        $is_localhost = in_array( $_SERVER['REMOTE_ADDR'], $whitelist) ? 1 : 0;
        $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $is_localhost ? 1 : 0;
		?>
        <div class="gmwd_opacity_div">
            <div class="gmwd_opacity_div_loading"><img src="<?php echo GMWD_URL."/images/loading.gif";?>"></div>
        </div>
		<div class="gmwd_edit">  
        
            <h2>
				<img src="<?php echo GMWD_URL . '/images/icon-map.png';?>" width="30" style="vertical-align:middle;">
				<span>
					<?php 
						if($id == 0) {
							_e("Add Map","gmwd");
						}	
						else{
							_e("Edit Map","gmwd");
						}	
					?>
				</span>
	
			</h2>
			<form method="post" action="" id="adminForm" enctype="multipart/form-data">   
                <?php wp_nonce_field('nonce_gmwd', 'nonce_gmwd'); ?>            
                <div class="wd-main-map">
                <div class="gmwd_import_container" style="display:none;">
                    <div class="gmwd_close_icon" onclick="jQuery('.gmwd_import_container, .gmwd_opacity_div').hide();return false;">x</div>
                    <table class="gmwd_edit_table">
                        <tr>
                            <td><?php _e("Import file","gmwd");?>:</td>
                            <td>
                                <input type="file" name="import_overlays" id="import_overlays">                                
                             </td>
                         </tr>
                        <tr>                        
                            <td colspan="2">
                                <button class="wd-btn wd-btn-secondary" onclick="if(jQuery('[name=id]').val() == '') {alert('Please save map.'); jQuery('.gmwd_import_container, .gmwd_opacity_div').hide(); return false;}; gmwdFormSubmit('import');return false;"><?php _e("Import","gmwd");?></button>
                                <button class="wd-btn wd-btn-secondary" onclick="jQuery('.gmwd_import_container, .gmwd_opacity_div').hide();return false;"><?php _e("Cancel","gmwd");?></button>
                             </td>
                         </tr>
                    </table>                 
                </div> 
                <div class="wd-map-header">   
                    <!-- title row -->
                    <div class="wd-clear wd-row">
                        <div class="wd-left">
                            <div class="title-wrapper">
							<table>
								<tr>
									<td>
										<label for="title"><strong><?php _e("Map title","gmwd"); ?></strong></label>
										<span style="color:#FF0000;">*</span>
									</td>
									<td>
										<input type="text" name="title" class="wd-required" value="<?php echo $row->title;?>">
									</td>
								</tr>
							</table>       
                            </div>
                        </div>
                        <div class="wd-right">
                            <div class="wd-table gmwd_btns">
                                <div class="wd-cell wd-cell-valign-middle">
                                    <button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save" onclick="gmwdFormSubmit('save');return false;"><?php _e("Save","gmwd");?></button>
                                </div>
                                <div class="wd-cell wd-cell-valign-middle">
                                    <button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" onclick="gmwdFormSubmit('apply');return false;"><?php _e("Apply","gmwd");?></button>
                                </div>							
                                <div class="wd-cell wd-cell-valign-middle">
                                    <button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save2copy" onclick="gmwdFormSubmit('save2copy');return false;"><?php _e("Save as Copy","gmwd");?></button>
                                </div>	
                                <div class="wd-cell wd-cell-valign-middle">
                                    <a href="<?php echo $urls["export"];?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-export" download ><?php _e("Export","gmwd");?></a>
                                </div>
                                <div class="wd-cell wd-cell-valign-middle">
                                    <a href="<?php echo $urls["import"];?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-import" onclick="event.stopPropagation();jQuery('.gmwd_import_container, .gmwd_opacity_div').show();return false;"><?php _e("Import","gmwd");?></a>
                                </div>								
                                <div class="wd-cell wd-cell-valign-middle">
                                    <button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-cancel" onclick="gmwdFormSubmit('cancel');return false;"><?php _e("Cancel","gmwd");?></button>
                                </div>															
                            </div>
                        </div>
                    </div>
                    <!-- setting/map row -->
                    <div class="wd-clear wd-row">
                        <div class="wd-left">
                            <ul class="wd-tabs wd-clear">
                                <?php
                                foreach($main_tabs as $tab_key => $tab_title){
                                    $active_tab = GMWDHelper::get('active_main_tab', 'map') == $tab_key  ? 'wd-active-tab' : '';
                                ?>
                                    <li>
                                        <a href="#<?php echo $tab_key; ?>" class="wd-btn wd-btn-secondary wd-btn-icon wd-btn-<?php echo $tab_key; ?> <?php echo $active_tab; ?>"><?php echo $tab_title; ?></a>
                                    </li>
                                <?php
                                }
                                ?>                                 
                            </ul>
                        </div>
                        <div class="wd-right pois_bar">
                            <div class="wd-table">
                                <div class="wd-cell wd-cell-valign-middle">
                                    <button data-href="<?php echo $query_url_marker;?>" data-poi="markers" class="wd-btn wd-btn-primary wd-btn-icon-poi wd-btn-add-marker" onclick="gmwdOpenPoiForm(this);return false;"><?php _e("Add Marker","gmwd");?></button>
                                </div>
                                <div class="wd-cell wd-cell-valign-middle">
                                    <button data-href="<?php echo $query_url_circle;?>" data-poi="circles" class="wd-btn wd-btn-primary wd-btn-icon-poi wd-btn-add-circle" onclick="gmwdOpenPoiForm(this);return false;"><?php _e("Add Circle","gmwd");?></button>
                                </div>
                                <div class="wd-cell wd-cell-valign-middle">
                                    <button data-href="<?php echo $query_url_rectangle;?>" data-poi="rectangles" class="wd-btn wd-btn-primary wd-btn-icon-poi wd-btn-add-rectangle" onclick="gmwdOpenPoiForm(this);return false;"><?php _e("Add Rectangle","gmwd");?></button>
                                </div>	                    
                                <div class="wd-cell wd-cell-valign-middle">
                                    <button data-href="<?php echo $query_url_polygon;?>" data-poi="polygons" class="wd-btn wd-btn-primary wd-btn-icon-poi wd-btn-add-polygon" onclick="gmwdOpenPoiForm(this);return false;"><?php _e("Add Polygon","gmwd");?></button>
                                </div>							
                                <div class="wd-cell wd-cell-valign-middle">
                                    <button data-href="<?php echo $query_url_polyline;?>" data-poi="polylines" class="wd-btn wd-btn-primary wd-btn-icon-poi wd-btn-add-polyline" onclick="gmwdOpenPoiForm(this);return false;"><?php _e("Add Polyline","gmwd");?></button>
                                </div>											  
                                                                
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="wd-tabs-container">
					<!-- map -->
					<div id="map" class="wd-clear wd-row" <?php echo (GMWDHelper::get('active_main_tab', "map") == "map") ? '' : 'style="display:none;"'; ?> >
						<?php $this->display_map($row, $urls);?>			
					</div>                
					<!-- setting -->
					<div id="settings" class="wd-clear wd-row" <?php echo (GMWDHelper::get('active_main_tab') == "settings" ) ? '' : 'style="display:none;"'; ?>>
						<?php $this->display_settings($id);?>				
					</div>
					<!-- themes/previews -->
					<div id="theme_preview" class="wd-clear wd-row" <?php echo (GMWDHelper::get('active_main_tab') == "theme_preview" ) ? '' : 'style="display:none;"'; ?>>
						<?php $this->display_theme_preview($row);?>				
					</div>

				</div>
                <div class="title-wrapper published-wrapper" <?php echo (GMWDHelper::get('active_main_tab', 'map') == "map" ) ? '' : 'style="display:none;"'; ?>>
                    <table>
                        <tr>                        
                            <td><?php _e("Published:","gmwd"); ?></td>
                            <td>
                              <input type="radio" class="inputbox wd-form-field" id="published1" name="published" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
                              <label for="published1"><?php _e("Yes","gmwd"); ?></label>                                   
                              <input type="radio" class="inputbox wd-form-field" id="published0" name="published" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0"  >
                              <label for="published0"><?php _e("No","gmwd"); ?></label>

                            </td>
                        </tr>
                    </table>  
                </div>
                </div>
                <div id="wd-overlays" style="display:none" ></div>   
				<input id="page" name="page" type="hidden" value="<?php echo GMWDHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="id" name="id" type="hidden" value="<?php echo $row->id;?>" />
				<input id="id" name="shortcode_id" type="hidden" value="<?php echo $row->shortcode_id;?>" />
                <?php
                    $number_of_markers = count($row->markers);
                    $number_of_arrays = ceil($number_of_markers/300);
                    $marker_arrays = $number_of_arrays >1 ? array_chunk($row->markers,$number_of_arrays) : array($row->markers);
                    for($i=0; $i<count($marker_arrays);$i++){
                        echo "<input id='main_markers".$i."' name='main_markers".$i."' type='hidden' value='". json_encode($marker_arrays[$i])."' />";
                    }
                ?>
				
				<input id="circles" name="circles" type="hidden" value='<?php echo json_encode($row->circles);?>' />
				<input id="rectangles" name="rectangles" type="hidden" value='<?php echo json_encode($row->rectangles);?>' />
				<input id="polygons" name="polygons" type="hidden" value='<?php echo json_encode($row->polygons);?>' />
				<input id="polylines" name="polylines" type="hidden" value='<?php echo json_encode($row->polylines);?>' />	
                <input id="active_settings_tab" name="active_settings_tab" type="hidden" value="<?php echo GMWDHelper::get('active_settings_tab');?>" />	
                <input id="active_main_tab" name="active_main_tab" type="hidden" value="<?php echo GMWDHelper::get('active_main_tab');?>" />	
                <input id="active_poi_tab" name="active_poi_tab" type="hidden" value="<?php echo GMWDHelper::get('active_poi_tab');?>" />	
                <input id="markers_count" name="markers_count" type="hidden" value="<?php echo $number_of_arrays;?>" />	
			</form>
		</div>
        <script>
		var isHttps = <?php echo $is_https;?>;
		jQuery(".wd-settings-tabs-container [data-slider]").each(function () {
       
		  var input = jQuery(this);
		  jQuery("<span>").addClass("output").insertAfter(jQuery(this));  
		}).bind("slider:ready slider:changed", function (event, data) {   
		  jQuery(this) .nextAll(".output:first").html(data.value.toFixed(1));   
		});
		gmwdSlider(this.jQuery || this.Zepto, jQuery("#settings"));
		</script>	
		<?php
		
	}
    
	public function display_pois(){
		$id = (int)GMWDHelper::get('id');
		$row = $this->model->get_row($id);
		$urls = $this->model->get_query_urls($id);
        $overlays = array("markers" => __("Markers","gmwd"), "circles" => __("Circles","gmwd"), "rectangles" => __("Rectangles","gmwd"), "polygons" => __("Polygons","gmwd"), "polylines" => __("Polylines","gmwd"));
		?>
		<ul class="wd-pois-tabs wd-clear">
            <?php 
            foreach($overlays as $overlay => $overlay_title){
                $active_tab = GMWDHelper::get('active_poi_tab', 'wd-gm-markers') == "wd-gm-".$overlay ? 'wd-pois-active-tab wd-pois-active-wd-gm-'.$overlay.'-tab' : '';
            ?>
                <li>
                    <a href="#wd-gm-<?php echo $overlay;?>" class="wd-poi-tab wd-<?php echo $overlay;?>-tab <?php echo $active_tab; ?>" title="<?php echo $overlay_title; ?>">
                        <span><?php echo $overlay_title; ?></span>                       
                    </a>
                    
                </li>
            <?php
            }
            ?>		
		</ul>
		<div class="wd-pois-tabs-container">
            <?php
                
                foreach($overlays as $overlay  => $overlay_title){
                    $header_title = $overlay == "markers" ? __("Title","gmwd").' / '.__("Address","gmwd") : __("Title","gmwd");
                ?>
                    <div class="wd-pois-container wd-clear wd-row" id="wd-gm-<?php echo $overlay;?>" <?php echo (GMWDHelper::get('active_poi_tab',"wd-gm-markers" ) == "wd-gm-".$overlay ) ? '' : 'style="display:none;"'; ?>>	
                        <h4><?php echo $overlay_title; ?></h4>	
                        <div class="wd-filter-poi wd-clear">
                            <div class="wd-left">
                                <input type="text" class="filter_by_<?php echo $overlay;?>" placeholder="<?php _e("Filter by Address","gmwd");?>" data-type="<?php echo $overlay;?>" onkeypress="poiPaginationFilter(event, this);">
                            </div>
                            <div class="wd-right"> 
                                 <button data-href="<?php echo $urls[substr($overlay,0,-1)];?>" data-poi="<?php echo $overlay;?>" class="wd-btn wd-btn-secondary wd-btn-icon wd-btn-addnew-blue" onclick="gmwdOpenPoiForm(this);return false;"><?php _e("Add New","gmwd");?></button>
                                 <button data-poi="<?php echo $overlay;?>" class="wd-btn wd-btn-secondary wd-btn-icon wd-btn-publish-blue" onclick="publishPoi(this);return false;" data-published="0"><?php _e("Publish","gmwd");?></button>
                                 <button data-poi="<?php echo $overlay;?>" class="wd-btn wd-btn-secondary wd-btn-icon wd-btn-unpublish-blue" onclick="publishPoi(this);return false;" data-published="1"><?php _e("Unpublish","gmwd");?></button>                         
                                 <button  data-poi="<?php echo $overlay;?>" class="wd-btn wd-btn-secondary wd-btn-icon wd-btn-save2copy-blue" onclick="copyPoi(this);return false;"><?php _e("Duplicate","gmwd");?></button>
                                 <button data-poi="<?php echo $overlay;?>" class="wd-btn wd-btn-secondary wd-btn-icon wd-btn-delete-blue" onclick="if (confirm('<?php _e("Do you want to delete?","gmwd"); ?>')) { removePois(this);} ; return false;"><?php _e("Delete","gmwd");?></button>
                            </div>
                        </div>
                        <div class="gmwd_pois gmwd_<?php echo $overlay;?>">
                            <div class="wd-pois-header-row wd-table" >
                                <div class="wd-cell wd-cell-valign-middle">
                                    <input type="checkbox" name="check_all" class="check_all" value="" onchange="gmwdCheckAll(this);">																
                                </div>
                                <div class="wd-cell wd-cell-valign-middle">
                                    <span class="poi_number">#</span>
                                </div>            
                                <div class="wd-cell wd-cell-valign-middle">
                                   <?php echo $header_title;?>
                                </div>
                                <div class="poi_actions wd-cell wd-cell-valign-bottom">
                                    <?php _e("Actions","gmwd");?>
                                </div>				
                            </div>                
                            <?php
                                $this->display_poi_lists(0, "", $urls[substr($overlay,0,-1)], $overlay, 1, 1);
                                if(empty($row->$overlay) == false){
                                    $i = 1;
                                    foreach($row->$overlay as $poi){											
                                        $query_url_overlay_edit = add_query_arg(array("id"=>$poi->id), $urls[substr($overlay,0,-1)]);$title_and_address = $overlay == "markers" ? $poi->title.' / '.$poi->address : $poi->title;		
                                        $this->display_poi_lists($poi,$title_and_address, $query_url_overlay_edit, $overlay, $i);
                                        $i++;
                                                        
                                    }
                                }
                                else{
                                    echo "<span class='no_pois'>" . __("No","gmwd") ." ". $overlay_title .  "</span>";
                                }
                                $overlay_count = $overlay."_count";   
                            ?>
                            <div class="wd-pagination-poi" data-limit="20" data-type="<?php echo $overlay;?>" data-total="<?php echo $row->$overlay_count;?>" onclick="poiPaginationFilter(event, this);" <?php if($row->$overlay_count<=20) echo "style='display:none;'";?>>
                                <span><?php _e("Load More","gmwd");?></span>
                            </div>                            
                        </div>                       
                    </div>                
                
                <?php
                }
            ?>

        </div>
	
		<?php
		if(GMWDHelper::get("action")){
			die();
		}
	}


	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	private function display_map($row,$urls){
        $theme = $this->model->get_theme($row->theme_id);
    ?>
        <script>
            var centerLat = Number("<?php echo $row->center_lat; ?>");
            var centerLng = Number("<?php echo $row->center_lng; ?>");				
            var mapTheme = '<?php echo sanitize_text_field(stripslashes(htmlspecialchars_decode ($row->map_theme_code))) ;?>';

            var GMWD_URL = "<?php echo GMWD_URL;?>";
            var enableDirections = "<?php echo  $row->enable_directions;?>";
            var markerListingType = "<?php echo  $row->marker_listing_type;?>";
            var markerDefaultIcon = "<?php echo  gmwd_get_option("marker_default_icon");?>";
            
            var zoom = Number("<?php echo $row->zoom_level; ?>");
            var mapType = "<?php echo $row->type; ?>";
            var maxZoom = Number("<?php echo $row->max_zoom; ?>");
            var minZoom = Number("<?php echo $row->min_zoom; ?>");
            var mapWhellScrolling = Number("<?php echo $row->whell_scrolling; ?>") == 1 ? true : false;				
            var mapDragable = Number("<?php echo $row->map_draggable; ?>") == 1 ? true : false;				
            var mapDbClickZoom = Number("<?php echo $row->map_db_click_zoom; ?>") == 1 ? true : false;				
            var enableZoomControl = Number("<?php echo $row->enable_zoom_control; ?>") == 1 ? true : false;
            var enableMapTypeControl = Number("<?php echo $row->enable_map_type_control; ?>") == 1 ? true : false;			
            var mapTypeControlOptions = {};
            var enableScaleControl = Number("<?php echo $row->enable_scale_control; ?>") == 1 ? true : false;
            var enableStreetViewControl = Number("<?php echo $row->enable_street_view_control; ?>") == 1 ? true : false;
            var enableFullscreenControl = Number("<?php echo $row->enable_fullscreen_control; ?>") == 1 ? true : false;
            var enableRotateControl = Number("<?php echo $row->enable_rotate_control; ?>") == 1 ? true : false;
            var mapTypeControlPosition = Number("<?php echo $row->map_type_control_position; ?>");
            var fullscreenControlPosition = Number("<?php echo $row->fullscreen_control_position; ?>");	
            var zoomControlPosition = Number("<?php echo $row->zoom_control_position; ?>");
            var streetViewControlPosition = Number("<?php echo $row->street_view_control_position; ?>");
            var mapTypeControlStyle = Number("<?php echo $row->map_type_control_style; ?>");
            var mapBorderRadius = "<?php echo $row->border_radius; ?>";
            var enableBykeLayer =  Number("<?php echo $row->enable_bicycle_layer; ?>");	
            var enableTrafficLayer =  Number("<?php echo $row->enable_traffic_layer; ?>");				
            var enableTransitLayer =  Number("<?php echo $row->enable_transit_layer; ?>");	
            var geoRSSURL = "<?php echo $row->georss_url; ?>";	
            var KMLURL = "<?php echo $row->kml_url; ?>";	
            var fusionTableId = '<?php echo $row->fusion_table_id; ?>';	
            var fusionTableWhereField = "<?php echo $row->fusion_table_where_filed; ?>";	
            var fusionTableWhereOperator = "<?php echo $row->fusion_table_where_operator; ?>";	
            var fusionTableWhereValue = "<?php echo htmlspecialchars_decode(addslashes($row->fusion_table_where_value),ENT_QUOTES); ?>";
           
            var mapMarkers = JSON.parse('<?php echo json_encode($row->all_markers);?>');
            var mapCircles = JSON.parse('<?php echo json_encode($row->all_circles);?>');
            var mapRectangles = JSON.parse('<?php echo json_encode($row->all_rectangles);?>');
            var mapPolygons = JSON.parse('<?php echo json_encode($row->all_polygons);?>');
            var mapPolylines = JSON.parse('<?php echo json_encode($row->all_polylines);?>');
   
            
        </script>
       <style>
            .gmwd_advanced_info_window{
                background:#<?php echo $theme->advanced_info_window_background;?>!important;
                padding:10px;
                width:300px;
                
                overflow:auto;
                margin: 10px;
                box-shadow: 0 4px 2px -2px #000;
                z-index: 9999999 !important;
            }

            .gmwd_advanced_info_window div{
                margin-bottom:4px;
            }

            .gmwd_advanced_info_window_title{
                font-weight: bold;
                font-size: 16px;
                background:#<?php echo $theme->advanced_info_window_title_background_color;?>!important;
                padding:4px 5px;
                color:#<?php echo $theme->advanced_info_window_title_color;?>!important;
            }
            .gmwd_advanced_info_window_address{
                font-size: 14px;
                color:#<?php echo $theme->advanced_info_window_desc_color;?>!important;
            }
            .gmwd_advanced_info_window_description{
                font-size: 12px;
                color:#<?php echo $theme->advanced_info_window_desc_color;?>!important;
            }
            .gmwd_advanced_info_window_directions a{
                display:inline-block;
                padding:4px 20px;
                background:#<?php echo $theme->advanced_info_window_dir_background_color;?>!important;
                color:#<?php echo $theme->advanced_info_window_dir_color;?>!important;
                font-size: 14px;
                border-radius:<?php echo $theme->marker_listsing_inside_map_border_radius;?>px !important;
            }
       </style>
        
        <div class="wd-map-wrapper">         
            <div id="wd-map" class="wd-row" style="height:400px;"></div>             
            <div class="pois wd-row">
				<?php $this->display_pois();?>
            </div>           
        </div>
        <div class="hidden-editor-container" style="display:none;">
            <?php 
            wp_editor('', 'mdescription', array('teeny' => FALSE, 'textarea_name' => 'mdescription', 'media_buttons' => FALSE, 'textarea_rows' => 10));
            ?>
        </div>
    <?php
             	
	}
	
	
	private function display_poi_lists($poi, $poi_name, $url, $poi_type, $poi_number, $template = 0){
   
		if($template == 0){
            $published_image = (($poi->published) ? 'publish-blue' : 'unpublish-blue');
            $published = (($poi->published) ? __("Published","gmwd") : __("Unublished","gmwd"));

		?>
			<div class="wd-pois-row wd-table" data-id="poiId_<?php echo $poi->id;?>" data-poi="<?php echo $poi_type;?>">
                <div class="wd-cell wd-cell-valign-middle">
                    <input type="checkbox" name="poi_ids" value="<?php echo $poi->id; ?>" data-poi="<?php echo $poi_type;?>" >																
				</div>
				<div class="wd-cell wd-cell-valign-middle">
					<span class="poi_number"><?php echo $poi_number; ?>.</span>
				</div>
				<div class="wd-cell wd-cell-valign-middle">
					<span class="poi_title"><?php echo htmlspecialchars_decode(str_replace("@@@",'"',$poi_name)); ?></span>
                    <?php
                        if(defined('GMWDUGM_NAME') && isset($poi->user_gen) && $poi->user_gen == 1){  
                            if(isset($poi->user_id) && $poi->user_id != 0){     
                                $user = get_userdata($poi->user_id);
                                $user_name = ($user->first_name || $user->last_name) ? $user->first_name." ".$user->last_name : $user->user_login;
                                echo '<span class="gmwd_ugm_user"><img src="'.GMWDUGM_URL.'/images/ugm.png"><small>'.$user_name.'</small></span>';
                            }
                            else{
                                echo '<span class="gmwd_ugm_user"><img src="'.GMWDUGM_URL.'/images/ugm.png"><small>'.__("Guest","gmwd").'</small></span>';
                            }
                         }   
                    ?>
				</div>
				<div class="poi_actions wd-cell wd-cell-valign-bottom">
                    <img src="<?php echo GMWD_URL . '/images/css/' . $published_image . '.png'; ?>" class="poi_published" title="<?php echo $published;?>" onclick="publishPoi(this);" data-published="<?php echo $poi->published;?>" data-poi="<?php echo $poi_type;?>"></img>
					<button title="<?php _e("Edit","gmwd");?>" class="wd-edit-poi" onclick="editPoi(this);return false;" data-url="<?php echo $url;?>" data-href="<?php echo $url;?>" data-poi="<?php echo $poi_type;?>" ></button>
					<button  title="<?php _e("Remove","gmwd");?>" class="wd-delete-poi" onclick="if (confirm('<?php _e("Do you want to delete?","gmwd"); ?>')) { removePoi(this,true);} ; return false;" data-poi="<?php echo $poi_type;?>"></button>
				</div>
				<input type="hidden" name="<?php echo $poi_type.'_'.$poi->id;?>" value='<?php echo htmlspecialchars(json_encode($poi));?>' class="poi_data">	
			</div>
		<?php
		}
		else{
			?>
			<div class="wd-pois-row wd-table wd-template" >
                <div class="wd-cell wd-cell-valign-middle">
                    <input type="checkbox" name="poi_ids" value=""  data-poi="<?php echo $poi_type;?>">																
				</div>
				<div class="wd-cell wd-cell-valign-middle">
					<span class="poi_number"></span>
				</div>            
				<div class="wd-cell wd-cell-valign-middle">
					<span class="poi_title"></span>
				</div>
				<div class="poi_actions wd-cell wd-cell-valign-bottom">
                    <img src="" class="poi_published" title="" onclick="publishPoi(this);" data-published="" data-poi="<?php echo $poi_type;?>"></img>                
					<button  title="<?php _e("Edit","gmwd");?>" class="wd-edit-poi" onclick="editPoi(this);return false;" data-url="<?php echo $url;?>" data-href="<?php echo $url;?>" data-poi="<?php echo $poi_type;?>"  ></button>
					<button  title="<?php _e("Remove","gmwd");?>"  class="wd-delete-poi"  onclick="if (confirm('<?php _e("Do you want to delete?","gmwd"); ?>')) { removePoi(this,true);} ; return false;" data-poi="<?php echo $poi_type;?>"></button>
				</div>
				<input type="hidden" name="<?php echo $poi_type."_tmp";?>" value="" class="poi_data">					
			</div>
		<?php			
		}
	}
	
    private function display_theme_preview($row){
        $themes = $this->model->get_themes();
        $url = admin_url( 'index.php?page=gmwd_preview');
    ?>
        <div class="gmwd">
            <h4><b><?php _e("Select Theme","gmwd");?></b></h4>
            <div class="wd-clear static-maps">

                <?php
                    $i=0;
                    $map_api_key = gmwd_get_option("map_api_key") ?  "key=" . gmwd_get_option("map_api_key")."&" : "";
                    foreach($themes as $theme){
                ?>  
                    <div class="wd-left <?php echo $row->theme_id == $theme->id ? "map_theme_img_active" : "";?>" style="width:250px; margin-right: 20px; border:3px solid #fff;">

                        <img src="<?php echo "https://maps.googleapis.com/maps/api/staticmap?".$map_api_key."size=600x300&zoom=13&center=".gmwd_get_option("center_address").$theme->image ; ?>" class="select_map_theme" onclick="gmwdOnChnageMapTheme(this);" >
                        <input type="radio" name="theme_id" id="theme_id<?php echo $theme->id;?>" value="<?php echo $theme->id;?>" <?php echo ($row->theme_id == $theme->id) ? "checked" : "";?>>
                        <input type="hidden" class="theme_code"  value="<?php echo $theme->map_style_code;?>">
                        <div class="wd-table gmwd_theme_title">
                            <div class="wd-cell wd-cell-valign-middle theme-title"><?php echo $theme->title; ?></div>
                            <div class="wd-cell wd-cell-valign-middle wd-text-right gmwd_theme_manage"><a href="admin.php?page=themes_gmwd&task=edit&id=<?php echo $theme->id;?>" target="_blank" title="<?php _e("Manage","gmwd");?>"></a></div>
                        </div>
                    </div>
                <?php
                    }
                ?>            
            </div>   
        </div>
      
    <?php
        if($row->id){          
            $url = add_query_arg(array("map_id" => $row->id, "f_p" => 1), $url);
            ?>
            <div class="gmwd">
                <h4><b><?php _e("Preview","gmwd"); ?></b></h4>
                <div style="padding:20px; border:1px solid #eee">
                    <iframe src="<?php echo add_query_arg(array("theme_id" => $row->theme_id), $url);?>" style="width:100%;" scrolling="yes" onload="this.style.height = this.contentWindow.document.body.scrollHeight+40 + 'px'" id="gmwd_preview_iframe"></iframe>
                </div>
            </div>

            <?php
        }
        else{
        ?>
        <div class="gmwd">
           <h4><b><?php _e("Please Save Your Changes to Preview the Map.","gmwd"); ?></b></h4>
        </div>
        <?php    
        }
        ?>
            <script>
                var previewIframeURL = '<?php echo $url;?>';
            </script>
        <?php
    }


    private function display_settings($id){
		$row = $this->model->get_row($id);
		$lists = $this->model->get_lists();
		?>
		<div class="wd-row wd-table" >
			<div class="wd-cell wd-cell-valign-top wd-settings-tabs-wrapper">
				<ul class="wd-settings-tabs wd-clear">
					<li>
						<a href="#settings-general" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-general" || GMWDHelper::get('active_settings_tab') == "" ) ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("General","gmwd");?></a>
					</li>
					<li>
						<a href="#settings-controls" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-controls") ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("Controls","gmwd");?></a>
					</li>
					<li>
						<a href="#settings-layers" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-layers") ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("Layers","gmwd");?></a>
					</li>						
					<li>
						<a href="#settings-directions" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-directions") ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("Directions","gmwd");?></a>
					</li>
					<li>
						<a href="#settings-store-locator" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-store-locator") ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("Store Locator","gmwd");?></a>
					</li>
					<li>
						<a href="#settings-marker-listing" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-marker-listing") ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("Marker Listing","gmwd");?></a>
					</li>
                    <li>             
						<a href="#settings-marker_category_filter	" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-marker_category_filter") ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("Marker Category Filter","gmwd");?></a>
					</li>                     
					<li>
						<a href="#settings-advanced" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-advanced") ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("Advanced Settings","gmwd");?></a>
					</li>					
                   <?php if(defined('GMWDMC_NAME') && is_plugin_active(GMWDMC_NAME.'/'.GMWDMC_NAME.'.php')  == true){
                    ?>
                        <li>
                            <a href="#settings-marker-clustering" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-marker-clustering") ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("Marker Clustering","gmwd");?></a>
                        </li>
                    <?php
                    }
                    ?>
                    <?php if(defined('GMWDUGM_NAME') && is_plugin_active(GMWDUGM_NAME.'/'.GMWDUGM_NAME.'.php') == true){
                    ?>
                        <li>
                            <a href="#settings-user-gen-markers" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-user-gen-markers") ? 'class="wd-settings-active-tab"' : ''; ?> ><?php _e("User Generated Markers","gmwd");?></a>
                        </li>
                    <?php
                    }
                    ?> 					
				</ul>
			</div>
			<div class="wd-cell wd-cell-valign-top wd-settings-tabs-container">
				<div class="wd-clear">
					<div id="settings-general" class="wd-left wd-settings-container" <?php echo (GMWDHelper::get('active_settings_tab') == "settings-general" || GMWDHelper::get('active_settings_tab') == "" ) ? '' : 'style="display:none;"'; ?>>
						<?php $this->settings_general($row, $lists); ?>
					</div>
					<div id="settings-controls" class="wd-left wd-settings-container" <?php echo GMWDHelper::get('active_settings_tab') == "settings-controls"  ? '' : 'style="display:none;"'; ?>>
						<?php $this->settings_controls($row, $lists); ?>
					</div>						

					<div id="settings-directions" class="wd-left wd-settings-container" <?php echo GMWDHelper::get('active_settings_tab') == "settings-directions"  ? '' : 'style="display:none;"'; ?>>
						<?php $this->settings_directions($row, $lists); ?>
					</div>
					<div id="settings-store-locator" class="wd-left wd-settings-container" <?php echo GMWDHelper::get('active_settings_tab') == "settings-store-locator"  ? '' : 'style="display:none;"'; ?>>
						<?php $this->settings_store_locator($row, $lists); ?>
					</div>	
					<div id="settings-layers" class="wd-left wd-settings-container" <?php echo GMWDHelper::get('active_settings_tab') == "settings-layers"  ? '' : 'style="display:none;"'; ?>>
						<?php $this->settings_layers($row, $lists); ?>
					</div>	
					<div id="settings-marker-listing" class="wd-left wd-settings-container" <?php echo GMWDHelper::get('active_settings_tab') == "settings-marker-listing"  ? '' : 'style="display:none;"'; ?>>
						<?php $this->settings_marker_listing($row, $lists); ?>
					</div>
					<div id="settings-marker_category_filter" class="wd-left wd-settings-container" <?php echo GMWDHelper::get('active_settings_tab') == "settings-marker_category_filter"  ? '' : 'style="display:none;"'; ?>>
						<?php $this->settings_marker_category_filter($row, $lists); ?>
					</div>	                       
					<div id="settings-advanced" class="wd-left wd-settings-container" <?php echo GMWDHelper::get('active_settings_tab') == "settings-advanced"  ? '' : 'style="display:none;"'; ?>>
						<?php $this->settings_advanced($row, $lists); ?>

					</div> 
                    <?php if(defined('GMWDMC_NAME') && is_plugin_active(GMWDMC_NAME.'/'.GMWDMC_NAME.'.php')  == true){
                    ?>
                        <div id="settings-marker-clustering" class="wd-left wd-settings-container" <?php echo GMWDHelper::get('active_settings_tab') == "settings-marker-clustering"  ? '' : 'style="display:none;"'; ?>>
                            <?php GMWDMCAdmin::map_settings_view($row); ?>
                        </div>                    
                    <?php
                    }
                    ?>                               				
                    <?php if(defined('GMWDUGM_NAME') && is_plugin_active(GMWDUGM_NAME.'/'.GMWDUGM_NAME.'.php') == true){
                    ?>
                        <div id="settings-user-gen-markers" class="wd-left wd-settings-container" <?php echo GMWDHelper::get('active_settings_tab') == "settings-user-gen-markers"  ? '' : 'style="display:none;"'; ?>>
                            <?php GMWDUGMAdmin::map_settings_view($row); ?>
                        </div>                    
                    <?php
                    }
                    ?>                    
					<div class="wd-right">
						<div id="wd-map3" class="wd_map gmwd_follow_scroll" style="height:400px;width:472px;"></div>
					</div>					
				</div>					
													
			</div>						
		</div>
		<?php
	}
	private function settings_general($row, $lists){	

	?>
		<table class="settings_table">
			<tr>
				<td><label for="type" title="<?php _e("Select the map type you want to use.","gmwd");?>"><?php _e("Map Type","gmwd");?>:</label></td>
				<td>
					<select name="type" id="type">
						<?php 
							foreach($lists["map_types"] as $key => $value){
								$selected = $row->type ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr>			
			<tr>
				<td><label for="width" title="<?php _e("Determine the width of your map in percentages or pixel values.","gmwd");?>"><?php _e("Width","gmwd");?>:</label></td>
				<td>
					<input type="number" name="width" id="width" value="<?php echo $row->width;?>">
					<select name="width_percent" id="width_percent">
						<option value="px" <?php if($row->width_percent == "px") echo "selected";?>>px</option>	
						<option value="%" <?php if($row->width_percent == "%") echo "selected";?>>%</option>	
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="height" title="<?php _e("Determine the height of your map in percentages or pixel values.","gmwd");?>"><?php _e("Height","gmwd");?>:</label></td>
				<td>
					<input type="number" name="height" id="height" value="<?php echo $row->height;?>"> px
				</td>
			</tr>	
			<tr>
				<td><label for="address" title="<?php _e("Set map center location.","gmwd");?>"><?php _e("Center address","gmwd");?>:</label></td>
				<td>
                    <input type="text" name="center_address" id="address" value="<?php echo $row->center_address;?>" autocomplete="off" ><br>                   
                     <small><em><?php _e("Or Right Click on the Map.","gmwd");?></em></small>
                </td>
			</tr>
			<tr>
				<td><label for="center_lat" title="<?php _e("Set map center latitude if not specified automatically.","gmwd");?>"><?php _e("Center Lat","gmwd");?>:</label></td>
				<td><input type="text" name="center_lat" id="center_lat" value="<?php echo $row->center_lat;?>"></td>
			</tr>
			<tr>
				<td><label for="center_lng" title="<?php _e("Set map center longitude if not specified automatically.","gmwd");?>"><?php _e("Center Lng","gmwd");?>:</label></td>
				<td><input type="text" name="center_lng" id="center_lng" value="<?php echo $row->center_lng;?>"></td>
			</tr>			
			<tr>
				<td><label for="zoom_level" title="<?php _e("Set overall zoom level for your Google Map.","gmwd");?>"><?php _e("Zoom Level","gmwd");?>:</label></td>
				<td><input type="text" name="zoom_level" id="zoom_level" value="<?php echo $row->zoom_level;?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="<?php echo implode(",",range(0,22)); ?>"></td>
			</tr>
			<tr>
				<td><label for="min_zoom" title="<?php _e("Set a minimum zoom level imaginary for your Google Map.","gmwd");?>"><?php _e("Minimum zoom","gmwd");?>:</label></td>
				<td><input type="text" name="min_zoom" id="min_zoom" value="<?php echo $row->min_zoom;?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="<?php echo implode(",",range(0,22)); ?>"></td>
			</tr>
			<tr>
				<td><label for="max_zoom" title="<?php _e("Set a maximum zoom level imaginary for your Google Map.","gmwd");?>"><?php _e("Maximum zoom","gmwd");?>:</label></td>
				<td><input type="text" name="max_zoom" id="max_zoom" value="<?php echo $row->max_zoom;?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="<?php echo implode(",",range(0,22)); ?>"></td>
			</tr>
			<tr>
				<td><label title="<?php _e("Enable or disable mouse scroll-wheel scaling with Google Maps.","gmwd");?>"><?php _e("Wheel Scrolling","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="whell_scrolling0" name="whell_scrolling" <?php echo (($row->whell_scrolling) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="whell_scrolling0"><?php _e("Off","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="whell_scrolling1" name="whell_scrolling" <?php echo (($row->whell_scrolling) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="whell_scrolling1"><?php _e("On","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label title="<?php _e("Enable or disable Google Maps dragging on your website.","gmwd");?>"><?php _e("Map Draggable","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="map_draggable0" name="map_draggable" <?php echo (($row->map_draggable) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="map_draggable0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="map_draggable1" name="map_draggable" <?php echo (($row->map_draggable) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="map_draggable1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label title="<?php _e("Enable or disable Google Maps double-click zoom.","gmwd");?>"><?php _e("Map Double-Click Zoom","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="map_db_click_zoom1" name="map_db_click_zoom" <?php echo (($row->map_db_click_zoom) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="map_db_click_zoom1"><?php _e("Off","gmwd"); ?></label>
                  
				  <input type="radio" class="inputbox" id="map_db_click_zoom0" name="map_db_click_zoom" <?php echo (($row->map_db_click_zoom) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="map_db_click_zoom0"><?php _e("On","gmwd"); ?></label>

				</td>
			</tr>
			<tr>
				<td><label for="map_alignment" title="<?php _e("Select the alignment of your map.","gmwd");?>"><?php _e("Alignment","gmwd");?>:</label></td>
				<td>
					<select name="map_alignment" id="map_alignment">
						<?php 
							foreach($lists["map_alignment"] as $key => $value){
								$selected = $row->map_alignment ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr>			
		</table>

	
	<?php		
	}
	
	private function settings_controls($row, $lists){
	?>
		<table class="settings_table">						
			<tr>
				<td><label title="<?php _e("The Zoom control displays '+' and '-' buttons, to control the zoom level of the map.","gmwd");?>"><?php _e("Enable Zoom Control","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_zoom_control0" name="enable_zoom_control" <?php echo (($row->enable_zoom_control) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_zoom_control0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_zoom_control1" name="enable_zoom_control" <?php echo (($row->enable_zoom_control) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_zoom_control1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>		
			<tr>
				<td><label title="<?php _e("Select the zoom control position on your Google Map.","gmwd");?>"><?php _e("Zoom Control Position","gmwd"); ?>:</label></td>
				<td>
					<select name="zoom_control_position" id="zoom_control_position">
						<?php 
							foreach($lists["map_controls_positions"] as $key => $value){
								$selected = $row->zoom_control_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr>			
			<tr>
				<td><label title="<?php _e("The Map Type control lets the user choose a map type (such as ROADMAP or SATELLITE).","gmwd");?>"><?php _e("Enable Map Type Control","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_map_type_control0" name="enable_map_type_control" <?php echo (($row->enable_map_type_control) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_map_type_control0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_map_type_control1" name="enable_map_type_control" <?php echo (($row->enable_map_type_control) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_map_type_control1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label title="<?php _e("Select the Map Type control position on your Google Map.","gmwd");?>"><?php _e("Map Type Control Position","gmwd"); ?>:</label></td>
				<td>
					<select name="map_type_control_position" id="map_type_control_position">
						<?php 
							foreach($lists["map_controls_positions"] as $key => $value){
								$selected = $row->map_type_control_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr>				
			<tr>
				<td><label title="<?php _e("Select Map Type Control Style.","gmwd");?>"><?php _e("Map Type Control Style","gmwd"); ?>:</label></td>
				<td>
					<select name="map_type_control_style" id="map_type_control_style">
						<?php 
							foreach($lists["map_type_control_styles"] as $key => $value){
								$selected = $row->map_type_control_style ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr>			
			<tr>
				<td><label title="<?php _e("The Scale control displays a map scale element.","gmwd");?>"><?php _e("Enable Scale Control","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_scale_control0" name="enable_scale_control" <?php echo (($row->enable_scale_control) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_scale_control0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_scale_control1" name="enable_scale_control" <?php echo (($row->enable_scale_control) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_scale_control1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>	
			<tr>
				<td><label title="<?php _e("The Street View control contains a Pegman icon which can be dragged onto the map to enable Street View.","gmwd");?>"><?php _e("Enable Street View Control","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_street_view_control0" name="enable_street_view_control" <?php echo (($row->enable_street_view_control) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_street_view_control0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_street_view_control1" name="enable_street_view_control" <?php echo (($row->enable_street_view_control) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_street_view_control1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label title="<?php _e("Select the Street View control position on your Google Map.","gmwd");?>"><?php _e("Street View Control Position","gmwd"); ?>:</label></td>
				<td>
					<select name="street_view_control_position" id="street_view_control_position">
						<?php 
							foreach($lists["map_controls_positions"] as $key => $value){
								$selected = $row->street_view_control_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr>				
			<tr>
				<td><label title="<?php _e("Fullscreen Control allows the user to open the map in fullscreen mode.","gmwd");?>"><?php _e("Fullscreen Control","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_fullscreen_control0" name="enable_fullscreen_control" <?php echo (($row->enable_fullscreen_control) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_fullscreen_control0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_fullscreen_control1" name="enable_fullscreen_control" <?php echo (($row->enable_fullscreen_control) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_fullscreen_control1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label title="<?php _e("Select the Fullscreen control position on your Google Map.","gmwd");?>"><?php _e("Fullscreen Control Position","gmwd"); ?>:</label></td>
				<td>
					<select name="fullscreen_control_position" id="fullscreen_control_position">
						<?php 
							foreach($lists["map_controls_positions"] as $key => $value){
								$selected = $row->fullscreen_control_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}						
						?>
					</select>
				</td>
			</tr>	            
			
			<tr>
				<td><label title="<?php _e("The Rotate control contains a small circular icon which allows you to rotate maps containing oblique imagery.","gmwd");?>"><?php _e("Enable Rotate Control","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_rotate_control0" name="enable_rotate_control" <?php echo (($row->enable_rotate_control) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_rotate_control0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_rotate_control1" name="enable_rotate_control" <?php echo (($row->enable_rotate_control) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_rotate_control1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>			
		</table>

	<?php	
		
	}
	
	private function settings_directions($row, $lists){
        $whitelist = array( '127.0.0.1', '::1' );
        $is_localhost = in_array( $_SERVER['REMOTE_ADDR'], $whitelist) ? 1 : 0;
        $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $is_localhost ? 1 : 0;	
	?>
		<table class="settings_table">
            <?php if($is_https == 0){
            ?>
                <tr class="geolocation_msg">
                    <td colspan="2">     
                        <div class="gmwd_notice_err"><?php _e("Geolocation API call must be served from a secure context such as HTTPS.","gmwd");?></div>
                    </td>
                </tr>
            <?php
            }
            ?>            
			<tr>
				<td><label title="<?php _e("Enable direction winow for Map.","gmwd");?>"><?php _e("Enable Directions","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_directions0" name="enable_directions" <?php echo (($row->enable_directions) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_directions0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_directions1" name="enable_directions" <?php echo (($row->enable_directions) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_directions1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label for="directions_header_title" title="<?php _e("Set the header title of your directions window.","gmwd");?>"><?php _e("Header Title","gmwd");?>:</label></td>
				<td>
                    <input type="text" name="directions_header_title" id="directions_header_title" value="<?php echo $row->directions_header_title;?>">                
                </td>
			</tr>            
			<tr>
				<td><label title="<?php _e("Showcase the direction window.","gmwd");?>"><?php _e("Directions Window Open","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="directions_window_open0" name="directions_window_open" <?php echo (($row->directions_window_open) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="directions_window_open0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="directions_window_open1" name="directions_window_open" <?php echo (($row->directions_window_open) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="directions_window_open1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>			
			<tr>
				<td><label for="directions_window_width" title="<?php _e("Set a width for direction window.","gmwd");?>"><?php _e("Directions Window Width","gmwd");?>:</label></td>
				<td>
                    <input type="number" name="directions_window_width" id="directions_window_width" value="<?php echo $row->directions_window_width;?>" style="width:100px;vertical-align: bottom;">
                    <select name="directions_window_width_unit" id="directions_window_width_unit">
						<option value="px" <?php if($row->directions_window_width_unit == "px") echo "selected";?>>px</option>	
						<option value="%" <?php if($row->directions_window_width_unit == "%") echo "selected";?>>%</option>	
					</select>
                </td>
			</tr>	
			<tr>
				<td><label title="<?php _e("Select the direction window position.","gmwd");?>"><?php _e("Direction Window Position","gmwd"); ?>:</label></td>
				<td>
					<select name="direction_position" id="direction_position">
						<?php 
							foreach($lists["map_direction_positions"] as $key => $value){
								$selected = $row->direction_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}						
						?>
					</select>
				</td>
			</tr>			
		</table>	
	<?php	
	}
	private function settings_store_locator($row, $lists){
        $whitelist = array( '127.0.0.1', '::1' );
        $is_localhost = in_array( $_SERVER['REMOTE_ADDR'], $whitelist) ? 1 : 0;
        $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $is_localhost ? 1 : 0;	    
	?>
		<table class="settings_table">
            <?php if($is_https == 0){
            ?>
                <tr class="geolocation_msg">
                    <td colspan="2">     
                        <div class="gmwd_notice_err"><?php _e("Geolocation API call must be served from a secure context such as HTTPS.","gmwd");?></div>
                    </td>
                </tr>
            <?php
            }
            ?>         
			<tr>
				<td><label title="<?php _e("Choose whether to display the store locator, including panels or not.","gmwd");?>"><?php _e("Enable Store Locator","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_store_locator0" name="enable_store_locator" <?php echo (($row->enable_store_locator) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_store_locator0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_store_locator1" name="enable_store_locator" <?php echo (($row->enable_store_locator) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_store_locator1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label for="store_locator_header_title" title="<?php _e("Set the header title of your store locator window.","gmwd");?>"><?php _e("Header Title","gmwd");?>:</label></td>
				<td>
                    <input type="text" name="store_locator_header_title" id="store_locator_header_title" value="<?php echo $row->store_locator_header_title;?>">                
                </td>
			</tr>             
			<tr>
				<td><label for="store_locator_window_width" title="<?php _e("Set the window width of your store locator.","gmwd");?>"><?php _e("Window Width","gmwd");?>:</label></td>
				<td>
                    <input type="number" name="store_locator_window_width" id="store_locator_window_width" value="<?php echo $row->store_locator_window_width;?>" style="width:100px;vertical-align: bottom;">
                    <select name="store_locator_window_width_unit" id="store_locator_window_width_unit">
						<option value="px" <?php if($row->store_locator_window_width_unit == "px") echo "selected";?>>px</option>	
						<option value="%" <?php if($row->store_locator_window_width_unit == "%") echo "selected";?>>%</option>	
					</select>
                
                </td>
			</tr>	
			<tr>
				<td><label title="<?php _e("Set the window position of your store locator.","gmwd");?>"><?php _e("Window Position","gmwd"); ?>:</label></td>
				<td>
					<select name="store_locator_position" id="store_locator_position">
						<?php 
							foreach($lists["map_direction_positions"] as $key => $value){
								$selected = $row->store_locator_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}						
						?>
					</select>
				</td>
			</tr>				
			<!--<tr>
				<td><label for="restrict_to_country"><?php _e("Restrict to Country","gmwd");?>:</label></td>
				<td><input type="text" name="restrict_to_country" id="restrict_to_country" value="<?php echo $row->restrict_to_country;?>"></td>
			</tr>	-->			
			<tr>
				<td><label for="distance_in" title="<?php _e("Set metric conversions for store locator.","gmwd");?>"><?php _e("Distance In","gmwd");?>:</label></td>
				<td>
					<select name="distance_in" id="distance_in">
						<option value="km" <?php if($row->distance_in == "km") echo "selected";?>><?php _e("Kilometers","gmwd");?></option>
						<option value="mile" <?php if($row->distance_in == "mile") echo "selected";?>><?php _e("Miles","gmwd");?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label title="<?php _e("Choose whether to display category filter or not.","gmwd");?>"><?php _e("Show Categories","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_store_locator_cats0" name="enable_store_locator_cats" <?php echo (($row->enable_store_locator_cats) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_store_locator_cats0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_store_locator_cats1" name="enable_store_locator_cats" <?php echo (($row->enable_store_locator_cats) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_store_locator_cats1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
            <tr>
                <td><label for="circle_line_width" title="<?php _e("Scale the area of the circle of store locator on the map.","gmwd");?>"><?php _e("Circle Line Width","gmwd");?>:</label></td>
                <td><input type="text" name="circle_line_width" id="circle_line_width" value="<?php echo $row->circle_line_width;?>"data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="<?php echo implode(",",range(1,50)); ?>" ></td>             
            </tr>
            <tr>
                <td><label for="circle_line_color" title="<?php _e("Select the color of the circle line of store locator on the map.","gmwd");?>"><?php _e("Circle Line Color","gmwd");?>:</label></td>
                <td><input type="text" name="circle_line_color" id="circle_line_color" value="<?php echo $row->circle_line_color;?>" class="color" ></td> 
            </tr>
            <tr>
				<td><label for="circle_line_opacity" title="<?php _e("Select the circle line opacity for store locator.","gmwd");?>"><?php _e("Circle Line Opacity","gmwd");?>:</label></td>
                <td><input type="text" name="circle_line_opacity" id="circle_line_opacity"  value="<?php echo $row->circle_line_opacity;?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1"></td>              
            </tr>
            <tr>
                <td><label for="circle_fill_color" title="<?php _e("Select the color fill of the circle of store locator on the map.","gmwd");?>"><?php _e("Circle Fill Color","gmwd");?>:</label></td>
                <td><input type="text" name="circle_fill_color" id="circle_fill_color" value="<?php echo $row->circle_fill_color;?>" class="color" ></td>    
            </tr>
            <tr>
                <td><label for="circle_fill_opacity" title="<?php _e("Select the circle fill opacity for store locator.","gmwd");?>"><?php _e("Circle Fill Opacity","gmwd");?>:</label></td>
                <td><input type="text" name="circle_fill_opacity" id="circle_fill_opacity" value="<?php echo $row->circle_fill_opacity;?>"data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1"  ></td>               
            </tr>
	
		</table>	
	<?php	
	}
	
	private function settings_layers($row, $lists){
        $lists = $this->model->get_lists();

	?>
		<table class="settings_table">
			<tr>
				<td><label title="<?php _e("Enable/Disable bicycle layer. The Bicycling layer object renders a layer of bike paths and/or bicycle-specific overlays into a common layer.","gmwd");?>"><?php _e("Enable Bicycle Layer","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_bicycle_layer0" name="enable_bicycle_layer" <?php echo (($row->enable_bicycle_layer) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_bicycle_layer0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_bicycle_layer1" name="enable_bicycle_layer" <?php echo (($row->enable_bicycle_layer) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_bicycle_layer1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label title="<?php _e("Enable/Disable Traffic layer. It displays traffic conditions on the map.","gmwd");?>"><?php _e("Enable Traffic Layer","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_traffic_layer0" name="enable_traffic_layer" <?php echo (($row->enable_traffic_layer) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_traffic_layer0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_traffic_layer1" name="enable_traffic_layer" <?php echo (($row->enable_traffic_layer) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_traffic_layer1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>	
			<tr>
				<td><label title="<?php _e("Enable/Disable The Transit layer. It displays the public transport network of your city on the map.","gmwd");?>"><?php _e("Enable Transit Layer","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_transit_layer0" name="enable_transit_layer" <?php echo (($row->enable_transit_layer) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_transit_layer0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_transit_layer1" name="enable_transit_layer" <?php echo (($row->enable_transit_layer) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_transit_layer1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label for="georss_url" title="<?php _e("Display GeoRSS elements on Maps.","gmwd");?>"><?php _e("GeoRSS URL","gmwd");?>:</label></td>
				<td><input type="text" name="georss_url" id="georss_url" value="<?php echo $row->georss_url;?>"></td>
			</tr>
			<tr>
				<td><label for="kml_url" title="<?php _e("Display KmlLayer feature details on your Map, whose constructor takes the URL of a publicly accessible KML file.","gmwd");?>"><?php _e("KML URL","gmwd");?>:</label></td>
				<td><input type="text" name="kml_url" id="kml_url" value="<?php echo $row->kml_url;?>"></td>
			</tr>
			<tr>
				<td><label for="fusion_table_id" title="<?php _e("The Fusion Tables Layer renders data contained in Google Fusion Tables.","gmwd");?>"><?php _e("Fusion Table Id","gmwd");?>:</label></td>
				<td><input type="text" name="fusion_table_id" id="fusion_table_id" value="<?php echo $row->fusion_table_id;?>"></td>
			</tr>
			<tr>
				<td><label for="fusion_table_where_filed" title="<?php _e("Fill in fusion table field title.","gmwd");?>"><?php _e("Fusion Table Field Title","gmwd");?>:</label></td>
				<td>
                    <input type="text" name="fusion_table_where_filed" id="fusion_table_where_filed" value="<?php echo $row->fusion_table_where_filed;?>">
                    <div><small  class="gmwd_eg_fusion_table_where_filed">e.g. ISO 2 Letter Code</small></div>
                </td>
			</tr>			
			<tr>
				<td><label for="fusion_table_where_operator" title="<?php _e("Choose the clause operator which will be used for filtering values from fusion table.","gmwd");?>"><?php _e("Fusion Table Clause Operator","gmwd");?>:</label></td>
				<td>
                    <select name="fusion_table_where_operator" id="fusion_table_where_operator">
                <?php
                    foreach($lists["fusion_table_where_operators"] as $key => $value){
                        $selected = $row->fusion_table_where_operator ==  $key ? "selected" : "";
                        echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';                   
                    }                
                ?>
                    </select>
                </td>
			</tr>
			<tr>
				<td><label for="fusion_table_where_value" title="<?php _e("Choose the field values which will serve as basis for filtering.","gmwd");?>"><?php _e("Fusion Table Conditional Value","gmwd");?>:</label></td>
				<td>
                    <input type="text" name="fusion_table_where_value" id="fusion_table_where_value" value="<?php echo $row->fusion_table_where_value;?>">
                    <div><small class="gmwd_eg_fusion_table_where_value"><?php echo $row->fusion_table_where_operator == "IN" ? "e.g. ('RU', 'AM', 'BE')" : "e.g. BE"; ?></small></div>
                </td>
                
			</tr>            
		</table>
	
	<?php
	
	}
	private function settings_marker_listing($row, $lists){
		$lists = $this->model->get_lists();
	?>
		<table class="settings_table">
			<tr>
				<td><label for="marker_listing_type" title="<?php _e("Select the list type of your markers.","gmwd");?>"><?php _e("Marker List Type","gmwd");?>:</label></td>
				<td>
					<select name="marker_listing_type" id="marker_listing_type">
						<?php 
							foreach($lists["map_markers"] as $key => $value){
								$selected = $row->marker_listing_type ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}						
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="listing_header_title" title="<?php _e("Set the header title of your marker listing.","gmwd");?>"><?php _e("Header Title","gmwd");?>:</label></td>
				<td>
                    <input type="text" name="listing_header_title" id="listing_header_title" value="<?php echo $row->listing_header_title;?>">                
                </td>
			</tr>
            <tr>
                <td><label for="marker_listing_order" title="<?php _e("Order your marker listsing.","gmwd");?>"><?php _e("Order By","gmwd");?>:</label></td>
                <td>
                    <select name="marker_listing_order" id="marker_listing_order">
                        <?php
                        foreach($lists["marker_listing_order"] as $key => $value){
                            $selected = $row->marker_listing_order ==  $key ? "selected" : "";
                            echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="marker_listing_order_dir" title="<?php _e("Choose order direction for your marker listsing.","gmwd");?>"><?php _e("Order Direction","gmwd");?>:</label></td>
                <td>
                    <input type="radio" class="inputbox" id="marker_listing_order_dir0" name="marker_listing_order_dir" <?php echo (($row->marker_listing_order_dir == "asc") ? 'checked="checked"' : ''); ?> value="asc" >
                    <label for="marker_listing_order_dir0"><?php _e("Asc","gmwd"); ?></label>
                    <input type="radio" class="inputbox" id="marker_listing_order_dir1" name="marker_listing_order_dir" <?php echo (($row->marker_listing_order_dir == "desc") ? 'checked="checked"' : ''); ?> value="desc" >
                    <label for="marker_listing_order_dir1"><?php _e("Desc","gmwd"); ?></label>
                </td>
            </tr>

			<tr class="tr_advanced_columns" <?php echo $row->marker_listing_type != 2 ? "style='display:none;'" : ""; ?> >
				<td><label for="advanced_table_columns" title="<?php _e("Choose markers advanced table columns to show.","gmwd");?>"><?php _e("Columns","gmwd");?>:</label></td>
				<td>
                    <input type="checkbox" value="icon" id="advanced_icon" class="advanced_table_columns" <?php echo in_array("icon", $row->advanced_table_columns) ? "checked" : ""; ?> ><label for="advanced_icon"><?php _e("Marker Icon","gmwd");?></label><br>                
                    <input type="checkbox" value="title" id="advanced_title" class="advanced_table_columns" <?php echo in_array("title", $row->advanced_table_columns) ? "checked" : ""; ?> ><label for="advanced_title"><?php _e("Title","gmwd");?></label><br>
                    <input type="checkbox" value="category" id="advanced_category" class="advanced_table_columns" <?php echo in_array("category", $row->advanced_table_columns) ? "checked" : ""; ?> ><label for="advanced_category"><?php _e("Category","gmwd");?></label><br>
                    <input type="checkbox" value="address" id="advanced_address" class="advanced_table_columns" <?php echo in_array("address", $row->advanced_table_columns) ? "checked" : ""; ?> ><label for="advanced_address"><?php _e("Address","gmwd");?></label><br>
                    <input type="checkbox" value="desc" id="advanced_desc" class="advanced_table_columns" <?php echo in_array("desc", $row->advanced_table_columns) ? "checked" : ""; ?>><label for="advanced_desc"><?php _e("Description","gmwd");?></label>
                    <input type="hidden" name="advanced_table_columns" value="<?php echo implode(",", $row->advanced_table_columns); ?>">
				</td>
			</tr>            

			<tr>
				<td><label for="marker_list_position" title="<?php _e("Select the position for marker listing.","gmwd");?>"><?php _e("Marker List Position","gmwd");?>:</label></td>
				<td>
					<select name="marker_list_position" id="marker_list_position">
                        <option value="0" <?php echo $row->marker_list_position == 0 ? "selected" : "";?>>
                            <?php _e("Bottom","gmwd");?>
                        </option>
                        <option value="1" <?php echo $row->marker_list_position == 1 ? "selected" : "";?>>
                            <?php _e("Top","gmwd");?>
                        </option>                        
					</select>
				</td>
			</tr>  

			<tr>
				<td><label title="<?php _e(" Disable/enable marker list on the map.","gmwd");?>"><?php _e("Enable List Inside Map","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="marker_list_inside_map0" name="marker_list_inside_map" <?php echo (($row->marker_list_inside_map) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="marker_list_inside_map0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="marker_list_inside_map1" name="marker_list_inside_map" <?php echo (($row->marker_list_inside_map) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="marker_list_inside_map1"><?php _e("Yes","gmwd"); ?></label>
				</td>	
			</tr>
			
			<tr>
				<td><label for="marker_list_inside_map_position" title="<?php _e("Select the inside map marker list position.","gmwd");?>"><?php _e("Inside Map List Position","gmwd"); ?>:</label></td>
				<td>
					<select name="marker_list_inside_map_position" id="marker_list_inside_map_position">
						<?php 
							foreach($lists["map_positions"] as $key => $value){
								$selected = $row->marker_list_inside_map_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr>
                      
		</table>
	
	<?php	
	}
	private function settings_marker_category_filter($row, $lists){
	?>
		<table class="settings_table">           
			<tr>
				<td><label title="<?php _e("Disable/enable category filter.","gmwd");?>"><?php _e("Enable Category Filter","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_category_filter0" name="enable_category_filter" <?php echo (($row->enable_category_filter) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_category_filter0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_category_filter1" name="enable_category_filter" <?php echo (($row->enable_category_filter) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_category_filter1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>
			<tr>
				<td><label for="category_filter_type" title="<?php _e("Select the view type of your markers category filter.","gmwd");?>"><?php _e("Category Filter View","gmwd"); ?>:</label></td>
				<td>
					<select name="category_filter_type" id="category_filter_type">
						<?php 
							foreach($lists["marker_category_types"] as $key => $value){
								$selected = $row->category_filter_type ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr> 
			<tr>
				<td><label title="<?php _e(" Disable/enable marker category filter on the map.","gmwd");?>"><?php _e("Enable Category Filter Inside Map","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="marker_categories_inside_map0" name="marker_categories_inside_map" <?php echo (($row->marker_categories_inside_map) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="marker_categories_inside_map0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="marker_categories_inside_map1" name="marker_categories_inside_map" <?php echo (($row->marker_categories_inside_map) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="marker_categories_inside_map1"><?php _e("Yes","gmwd"); ?></label>
				</td>	
			</tr>
			<tr>
				<td><label for="imcategory_filter_type" title="<?php _e("Select the view type of your inside map markers category filter.","gmwd");?>"><?php _e("Inside Map Category Filter View","gmwd"); ?>:</label></td>
				<td>
					<select name="imcategory_filter_type" id="imcategory_filter_type">
						<?php 
							foreach($lists["inside_map_marker_category_types"] as $key => $value){
								$selected = $row->imcategory_filter_type ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="category_filter_im_position" title="<?php _e("Select the inside map marker category filter position.","gmwd");?>"><?php _e("Inside Map Category Filter Position","gmwd"); ?>:</label></td>
				<td>
					<select name="category_filter_im_position" id="category_filter_im_position">
						<?php 
							foreach($lists["map_positions"] as $key => $value){
								$selected = $row->category_filter_im_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						
						?>
					</select>
				</td>
			</tr> 
			<tr>
				<td><label title="<?php _e(" Show/hide marker category icon.","gmwd");?>"><?php _e("Show Category Icon","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="show_cat_icon0" name="show_cat_icon" <?php echo (($row->show_cat_icon) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="show_cat_icon0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="show_cat_icon1" name="show_cat_icon" <?php echo (($row->show_cat_icon) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="show_cat_icon1"><?php _e("Yes","gmwd"); ?></label>
				</td>	
			</tr>            
		</table>
	
	<?php	
	}



    private function settings_advanced($row, $lists){
        $whitelist = array( '127.0.0.1', '::1' );
        $is_localhost = in_array( $_SERVER['REMOTE_ADDR'], $whitelist) ? 1 : 0;
        $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $is_localhost ? 1 : 0;	    
    ?>
        <table class="settings_table">
            <tr class="geolocation_msg" <?php if($is_https == 1 || ($is_https == 0 && $row->geolocate_user == 0)) echo "style='display:none;'"; ?>>
                <td colspan="2">     
                    <div class="gmwd_notice_err"><?php _e("Geolocation API call must be served from a secure context such as HTTPS.","gmwd");?></div>
                </td>
            </tr>
			<tr>
				<td><label title="<?php _e("With  W3C Geolocation standard you can detect the user's location on front end.","gmwd");?>"><?php _e("Geolocate User","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="geolocate_user0" name="geolocate_user" <?php echo (($row->geolocate_user) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="geolocate_user0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="geolocate_user1" name="geolocate_user" <?php echo (($row->geolocate_user) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="geolocate_user1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr>         
			<tr>
				<td><label for="info_window_open_on" title="<?php _e("Choose whether to open info window by hovering or a click.","gmwd");?>"><?php _e("Info Window Open","gmwd");?>:</label></td>
				<td>
					<select name="info_window_open_on" id="info_window_open_on">
						<option value="click" <?php if($row->info_window_open_on == "click") echo "selected";?>><?php _e("Click","gmwd");?></option>
						<option value="hover" <?php if($row->info_window_open_on == "hover") echo "selected";?>><?php _e("Hover","gmwd");?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="info_window_info" title="<?php _e("Choose information to show in an info window.","gmwd");?>"><?php _e("Show in Info Window","gmwd");?>:</label></td>
				<td>
                    <input type="checkbox" value="title" id="info_window_title" class="info_window_info" <?php echo in_array("title", $row->info_window_info) ? "checked" : ""; ?> ><label for="info_window_title"><?php _e("Marker Title","gmwd");?></label><br> 
                    <input type="checkbox" value="address" id="info_window_address" class="info_window_info" <?php echo in_array("address", $row->info_window_info) ? "checked" : ""; ?> ><label for="info_window_address"><?php _e("Marker Address","gmwd");?></label><br> 
                    <input type="checkbox" value="desc" id="info_window_desc" class="info_window_info" <?php echo in_array("desc", $row->info_window_info) ? "checked" : ""; ?> ><label for="info_window_desc"><?php _e("Marker Description","gmwd");?></label><br> 
                    <input type="checkbox" value="pic" id="info_window_pic" class="info_window_info" <?php echo in_array("pic", $row->info_window_info) ? "checked" : ""; ?> ><label for="info_window_pic"><?php _e("Marker Image","gmwd");?></label><br>                     
                    <input type="hidden" name="info_window_info" value="<?php echo implode(",", $row->info_window_info); ?>">                    

				</td>
			</tr>            
	
			<tr>
				<td><label title="<?php _e("Select marker info window type.","gmwd");?>"><?php _e("Marker Info Window Type","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="infowindow_type0" name="infowindow_type" <?php echo (($row->infowindow_type) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="infowindow_type0"><?php _e("Default","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="infowindow_type1" name="infowindow_type" <?php echo (($row->infowindow_type) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="infowindow_type1"><?php _e("Advanced","gmwd"); ?></label>
				</td>
			</tr>
			<tr class="advanced_info_window_position_tr" <?php echo $row->infowindow_type == 0 ? 'style="display:none;"' : ''; ?>>
				<td><label for="advanced_info_window_position" title="<?php _e("Select the position of the marker advanced window.","gmwd");?>"><?php _e("Advanced Info Window Position","gmwd"); ?>:</label></td>
				<td>
					<select name="advanced_info_window_position" id="advanced_info_window_position">
						<?php 
							foreach($lists["map_positions"] as $key => $value){
								$selected = $row->advanced_info_window_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						?>
                </td>        
			</tr> 
			<tr>
				<td><label title="<?php _e("Disable/enable searchbox on map.","gmwd");?>"><?php _e("Enable Searchbox","gmwd"); ?>:</label></td>
				<td>
				  <input type="radio" class="inputbox" id="enable_searchbox0" name="enable_searchbox" <?php echo (($row->enable_searchbox) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="enable_searchbox0"><?php _e("No","gmwd"); ?></label>
				  <input type="radio" class="inputbox" id="enable_searchbox1" name="enable_searchbox" <?php echo (($row->enable_searchbox) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="enable_searchbox1"><?php _e("Yes","gmwd"); ?></label>
				</td>
			</tr> 
			<tr>
				<td><label for="searchbox_position" title="<?php _e("Select the position of the searchbox.","gmwd");?>"><?php _e("Searchbox Position","gmwd"); ?>:</label></td>
				<td>
					<select name="searchbox_position" id="searchbox_position">
						<?php 
							foreach($lists["map_positions"] as $key => $value){
								$selected = $row->searchbox_position ==  $key ? "selected" : "";
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						?>
					</select>
				</td>
			</tr>            
        
        </table>
    <?php
    }    

	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}