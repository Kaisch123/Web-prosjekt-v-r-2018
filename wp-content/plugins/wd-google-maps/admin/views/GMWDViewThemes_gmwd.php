<?php

class GMWDViewThemes_gmwd extends GMWDView{

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
					<img src="<?php echo GMWD_URL . '/images/themes.png';?>"  style="vertical-align: middle;">
					<span><?php _e("Themes","gmwd");?></span>
					<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-addnew" onclick="gmwdFormSubmit('edit');return false;"><?php _e("Add new","gmwd");?></button>
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
							<th class="col <?php if ($order_by == 'default') {echo $order_class;} ?>" width="8%">
								<a onclick="gmwdFormInputSet('order_by', 'default');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'default') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Default","gmwd"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>			                           
							<th class="col <?php if ($order_by == 'published') {echo $order_class;} ?>" width="8%">
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
								$default_image = (($row->default) ? 'default' : 'notdefault');
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
										<a href="admin.php?page=themes_gmwd&task=edit&id=<?php echo $row->id;?>">
											<?php echo $row->title;?>
										</a>
									</td>
									<td class="table_big_col" >
										<a onclick="gmwdFormInputSet('task', 'make_default');gmwdFormInputSet('current_id', '<?php echo $row->id; ?>');document.getElementById('adminForm').submit();return false;" href="">
											<img src="<?php echo GMWD_URL . '/images/css/' . $default_image . '.png'; ?>"></img>
										</a>
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
        $tabs = array("map_styles" => __("Map Styles","gmwd"),"directions" => __("Directions","gmwd"), "store_locator" => __("Store Locator","gmwd"), "marker_listsing_basic" => __("Marker Listing Basic","gmwd"), "marker_listsing_advanced" => __("Marker Listing Advanced","gmwd"), "marker_listsing_carousel" => __("Marker Listing Carousel","gmwd"), "marker_listsing_inside_map" => __("Marker Listing Inside Map","gmwd"));
        
        if(defined('GMWDUGM_NAME')){
            $tabs["user_generated_markers"] = __("User Generated Markers","gmwd");
        }
    ?>

        <div class="gmwd_opacity_div">
            <div class="gmwd_opacity_div_loading"></div>
        </div>
		<div class="gmwd_edit" ng-app="gmwdThemeParams">          
            <h2>
				<img src="<?php echo GMWD_URL . '/images/themes.png';?>"  style="vertical-align:middle;">
				<span>
					<?php 
						if($id == 0) {
							_e("Add Theme","gmwd");
						}	
						else{
							_e("Edit Theme","gmwd");
						}	
					?>
				</span>
	
			</h2>
			<form method="post" action="" id="adminForm" enctype="multipart/form-data">
                <?php wp_nonce_field('nonce_gmwd', 'nonce_gmwd'); ?>
 				<div class="wd-clear wd-row">
				   <div class="wd-left">
						<div class="title-wrapper">
							<table>
								<tr>
									<td>
										<label for="title"><strong><?php _e("Theme title","gmwd"); ?></strong></label>
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
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save" onclick="gmwdFormSubmit('save');"><?php _e("Save","gmwd");?></button>
							</div>
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" onclick="gmwdFormSubmit('apply');return false;"><?php _e("Apply","gmwd");?></button>
							</div>							
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save2copy" onclick="gmwdFormSubmit('save2copy');"><?php _e("Save as Copy","gmwd");?></button>
							</div>											  
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-cancel" onclick="gmwdFormSubmit('cancel');"><?php _e("Cancel","gmwd");?></button>
							</div>															
						</div>
					</div>
				</div>
                 <div class="gmwd" ng-controller="gmwdTheme">
                     <style>
         
                        .gmwd_directions_container{
                            background:#{{directions_window_background_color}};
                            padding:5px;
                            border-radius:{{directions_window_border_radius}}px;
                        }
                        .gmwd_directions_title{
                            color:#{{directions_title_color}};
                            margin:5px 0px !important;
                        }
                        .gmwd_direction_mode, .gmwd_direction_from, .gmwd_direction_to{
                            border-radius:{{directions_input_border_radius}}px!important;
                            border-color:#{{directions_input_border_color}}!important;
                            padding:5px!important;
                            background-color:#fff!important;
                        }
                         .gmwd_directions_label{
                            color:#{{directions_label_color}};
                            background:#{{directions_label_background_color}};
                            border-radius:{{directions_label_border_radius}}px;
                            padding: 1px 5px!important;
                            display:block;
                            width:70px;
                            margin-right: 8px;
                        }
             
