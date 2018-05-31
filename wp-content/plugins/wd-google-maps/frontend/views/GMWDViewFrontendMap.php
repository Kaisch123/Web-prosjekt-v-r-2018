<?php
	
class GMWDViewFrontendMap extends GMWDViewFrontend{
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

		$params = $this->model->params;
        $shortcode_id = $params["id"]; 
		$row = $this->model->get_map();

        if($row){
            $order = $row->marker_listing_order ? $row->marker_listing_order : "title";
            $order_by = $row->marker_listing_order_dir ? $row->marker_listing_order_dir : "asc";
            $overlays = $this->model->get_overlays( $order, $order_by);
            $theme_id = GMWDHelper::get("f_p") == 1 ?  GMWDHelper::get("theme_id") : $row->theme_id;
            $theme = $this->model->get_theme($theme_id);
            $map_alignment =  $row->map_alignment == "right" ? "wd-right" : "" ; 
            $map_center =  $row->map_alignment == "center" ?  "margin-right:auto; margin-left:auto;" : "";
            $categories = array();
            foreach($overlays->all_markers as $marker){
               if($marker->category) {
                    $categories = array_merge($categories, explode(",",$marker->category));
               }              
            }
            $categories = array_unique( $categories );
		?>
        <style>

        .gmwd_marker_list_inside_map<?php echo $shortcode_id;?> {
            width:<?php echo $theme->marker_listsing_inside_map_width ? $theme->marker_listsing_inside_map_width."px" : "50%";?> !important;
            height:<?php echo $theme->marker_listsing_inside_map_height ? $theme->marker_listsing_inside_map_height."px" : "auto";?> !important;
            max-height: <?php echo $theme->marker_listsing_inside_map_height ? $theme->marker_listsing_inside_map_height."px" : "45%";?> !important;
            background:#<?php echo $theme->marker_listsing_inside_map_bgcolor;?> !important;
            border-radius:<?php echo $theme->marker_listsing_inside_map_border_radius ? $theme->marker_listsing_inside_map_border_radius : "0";?>px !important;
            color:#<?php echo $theme->marker_listsing_inside_map_color;?> !important;
        }
    
       
        </style>
        <div class="gmwd_container_wrapper">
            <div class="gmwd_container">
                <div id="gmwd_container_1">
                    <script>
                        if(typeof gmwdmapData == 'undefined'){
                            var gmwdmapData = []; 
                        }                
                        
                        gmwdmapData["widget" + '<?php echo $shortcode_id;?>'] = "<?php isset($params["widget"]) ? 1 : 0; ?>";                   
                        gmwdmapData["mapId" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->id; ?>");                   
                        gmwdmapData["centerLat" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->center_lat; ?>");
                        gmwdmapData["centerLng" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->center_lng; ?>");	
                        gmwdmapData["zoom" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->zoom_level; ?>");
                        gmwdmapData["mapType" + '<?php echo $shortcode_id;?>'] = "<?php echo $row->type; ?>";
                        gmwdmapData["maxZoom" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->max_zoom; ?>");
                        gmwdmapData["minZoom" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->min_zoom; ?>");
                        gmwdmapData["mapWhellScrolling" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->whell_scrolling; ?>") == 1 ? true : false;				
                        gmwdmapData["infoWindowOpenOn" + '<?php echo $shortcode_id;?>'] = "<?php echo $row->info_window_open_on; ?>" ;				
                        gmwdmapData["mapDragable" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->map_draggable; ?>") == 1 ? true : false;	
    
                        gmwdmapData["mapDbClickZoom" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->map_db_click_zoom; ?>") == 1 ? true : false;	
                                    
                        gmwdmapData["enableZoomControl" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->enable_zoom_control; ?>") == 1 ? true : false;
                        gmwdmapData["enableMapTypeControl" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->enable_map_type_control; ?>") == 1 ? true : false;			
                        gmwdmapData["mapTypeControlOptions" + '<?php echo $shortcode_id;?>'] = {};
                        
                        gmwdmapData["enableScaleControl" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->enable_scale_control; ?>") == 1 ? true : false;
                        gmwdmapData["enableStreetViewControl" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->enable_street_view_control; ?>") == 1 ? true : false;
                        gmwdmapData["enableFullscreenControl" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->enable_fullscreen_control; ?>") == 1 ? true : false;
                        gmwdmapData["enableRotateControl" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->enable_rotate_control; ?>") == 1 ? true : false;
                        gmwdmapData["mapTypeControlPosition" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->map_type_control_position; ?>");
                        
                        gmwdmapData["zoomControlPosition" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->zoom_control_position; ?>");
                        gmwdmapData["streetViewControlPosition" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->street_view_control_position; ?>");
                        
                        gmwdmapData["fullscreenControlPosition" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->fullscreen_control_position; ?>");
                        
                        gmwdmapData["mapTypeControlStyle" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->map_type_control_style; ?>");				
                        gmwdmapData["mapBorderRadius" + '<?php echo $shortcode_id;?>'] = "<?php echo $row->border_radius; ?>";
                        gmwdmapData["enableBykeLayer" + '<?php echo $shortcode_id;?>'] =  Number("<?php echo $row->enable_bicycle_layer; ?>");	
                        gmwdmapData["enableTrafficLayer" + '<?php echo $shortcode_id;?>'] =  Number("<?php echo $row->enable_traffic_layer; ?>");				
                        gmwdmapData["enableTransitLayer" + '<?php echo $shortcode_id;?>'] =  Number("<?php echo $row->enable_transit_layer; ?>");	
                        gmwdmapData["geoRSSURL" + '<?php echo $shortcode_id;?>'] = "<?php echo $row->georss_url; ?>";	
                        gmwdmapData["KMLURL" + '<?php echo $shortcode_id;?>'] = "<?php echo $row->kml_url; ?>";	
                        gmwdmapData["fusionTableId" + '<?php echo $shortcode_id;?>'] = '<?php echo $row->fusion_table_id; ?>';	
                        gmwdmapData["fusionTableWhereField" + '<?php echo $shortcode_id;?>'] = '<?php echo $row->fusion_table_where_filed; ?>';	
                        gmwdmapData["fusionTableWhereOperator" + '<?php echo $shortcode_id;?>'] = '<?php echo $row->fusion_table_where_operator; ?>';	
                        gmwdmapData["fusionTableWhereValue" + '<?php echo $shortcode_id;?>'] = "<?php echo htmlspecialchars_decode(addslashes($row->fusion_table_where_value),ENT_QUOTES); ?>";	

                        gmwdmapData["mapTheme" + '<?php echo $shortcode_id;?>'] = '<?php echo sanitize_text_field(stripslashes(htmlspecialchars_decode ($theme->map_style_code))) ;?>';			
                        gmwdmapData["mapMarkers" + '<?php echo $shortcode_id;?>'] = JSON.parse('<?php echo $overlays->all_markers ? json_encode($overlays->all_markers) : "[]";?>');
                        gmwdmapData["mapCircles" + '<?php echo $shortcode_id;?>'] = JSON.parse('<?php echo $overlays->circles ? json_encode($overlays->circles) : "[]";?>');
                        
                        gmwdmapData["mapRectangles" + '<?php echo $shortcode_id;?>'] = JSON.parse('<?php echo $overlays->rectangles ? json_encode($overlays->rectangles) : "[]";?>');
                        
                        gmwdmapData["mapPolygons" + '<?php echo $shortcode_id;?>'] =  JSON.parse('<?php echo $overlays->polygons ?json_encode($overlays->polygons) : "[]";?>');
                        gmwdmapData["mapPolylines" + '<?php echo $shortcode_id;?>'] = JSON.parse('<?php echo $overlays->polylines ? json_encode($overlays->polylines) : "[]";?>');
                        
                        gmwdmapData["enableCategoryFilter" + '<?php echo $shortcode_id;?>'] = "<?php echo  $row->enable_category_filter;?>";
                        
                        gmwdmapData["enableCategoryFilterInsideMap" + '<?php echo $shortcode_id;?>'] = "<?php echo  $row->marker_categories_inside_map;?>";
                        gmwdmapData["categoriesFilterPosition" + '<?php echo $shortcode_id;?>'] = Number("<?php echo  $row->category_filter_im_position ? $row->category_filter_im_position : 10 ;?>");
                      
                        gmwdmapData["enableDirections" + '<?php echo $shortcode_id;?>'] = "<?php echo  isset($params["widget"]) ? 0 : $row->enable_directions;?>";
                        gmwdmapData["enableStoreLocatior" + '<?php echo $shortcode_id;?>'] = "<?php echo  $row->enable_store_locator;?>";
                        gmwdmapData["storeLocatorDistanceIn" + '<?php echo $shortcode_id;?>'] = "<?php echo $row->distance_in;?>";
                        
                        gmwdmapData["storeLocatorStrokeWidth" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->circle_line_width;?>");
                        gmwdmapData["storeLocatorFillColor" + '<?php echo $shortcode_id;?>'] = "#" + "<?php echo $row->circle_fill_color;?>";
                        gmwdmapData["storeLocatorFillOpacity" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->circle_fill_opacity;?>");
                        gmwdmapData["storeLocatorLineColor" + '<?php echo $shortcode_id;?>'] = "#" + "<?php echo $row->circle_line_color;?>";
                        gmwdmapData["storeLocatorLineOpacity" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->circle_line_opacity;?>");
                        
                        gmwdmapData["markerListingType" + '<?php echo $shortcode_id;?>'] = "<?php echo  $row->marker_listing_type;?>";
                        gmwdmapData["markerListInsideMap" + '<?php echo $shortcode_id;?>'] = "<?php echo  $row->marker_list_inside_map;?>";
                        gmwdmapData["markerListPosition" + '<?php echo $shortcode_id;?>'] = Number("<?php echo  $row->marker_list_inside_map_position;?>");
                    
                        gmwdmapData["infoWindowtype" + '<?php echo $shortcode_id;?>'] = "<?php echo  $row->infowindow_type ;?>";
                        
                        gmwdmapData["advancedInfoWindowPosition" + '<?php echo $shortcode_id;?>'] = Number("<?php echo  $row->advanced_info_window_position ? $row->advanced_info_window_position : 10 ;?>");
                        
                        gmwdmapData["geolocateUser" + '<?php echo $shortcode_id;?>'] = Number("<?php echo $row->geolocate_user;?>");
                        gmwdmapData["items" + '<?php echo $shortcode_id;?>'] = "<?php echo $theme->carousel_items_count;?>";
                        
                        gmwdmapData["infoWindowInfo" + '<?php echo $shortcode_id;?>'] = "<?php echo $row->info_window_info;?>";
      
                        gmwdmapData["enableSerchBox" + '<?php echo $shortcode_id;?>'] = "<?php echo $row->enable_searchbox;?>";
                        gmwdmapData["serchBoxPosition" + '<?php echo $shortcode_id;?>'] = Number("<?php echo  $row->searchbox_position ? $row->searchbox_position : 3 ;?>");
                        
                        gmwdmapData["allMarkers" + '<?php echo $shortcode_id;?>'] = [];
                        gmwdmapData["allCircles" + '<?php echo $shortcode_id;?>'] = [];
                        gmwdmapData["allCircleMarkers" + '<?php echo $shortcode_id;?>'] = [];
                        gmwdmapData["allPolygons" + '<?php echo $shortcode_id;?>'] = [];
                        gmwdmapData["allRectangles" + '<?php echo $shortcode_id;?>'] = [];
                        gmwdmapData["allPolygonMarkers" + '<?php echo $shortcode_id;?>'] = [];
                        gmwdmapData["allPolylines" + '<?php echo $shortcode_id;?>'] = [];
                        gmwdmapData["allPolylineMarkers" + '<?php echo $shortcode_id;?>'] = [];
                        gmwdmapData["infoWindows" + '<?php echo $shortcode_id;?>'] = [];
                        gmwdmapData["ajaxData" + '<?php echo $shortcode_id;?>']  = {};
                        
                        var ajaxURL = "<?php echo admin_url('admin-ajax.php');?>";
                        var markerDefaultIcon = "<?php echo  gmwd_get_option("marker_default_icon");?>";
                        var GMWD_URL = "<?php echo GMWD_URL;?>";
                        jQuery( document ).ready(function() {					
                            gmwdInitMainMap("wd-map<?php echo $shortcode_id;?>",false, '<?php echo $shortcode_id;?>');
                            gmwdReadyFunction('<?php echo $shortcode_id;?>');
                        });
                    </script>
                    <style>
                        .gmwd_advanced_info_window<?php echo $shortcode_id;?>{
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

                        .gmwd_advanced_info_window_title<?php echo $shortcode_id;?>{
                            font-weight: bold;
                            font-size: 16px;
                            background:#<?php echo $theme->advanced_info_window_title_background_color;?>!important;
                            padding:4px 5px;
                            color:#<?php echo $theme->advanced_info_window_title_color;?>!important;
                        }
                        .gmwd_advanced_info_window_address<?php echo $shortcode_id;?>{
                            font-size: 14px;
                            color:#<?php echo $theme->advanced_info_window_desc_color;?>!important;
                        }
                        .gmwd_advanced_info_window_description<?php echo $shortcode_id;?>{
                            font-size: 12px;
                            color:#<?php echo $theme->advanced_info_window_desc_color;?>!important;
                        }
                        .gmwd_advanced_info_window_directions<?php echo $shortcode_id;?> a{
                            display:inline-block;
                            padding:4px 20px;
                            background:#<?php echo $theme->advanced_info_window_dir_background_color;?>!important;
                            color:#<?php echo $theme->advanced_info_window_dir_color;?>!important;
                            font-size: 14px;
                            border-radius:<?php echo $theme->marker_listsing_inside_map_border_radius;?>px !important;
                        }
                    </style>
                    <?php
                        if(defined('GMWDMC_NAME')){
                            GMWDMC::marker_clusters_view($row, $shortcode_id);
                        }
                        if(!isset($params["widget"])){
                            //directions
                            if(($row->direction_position == 0 || $row->direction_position == 1) && $row->enable_directions == 1){						
                                $this->display_directions($row, $theme, $shortcode_id);					
                            }
                            
                            //store locator
                            if(($row->store_locator_position == 0 || $row->store_locator_position == 1) && $row->enable_store_locator == 1){						
                                $this->display_store_locator($row, $theme, $shortcode_id);				
                            }
                            
                            //marker listing                   
                            if(count($overlays->markers) > 0 && $row->marker_list_position == 1){
                                if($row->marker_listing_type == 1){
                                    $this->display_markers_list_basic($row, $overlays->markers, $theme, $shortcode_id);
                                }
                                elseif($row->marker_listing_type == 2){
                                    $this->display_markers_list_advanced($row, $overlays->markers, $theme, $shortcode_id);
                                }
                                elseif($row->marker_listing_type == 3 ){
                                    $this->display_markers_list_carousel($row, $overlays->all_markers, $theme, $shortcode_id);
                                }
                            }
                            // category filter
                            if($row->enable_category_filter == 1){
                                $marker_categories = $this->model->get_marker_categories($row->show_cat_icon, $categories);
                                // category filter inside map
                                if($row->marker_categories_inside_map == 1){
                                   $this->display_category_filter_inside_map($row, $shortcode_id, $marker_categories);
                                } 
                                else{              
                                    $this->display_category_filter($row, $shortcode_id, $marker_categories);
                                } 
                            }

                        }				
                    ?>
                    <div class="wd-clear">
                        <div id="wd-map<?php echo $shortcode_id;?>" class="wd-row <?php echo $map_alignment;?>" style="<?php echo $map_center;?> height:<?php echo $row->height ;?>px; width:<?php echo $row->width.$row->width_percent;?>"></div>
                    </div>
                    <?php
                        if(!isset($params["widget"])){
                            // user generated markers
                            if(defined('GMWDUGM_NAME') && $row->enable_user_gen_markers == 1){
                                GMWDUGM::user_generated_markers_view($row, $shortcode_id, $params, $theme);  
                            }
                        
                            //directions
                            if(($row->direction_position == 2 || $row->direction_position == 3) && $row->enable_directions == 1){						
                                $this->display_directions($row, $theme, $shortcode_id);					
                            }
                            
                            //store locator
                            if(($row->store_locator_position == 2 || $row->store_locator_position == 3) && $row->enable_store_locator == 1){						
                                $this->display_store_locator($row, $theme, $shortcode_id);				
                            }
                            
                            //marker listing
                            
                            if(count($overlays->markers) > 0 && $row->marker_list_position == 0){
                                if($row->marker_listing_type == 1){
                                    $this->display_markers_list_basic($row, $overlays->markers, $theme, $shortcode_id);
                                }
                                elseif($row->marker_listing_type == 2){
                                    $this->display_markers_list_advanced($row, $overlays->markers, $theme, $shortcode_id);
                                }
                                elseif($row->marker_listing_type == 3 ){
                                    $this->display_markers_list_carousel($row, $overlays->all_markers, $theme, $shortcode_id);
                                }
                            }
                        }
            
                    ?>
                            
                </div>
            </div>
		</div>
		<?php
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
    private function display_category_filter($row, $shortcode_id, $marker_categories){   
    ?>
         <style>
            <?php if($row->category_filter_type == 0){
            ?>
                .gmwd_cat_dropdown{
                    display:none;
                    border: 1px solid #ccc;
                }
            <?php
            }
            else{
            ?>
                .gmwd_cat_dropdown > ul > li{
                   float:left;
                   border: 1px solid #ccc;
                   margin-right: 2px;
                }
                .gmwd_cat_dropdown  ul  li{
                   position:relative;
                }                
                .gmwd_cat_dropdown > ul > li  ul{
                    position: absolute;
                    z-index: 9999;
                    background: #fff;
                    width: 300px;
                    top: 41px;
                    margin: 0;
                }
                
            <?php
            }
          
            ?>
            .gmwd_cat_dropdown .gmwd_categories ul{
                display:none;
            }
            .gmwd_categories_container ul, .gmwd_store_locator_categories_container ul {
                margin: 0 0 0 20px;
                list-style-type:none;

            }
            .gmwd_cat_dropdown > .gmwd_categories, .gmwd_store_locator_categories_container > .gmwd_categories{   
               margin:0 !important;
            } 
          
         </style>   
         <div class="gmwd_categories_wrapper gmwd_categories_wrapper<?php echo $shortcode_id;?>">           
            <div class="gmwd_categories_container wd-clear">                
                <?php if($row->category_filter_type == 0){
                ?>
                    <div class="gmwd_category gmwd_category_selected">
                        <div class="gmwd_category_label gmwd_category_selected_cats<?php echo $shortcode_id;?>"> &nbsp;<?php _e("Filter By Category","gmwd");?></div>
                        <div class="gmwd_arrow">
                            <img src="<?php echo GMWD_URL;?>/images/arrow_down.png" style="max-height:20px; margin-top:-4px; margin-right: 4px;" class="gmwd_open_filter<?php echo $shortcode_id;?>">							
                        </div>
                    </div>

                <?php
                }  
                else{    
                ?>
                    <h4><?php _e("Filter By Category","gmwd");?></h4>
                <?php
                }
                ?>                
                    <div class="gmwd_cat_dropdown">
                        <?php echo $marker_categories[0];?>
                    </div>    
                
            </div>   
        </div>   
    <?php
    }
    
    private function display_category_filter_inside_map($row, $shortcode_id, $marker_categories){
    ?>
          <style>
            <?php if($row->imcategory_filter_type == 1 ){
            ?>
                .gmwd_cat_inside_map > ul > li{
                   float:left;
                   border: 1px solid #ccc;                   
                }
                .gmwd_cat_inside_map  ul  li{
                   position:relative;
                }                
                .gmwd_cat_inside_map > ul > li  ul{
                    position: absolute;
                    z-index: 9999;
                    background: #fff;
                    width: 300px;
                    top: 35px;
                    margin: 0;
                }
                .gmwd_cat_inside_map{ 
                   background: transparent;                   
                }
                .gmwd_category{
                    background: #fff;
                }
                .gmwd_cat_inside_map ul ul{
                    display:none;
                }                
            <?php
            }
            ?>            
         </style>                               
         <script>   
            var markerCategoriesTree = '<?php echo addslashes($marker_categories[0]); ?>';
         </script>                              

    <?php                                
    }
    
	private function display_markers_list_basic($row, $markers, $theme, $shortcode_id){
        $total = $this->model->get_markers_page_nav(); 
	?>
		<style>
		.gmwd_markers_basic_title<?php echo $shortcode_id;?>{
			color:#<?php echo $theme->marker_listsing_basic_title_color;?>!important;
		}
        .gmwd_markers_basic_container<?php echo $shortcode_id;?>{
            background:#<?php echo $theme->marker_listsing_basic_bgcolor;?>;
            padding:5px;
        }
        .gmwd_marker_title<?php echo $shortcode_id;?>{
            color:#<?php echo $theme->marker_listsing_basic_marker_title_color;?>;
        }   
        .gmwd_marker_basic_desc<?php echo $shortcode_id;?>{
            color:#<?php echo $theme->marker_listsing_basic_marker_desc_color;?>;
        }  
		.gmwd_marker_listing_basic_direction<?php echo $shortcode_id;?>{
			border-radius:<?php echo $theme->marker_listsing_basic_dir_border_radius;?>px!important;
			padding: 5px 28px 6px 6px!important;
			background-color:#<?php echo $theme->marker_listsing_basic_dir_background_color;?>!important;
		    color:#<?php echo $theme->marker_listsing_basic_dir_color;?>!important;
			background-image:url('<?php echo  GMWD_URL."/images/css/d_arrow.png";?>');
			background-position:95% center;
			background-repeat:no-repeat;		
			width:<?php echo $theme->marker_listsing_basic_dir_width ? $theme->marker_listsing_basic_dir_width."px" : "auto" ;?>!important;
			height:<?php echo $theme->marker_listsing_basic_dir_height ? $theme->marker_listsing_basic_dir_height."px" : "auto" ;?>!important;
		}
		.gmwd_marker_listing_basic_direction<?php echo $shortcode_id;?>:hover{
			color:#<?php echo $theme->marker_listsing_basic_dir_color;?>!important;
		}
        .gmwd_marker_listing_basic_active{
            border: 1px solid #ccc;
            padding: 3px;
        }
		</style>
		<div class="gmwd_markers_basic_container gmwd_markers_basic_container<?php echo $shortcode_id;?> gmwd_markers_data<?php echo $shortcode_id;?>">
			<h3 class="gmwd_markers_basic_title<?php echo $shortcode_id;?>"><?php echo $row->listing_header_title ? $row->listing_header_title : __("Markers","gmwd");?></h3>	
        <?php
		$i = 0;
		foreach($markers as $marker){
			$marker_icon = GMWD_URL."/images/default.png";
			if($marker->custom_marker_url){
				$marker_icon = 	$marker->custom_marker_url;
			}
			else if(gmwd_get_option("marker_default_icon")){
				$marker_icon = gmwd_get_option("marker_default_icon");
			}			
		
	?>
			<div class="gmwd_markers_basic_box wd-clear">
				<div class="container wd-clear">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<p class="gmwd_marker_title gmwd_marker_title<?php echo $shortcode_id;?>" data-lat="<?php echo $marker->lat;?>" data-lng="<?php echo $marker->lng;?>"  data-id="<?php echo $marker->id;?>" data-shortcode="<?php echo $shortcode_id;?>" onclick="onBasicRowClick(this);"><img src="<?php echo $marker_icon;?> " style="width:32px;max-width:32px;" class="gmwd_markers_basic_icon"><?php echo $marker->title;?></p>
							<p class="gmwd_marker_title gmwd_marker_title<?php echo $shortcode_id;?>" data-lat="<?php echo $marker->lat;?>" data-lng="<?php echo $marker->lng;?>"  data-id="<?php echo $marker->id;?>" data-shortcode="<?php echo $shortcode_id;?>" onclick="onBasicRowClick(this);"><?php echo $marker->address;?></p>
							<?php if($marker->description){
								echo "<p class='gmwd_marker_basic_desc".$shortcode_id."'>".htmlspecialchars_decode(implode('<br>', $marker->description))."</p>";

							}
							?>																
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<p class="gmwd_marker_picture wd-text-right">
								<a href="<?php echo $marker->link_url ;?>" ><img src="<?php echo $marker->pic_url ? $marker->pic_url : GMWD_URL."/images/no-image.png";?>" ></a>
							</p>
						</div>						
					</div>	
					<?php if($row->enable_directions == 1){
					?>
						<p>
							<a href="javascript:void(0)" data-lat="<?php echo $marker->lat;?>" data-lng="<?php echo $marker->lng;?>" data-address="<?php echo $marker->address;?>" class="gmwd_marker_listing_basic_direction gmwd_marker_listing_basic_direction<?php echo $shortcode_id;?> gmwd_direction" data-key="<?php echo $shortcode_id;?>" onclick="showDirectionsBox(this);"><?php _e("Get Directions","gmwd");?></a>
						</p>
					<?php
					}
					?>					
				</div>

			</div>
	
	<?php
			$i++;
		}
	?>
            <div class="gmwd-pagination" data-limit="<?php echo isset($_POST["limit"]) ? esc_html(stripslashes($_POST["limit"])) : 20;?>" data-total="<?php echo $total;?>" onclick="gmwdPagination(event, this);" <?php if($total<=20) echo "style='display:none;'";?> data-key="<?php echo $shortcode_id;?>" >
                <div class="wd-pagination">
                    <span><?php _e("Load More","gmwd");?></span>
                </div>
            </div>  
		</div>
	<?php	
	}
	private function display_markers_list_advanced($row, $markers, $theme, $shortcode_id){
        $total = $this->model->get_markers_page_nav();
	?>
		<style>
        .gmwd_markers_advanced_container  .wd-table{
            width: 100%;
        }
		.gmwd_markers_advanced_title<?php echo $shortcode_id;?>{
			color:#<?php echo $theme->marker_advanced_title_color;?>!important;
            margin:5px 0px !important;
		}
   
		.gmwd_markers_advanced_table_header<?php echo $shortcode_id;?>{
			background-color:#<?php echo $theme->marker_advanced_table_header_background;?>!important;			
		}
        .gmwd_markers_advanced_table_header<?php echo $shortcode_id;?> .wd-cell:first-child{
            border-top-left-radius:<?php echo $theme->marker_advanced_table_border_radius;?>px;  
        }
        .gmwd_markers_advanced_table_header<?php echo $shortcode_id;?> .wd-cell:last-child{
            border-top-right-radius:<?php echo $theme->marker_advanced_table_border_radius;?>px;  
        }
		.gmwd_markers_advanced_table_header<?php echo $shortcode_id;?> .wd-cell a{
			color:#<?php echo $theme->marker_advanced_table_header_color;?>!important;
		}
		.gmwd_advanced_markers_tbody<?php echo $shortcode_id;?>{
			background-color:#<?php echo $theme->marker_advanced_table_background;?>!important;	
		}
		.gmwd_advanced_markers_tbody<?php echo $shortcode_id;?>  {
			color:#<?php echo $theme->marker_advanced_table_color;?>!important;			
		}
        .gmwd_advanced_markers_tbody<?php echo $shortcode_id;?>:last-child .wd-cell:first-child{
            border-bottom-left-radius:<?php echo $theme->marker_advanced_table_border_radius;?>px;  
        }
        .gmwd_advanced_markers_tbody<?php echo $shortcode_id;?>:last-child .wd-cell:last-child{
            border-bottom-right-radius:<?php echo $theme->marker_advanced_table_border_radius;?>px;  
        }

        .gmwd_marker_listing_advanced_active{
            color:#<?php echo $theme->marker_advanced_table_header_color;?>!important;
            background-color:#<?php echo $theme->marker_advanced_table_header_background;?>!important;		
        }
        .gmwd_markers_advanced_table_header<?php echo $shortcode_id;?> .gmwd_header_img{
            width:2%;
        }    
        .gmwd_markers_advanced_table_header<?php echo $shortcode_id;?> .gmwd_header_desc{
            width:30%;
        }
        .gmwd_markers_advanced_table_header<?php echo $shortcode_id;?> .gmwd_header_title{
            width:20%;
        }
        .gmwd_markers_advanced_table_header<?php echo $shortcode_id;?> .gmwd_header_cat{
            width:20%;
        }   
        .gmwd_markers_advanced_table_header<?php echo $shortcode_id;?> .gmwd_header_address{
            width:30%;
        } 
        <?php if(count($row->advanced_table_columns) > 3){
        ?>
            @media all and (max-width: 640px) {
                .gmwd_markers_advanced_container .wd-table .wd-table-row .gmwd_header_desc, .gmwd_markers_advanced_container .wd-table .wd-table-row .wd-cell-desc{
                    display:none;
                }
            }

            @media all and (max-width: 475px) {
                .gmwd_markers_advanced_container .wd-table .wd-table-row .gmwd_header_title, .gmwd_markers_advanced_container .wd-table .wd-table-row .wd-cell-title{
                    display:none;
                }
            } 
        <?php
        }
        ?>        
		</style>
		<div class="gmwd_markers_advanced_container gmwd_markers_data<?php echo $shortcode_id;?>" >
			<h3 class="gmwd_markers_advanced_title gmwd_markers_advanced_title<?php echo $shortcode_id;?>"><?php echo $row->listing_header_title ? $row->listing_header_title : __("Markers","gmwd");?></h3>	
			<div class="wd-clear gmwd_markers_advanced_filtr">  
                <div class="wd-right">                
                    <input type="text" id="gmwd_search<?php echo $shortcode_id;?>" placeholder="<?php _e("Search","gmwd");?>" value="<?php echo isset($_POST["search"]) ? esc_html(stripslashes($_POST["search"])) : "";?>"><br>			
                </div> 
			</div>
			<div class="wd-table wd-clear">
				<div class="wd-table-row gmwd_markers_advanced_table_header<?php echo $shortcode_id;?>">
                    <?php 
                    if(in_array("icon", $row->advanced_table_columns)){
                    ?>
                        <div class="wd-cell gmwd_header_img"></div>
                    <?php 
                    }
                    if(in_array("title", $row->advanced_table_columns)){
                    ?>
                        <div class="wd-cell gmwd_header_title"><a href="#" onclick="gmwdMarkerOrder('title', <?php echo $shortcode_id;?>);return false;"><?php _e("Title","gmwd");?></a></div>
                    <?php
                    }
                    if(in_array("category", $row->advanced_table_columns)){
                    ?>
                        <div class="wd-cell gmwd_header_cat"><a href="#" onclick="gmwdMarkerOrder('cat_title', <?php echo $shortcode_id;?>);return false;"><?php _e("Category","gmwd");?></a></div>
                    <?php
                    }
                    if(in_array("address", $row->advanced_table_columns)){
                    ?>
                        <div class="wd-cell gmwd_header_address"><a href="#" onclick="gmwdMarkerOrder('address', <?php echo $shortcode_id;?>);return false;"><?php _e("Address","gmwd");?></a></div>
                    <?php
                    }
                    if(in_array("desc", $row->advanced_table_columns)){
                    ?>
                        <div class="wd-cell gmwd_header_desc"><a href="#" onclick="gmwdMarkerOrder('description', <?php echo $shortcode_id;?>);return false;"><?php _e("Description","gmwd");?></a></div>
                    <?php
                    }
                    ?>
				</div>
                
                <div class="gmwd_advanced_markers_tbody<?php echo $shortcode_id;?> wd-table-row-group">
                <?php
                    $i = 0;
                    foreach($markers as $marker){
                        $marker_icon = GMWD_URL."/images/default.png";;
                        if($marker->custom_marker_url){
                            $marker_icon = 	$marker->custom_marker_url;
                        }
						else if(gmwd_get_option("marker_default_icon")){
							$marker_icon = gmwd_get_option("marker_default_icon");
						}
    
                        $alternate = $i%2 == 0 ? 'gmwd_alternate'.$shortcode_id : "";			
                ?>
                        <div class="wd-table-row gmwd_marker_advanced_row  gmwd_marker_advanced_row<?php echo $shortcode_id;?> <?php echo $alternate; ?>" data-lat="<?php echo $marker->lat;?>" data-lng="<?php echo $marker->lng;?>" data-id="<?php echo $marker->id;?>" data-shortcode="<?php echo $shortcode_id;?>">
                            <?php 
                            if(in_array("icon", $row->advanced_table_columns)){
                            ?>
                                <div class="wd-cell wd-cell-img"><img src="<?php echo $marker_icon;?>" style="width:32px;max-width:32px;"></div>
                            <?php 
                            }
                            if(in_array("title", $row->advanced_table_columns)){
                            ?>  
                                <div class="wd-cell wd-cell-title"><?php echo $marker->title;?></div>
                            <?php
                            }
                            if(in_array("category", $row->advanced_table_columns)){
                            ?>
                                <div class="wd-cell wd-cell-cat"><?php echo $marker->cat_title;?></div>
                            <?php
                            }
                            if(in_array("address", $row->advanced_table_columns)){
                            ?>
                                <div class="wd-cell wd-cell-address"><?php echo $marker->address;?></div>
                            <?php
                            }
                            if(in_array("desc", $row->advanced_table_columns)){
                            ?>                            
                                <div class="wd-cell wd-cell-desc"><?php echo htmlspecialchars_decode(implode('<br>', $marker->description)); ?></div>
                            <?php
                            }
                            ?>
                        </div>
                
                <?php
                        $i++;
                    }
                ?>
                </div>
                
			</div>
            <div class="gmwd-pagination" data-limit="<?php echo isset($_POST["limit"]) ? esc_html(stripslashes($_POST["limit"])) : 20;?>" data-total="<?php echo $total;?>" onclick="gmwdPagination(event, this);" <?php if($total<=20) echo "style='display:none;'";?> data-key="<?php echo $shortcode_id;?>" >
                <div class="wd-pagination">
                    <span><?php _e("Load More","gmwd");?></span>
                </div>
            </div>  
		</div>
		<input type="hidden" id="orderDir<?php echo $shortcode_id;?>" value="ASC">

	<?php	
	}

	private function display_markers_list_carousel($row, $markers, $theme, $shortcode_id){
  
	?>
		<style>
		.gmwd_marker_carousel_box<?php echo $shortcode_id;?>{
			padding: 8px;
			background: #<?php echo $theme->carousel_background_color;?>!important;
			color: #<?php echo $theme->carousel_color;?>!important;
			border-right: 1px solid #fff;
			cursor:pointer;
			min-height:<?php echo $theme->carousel_item_height ? $theme->carousel_item_height : "70";?>px;
			overflow: hidden;
            border-radius:<?php echo $theme->carousel_item_border_radius ? $theme->carousel_item_border_radius : 0;?>px;     
		}
		.gmwd_marker_carousel_box<?php echo $shortcode_id;?>:hover, .gmwd_carousel_active{
			background: #<?php echo $theme->carousel_hover_background_color;?>!important;
			color:#<?php echo $theme->carousel_hover_color;?>!important;
		}

        .gmwd_marker_carousel_box<?php echo $shortcode_id;?> .gmwd_item_box{
            height:<?php echo $theme->carousel_item_height ? $theme->carousel_item_height : "70";?>px;
            overflow:hidden;
        }
        .gmwd_marker_carousel_box<?php echo $shortcode_id;?> .gmwd_item_box .gmwd_item{
            height:97%;
            overflow:hidden;
        }

		.gmwd_marker_carousel_box<?php echo $shortcode_id;?>:hover a{
			color:#<?php echo $theme->carousel_hover_color;?>!important;
			
		}
		</style>
		<div class="gmwd_markers_carousel_container gmwd_markers_data<?php echo $shortcode_id;?>">			
			<div id="gmwd_marker_carousel<?php echo $shortcode_id;?>" class="owl-carousel owl-theme">
        <?php
		$i = 0;
		foreach($markers as $marker){
			$marker_icon = GMWD_URL."/images/default.png";
			if($marker->custom_marker_url){
				$marker_icon = 	$marker->custom_marker_url;
			}
			else if(gmwd_get_option("marker_default_icon")){
				$marker_icon = gmwd_get_option("marker_default_icon");
			}			
        ?>
			<div class="gmwd_marker_carousel_box gmwd_marker_carousel_box<?php echo $shortcode_id;?> " data-lat="<?php echo $marker->lat;?>" data-lng="<?php echo $marker->lng;?>" data-id="<?php echo $marker->id;?>"  data-shortcode="<?php echo $shortcode_id;?>">
				<div class="wd-clear gmwd_item_box">
					<div class="gmwd_item"><p class="gmwd_carousel_title"><img src="<?php echo $marker_icon;?>" style="width:32px;max-width:32px;float:left; padding-right:5px;"><?php echo $marker->address;?></p></div>
				</div>
			</div>
	
	<?php
			$i++;
		}
	?>
			</div>			
          <a class="btn prev prev<?php echo $shortcode_id;?>"></a>
          <a class="btn next next<?php echo $shortcode_id;?>"></a>		
		</div>
		

	<?php	
	}		
	
	private function display_store_locator($row, $theme, $shortcode_id){
		$marker_categories = $this->model->get_marker_categories(false);
        $class_columns_first =  $theme->store_locator_columns == 0 ? "col-lg-12 col-md-12 col-sm-12 col-xs-12" : "col-lg-7 col-md-7 col-sm-12 col-xs-12";
        $class_columns_second =  $theme->store_locator_columns == 0 ? "col-lg-12 col-md-12 col-sm-12 col-xs-12" : "col-lg-5 col-md-5 col-sm-12 col-xs-12";
        $btn_alignment_class = $theme->store_locator_buttons_alignment == 0 ? "wd-text-left" : ($theme->store_locator_buttons_alignment == 1 ? "wd-text-center" : "wd-text-right");
	?>
		<style>
		.gmwd_store_locator_container<?php echo $shortcode_id;?>{
			width:<?php echo  $row->store_locator_window_width ? $row->store_locator_window_width .$row->store_locator_window_width_unit : "auto";?>!important;
			float:<?php echo $row->store_locator_position == 1 || $row->store_locator_position == 3 ? "right" : "left";?>;
            background:#<?php echo $theme->store_locator_window_bgcolor;?>!important;
            padding:5px;
            border-radius:<?php echo $theme->store_locator_window_border_radius ? $theme->store_locator_window_border_radius : 0 ;?>px!important;
		}
		.gmwd_store_locator_title<?php echo $shortcode_id;?>{
			color:#<?php echo $theme->store_locator_title_color;?>!important;
            margin:5px 0px !important;
		}
		.gmwd_store_locator_address<?php echo $shortcode_id;?>, .gmwd_store_locator_radius<?php echo $shortcode_id;?>{
			border-radius:<?php echo $theme->store_locator_input_border_radius;?>px!important;
			border-color:#<?php echo $theme->store_locator_input_border_color;?>!important;
			padding:5px!important;
		
		}
		.gmwd_store_locator_container<?php echo $shortcode_id;?> .gmwd_store_locator_label{
			color:#<?php echo $theme->store_locator_label_color;?>!important;
			background:#<?php echo $theme->store_locator_label_background_color;?>!important;
            border-radius:<?php echo $theme->store_locator_label_border_radius ? $theme->store_locator_label_border_radius : 0 ;?>px!important;
			padding: 1px 5px!important;
			display:block;
			width:120px;
            margin-right: 8px;
		}
		#gmwd_store_locator_search<?php echo $shortcode_id;?>, #gmwd_store_locator_reset<?php echo $shortcode_id;?>{
			border-radius:<?php echo $theme->store_locator_button_border_radius ? $theme->store_locator_button_border_radius : 0;?>px!important;
            width:<?php echo $theme->store_locator_button_width ? $theme->store_locator_button_width."px" : "auto"; ?>!important;
            padding:3px 15px !important;
            border: 0!important;
		}
		#gmwd_store_locator_search<?php echo $shortcode_id;?>{			
			background:#<?php echo $theme->store_locator_search_button_background_color;?>!important;
			color:#<?php echo $theme->store_locator_search_button_color;?>!important;
		}
		#gmwd_store_locator_reset<?php echo $shortcode_id;?>{
			background:#<?php echo $theme->store_locator_reset_button_background_color;?>!important;
			color:#<?php echo $theme->store_locator_reset_button_color;?>!important;
		}
        .gmwd_categories {
            padding:0 !important;
        }
		</style>
        <div class="wd-clear">
            <div class="gmwd_store_locator_container gmwd_store_locator_container<?php echo $shortcode_id;?> wd-clear">
                <h3 class="gmwd_store_locator_title<?php echo $shortcode_id;?>"><?php echo $row->store_locator_header_title ? $row->store_locator_header_title : __("Store Locator","gmwd");?></h3>
                <div class="container">
                    <div class="row">
                        <div class="<?php echo $class_columns_first;?>">
                            <div class="wd-clear wd-row">
                                <div class="wd-left">
                                    <label for="gmwd_store_locator_address<?php echo $shortcode_id;?>" class="gmwd_store_locator_label"><?php _e("Address","gmwd");?></label>
                                </div>
                                <div class="wd-left">
                                    <input type="text" id="gmwd_store_locator_address<?php echo $shortcode_id;?>" autocomplete="off" class="gmwd_store_locator_address<?php echo $shortcode_id;?>" >                               
                                </div>
                                <div class="wd-left">
                                    <span class="gmwd_my_location gmwd_my_location_store_locator<?php echo $shortcode_id;?>"><i title="<?php _e("Get My Location","gmwd");?>" class=""></i></span>                                
                                </div>	                                
                            </div>				
                            <div class="wd-clear">
                                <div class="wd-left">
                                    <label for="gmwd_store_locator_radius<?php echo $shortcode_id;?>" class="gmwd_store_locator_label"><?php _e("Radius","gmwd");?>
                                </div>
                                <div class="wd-left">
                                    <select class="gmwd_store_locator_radius<?php echo $shortcode_id;?>" id="gmwd_store_locator_radius<?php echo $shortcode_id;?>">                                  
                                        <option value="1">1<?php echo $row->distance_in;?></option>                 
                                        <option value="5">5<?php echo $row->distance_in;?></option>                
                                        <option value="10" selected="">10<?php echo $row->distance_in;?></option>      
                                        <option value="25">25<?php echo $row->distance_in;?></option>                
                                        <option value="50">50<?php echo $row->distance_in;?></option>                
                                        <option value="75">75<?php echo $row->distance_in;?></option>              
                                        <option value="100">100<?php echo $row->distance_in;?></option>             
                                        <option value="150">150<?php echo $row->distance_in;?></option>            
                                        <option value="200">200<?php echo $row->distance_in;?></option>         
                                        <option value="300">300<?php echo $row->distance_in;?></option>               
                                    </select>
                                </div>						
                            </div>
                        </div>
						<?php if($row->enable_store_locator_cats == 1){ ?>
                        <div class="<?php echo $class_columns_second;?>">
                            <div class="wd-clear">
                                <div class="wd-left">
                                    <label for="gmwd_marker_categories" class="gmwd_store_locator_label"><?php _e("Categories","gmwd");?>
                                </div>
								
                                <div class="wd-left gmwd_store_locator_categories_container">
                                    <?php echo $marker_categories[1];?>			
                                </div>						
                            </div>		
                        </div>
						<?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $btn_alignment_class;?>">
                            <button id="gmwd_store_locator_search<?php echo $shortcode_id;?>"><?php _e("Search","gmwd");?></button>
                            <button id="gmwd_store_locator_reset<?php echo $shortcode_id;?>"><?php _e("Reset","gmwd");?></button>
                        </div>						
                    </div>
                </div>	
            </div>		
		</div>		
	
	<?php
	}
	private function display_directions($row, $theme, $shortcode_id){
        $class_columns_first =  $theme->directions_columns == 0 ? "col-lg-12 col-md-12 col-sm-12 col-xs-12" : "col-lg-4 col-md-4 col-sm-12 col-xs-12";
        $class_columns_second =  $theme->directions_columns == 0 ? "col-lg-12 col-md-12 col-sm-12 col-xs-12" : "col-lg-8 col-md-8 col-sm-12 col-xs-12";
        $btn_alignment_class = $theme->directions_button_alignment == 0 ? "wd-text-left" : ($theme->directions_button_alignment == 1 ? "wd-text-center" : "wd-text-right");
		
	?>
		<style>
		.gmwd_directions_container<?php echo $shortcode_id;?>{
			width:<?php echo  $row->directions_window_width ? $row->directions_window_width .$row->directions_window_width_unit : "auto";?>!important;
			float:<?php echo $row->direction_position == 1 || $row->direction_position == 3 ? "right" : "left";?>;
            background:#<?php echo $theme->directions_window_background_color;?>!important;
            padding:5px;
            border-radius:<?php echo $theme->directions_window_border_radius ? $theme->directions_window_border_radius : 0 ;?>px!important;
		}
		.gmwd_directions_title<?php echo $shortcode_id;?>{
			color:#<?php echo $theme->directions_title_color;?>!important;;
            margin:5px 0px !important;
		}
		.gmwd_direction_mode<?php echo $shortcode_id;?>, .gmwd_direction_from<?php echo $shortcode_id;?>, .gmwd_direction_to<?php echo $shortcode_id;?>{
			border-radius:<?php echo $theme->directions_input_border_radius ? $theme->directions_input_border_radius : 0 ;?>px!important;
			border-color:#<?php echo $theme->directions_input_border_color;?>!important;
			padding:5px!important;
		
		}
		.gmwd_directions_container<?php echo $shortcode_id;?> .gmwd_directions_label{
			color:#<?php echo $theme->directions_label_color;?>!important;
			background:#<?php echo $theme->directions_label_background_color;?>!important;
            border-radius:<?php echo $theme->directions_label_border_radius ? $theme->directions_label_border_radius : 0 ;?>px!important;
			padding: 1px 5px!important;
			display:block;
			width:70px;
            margin-right: 8px;
		}

		#gmwd_directions_go<?php echo $shortcode_id;?>{
			border-radius:<?php echo $theme->directions_button_border_radius;?>px!important;
			background:#<?php echo $theme->directions_button_background_color;?>!important;
			color:#<?php echo $theme->directions_button_color;?>!important;
			width:<?php echo $theme->directions_button_width ? $theme->directions_button_width."px" : "auto"; ?>!important;
            padding:3px 6px !important;
            border: 0!important;
		}
        #gmwd_directions_panel<?php echo $shortcode_id;?> table tr td{
            padding: 5px 10px;
        }
		</style>
        <div class="wd-clear">
            <div class="gmwd_directions_container gmwd_directions_container<?php echo $shortcode_id;?> <?php echo $row->directions_window_open == 0 ? "wd-hide" : "";?> wd-clear">
                <h3 class="gmwd_directions_title<?php echo $shortcode_id;?>"><?php echo $row->directions_header_title ? $row->directions_header_title : __("Get Directions","gmwd");?></h3>				                
                <div class="container">
                    <div class="row">
                        <div class="<?php echo $class_columns_first;?>">
                            <div class="wd-clear wd-row">
                                <div class="wd-left">
                                    <label for="gmwd_direction_mode<?php echo $shortcode_id;?>" class="gmwd_directions_label"><?php _e("Mode","gmwd");?></label>
                                </div>
                                <div class="wd-left">
                                    <select id="gmwd_direction_mode<?php echo $shortcode_id;?>" class="gmwd_direction_mode<?php echo $shortcode_id;?>">
                                        <option value="DRIVING"><?php _e("Driving","gmwd");?></option>
                                        <option value="WALKING"><?php _e("Walking","gmwd");?></option>
                                        <option value="BICYCLING"><?php _e("Bicycling","gmwd");?></option>
                                        <option value="TRANSIT"><?php _e("Transit","gmwd");?></option>
                                    </select>
                                </div>						
                            </div>
                            <div class="wd-clear">
                                <div class="wd-left">
                                    <label class="gmwd_directions_label"><?php _e("Avoid","gmwd");?></label>
                                </div>
                                <div class="wd-left">
                                    <div class="wd-form-row">
                                        <label for="gmwd_tolls" class="gmwd_avoid<?php echo $shortcode_id;?>"><?php _e("Tolls","gmwd");?></label>
                                        <input type="checkbox" class="gmwd_direction_avoid_tolls<?php echo $shortcode_id;?>" value="tolls" id="gmwd_tolls">
                                    </div>
                                    <div class="wd-form-row">
                                        <label for="gmwd_highways" class="gmwd_avoid<?php echo $shortcode_id;?>"><?php _e("Highways","gmwd");?></label>
                                        <input type="checkbox" class="gmwd_direction_avoid_highways<?php echo $shortcode_id;?>" value="highways" id="gmwd_highways">
                                    </div>							
                                </div>						
                            </div>	
                        </div>	                  	
                        <div class="<?php echo $class_columns_second;?>">
                            <div class="wd-clear wd-row">
                                <div class="wd-left">
                                    <label for="gmwd_form" class="gmwd_directions_label"><?php _e("From","gmwd");?></label>
                                </div>
                                <div class="wd-left">
                                    <input type="text" id="gmwd_form<?php echo $shortcode_id;?>" autocomplete="off" class="gmwd_direction_from<?php echo $shortcode_id;?>" >
                                </div>
                                <div class="wd-left">
                                    <span data-for="gmwd_form" class="gmwd_my_location gmwd_my_location<?php echo $shortcode_id;?>"><i title="<?php _e("Get My Location","gmwd");?>" class=""></i></span>
                                </div>                                
                            </div>
                            <div class="wd-clear">
                                <div class="wd-left">
                                    <label for="gmwd_to" class="gmwd_directions_label"><?php _e("To","gmwd");?></label>
                                </div>
                                <div class="wd-left">
                                    <input type="text" id="gmwd_to<?php echo $shortcode_id;?>" autocomplete="off" class="gmwd_direction_to<?php echo $shortcode_id;?>"  >
                                </div>
                                <div class="wd-left">
                                    <span data-for="gmwd_to" class="gmwd_my_location gmwd_my_location<?php echo $shortcode_id;?>"><i title="<?php _e("Get My Location","gmwd");?>" class=""></i></span>
                                </div>	                                
                            </div>	
                        </div>
                     </div>	   
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $btn_alignment_class;?>">
                            <button id="gmwd_directions_go<?php echo $shortcode_id;?>" class="gmwd_directions_go" ><?php _e("Go","gmwd");?></button>
                        </div>						
                    </div>              
                <div class="wd-row" id="gmwd_directions_panel<?php echo $shortcode_id;?>"></div>	       
            </div>
        </div>
    </div>
					
	<?php	
	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}