                        #gmwd_directions_go{
                            border-radius:{{directions_button_border_radius}}px;
                            background:#{{directions_button_background_color}};
                            color:#{{directions_button_color}};
                            width:{{directions_button_width}}px;
                            padding:3px 6px !important;
                            border: 0 !important;
                        }
                        .gmwd_store_locator_container{
       
                            background:#{{store_locator_window_bgcolor}};
                            padding:5px;
                            border-radius:{{store_locator_window_border_radius}}px;
                        }
                        .gmwd_store_locator_title{
                            color:#{{store_locator_title_color}};
                            margin:5px 0px !important;
                        }
                        .gmwd_store_locator_address, .gmwd_store_locator_radius{
                            border-radius:{{store_locator_input_border_radius}}px;
                            border-color:#{{store_locator_input_border_color}} !important;
                            padding:5px!important;
                        
                        }
                        .gmwd_store_locator_radius{
                            background-color:#fff !important;
                        }
                        .gmwd_store_locator_container .gmwd_store_locator_label{
                            color:#{{store_locator_label_color}};
                            background:#{{store_locator_label_background_color}};
                            border-radius:{{store_locator_label_border_radius}}px;
                            padding: 1px 5px!important;
                            display:block;
                            width:120px;
                            margin-right: 8px;
                        }
                        #gmwd_store_locator_search, #gmwd_store_locator_reset{
                            border-radius:{{store_locator_button_border_radius}}px;
                            width:{{store_locator_button_width}}px;
                            padding:3px 15px !important;
                            border: 0 !important;
                        }
                        #gmwd_store_locator_search{			
                            background:#{{store_locator_search_button_background_color}};
                            color:#{{store_locator_search_button_color}};
                        }
                        #gmwd_store_locator_reset{
                            background:#{{store_locator_reset_button_background_color}};
                            color:#{{store_locator_reset_button_color}};
                        }
                        .gmwd_markers_basic_title{
                            color:#{{marker_listsing_basic_title_color}};
                        }
                        .gmwd_marker_listing_basic_direction{
                            border-radius:{{marker_listsing_basic_dir_border_radius}}px!important;
                            padding: 5px 28px 6px 6px!important;
                            background-color:#{{marker_listsing_basic_dir_background_color}};
                            color:#{{marker_listsing_basic_dir_color}}!important;
                            background-image:url('<?php echo  GMWD_URL."/images/css/d_arrow.png";?>');
                            background-position:95% center;
                            background-repeat:no-repeat;		
                            width:{{marker_listsing_basic_dir_width}}px;
                            height:{{marker_listsing_basic_dir_height}}px;
                            display: block;
                        }
                        .gmwd_marker_listing_basic_direction:hover{
                            color:#{{marker_listsing_basic_dir_color}};
                        }
                        .gmwd_markers_advanced_title{
                            color:#{{marker_advanced_title_color}};
                            margin:5px 0px !important;
                        }
                        .gmwd_markers_basic_container{
                            background:#{{marker_listsing_basic_bgcolor}};
                            padding:5px;
                        }
                        .gmwd_marker_title{
                            color:#{{marker_listsing_basic_marker_title_color}};
                        }   
                        .gmwd_marker_basic_desc{
                            color:#{{marker_listsing_basic_marker_desc_color}};
                        }                         
                        .gmwd_marker_listing_basic_direction:hover{
                            color:#{{marker_listsing_basic_dir_color}}!important;
                        }
              
                        .gmwd_markers_advanced_table_header{
                            background-color:#{{marker_advanced_table_header_background}}; 
                        }
                        .gmwd_markers_advanced_table_header .wd-cell:first-child{
                            border-top-left-radius:{{marker_advanced_table_border_radius}}px;  
                        }
                        .gmwd_markers_advanced_table_header .wd-cell:last-child{
                            border-top-right-radius:{{marker_advanced_table_border_radius}}px;  
                        }
                        .gmwd_markers_advanced_table_header .wd-cell a{
                            color:#{{marker_advanced_table_header_color}}!important;
                        }
                        .gmwd_advanced_markers_tbody{
                            background-color:#{{marker_advanced_table_background}};                            
                        }
                        .gmwd_advanced_markers_tbody:last-child .wd-cell:first-child{
                            border-bottom-left-radius:{{marker_advanced_table_border_radius}}px;  
                        }
                        .gmwd_advanced_markers_tbody:last-child .wd-cell:last-child{
                            border-bottom-right-radius:{{marker_advanced_table_border_radius}}px;  
                        }
                        .gmwd_advanced_markers_tbody .wd-cell {
                            color:#{{marker_advanced_table_color}}!important;			
                        }
                        .gmwd_advanced_info_window{
                            background:#{{advanced_info_window_background}};
                            padding:10px;
                            width:300px;
                            box-shadow: 0 4px 2px -2px #000;
                        }

                        .gmwd_advanced_info_window div{
                            margin-bottom:4px;
                        }

                        .gmwd_advanced_info_window_title{
                            font-weight: bold;
                            font-size: 16px;
                            background:#{{advanced_info_window_title_background_color}};
                            padding:4px 5px;
                            color:#{{advanced_info_window_title_color}};
                        }
                        .gmwd_advanced_info_window_address{
                            font-size: 14px;
                            color:#{{advanced_info_window_desc_color}};
                        }
                        .gmwd_advanced_info_window_description{
                            font-size: 12px;
                            color:#{{advanced_info_window_desc_color}};
                        }
                        .gmwd_advanced_info_window_directions a{
                            display:inline-block;
                            padding:4px 20px;
                            background:#{{advanced_info_window_dir_background_color}}!important;
                            color:#{{advanced_info_window_dir_color}}!important;
                            font-size: 14px;
                            border-radius:{{advanced_info_window_dir_border_radius}}px;
                        } 
                        .gmwd_marker_carousel_box{
                            padding: 8px;
                            background: #{{carousel_background_color}};
                            color: #{{carousel_color}};
                            border-right: 1px solid #fff;
                            cursor:pointer;
                            min-height:{{carousel_item_height}}px;
                            overflow: hidden;
                            border-radius:{{carousel_item_border_radius}}px;  
                        }
                        .gmwd_marker_carousel_box:hover{
                            background: #{{carousel_hover_background_color}};
                            color:#{{carousel_hover_color}};
                        }
                        .gmwd_marker_carousel_box .gmwd_item_box{
                            height:{{carousel_item_height}}px;
                            overflow:hidden;
                        }
                        .gmwd_marker_carousel_box .gmwd_item_box .gmwd_item{
                            height:97%;
                            overflow:hidden;
                        }             
                        .gmwd_marker_carousel_box:hover a{
                            color:#{{carousel_hover_color}};                            
                        }  
                        .gmwd_marker_list_inside_map {
                            width:{{marker_listsing_inside_map_width }}px;
                            height:{{marker_listsing_inside_map_height}}px;
                            max-height: 265px;
                            background:#{{marker_listsing_inside_map_bgcolor}};
                            border-radius:{{marker_listsing_inside_map_border_radius}}px !important;
                            overflow:auto;
                        }
                        .gmwd_marker_list_inside_map {
                             color:#{{marker_listsing_inside_map_color}};
                        }                        
                    </style>  
					<ul class="wd-themes-tabs wd-clear">
                        <?php 
                        
                        foreach($tabs as $tab_key => $tab_title){
                           $active_tab = GMWDHelper::get('active_tab',"#map_styles") == "#".$tab_key  ? "wd-btn-primary" : "wd-btn-secondary"; 
                        ?>
                        	<li><a href="#<?php echo $tab_key;?>" class="wd-btn wd-btn-secondary <?php echo $active_tab; ?>" ><?php echo $tab_title; ?></a></li>
                        <?php
                        }
                        ?>
                    
					</ul>
					<div class="wd-themes-tabs-container">
						<div id="map_styles" class="wd-themes-container" <?php echo (GMWDHelper::get('active_tab') == "#map_styles" || GMWDHelper::get('active_tab') == "" ) ? "" : "style='display:none;'";?>>
                            <?php $this->theme_map_styles($row); ?>
                        </div>
						<div id="directions" class="wd-themes-container" <?php echo (GMWDHelper::get('active_tab') == "#directions"  ) ? "" : "style='display:none;'";?>>
                            <?php $this->theme_directions($row); ?>
                        </div>
						<div id="store_locator" class="wd-themes-container" <?php echo GMWDHelper::get('active_tab') == "#store_locator" ? "" : "style='display:none;'";?>>
                            <?php $this->theme_store_locator($row); ?>
                        </div>
						<div id="marker_listsing_basic" class="wd-themes-container" <?php echo GMWDHelper::get('active_tab') == "#marker_listsing_basic" ? "" : "style='display:none;'";?>>
                            <?php $this->theme_marker_listing_basic($row); ?>
                        </div>
						<div id="marker_listsing_advanced" class="wd-themes-container" <?php echo GMWDHelper::get('active_tab') == "#marker_listsing_advanced" ? "" : "style='display:none;'";?>>
                            <?php $this->theme_marker_listing_advanced($row); ?>

                        </div>
						<div id="marker_listsing_carousel" class="wd-themes-container" <?php echo GMWDHelper::get('active_tab') == "#marker_listsing_carousel" ? "" : "style='display:none;'";?>>
                            <?php $this->theme_marker_listing_carousel($row); ?>
                        </div>						
                        
                        <div id="marker_listsing_inside_map" class="wd-themes-container" <?php echo GMWDHelper::get('active_tab') == "#marker_listsing_inside_map" ? "" : "style='display:none;'";?>>
                            <?php $this->theme_marker_listing_inside_map($row); ?>
                        </div>
                        <?php
                        if(defined('GMWDUGM_NAME')){
                        ?>
                            <div id="user_generated_markers" class="wd-themes-container" <?php echo GMWDHelper::get('active_tab') == "#user_generated_markers" ? "" : "style='display:none;'";?>>
                            <?php GMWDUGMAdmin::map_themes_view($row); ?>
                            </div>
                        <?php
                        }
                        ?>
                    
                    </div>                    
                </div>                 
               <br>
                <div class="title-wrapper">
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
				<input id="page" name="page" type="hidden" value="<?php echo GMWDHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="id" name="id" type="hidden" value="<?php echo $row->id;?>" />
				<input id="default" name="default" type="hidden" value="<?php echo $row->default;?>" />
				<input id="active_tab" name="active_tab" type="hidden" value="<?php echo GMWDHelper::get('active_tab'); ?>" />
				<input id="all_styles" name="all_styles" type="hidden" value="" />
				<input id="map_style_id" name="map_style_id" type="hidden" value="<?php echo $row->map_style_id;?>" />
				
			</form>
         
		</div>

        <script>
            angular.module('gmwdThemeParams', []).controller('gmwdTheme', function($scope) {
			});		
        </script>
		<?php
		
	}

	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
    private function theme_map_styles($row){
        $map_styles = $this->model->get_map_styles();

    ?>
        <table class="gmwd_edit">
            <tr>
				<td><label for="map_border_radius" title="<?php _e("Detect the border radius of your map.","gmwd");?>"><?php _e("Border Radius","gmwd");?>:</label></td>
				<td><input type="number" name="map_border_radius" id="map_border_radius" value="<?php echo $row->map_border_radius;?>"></td>
			</tr>
        </table>    
       <div class="wd-row">
           <h4><b><?php _e("Select Map Style","gmwd");?></b></h4>
            <div class="wd-clear static-maps">

                <?php
                    $i=0;
                    $map_api_key = gmwd_get_option("map_api_key") ?  "key=" . gmwd_get_option("map_api_key")."&" : "";
                    foreach($map_styles as $map_style){
                ?>  
                    <div class="wd-left" >
                        <img src="<?php echo "https://maps.googleapis.com/maps/api/staticmap?".$map_api_key."size=600x300&zoom=13&center=".gmwd_get_option("center_address").$map_style->image; ?>" class="map_theme_img <?php echo $row->map_style_id == $map_style->id ? "map_theme_img_active" : "";?>" onclick="onBtnClickStyleImg(jQuery(this));">
                        <input type="radio" name="map_style_id_radio" id="map_style_id<?php echo $map_style->id;?>" value="<?php echo $map_style->id;?>" <?php echo ($row->map_style_id == $map_style->id) ? "checked" : "";?>>
                        <input type="hidden" class="gmwd_map_style_code" value='<?php echo $map_style->styles;?>'>
                    </div>
                <?php
                    }
                ?>
                <div class="wd-left gmwd_add_style_wrapper">
                    <div class="gmwd_add_style"><span class="gmwd_add_style_text"><?php _e("Add Map Style", "gmwd");?></span></div>    
                    
                </div>                
            </div> 
        </div>
        <div>
            <div class="gmwd_opacity_div">
                <div class="gmwd_opacity_div_loading"><img src="<?php echo GMWD_URL."/images/loading.gif";?>"></div>
            </div>
            <div class="wd-row edit_map_style"> 
                <h4><b class="edit-map-style"><?php _e("Edit Map Style", "gmwd");?></b></h4>
                <?php $this->edit_map_style($row->map_style_id); ?>
            </div>    
            <div class="wd-row">
                <h4>
                    <?php _e("To Customize the Map Style Uncheck the Auto Generate Map Style Code box and edit the JavaScript.", "gmwd");?><br><br>               
                    <b><label for="auto_generate_style_code"><?php _e("Auto Generate Map Style Code?", "gmwd");?></label></b>
                    <input type="checkbox" value="1" id="auto_generate_style_code" name="auto_generate_style_code" <?php if($row->auto_generate_style_code == 1) echo "checked"; ?>>
                
                </h4>
                <textarea name="map_style_code" id="map_style_code" style="width:100%; height:120px;" <?php if($row->auto_generate_style_code == 1) echo "readonly"; ?>><?php echo stripslashes(htmlspecialchars_decode ($row->map_style_code)) ;?></textarea>
            </div>
        </div>

		<script>         	
			var mapWhellScrolling = Number(<?php echo gmwd_get_option("whell_scrolling");?>) == 1 ? true : false;
			var zoom = Number(<?php echo gmwd_get_option("zoom_level");?>);
			var mapDragable = Number(<?php echo gmwd_get_option("map_draggable");?>) == 1 ? true : false;
			var centerLat = Number(<?php echo gmwd_get_option("center_lat");?>);
			var centerLng = Number(<?php echo gmwd_get_option("center_lng");?>);
			var centerAddress = '<?php echo gmwd_get_option("center_address");?>';
			var APIKey = '<?php echo gmwd_get_option("map_api_key");?>';
			var styles = '<?php echo sanitize_text_field(stripslashes(htmlspecialchars_decode($row->map_style_code))) ;?>';	
		  
		</script>        
    <?php
    }
    private function theme_directions($row){
    ?>
        <div class="wd-clear">
            <div class="wd-left">
                <table class="gmwd_edit_table">  
                    <tr>
                        <td><label for="directions_title_color" title="<?php _e("Set title text color.","gmwd"); ?>"><?php _e("Title Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->directions_title_color;?>" name="directions_title_color" id="directions_title_color" ng-model="directions_title_color" ng-init="directions_title_color='<?php echo $row->directions_title_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="directions_window_background_color" title="<?php _e("Set directions window background color.","gmwd"); ?>"><?php _e("Directions Window Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->directions_window_background_color;?>" name="directions_window_background_color" id="directions_window_background_color" ng-model="directions_window_background_color" ng-init="directions_window_background_color='<?php echo $row->directions_window_background_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="directions_window_border_radius" title="<?php _e("Set directions window border radius.","gmwd"); ?>"><?php _e("Directions Window Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->directions_window_border_radius;?>" name="directions_window_border_radius" id="directions_window_border_radius" ng-model="directions_window_border_radius" ng-init="directions_window_border_radius='<?php echo $row->directions_window_border_radius;?>'"> px
                        </td>
                    </tr>                                
                    <tr>
                        <td><label for="directions_input_border_radius" title="<?php _e("Set input field border radius.","gmwd"); ?>"><?php _e("Input Field Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->directions_input_border_radius;?>" name="directions_input_border_radius" id="directions_input_border_radius" min="0" ng-model="directions_input_border_radius" ng-init="directions_input_border_radius='<?php echo $row->directions_input_border_radius;?>'"> px
                        </td>
                    </tr>

                    <tr>
                        <td><label for="directions_input_border_color" title="<?php _e("Set input field border color.","gmwd"); ?>"><?php _e("Input Field Border Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->directions_input_border_color;?>" name="directions_input_border_color" id="directions_input_border_color" ng-model="directions_input_border_color" ng-init="directions_input_border_color='<?php echo $row->directions_input_border_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="directions_label_color" title="<?php _e("Set label text color.","gmwd"); ?>"><?php _e("Label Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->directions_label_color;?>" name="directions_label_color" id="directions_label_color" ng-model="directions_label_color" ng-init="directions_label_color='<?php echo $row->directions_label_color;?>'">
                        </td>
                    </tr>	
                    <tr>
                        <td><label for="directions_label_background_color" title="<?php _e("Set label background color.","gmwd"); ?>"><?php _e("Label Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->directions_label_background_color;?>" name="directions_label_background_color" id="directions_label_background_color" ng-model="directions_label_background_color" ng-init="directions_label_background_color='<?php echo $row->directions_label_background_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="directions_label_border_radius" title="<?php _e("Set label border radius.","gmwd"); ?>"><?php _e("Label Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->directions_label_border_radius;?>" name="directions_label_border_radius" id="directions_label_border_radius" min="0" ng-model="directions_label_border_radius" ng-init="directions_label_border_radius='<?php echo $row->directions_label_border_radius;?>'"> px
                        </td>
                    </tr>
                    <tr>
                        <td><label for="directions_button_alignment" title="<?php _e("Set button alignment.","gmwd"); ?>"><?php _e("Button Alignment:","gmwd"); ?></label></td>
                        <td>
                            <select name="directions_button_alignment" id="directions_button_alignment" ng-model="directions_button_alignment" ng-init="directions_button_alignment='<?php echo $row->directions_button_alignment;?>'">
                                <option value="0" <?php echo $row->directions_button_alignment == 0 ? "selected" : ""; ?>><?php _e("Left","gmwd"); ?></option>
                                <option value="1" <?php echo $row->directions_button_alignment == 1 ? "selected" : ""; ?>><?php _e("Center","gmwd"); ?></option>
                                <option value="2" <?php echo $row->directions_button_alignment == 2 ? "selected" : ""; ?>><?php _e("Right","gmwd"); ?></option>
                            </select>	                       
                        </td>
                    </tr>                                  
                    <tr>
                        <td><label for="directions_button_width" title="<?php _e("Set button width.","gmwd"); ?>"><?php _e("Button Width:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->directions_button_width;?>" name="directions_button_width" id="directions_button_width" min="0" ng-model="directions_button_width" ng-init="directions_button_width='<?php echo $row->directions_button_width;?>'"> px
                        </td>
                    </tr>                                
                    <tr>
                        <td><label for="directions_button_border_radius" title="<?php _e("Set button border radius.","gmwd"); ?>"><?php _e("Button Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->directions_button_border_radius;?>" name="directions_button_border_radius" id="directions_button_border_radius" min="0" ng-model="directions_button_border_radius" ng-init="directions_button_border_radius='<?php echo $row->directions_button_border_radius;?>'"> px
                        </td>
                    </tr>	
                    <tr>
                        <td><label for="directions_button_background_color" title="<?php _e("Set button background color.","gmwd"); ?>"><?php _e("Button Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->directions_button_background_color;?>" name="directions_button_background_color" id="directions_button_background_color" ng-model="directions_button_background_color" ng-init="directions_button_background_color='<?php echo $row->directions_button_background_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="directions_button_color" title="<?php _e("Set button text color.","gmwd"); ?>"><?php _e("Button Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->directions_button_color;?>" name="directions_button_color" id="directions_button_color" ng-model="directions_button_color" ng-init="directions_button_color='<?php echo $row->directions_button_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label title="<?php _e("Set columns.","gmwd"); ?>"><?php _e("Columns:","gmwd"); ?></label></td>
                        <td>
                            <input type="radio" class="inputbox wd-form-field" id="directions_columns0" name="directions_columns" <?php echo (($row->directions_columns) ? '' : 'checked="checked"'); ?> value="0" ng-model="directions_columns" ng-init="directions_columns='<?php echo $row->directions_columns;?>'" >
                            <label for="directions_columns0"><?php _e("One Column","gmwd"); ?></label>                                    
                            <input type="radio" class="inputbox wd-form-field" id="directions_columns1" name="directions_columns" <?php echo (($row->directions_columns) ? 'checked="checked"' : ''); ?> value="1" ng-model="directions_columns" ng-init="directions_columns='<?php echo $row->directions_columns;?>'" >
                            <label for="directions_columns1"><?php _e("Two Columns","gmwd"); ?></label>
                        </td>
                    </tr>								
                </table>                            
            </div>
            
            <div class="wd-right gmwd_theme_preview_container">
                <div class="gmwd_container_wrapper">
                    <div class="gmwd_container">
                        <div id="gmwd_container_1">
                             <div class="gmwd_directions_container wd-clear">
                                <h3 class="gmwd_directions_title"><?php _e("Get Directions","gmwd");?></h3>				                
                                <div class="container">                                                    
                                    <div class="row">
                                        <div  ng-class="{ 'col-lg-4 col-md-4 col-sm12 col-xs-12': directions_columns == 1, 'col-lg-12 col-md-12 col-sm-12 col-xs-12': directions_columns == 0}">
                                            <div class="wd-clear wd-row">
                                                <div class="wd-left">
                                                    <label for="gmwd_direction_mode" class="gmwd_directions_label"><?php _e("Mode","gmwd");?></label>
                                                </div>
                                                <div class="wd-left">
                                                    <select id="gmwd_direction_mode" class="gmwd_direction_mode">
                                                        <option><?php _e("Driving","gmwd");?></option>
                                                        <option><?php _e("Walking","gmwd");?></option>
                                                        <option><?php _e("Bicycling","gmwd");?></option>
                                                        <option><?php _e("Transit","gmwd");?></option>
                                                    </select>
                                                </div>						
                                            </div>
                                            <div class="wd-clear wd-row">
                                                <div class="wd-left">
                                                    <label class="gmwd_directions_label"><?php _e("Avoid","gmwd");?></label>
                                                </div>
                                                <div class="wd-left">
                                                    <div class="wd-form-row">
                                                        <label for="gmwd_tolls" class="gmwd_avoid"><?php _e("Tolls","gmwd");?></label>
                                                        <input type="checkbox" class="gmwd_direction_avoid_tolls" value="tolls" id="gmwd_tolls">
                                                    </div>
                                                    <div class="wd-form-row">
                                                        <label for="gmwd_highways" class="gmwd_avoid"><?php _e("Highways","gmwd");?></label>
                                                        <input type="checkbox" class="gmwd_direction_avoid_highways" value="highways" id="gmwd_highways">
                                                    </div>							
                                                </div>						
                                            </div>	
                                        </div>	                  	
                                        <div ng-class="{'col-lg-8 col-md-8 col-sm-12 col-xs-12' : directions_columns == 1, 'col-lg-12 col-md-12 col-sm-12 col-xs-12' : directions_columns == 0}" >
                                            <div class="wd-clear wd-row">
                                                <div class="wd-left">
                                                    <label for="gmwd_form" class="gmwd_directions_label"><?php _e("From","gmwd");?></label>
                                                </div>
                                                <div class="wd-left">
                                                    <input type="text" id="gmwd_form" autocomplete="off" class="gmwd_direction_from" >
                                                    <span data-for="gmwd_form" class="gmwd_my_location gmwd_my_location"><i title="<?php _e("Get My Location","gmwd");?>" class=""></i></span>
                                                </div>						
                                            </div>
                                            <div class="wd-clear wd-row">
                                                <div class="wd-left">
                                                    <label for="gmwd_to" class="gmwd_directions_label"><?php _e("To","gmwd");?></label>
                                                </div>
                                                <div class="wd-left">
                                                    <input type="text" id="gmwd_to" autocomplete="off" class="gmwd_direction_to"  >
                                                    <span data-for="gmwd_to" class="gmwd_my_location gmwd_my_location"><i title="<?php _e("Get My Location","gmwd");?>" class=""></i></span>
                                                </div>						
                                            </div>	
                                        </div>
                                     </div>	   
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-class="{ 'text-left': directions_button_alignment == 0, 'text-center': directions_button_alignment == 1, 'text-right': directions_button_alignment == 2 }">
                                            <button id="gmwd_directions_go" class="gmwd_directions_go"  ><?php _e("Go","gmwd");?></button>
                                        </div>						
                                    </div>                                                          	       
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php

    }	
    
    private function theme_store_locator($row){
    ?>
        <div class="wd-clear">
            <div class="wd-left">
                <table class="gmwd_edit_table">  
                    <tr>
                        <td><label for="store_locator_title_color" title="<?php _e("Set title text color.","gmwd"); ?>"><?php _e("Title Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->store_locator_title_color;?>" name="store_locator_title_color" id="store_locator_title_color" ng-model="store_locator_title_color" ng-init="store_locator_title_color='<?php echo $row->store_locator_title_color;?>'" >
                        </td>
                    </tr>
                    <tr>
                        <td><label for="store_locator_window_bgcolor" title="<?php _e("Set store locator window background color.","gmwd"); ?>"><?php _e("Store Locator Window Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->store_locator_window_bgcolor;?>" name="store_locator_window_bgcolor" id="store_locator_window_bgcolor" ng-model="store_locator_window_bgcolor" ng-init="store_locator_window_bgcolor='<?php echo $row->store_locator_window_bgcolor;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="store_locator_window_border_radius" title="<?php _e("Set store locator window border radius.","gmwd"); ?>"><?php _e("Store Locator Window Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->store_locator_window_border_radius;?>" name="store_locator_window_border_radius" id="store_locator_window_border_radius" min="0" ng-model="store_locator_window_border_radius" ng-init="store_locator_window_border_radius='<?php echo $row->store_locator_window_border_radius;?>'"> px
                        </td>
                    </tr>                                
                    <tr>
                        <td><label for="store_locator_input_border_radius" title="<?php _e("Set input field border radius.","gmwd"); ?>"><?php _e("Input Field Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->store_locator_input_border_radius;?>" name="store_locator_input_border_radius" id="store_locator_input_border_radius" min="0" ng-model="store_locator_input_border_radius" ng-init="store_locator_input_border_radius='<?php echo $row->store_locator_input_border_radius;?>'"> px
                        </td>
                    </tr>

                    <tr>
                        <td><label for="store_locator_input_border_color" title="<?php _e("Set input field border color.","gmwd"); ?>"><?php _e("Input Field Border Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->store_locator_input_border_color;?>" name="store_locator_input_border_color" id="store_locator_input_border_color" ng-model="store_locator_input_border_color" ng-init="store_locator_input_border_color='<?php echo $row->store_locator_input_border_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="store_locator_label_color" title="<?php _e("Set label text color.","gmwd"); ?>"><?php _e("Label Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->store_locator_label_color;?>" name="store_locator_label_color" id="store_locator_label_color" ng-model="store_locator_label_color" ng-init="store_locator_label_color='<?php echo $row->store_locator_label_color;?>'">
                        </td>
                    </tr>	
                    <tr>
                        <td><label for="store_locator_label_background_color" title="<?php _e("Set label background color.","gmwd"); ?>"><?php _e("Label Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->store_locator_label_background_color;?>" name="store_locator_label_background_color" id="store_locator_label_background_color" ng-model="store_locator_label_background_color" ng-init="store_locator_label_background_color='<?php echo $row->store_locator_label_background_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="store_locator_label_border_radius" title="<?php _e("Set label border radius.","gmwd"); ?>"><?php _e("Label Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->store_locator_label_border_radius;?>" name="store_locator_label_border_radius" id="store_locator_label_border_radius" min="0" ng-model="store_locator_label_border_radius" ng-init="store_locator_label_border_radius='<?php echo $row->store_locator_label_border_radius;?>'">
                        </td>
                    </tr>                               
                    <tr>
                        <td><label for="store_locator_buttons_alignment" title="<?php _e("Set buttons alignment.","gmwd"); ?>"><?php _e("Buttons Alignment:","gmwd"); ?></label></td>
                        <td>
                            <select name="store_locator_buttons_alignment" id="store_locator_buttons_alignment" ng-model="store_locator_buttons_alignment" ng-init="store_locator_buttons_alignment='<?php echo $row->store_locator_buttons_alignment;?>'">
                                <option value="0" <?php echo $row->store_locator_buttons_alignment == 0 ? "selected" : ""; ?>><?php _e("Left","gmwd"); ?></option>
                                <option value="1" <?php echo $row->store_locator_buttons_alignment == 1 ? "selected" : ""; ?>><?php _e("Center","gmwd"); ?></option>
                                <option value="2" <?php echo $row->store_locator_buttons_alignment == 2 ? "selected" : ""; ?>><?php _e("Right","gmwd"); ?></option>
                            </select>										
                        </td>
                    </tr> 
                    <tr>
                        <td><label for="store_locator_button_width" title="<?php _e("Set buttons width.","gmwd"); ?>"><?php _e("Buttons Width:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->store_locator_button_width;?>" name="store_locator_button_width" id="store_locator_button_width" min="0" ng-model="store_locator_button_width" ng-init="store_locator_button_width='<?php echo $row->store_locator_button_width;?>'"> px
                        </td>
                    </tr>                                
                    <tr>
                        <td><label for="store_locator_button_border_radius" title="<?php _e("Set buttons border radius.","gmwd"); ?>"><?php _e("Buttons Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->store_locator_button_border_radius;?>" name="store_locator_button_border_radius" id="store_locator_button_border_radius" min="0" ng-model="store_locator_button_border_radius" ng-init="store_locator_button_border_radius='<?php echo $row->store_locator_button_border_radius;?>'"> px
                        </td>
                    </tr>	
                    <tr>
                        <td><label for="store_locator_search_button_background_color" title="<?php _e("Set search button background color.","gmwd"); ?>"><?php _e("Search Button Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->store_locator_search_button_background_color;?>" name="store_locator_search_button_background_color" id="store_locator_search_button_background_color" ng-model="store_locator_search_button_background_color" ng-init="store_locator_search_button_background_color='<?php echo $row->store_locator_search_button_background_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="store_locator_search_button_color" title="<?php _e("Set search button text color.","gmwd"); ?>"><?php _e("Search Button Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->store_locator_search_button_color;?>" name="store_locator_search_button_color" id="store_locator_search_button_color" ng-model="store_locator_search_button_color" ng-init="store_locator_search_button_color='<?php echo $row->store_locator_search_button_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="store_locator_reset_button_background_color" title="<?php _e("Set reset button background color.","gmwd"); ?>"><?php _e("Reset Button Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->store_locator_reset_button_background_color;?>" name="store_locator_reset_button_background_color" id="store_locator_reset_button_background_color" ng-model="store_locator_reset_button_background_color" ng-init="store_locator_reset_button_background_color='<?php echo $row->store_locator_reset_button_background_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="store_locator_reset_button_color" title="<?php _e("Set reset button text color.","gmwd"); ?>"><?php _e("Reset Button Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->store_locator_reset_button_color;?>" name="store_locator_reset_button_color" id="store_locator_reset_button_color" ng-model="store_locator_reset_button_color" ng-init="store_locator_reset_button_color='<?php echo $row->store_locator_reset_button_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label title="<?php _e("Set columns.","gmwd"); ?>"><?php _e("Columns:","gmwd"); ?></label></td>
                        <td>
                            <input type="radio" class="inputbox wd-form-field" id="store_locator_columns0" name="store_locator_columns" <?php echo (($row->store_locator_columns) ? '' : 'checked="checked"'); ?> value="0" ng-model="store_locator_columns" ng-init="store_locator_columns='<?php echo $row->store_locator_columns;?>'" >
                            <label for="store_locator_columns0"><?php _e("One Column","gmwd"); ?></label>                                    
                          <input type="radio" class="inputbox wd-form-field" id="store_locator_columns1" name="store_locator_columns" <?php echo (($row->store_locator_columns) ? 'checked="checked"' : ''); ?> value="1" ng-model="store_locator_columns" ng-init="store_locator_columns='<?php echo $row->store_locator_columns;?>'">
                          <label for="store_locator_columns1"><?php _e("Two Columns","gmwd"); ?></label>
                        </td>
                    </tr>                                
                </table>						
        
            </div>
            <div class="wd-right gmwd_theme_preview_container">
                <div class="gmwd_container_wrapper">
                    <div class="gmwd_container">
                        <div id="gmwd_container_1">
                            <div class="gmwd_store_locator_container wd-clear">
                                <h3 class="gmwd_store_locator_title"><?php _e("Store Locator","gmwd");?></h3>
                                <div class="container">
                                    <div class="row">
                                        <div ng-class="{ 'col-lg-8 col-md-8 col-sm-12 col-xs-12': store_locator_columns == 1, 'col-lg-12 col-md-12 col-sm-12 col-xs-12': store_locator_columns == 0}">
                                            <div class="wd-clear wd-row">
                                                <div class="wd-left">
                                                    <label for="gmwd_store_locator_address" class="gmwd_store_locator_label"><?php _e("Address","gmwd");?></label>
                                                </div>
                                                <div class="wd-left">
                                                    <input type="text" id="gmwd_store_locator_address" autocomplete="off" class="gmwd_store_locator_address" >                                                             
                                                </div>
                                                <div class="wd-left">
                                                    <span class="gmwd_my_location gmwd_my_location_store_locator"><i title="<?php _e("Get My Location","gmwd");?>" class=""></i></span>                                
                                                </div>	                                                                    
                                            </div>				
                                            <div class="wd-clear wd-row">
                                                <div class="wd-left">
                                                    <label for="gmwd_store_locator_radius" class="gmwd_store_locator_label"><?php _e("Radius","gmwd");?>
                                                </div>
                                                <div class="wd-left">
                                                    <select class="gmwd_store_locator_radius" id="gmwd_store_locator_radius">                                  
                                                        <option value="1">1km</option>                 
                                                        <option value="5">5km</option>                
                                                        <option value="10" selected="">10km</option>      
                                                        <option value="25">25km</option>                
                                                        <option value="50">50km</option>                
                                                        <option value="75">75km</option>              
                                                        <option value="100">100km</option>             
                                                        <option value="150">150km</option>            
                                                        <option value="200">200km</option>         
                                                        <option value="300">300km</option>         
                                                    </select>
                                                </div>						
                                            </div>
                                        </div>
                                        <div ng-class="{ 'col-lg-4 col-md-4 col-sm-12 col-xs-12': store_locator_columns == 1, 'col-lg-12 col-md-12 col-sm-12 col-xs-12': store_locator_columns == 0}">
                                            <div class="wd-clear wd-row">
                                                <div class="wd-left">
                                                    <label for="gmwd_marker_categories" class="gmwd_store_locator_label"><?php _e("Categories","gmwd");?>
                                                </div>
                                                <div class="wd-left gmwd_store_locator_categories_container">
                                                    <div class="wd-form-row">
                                                        <label for="cat1">Cat1</label>
                                                        <input type="checkbox" id="cat1">
                                                    </div>
                                                    <div class="wd-form-row">
                                                        <label for="cat2">Cat2</label>
                                                        <input type="checkbox" id="cat2">
                                                    </div>	                                                                        
                                                </div>						
                                            </div>		
                                        </div>		
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " class="col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-class="{ 'text-left': store_locator_buttons_alignment == 0, 'text-center': store_locator_buttons_alignment == 1, 'text-right': store_locator_buttons_alignment == 2 }">
                                            <button id="gmwd_store_locator_search"><?php _e("Search","gmwd");?></button>
                                            <button id="gmwd_store_locator_reset"><?php _e("Reset","gmwd");?></button>
                                        </div>						
                                    </div>
                                </div>	
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    <?php
    }
    private function theme_marker_listing_basic($row){
    ?>
        <div class="wd-clear">
            <div class="wd-left">
                <table class="gmwd_edit_table">  
                    <tr>
                        <td><label for="marker_listsing_basic_title_color" title="<?php _e("Set title text color. ","gmwd"); ?>"><?php _e("Title Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->marker_listsing_basic_title_color;?>" name="marker_listsing_basic_title_color" id="marker_listsing_basic_title_color" ng-model="marker_listsing_basic_title_color" ng-init="marker_listsing_basic_title_color='<?php echo $row->marker_listsing_basic_title_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="marker_listsing_basic_bgcolor" title="<?php _e("Set table background color.","gmwd"); ?>"><?php _e("Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  class="color" value="<?php echo $row->marker_listsing_basic_bgcolor;?>" name="marker_listsing_basic_bgcolor" id="marker_listsing_basic_bgcolor"  ng-model="marker_listsing_basic_bgcolor" ng-init="marker_listsing_basic_bgcolor='<?php echo $row->marker_listsing_basic_bgcolor;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="marker_listsing_basic_marker_title_color" title="<?php _e("Set marker title color.","gmwd"); ?>"><?php _e("Marker Title Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  class="color" value="<?php echo $row->marker_listsing_basic_marker_title_color;?>" name="marker_listsing_basic_marker_title_color" id="marker_listsing_basic_marker_title_color"  ng-model="marker_listsing_basic_marker_title_color" ng-init="marker_listsing_basic_marker_title_color='<?php echo $row->marker_listsing_basic_marker_title_color;?>'">
                        </td>
                    </tr> 
                    <tr>
                        <td><label for="marker_listsing_basic_marker_desc_color" title="<?php _e("Set marker description color.","gmwd"); ?>"><?php _e("Marker Description Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  class="color" value="<?php echo $row->marker_listsing_basic_marker_desc_color;?>" name="marker_listsing_basic_marker_desc_color" id="marker_listsing_basic_marker_desc_color"  ng-model="marker_listsing_basic_marker_desc_color" ng-init="marker_listsing_basic_marker_desc_color='<?php echo $row->marker_listsing_basic_marker_desc_color;?>'">
                        </td>
                    </tr>                                         
                    <tr>
                        <td><label for="marker_listsing_basic_dir_border_radius" title="<?php _e("Set directions button border radius.","gmwd"); ?>"><?php _e("Directions Button Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->marker_listsing_basic_dir_border_radius;?>" name="marker_listsing_basic_dir_border_radius" id="marker_listsing_basic_dir_border_radius" min="0" ng-model="marker_listsing_basic_dir_border_radius" ng-init="marker_listsing_basic_dir_border_radius='<?php echo $row->marker_listsing_basic_dir_border_radius;?>'"> px
                        </td>
                    </tr>
                    <tr>
                        <td><label for="marker_listsing_basic_dir_width" title="<?php _e("Set directions button width.","gmwd"); ?>"><?php _e("Directions Button Width:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" min="0" value="<?php echo $row->marker_listsing_basic_dir_width;?>" name="marker_listsing_basic_dir_width" id="marker_listsing_basic_dir_width" ng-model="marker_listsing_basic_dir_width" ng-init="marker_listsing_basic_dir_width='<?php echo $row->marker_listsing_basic_dir_width;?>'"> px
                        </td>
                    </tr>								
                    <tr>
                        <td><label for="marker_listsing_basic_dir_height" title="<?php _e("Set directions button height.","gmwd"); ?>"><?php _e("Directions Button Height:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" min="0" value="<?php echo $row->marker_listsing_basic_dir_height;?>" name="marker_listsing_basic_dir_height" id="marker_listsing_basic_dir_height" ng-model="marker_listsing_basic_dir_height" ng-init="marker_listsing_basic_dir_height='<?php echo $row->marker_listsing_basic_dir_height;?>'"> px
                        </td>
                    </tr>					
                    <tr>
                        <td><label for="marker_listsing_basic_dir_background_color" title="<?php _e("Set directions button background color.","gmwd"); ?>"><?php _e("Directions Button Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->marker_listsing_basic_dir_background_color;?>" name="marker_listsing_basic_dir_background_color" id="marker_listsing_basic_dir_background_color" ng-model="marker_listsing_basic_dir_background_color" ng-init="marker_listsing_basic_dir_background_color='<?php echo $row->marker_listsing_basic_dir_background_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="marker_listsing_basic_dir_color" title="<?php _e("Set directions button text color.","gmwd"); ?>"><?php _e("Directions Button Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->marker_listsing_basic_dir_color;?>" name="marker_listsing_basic_dir_color" id="marker_listsing_basic_dir_color" ng-model="marker_listsing_basic_dir_color" ng-init="marker_listsing_basic_dir_color='<?php echo $row->marker_listsing_basic_dir_color;?>'">
                        </td>
                    </tr>									
                </table>							                            
            </div>
            <div class="wd-right gmwd_theme_preview_container" >
                <div class="gmwd_container_wrapper">
                    <div class="gmwd_container">
                        <div id="gmwd_container_1"> 
                            <div class="gmwd_markers_basic_container" style="">
                                <h3 class="gmwd_markers_basic_title"><?php _e("Markers","gmwd");?></h3>	

                                <div class="gmwd_markers_basic_box wd-clear">
                                    <div class="container wd-clear">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <p class="gmwd_marker_title"><img src="<?php echo GMWD_URL."/images/default.png";?> " style="width:32px;max-width:32px;" class="gmwd_markers_basic_icon">Marker 1</p>
                                                <p class="gmwd_marker_title">Marker Address 1</p>
                                                <p class="gmwd_marker_basic_desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>															
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <p class="gmwd_marker_picture wd-text-right">
                                                    <a href="#" ><img src="<?php echo GMWD_URL."/images/no-image.png";?>" ></a>
                                                </p>
                                            </div>						
                                        </div>	                                          
                                        <p>
                                            <a href="javascript:void(0)" class="gmwd_marker_listing_basic_direction"><?php _e("Get Directions","gmwd");?></a>
                                        </p>                                        				
                                    </div>
                                </div> 
                                <div class="gmwd_markers_basic_box wd-clear">
                                    <div class="container wd-clear">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <p class="gmwd_marker_title"><img src="<?php echo GMWD_URL."/images/default.png";?>" style="width:32px;max-width:32px;" class="gmwd_markers_basic_icon">Marker 2</p>
                                                <p class="gmwd_marker_title"> Marker Address 2</p>
                                                <p class="gmwd_marker_basic_desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>															
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <p class="gmwd_marker_picture wd-text-right">
                                                    <a href="#" ><img src="<?php echo GMWD_URL."/images/no-image.png";?>" ></a>
                                                </p>
                                            </div>						
                                        </div>	                                          
                                        <p>
                                            <a href="javascript:void(0)" class="gmwd_marker_listing_basic_direction"><?php _e("Get Directions","gmwd");?></a>
                                        </p>                                        				
                                    </div>
                                </div>                                         
                            </div>                                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } 
    private function theme_marker_listing_advanced($row){
    ?>
        <div class="wd-clear">
            <div class="wd-left">
                <table class="gmwd_edit_table">  
                    <tr>
                        <td><label for="marker_advanced_title_color" title="<?php _e("Set title text color.","gmwd"); ?>"><?php _e("Title Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->marker_advanced_title_color;?>" name="marker_advanced_title_color" id="marker_advanced_title_color" ng-model="marker_advanced_title_color" ng-init="marker_advanced_title_color='<?php echo $row->marker_advanced_title_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="marker_advanced_table_background" title="<?php _e("Set table background color.","gmwd"); ?>"><?php _e("Table Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->marker_advanced_table_background;?>" name="marker_advanced_table_background" id="marker_advanced_table_background" ng-model="marker_advanced_table_background" ng-init="marker_advanced_table_background='<?php echo $row->marker_advanced_table_background;?>'"> 
                        </td>
                    </tr>
                    <tr>
                        <td><label for="marker_advanced_table_border_radius" title="<?php _e("Set table border radius.","gmwd"); ?>"><?php _e("Table Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" value="<?php echo $row->marker_advanced_table_border_radius;?>" name="marker_advanced_table_border_radius" id="marker_advanced_table_border_radius" ng-model="marker_advanced_table_border_radius" ng-init="marker_advanced_table_border_radius='<?php echo $row->marker_advanced_table_border_radius;?>'"> 
                        </td>
                    </tr>                                        
                    <tr>
                        <td><label for="marker_advanced_table_color" title="<?php _e("Set table text color.","gmwd"); ?>"><?php _e("Table Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->marker_advanced_table_color;?>" name="marker_advanced_table_color" id="marker_advanced_table_color" ng-model="marker_advanced_table_color" ng-init="marker_advanced_table_color='<?php echo $row->marker_advanced_table_color;?>'"> 
                        </td>
                    </tr>								
                    <tr>
                        <td><label for="marker_advanced_table_header_background" title="<?php _e("Set table header background color.","gmwd"); ?>"><?php _e("Table Header Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->marker_advanced_table_header_background;?>" name="marker_advanced_table_header_background" id="marker_advanced_table_header_background" ng-model="marker_advanced_table_header_background" ng-init="marker_advanced_table_header_background='<?php echo $row->marker_advanced_table_header_background;?>'"> 
                        </td>
                    </tr>
                    <tr>
                        <td><label for="marker_advanced_table_header_color" title="<?php _e("Set table header text color.","gmwd"); ?>"><?php _e("Table Header Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->marker_advanced_table_header_color;?>" name="marker_advanced_table_header_color" id="marker_advanced_table_header_color" ng-model="marker_advanced_table_header_color" ng-init="marker_advanced_table_header_color='<?php echo $row->marker_advanced_table_header_color;?>'"> 
                        </td>
                    </tr>	
                    <tr>
                        <td><label for="advanced_info_window_background" title="<?php _e("Set advanced info window background color.","gmwd"); ?>"><?php _e("Advanced Info Window Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->advanced_info_window_background;?>" name="advanced_info_window_background" id="advanced_info_window_background" ng-model="advanced_info_window_background" ng-init="advanced_info_window_background='<?php echo $row->advanced_info_window_background;?>'"> 
                        </td>
                    </tr>	
                    <tr>
                        <td><label for="advanced_info_window_title_color" title="<?php _e("Set advanced info window title text color.","gmwd"); ?>"><?php _e("Advanced Info Window Title Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->advanced_info_window_title_color;?>" name="advanced_info_window_title_color" id="advanced_info_window_title_color" ng-model="advanced_info_window_title_color" ng-init="advanced_info_window_title_color='<?php echo $row->advanced_info_window_title_color;?>'"> 
                        </td>
                    </tr>
                    <tr>
                        <td><label for="advanced_info_window_title_background_color" title="<?php _e("Set advanced info window title background color.","gmwd"); ?>"><?php _e("Advanced Info Window Title Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->advanced_info_window_title_background_color;?>" name="advanced_info_window_title_background_color" id="advanced_info_window_title_background_color" ng-model="advanced_info_window_title_background_color" ng-init="advanced_info_window_title_background_color='<?php echo $row->advanced_info_window_title_background_color;?>'"> 
                        </td>
                    </tr>	
                    <tr>
                        <td><label for="advanced_info_window_desc_color" title="<?php _e("Set advanced info window description text color.","gmwd"); ?>"><?php _e("Advanced Info Window Description Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->advanced_info_window_desc_color;?>" name="advanced_info_window_desc_color" id="advanced_info_window_desc_color" ng-model="advanced_info_window_desc_color" ng-init="advanced_info_window_desc_color='<?php echo $row->advanced_info_window_desc_color;?>'"> 
                        </td>
                    </tr>
                    <tr>
                        <td><label for="advanced_info_window_dir_color" title="<?php _e("Set advanced info window directions text color.","gmwd"); ?>"><?php _e("Advanced Info Window Directions Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->advanced_info_window_dir_color;?>" name="advanced_info_window_dir_color" id="advanced_info_window_dir_color" ng-model="advanced_info_window_dir_color" ng-init="advanced_info_window_dir_color='<?php echo $row->advanced_info_window_dir_color;?>'"> 
                        </td>
                    </tr>
                    <tr>
                        <td><label for="advanced_info_window_dir_background_color" title="<?php _e("Set advanced info window directions background color.","gmwd"); ?>"><?php _e("Advanced Info Window Directions Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color"  value="<?php echo $row->advanced_info_window_dir_background_color;?>" name="advanced_info_window_dir_background_color" id="advanced_info_window_dir_background_color" ng-model="advanced_info_window_dir_background_color" ng-init="advanced_info_window_dir_background_color='<?php echo $row->advanced_info_window_dir_background_color;?>'"> 
                        </td>
                    </tr>
                    <tr>
                        <td><label for="advanced_info_window_dir_border_radius" title="<?php _e("Set advanced info window directions border radius.","gmwd"); ?>"><?php _e("Advanced Info Window Directions Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" value="<?php echo $row->advanced_info_window_dir_border_radius;?>" name="advanced_info_window_dir_border_radius" id="advanced_info_window_dir_border_radius" ng-model="advanced_info_window_dir_border_radius" ng-init="advanced_info_window_dir_border_radius='<?php echo $row->advanced_info_window_dir_border_radius;?>'"> 
                        </td>
                    </tr>                                          
                </table>                     
            </div>
            <div class="wd-right gmwd_theme_preview_container" >
                <div class="gmwd_container_wrapper">
                    <div class="gmwd_container">
                        <div id="gmwd_container_1"> 
                            <div class="gmwd_markers_advanced_container">
                                <h3 class="gmwd_markers_advanced_title"><?php _e("Markers","gmwd");?></h3>	
                                <div class="wd-clear gmwd_markers_advanced_filtr">  
                                    <div class="wd-right">                
                                        <input type="text" id="gmwd_search" placeholder="<?php _e("Search","gmwd");?>">			
                                    </div> 
                                </div>
                                <div class="wd-table wd-clear gmwd_markers_advanced_table">
                                    <div class="wd-table-row gmwd_markers_advanced_table_header">
                                        <div class="wd-cell"><a href="#"><?php _e("Title","gmwd");?></a></div>
                                        <div class="wd-cell"><a href="#"><?php _e("Category","gmwd");?></a></div>
                                        <div class="wd-cell"><a href="#"><?php _e("Address","gmwd");?></a></div>
                                        <div class="wd-cell"><a href="#"><?php _e("Description","gmwd");?></a></div>
                                    </div>
                                    
                                    <div class="gmwd_advanced_markers_tbody wd-table-row-group">                                   
                                        <div class="wd-table-row gmwd_marker_advanced_row">
                                            <div class="wd-cell"><img src="<?php echo GMWD_URL."/images/default.png";?>" style="width:32px;max-width:32px;"> &nbsp;Marker 1</div>
                                            <div class="wd-cell">Cat 1</div>
                                            <div class="wd-cell">Address 1</div>
                                            <div class="wd-cell">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </div>
                                        </div>
                                    </div>
                                    <div class="gmwd_advanced_markers_tbody wd-table-row-group">                                   
                                        <div class="wd-table-row gmwd_marker_advanced_row">
                                            <div class="wd-cell"><img src="<?php echo GMWD_URL."/images/default.png";?>" style="width:32px;max-width:32px;"> &nbsp;Marker 2</div>
                                            <div class="wd-cell">Cat 2</div>
                                            <div class="wd-cell">Address 2</div>
                                            <div class="wd-cell">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </div>
                                        </div>
                                    </div>                                                        
                                    
                                </div>
                            </div>
                            <div class="wd_divider"></div>
                            <div style="background-image:url(<?php echo GMWD_URL;?>/images/map.png); height: 255px;padding:15px">       
                                <div class="gmwd_advanced_info_window" >
                                    <div style="text-align:right;cursor:pointer; right:2px">X</div>
                                    <div class="gmwd_advanced_info_window_title">test</div>
                                    <div class="gmwd_advanced_info_window_address">Bedford Ave, Breezy Point, NY 11697, Spojené státy americké</div>
                                    <div class="gmwd_advanced_info_window_description wd-clear">The second form of this method evaluates expressions related to elements based on a function rather than a selector.</div>
                                    <div class="gmwd_advanced_info_window_directions"><a href="javascript:void(0)" class="gmwd_direction">Directions</a>
                                    </div>
                                </div>                                                    
                            </div>                                                    
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }   

    private function theme_marker_listing_carousel($row){
    ?>
        <div class="wd-clear">
            <div class="wd-left">                           
                <table class="gmwd_edit_table">  
                    <tr>
                        <td><label for="carousel_color" title="<?php _e("Set text color.","gmwd"); ?>"><?php _e("Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->carousel_color;?>" name="carousel_color" id="carousel_color" ng-model="carousel_color" ng-init="carousel_color='<?php echo $row->carousel_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="carousel_background_color" title="<?php _e("Set background color.","gmwd"); ?>"><?php _e("Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->carousel_background_color;?>" name="carousel_background_color" id="carousel_background_color" ng-model="carousel_background_color" ng-init="carousel_background_color='<?php echo $row->carousel_background_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="carousel_hover_color" title="<?php _e("Set hover text color.","gmwd"); ?>"><?php _e("Hover Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->carousel_hover_color;?>" name="carousel_hover_color" id="carousel_hover_color" ng-model="carousel_hover_color" ng-init="carousel_hover_color='<?php echo $row->carousel_hover_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="carousel_hover_background_color" title="<?php _e("Set hover background color.","gmwd"); ?>"><?php _e("Hover Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->carousel_hover_background_color;?>" name="carousel_hover_background_color" id="carousel_hover_background_color" ng-model="carousel_hover_background_color" ng-init="carousel_hover_background_color='<?php echo $row->carousel_hover_background_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="carousel_items_count" title="<?php _e("Set items count.","gmwd"); ?>"><?php _e("Items Count:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->carousel_items_count;?>" name="carousel_items_count" id="carousel_items_count" ng-model="carousel_items_count" ng-init="carousel_items_count='<?php echo $row->carousel_items_count;?>'">
                        </td>
                    </tr>	
                    <tr>
                        <td><label for="carousel_item_height" title="<?php _e("Set items height.","gmwd"); ?>"><?php _e("Items Height:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->carousel_item_height;?>" name="carousel_item_height" id="carousel_item_height" ng-model="carousel_item_height" ng-init="carousel_item_height='<?php echo $row->carousel_item_height;?>'"> px
                        </td>
                    </tr>
                    <tr>
                        <td><label for="carousel_item_border_radius" title="<?php _e("Set items border radius.","gmwd"); ?>"><?php _e("Items Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->carousel_item_border_radius;?>" name="carousel_item_border_radius" id="carousel_item_border_radius" ng-model="carousel_item_border_radius" ng-init="carousel_item_border_radius='<?php echo $row->carousel_item_border_radius;?>'"> px
                        </td>
                    </tr>									
                </table>						
    
            </div>						
            <div class="wd-right gmwd_theme_preview_container" >
                <div class="gmwd_container_wrapper">
                    <div class="gmwd_container">
                        <div id="gmwd_container_1"> 
                            <div class="gmwd_markers_carousel_container">			
                                <div id="gmwd_marker_carousel" class="owl-carousel owl-theme">                                    
                                <div class="gmwd_marker_carousel_box">
                                    <div class="wd-clear gmwd_item_box">
                                        <div class="gmwd_item"><p class="gmwd_carousel_title">
                                        <img src="<?php echo GMWD_URL."/images/default.png";?>" style="width:32px;max-width:32px;float:left; padding-right:5px;">
                                        Address 1</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="gmwd_marker_carousel_box">
                                    <div class="wd-clear gmwd_item_box">
                                        <div class="gmwd_item"><p class="gmwd_carousel_title">
                                        <img src="<?php echo GMWD_URL."/images/default.png";?>" style="width:32px;max-width:32px;float:left; padding-right:5px;">
                                        Address 2</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="gmwd_marker_carousel_box">
                                    <div class="wd-clear gmwd_item_box">
                                        <div class="gmwd_item"><p class="gmwd_carousel_title">
                                        <img src="<?php echo GMWD_URL."/images/default.png";?>" style="width:32px;max-width:32px;float:left; padding-right:5px;">
                                        Address 3</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="gmwd_marker_carousel_box">
                                    <div class="wd-clear gmwd_item_box">
                                        <div class="gmwd_item"><p class="gmwd_carousel_title">
                                        <img src="<?php echo GMWD_URL."/images/default.png";?>" style="width:32px;max-width:32px;float:left; padding-right:5px;">
                                        Address 4</p>
                                        </div>
                                    </div>
                                </div>                                                     
                                </div>			
                              <a class="btn prev "></a>
                              <a class="btn next"></a>		
                            </div>
                        </div>						
                    </div>						
                </div>						
            </div>						
       </div>	    
    
    <?php
    } 
    private function theme_marker_listing_inside_map($row){
    ?>
        <div class="wd-clear">
            <div class="wd-left">
                <table class="gmwd_edit_table">  
                    <tr>
                        <td><label for="marker_listsing_inside_map_color" title="<?php _e("Set text color.","gmwd"); ?>"><?php _e("Text Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->marker_listsing_inside_map_color;?>" name="marker_listsing_inside_map_color" id="marker_listsing_inside_map_color" ng-model="marker_listsing_inside_map_color" ng-init="marker_listsing_inside_map_color='<?php echo $row->marker_listsing_inside_map_color;?>'">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="marker_listsing_inside_map_bgcolor" title="<?php _e("Set background color.","gmwd"); ?>"><?php _e("Background Color:","gmwd"); ?></label></td>
                        <td>
                            <input type="text" class="color" value="<?php echo $row->marker_listsing_inside_map_bgcolor;?>" name="marker_listsing_inside_map_bgcolor" id="marker_listsing_inside_map_bgcolor" ng-model="marker_listsing_inside_map_bgcolor" ng-init="marker_listsing_inside_map_bgcolor='<?php echo $row->marker_listsing_inside_map_bgcolor;?>'">
                        </td>
                    </tr>

                    <tr>
                        <td><label for="marker_listsing_inside_map_width" title="<?php _e("Set width.","gmwd"); ?>"><?php _e("Width:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->marker_listsing_inside_map_width;?>" name="marker_listsing_inside_map_width" id="marker_listsing_inside_map_width" ng-model="marker_listsing_inside_map_width" ng-init="marker_listsing_inside_map_width='<?php echo $row->marker_listsing_inside_map_width;?>'">
                        </td>
                    </tr>	
                    <tr>
                        <td><label for="marker_listsing_inside_map_height" title="<?php _e("Set height.","gmwd"); ?>"><?php _e("Height:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->marker_listsing_inside_map_height;?>" name="marker_listsing_inside_map_height" id="marker_listsing_inside_map_height" ng-model="marker_listsing_inside_map_height" ng-init="marker_listsing_inside_map_height='<?php echo $row->marker_listsing_inside_map_height;?>'"> px
                        </td>
                    </tr>
                    <tr>
                        <td><label for="marker_listsing_inside_map_border_radius" title="<?php _e("Set border radius.","gmwd"); ?>"><?php _e("Border Radius:","gmwd"); ?></label></td>
                        <td>
                            <input type="text"  value="<?php echo $row->marker_listsing_inside_map_border_radius;?>" name="marker_listsing_inside_map_border_radius" id="marker_listsing_inside_map_border_radius"ng-model="marker_listsing_inside_map_border_radius" ng-init="marker_listsing_inside_map_border_radius='<?php echo $row->marker_listsing_inside_map_border_radius;?>'"> px
                        </td>
                    </tr>									
                </table>
            </div>
             <div class="wd-right gmwd_theme_preview_container" >
                <div class="gmwd_container_wrapper" style="background-image:url(<?php echo GMWD_URL;?>/images/map.png); height: 265px;">
                    <div class="gmwd_container">
                        <div id="gmwd_container_1"> 
                             <div > 
                                <div class="gmwd_marker_list_inside_map">
                                    <div><img src="<?php echo GMWD_URL;?>/images/marker.png"> Address 1</div>
                                    <div><img src="<?php echo GMWD_URL;?>/images/marker.png"> Address 2</div>
                                    <div><img src="<?php echo GMWD_URL;?>/images/marker.png"> Address 3</div>
                                    <div><img src="<?php echo GMWD_URL;?>/images/marker.png"> Address 4</div>
                                    <div><img src="<?php echo GMWD_URL;?>/images/marker.png"> Address 5</div>
                                </div>
                             </div>           
                        </div>						
                    </div>						
                </div>						
            </div>	                               
        </div>

    <?php
    } 

	private function map_features($style){
		$lists = $this->model->get_lists(); 
		$stylers_array_obj = $style && isset($style->stylers) ? $style->stylers : array();
        $stylers = new StdClass();
        foreach($stylers_array_obj as $styler){
            foreach($styler as $key => $val){
                $stylers->$key = $val;
            }
        }

	?>
		<div class="<?php echo $style ? "" : "wd-template"; ?> gmwd_map_feature" data-key="<?php echo $style ? $style->key : ""; ?>">
			
            <div class="wd-table gmwd_map_feature_type <?php echo $style ? "" : "hide"; ?>">
				<div class="wd-cell wd-cell-valign-middle">
					<span class="feature_type"><?php echo $style ? $style->featureType : ""; ?></span> /
					<span class="element_type"><?php echo $style ? $style->elementType : ""; ?></span>
				</div>
				<div class="wd-cell wd-cell-valign-bottom wd-text-right">				
					<button title="<?php _e("Edit","gmwd");?>"  onclick="gmwdEditFeature(this);return false;" class="wd-edit-feature"></button>
					<button  title="<?php _e("Remove","gmwd");?>" class="wd-delete-feature" onclick="if (confirm('<?php _e("Do you want to delete?","gmwd"); ?>')) { gmwdRemoveFeature(this);} ; return false;"></button>
				</div>
			</div>
			
			<table class="gmwd_edit_table map_styles <?php echo $style ? "hide" : ""; ?>" style="width:100%;">
                <tr>
					<td colspan="2" align="right">
						<button onclick="gmwdAddSingleFeature(this);return false;" class="wd-btn wd-btn-primary" ><?php echo $style ? __("Edit","gmwd") : __("Add","gmwd") ;?></button>
						<button onclick="gmwdCancelFeature(this);return false;" class="wd-btn wd-btn-primary" ><?php _e("Cancel","gmwd");?></button>
					</td>
				</tr>	
				<tr>
					<td><label for="featureType" title="<?php _e("Set map style feature type.","gmwd");?>"><?php _e("Map Style Feature Type","gmwd");?>:</label></td>
					<td>
						<select name="featureType" id="featureType">
							<?php 
								foreach($lists["map_style_feature_types"] as $key => $value){
									$selected = ($style && $style->featureType ==  $key) ? "selected" : "";
									echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
								}
							
							?>
						</select>
					</td>
				</tr>	
				<tr>
					<td>
						<label for="elementType" title="<?php _e("Set map style element type.","gmwd");?>"><?php _e("Map Style Element Type","gmwd");?>:</label>
					</td>
					<td>
						<select name="elementType" id="elementType">
							<?php 
								foreach($lists["map_style_element_types"] as $key => $value){
									$selected = ($style && $style->elementType ==  $key) ? "selected" : "";
									echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
								}							
							?>
						</select>
					</td>								
				</tr>
               <tr> 
                    <td colspan="2" style="border-bottom:1px solid #ccc;"><strong><?php _e("Stylers","gmwd");?></strong></td>
               </tr>  
				<tr>
					<td><label for="color" class="role_label wd-btn wd-btn-primary wd-btn-icon wd-btn-edit"><?php _e("Change Color","gmwd");?></label></td>
					<td class="<?php echo $style && isset($stylers->color) ? "changed_role" : "hide_role"; ?>">
						<input type="text" name="color" id="color" value="<?php echo $style && isset($stylers->color) ? $stylers->color : ""; ?>" class="color wd-form-field" >
                        <span class="default_role wd-btn wd-btn-secondary"><?php _e("Default","gmwd");?></span>
					</td>
				</tr>
				<tr>
					<td><label for="gamma" class="role_label wd-btn wd-btn-primary wd-btn-icon wd-btn-edit"><?php _e("Change Gamma","gmwd");?></label></td>
					<td class="<?php echo $style && isset($stylers->gamma) ? "changed_role" : "hide_role"; ?>">
						<input type="number" name="gamma" id="gamma" value="<?php echo $style && isset($stylers->gamma) ? $stylers->gamma : ""; ?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="<?php echo implode(",",range(0.01,10, 0.01)); ?>" class="wd-form-field" >
                         <span class="default_role wd-btn wd-btn-secondary"><?php _e("Default","gmwd");?></span>
                         <br><span>[0.01,10]</span>
					</td>
				</tr>	
				<tr>
					<td><label for="hue" class="role_label wd-btn wd-btn-primary wd-btn-icon wd-btn-edit"><?php _e("Change Hue","gmwd");?></label></td>
					<td class="<?php echo $style && isset($stylers->hue) ? "changed_role" : "hide_role"; ?>">
						<input type="text" name="hue" id="hue" value="<?php echo $style && isset($stylers->hue) ? $stylers->hue : ""; ?>" class="color wd-form-field" >
                         <span class="default_role wd-btn wd-btn-secondary"><?php _e("Default","gmwd");?></span>
					</td>
				</tr>
				<tr>
					<td><label for="invert_lightness" class="role_label wd-btn wd-btn-primary wd-btn-icon wd-btn-edit"><?php _e("Change Invert Lightness","gmwd");?></label></td>
					<td class="<?php echo $style && isset($stylers->invert_lightness) ? "changed_role" : "hide_role"; ?>">
					  <input type="radio" class="inputbox wd-form-field" id="invert_lightness0" name="invert_lightness"  value="0" <?php echo ($style && isset($stylers->invert_lightness) && $stylers->invert_lightness  ) ? '' : 'checked="checked"'; ?>>
					  <label for="invert_lightness0"><?php _e("No","gmwd"); ?></label>
					  <input type="radio" class="inputbox wd-form-field" id="invert_lightness1" name="invert_lightness"   value="1" <?php echo ($style && isset($stylers->invert_lightness) && $stylers->invert_lightness ) ? 'checked="checked"' : ''; ?>>
					  <label for="invert_lightness1"><?php _e("Yes","gmwd"); ?></label>
                      <span class="default_role wd-btn wd-btn-secondary"><?php _e("Default","gmwd");?></span>
					</td>
				</tr>
				<tr>
					<td><label for="lightness" class="role_label wd-btn wd-btn-primary wd-btn-icon wd-btn-edit"><?php _e("Change Lightness","gmwd");?></label></td>
					<td class="<?php echo $style && isset($stylers->lightness) ? "changed_role" : "hide_role"; ?>">
						<input type="number" name="lightness" id="lightness" value="<?php echo $style && isset($stylers->lightness) ? $stylers->lightness : ""; ?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="<?php echo implode(",",range(-100,100)); ?>" class="wd-form-field">
                         <span class="default_role wd-btn wd-btn-secondary"><?php _e("Default","gmwd");?></span>
                         <br><span>[-100,100]</span>

					</td>
				</tr>
				<tr>
					<td><label for="saturation" class="role_label wd-btn wd-btn-primary wd-btn-icon wd-btn-edit"><?php _e("Change Saturation","gmwd");?></label></td>
					<td class="<?php echo $style && isset($stylers->saturation) ? "changed_role" : "hide_role"; ?>">
						<input type="number" name="saturation" id="saturation" value="<?php echo $style && isset($stylers->saturation) ? $stylers->saturation : ""; ?>" data-slider="true" data-slider-highlight="true" data-slider-theme="volume" data-slider-values="<?php echo implode(",",range(-100,100)); ?>" class="wd-form-field" >
                        <span class="default_role wd-btn wd-btn-secondary"><?php _e("Default","gmwd");?></span>
                        <br><span>[-100,100]</span>
					</td>
				</tr>	
				<tr>
					<td><label for="visibility" class="role_label wd-btn wd-btn-primary wd-btn-icon wd-btn-edit"><?php _e("Change Visibility","gmwd");?></label></td>
					<td class="<?php echo $style && isset($stylers->visibility) ? "changed_role" : "hide_role"; ?>">
						<select name="visibility" id="visibility" class="wd-form-field">
							<option value="on" <?php echo ($style && isset($stylers->visibility) && $stylers->visibility == "on" ) ? "selected" : "";?>><?php _e("On","gmwd");?></option>
							<option value="off" <?php echo ($style && isset($stylers->visibility) && $stylers->visibility == "off" ) ? "selected" : "";?>><?php _e("Off","gmwd");?></option>
							<option value="simplified" <?php echo ($style && isset($stylers->visibility) && $stylers->visibility == "simplified" ) ? "selected" : "";?>><?php _e("Simplified","gmwd");?></option>
						</select>
                        <span class="default_role wd-btn wd-btn-secondary"><?php _e("Default","gmwd");?></span>
					</td>
				</tr>
				<tr>
					<td><label for="weight" class="role_label wd-btn wd-btn-primary wd-btn-icon wd-btn-edit"><?php _e("Change Weight","gmwd");?></label></td>
					<td class="<?php echo $style && isset($stylers->weight) ? "changed_role" : "hide_role"; ?>">
						<input type="number" name="weight" id="weight" value="<?php echo $style && isset($stylers->weight) ? $stylers->weight : ""; ?>" min="0" class="wd-form-field" > <span class="default_role wd-btn wd-btn-secondary"><?php _e("Default","gmwd");?></span><br><small><?php _e("Integers Greater than or Equal to Zero","gmwd");?>.</small>
                       
					</td>
				</tr>
				
			</table>						
            <input type="hidden" value='<?php echo json_encode($style);?>' class="map_style"> 
		</div>

	<?php	
		
	}

    private function edit_map_style($style_id){
        $styles_array = $this->model->get_map_style($style_id);
    ?>
         <div class="wd-clear" style="border: 1px solid #ccc; padding:2px;" > 
            <div class="wd-left" style="width:500px">
            <table class="gmwd_edit_table" style="width:100%;" data-id="<?php echo $style_id;?>">
                <tr class="add-template">
                    <td colspan="2">
                        <button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-addnew" onclick="gmwdAddFeatureTemplate();return false;"><?php _e("Add Feature Style","gmwd");?></button>
                        <div class="wd_divider"></div>
                    </td>
                </tr>                    
                <tr>
                    <td colspan="2">
                        <div class="gmwd_map_features">
                            <?php	
                                $i=0;
                                if($styles_array){
                                    foreach($styles_array as $style){
                                        $style->key = $i;
                                        $style->featureType = isset($style->featureType) ? $style->featureType : "all";
                                        $style->elementType  = isset($style->elementType ) ? $style->elementType  : "all";
                                        $this->map_features($style);
                                        $i++;	
                                    }
                                }
                            ?>
                        </div>
                    </td>
                </tr>	
            </table>
            <?php echo $this->map_features(false);?>
            </div>
            <div class="wd-right">
                <div id="wd-map_styles-map" style="width:600px; height:300px;"></div>
            </div>
        </div>     
    <?php

    }    
    
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}