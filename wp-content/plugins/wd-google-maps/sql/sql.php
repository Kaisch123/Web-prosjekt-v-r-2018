<?php
/**
 *
 */
function gmwd_create_tables(){
    global $wpdb;

    $gmwd_maps = /** @lang text */
        "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_maps` (
        `id`                              INT(16) 	 NOT NULL AUTO_INCREMENT,
        `title`                           VARCHAR(256) NOT NULL,
        `width`                           VARCHAR(256) NOT NULL,
        `height`                          VARCHAR(256) NOT NULL,
        `center_address`                  VARCHAR(256) NOT NULL,
        `center_lat`                      VARCHAR(256) NOT NULL,
        `center_lng`                      VARCHAR(256) NOT NULL,
        `width_percent`                   VARCHAR(16)  NOT NULL,
        `map_alignment`                   VARCHAR(16)  NOT NULL,
        `border_radius`                   VARCHAR(16)  NOT NULL,
        `zoom_level`                      INT(16)      NOT NULL,
        `min_zoom`                        INT(16)      NOT NULL,
        `max_zoom`                        INT(16)      NOT NULL,
        `enable_zoom_control`             TINYINT(1)   NOT NULL,
        `zoom_control_position`           INT(16)      NOT NULL,
        `enable_map_type_control`         TINYINT(1)   NOT NULL,
        `map_type_control_position`       INT(16)      NOT NULL,
        `map_type_control_style`          INT(16)      NOT NULL,
        `enable_scale_control`            TINYINT(1)   NOT NULL,
        `enable_street_view_control`      TINYINT(1)   NOT NULL,
        `street_view_control_position`    INT(16)      NOT NULL,
        `enable_fullscreen_control`       TINYINT(1)   NOT NULL,
        `fullscreen_control_position`     INT(16)      NOT NULL,
        `enable_rotate_control`   		  TINYINT(1)   NOT NULL,
        `whell_scrolling`   			  TINYINT(1)   NOT NULL,
        `map_draggable`  			      TINYINT(1)   NOT NULL,
        `map_db_click_zoom`  			  TINYINT(1)   NOT NULL,
        `enable_directions`   			  TINYINT(1)   NOT NULL,
        `directions_header_title`   	  VARCHAR(256)  NOT NULL,
        `directions_window_open`   	      TINYINT(1)   NOT NULL,
        `info_window_open_on`   		  VARCHAR(16)  NOT NULL,
        `direction_position`   			  INT(16)      NOT NULL,
        `directions_window_width`   	  VARCHAR(16)  NOT NULL,
        `directions_window_width_unit`    VARCHAR(16)  NOT NULL,
        
        `enable_store_locator`   		  TINYINT(1)   NOT NULL,
        `enable_store_locator_cats`   	  TINYINT(1)   NOT NULL,
        `store_locator_header_title`   	  VARCHAR(256) NOT NULL,
        `store_locator_window_width`   	  VARCHAR(16)  NOT NULL,
        `store_locator_window_width_unit` VARCHAR(16)  NOT NULL,
        
        `store_locator_position`   		  VARCHAR(16)  NOT NULL,
        `restrict_to_country`   		  VARCHAR(256) NOT NULL,
        `distance_in`   				  VARCHAR(256) NOT NULL,
        `enable_bicycle_layer`   		  TINYINT(1)   NOT NULL,
        `enable_traffic_layer`   		  TINYINT(1)   NOT NULL,
        `enable_transit_layer`   		  TINYINT(1)   NOT NULL,
        `georss_url`   		              VARCHAR(256) NOT NULL,
        `kml_url`   		              VARCHAR(256) NOT NULL,
        `fusion_table_id`   		      VARCHAR(256) NOT NULL,
        `fusion_table_where_filed`        VARCHAR(256) NOT NULL,
        `fusion_table_where_operator`     VARCHAR(16) NOT NULL,
        `fusion_table_where_value`   	  VARCHAR(256) NOT NULL,

        `geolocate_user`   				  TINYINT(1)   NOT NULL,
        `marker_listing_type`   		  INT(16)      NOT NULL,
        `listing_header_title`   		  VARCHAR(256) NOT NULL,
        `marker_listing_order`   		  VARCHAR(25)  NOT NULL,
        `marker_listing_order_dir`   	  VARCHAR(25)  NOT NULL,
        `marker_list_position`   	      INT(16)      NOT NULL,
        `enable_category_filter`   		  TINYINT(1)   NOT NULL,
        `category_filter_type`   		  TINYINT(1)   NOT NULL,
        `marker_categories_inside_map`    TINYINT(1)   NOT NULL,
        `imcategory_filter_type`          TINYINT(1)   NOT NULL,
        `category_filter_im_position`     INT(16)      NOT NULL,
        `show_cat_icon`                   TINYINT(1)   NOT NULL,

        `type`                            TINYINT(1)   NOT NULL,
        `marker_list_inside_map`          TINYINT(1)   NOT NULL,
        `marker_list_inside_map_position` INT(16)      NOT NULL,
        `infowindow_type`                 TINYINT(1)   NOT NULL,
        `advanced_info_window_position`   INT(16)      NOT NULL,
        `advanced_table_columns`          VARCHAR(32)  NOT NULL,
		
        `circle_line_color`               VARCHAR(256) NOT NULL,
        `circle_line_opacity`             VARCHAR(256) NOT NULL,
        `circle_fill_color`               VARCHAR(256) NOT NULL,
        `circle_fill_opacity`             VARCHAR(256) NOT NULL,
        `circle_line_width`               INT(16)      NOT NULL,
        `shortcode_id`                    INT(16)      NOT NULL,
        `theme_id`                        INT(16)      NOT NULL,
        `enable_searchbox`      	      TINYINT(1)   NOT NULL, 
        `searchbox_position`      	      INT(16)      NOT NULL, 
        `info_window_info`      	      VARCHAR(256) NOT NULL, 
        
        `published`      				  TINYINT(1)   NOT NULL DEFAULT '1', 

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
    $wpdb->query($gmwd_maps);
	
    $gmwd_mapstyles = /** @lang text */
        "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_mapstyles` (
        `id`             INT(16) 	  NOT NULL AUTO_INCREMENT,
        `title`          VARCHAR(256) NOT NULL,
        `styles`         LONGTEXT     NOT NULL,
        `image`          LONGTEXT     NOT NULL,

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
        
    $wpdb->query($gmwd_mapstyles);	

    $gmwd_options = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_options` (
        `id`             INT(16) 	 NOT NULL AUTO_INCREMENT,
        `name`           VARCHAR(256) NOT NULL,
        `value`          VARCHAR(256) NOT NULL,
        `default_value`  VARCHAR(256) NOT NULL,

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
        
    $wpdb->query($gmwd_options);

        
    $gmwd_markers = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_markers` (
        `id`                            INT(16) 	 NOT NULL AUTO_INCREMENT,
        `map_id`                        INT(16) 	 NOT NULL,
        `lat`                           VARCHAR(256) NOT NULL,
        `lng`                           VARCHAR(256) NOT NULL,
        `category`                      VARCHAR(256) NOT NULL,
        `title`                         VARCHAR(256) NOT NULL,
        `address`                       VARCHAR(256) NOT NULL,
        `animation`                     VARCHAR(16)  NOT NULL,
        `enable_info_window`            TINYINT(1)   NOT NULL,
        `info_window_open`              TINYINT(1)   NOT NULL,
        `marker_size`                   INT(16) 	 NOT NULL,
        `custom_marker_url`             VARCHAR(256) NOT NULL,
        `choose_marker_icon`            TINYINT(1)   NOT NULL,
        `description`                   LONGTEXT     NOT NULL,
        `link_url`                      VARCHAR(256) NOT NULL,
        `pic_url`                       VARCHAR(256) NOT NULL,
        `published`                     TINYINT(1)   NOT NULL,


        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
    $wpdb->query($gmwd_markers);

    $gmwd_marker_categories = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_markercategories` (
        `id`                            INT(16) 	 NOT NULL AUTO_INCREMENT,
        `title`                         VARCHAR(256) NOT NULL,
        `category_picture`              VARCHAR(256) NOT NULL,
        `parent`      					INT(16)    	 NOT NULL, 
        `level`      					INT(16)    	 NOT NULL, 
        `published`      				TINYINT(1)   NOT NULL, 

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
    $wpdb->query($gmwd_marker_categories);

    $gmwd_polygones = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_polygons` (
        `id`                            INT(16) 	 NOT NULL AUTO_INCREMENT,
        `map_id`                        INT(16) 	 NOT NULL,
        `title`                         VARCHAR(256) NOT NULL,
        `link`                          VARCHAR(256) NOT NULL,
        `line_width`                    VARCHAR(256) NOT NULL,
        `line_color`                    VARCHAR(256) NOT NULL,
        `line_opacity`                  VARCHAR(256) NOT NULL,
        `fill_color`                    VARCHAR(256) NOT NULL,
        `fill_opacity`                  VARCHAR(256) NOT NULL,
        `line_color_hover`              VARCHAR(256) NOT NULL,
        `line_opacity_hover`            VARCHAR(256) NOT NULL,
        `fill_color_hover`              VARCHAR(256) NOT NULL,
        `fill_opacity_hover`            VARCHAR(256) NOT NULL,
        `data`            				TEXT         NOT NULL,
        `show_markers`                  TINYINT(1)   NOT NULL,
        `enable_info_windows`           TINYINT(1)   NOT NULL,
        `published`      				TINYINT(1)   NOT NULL, 

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
    $wpdb->query($gmwd_polygones);
    
    $gmwd_rectangles = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_rectangles` (
        `id`                            INT(16) 	 NOT NULL AUTO_INCREMENT,
        `map_id`                        INT(16) 	 NOT NULL,
        `title`                         VARCHAR(256) NOT NULL,
        `link`                          VARCHAR(256) NOT NULL,
        `line_width`                    VARCHAR(256) NOT NULL,
        `line_color`                    VARCHAR(256) NOT NULL,
        `line_opacity`                  VARCHAR(256) NOT NULL,
        `fill_color`                    VARCHAR(256) NOT NULL,
        `fill_opacity`                  VARCHAR(256) NOT NULL,
        `line_color_hover`              VARCHAR(256) NOT NULL,
        `line_opacity_hover`            VARCHAR(256) NOT NULL,
        `fill_color_hover`              VARCHAR(256) NOT NULL,
        `fill_opacity_hover`            VARCHAR(256) NOT NULL,
        `south_west`            		VARCHAR(256) NOT NULL,
        `north_east`            		VARCHAR(256) NOT NULL,
        `show_markers`                  TINYINT(1)   NOT NULL ,
        `enable_info_windows`           TINYINT(1)   NOT NULL,
        `published`      				TINYINT(1)   NOT NULL, 

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
    $wpdb->query($gmwd_rectangles);   

    $gmwd_circles = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_circles` (
        `id`                            INT(16) 	 NOT NULL AUTO_INCREMENT,
        `map_id`                        INT(16) 	 NOT NULL,
        `title`                         VARCHAR(256) NOT NULL,
        `link`                          VARCHAR(256) NOT NULL,	
        `center_address`                VARCHAR(256) NOT NULL,
        `center_lat`                    VARCHAR(256) NOT NULL,
        `center_lng`                    VARCHAR(256) NOT NULL,
        `show_marker`                   TINYINT(1)   NOT NULL ,
        `radius`                        VARCHAR(256) NOT NULL,
        `line_width`                    VARCHAR(256) NOT NULL,
        `line_color`                    VARCHAR(256) NOT NULL,
        `line_opacity`                  VARCHAR(256) NOT NULL,
        `fill_color`                    VARCHAR(256) NOT NULL,
        `fill_opacity`                  VARCHAR(256) NOT NULL,
        `line_color_hover`              VARCHAR(256) NOT NULL,
        `line_opacity_hover`            VARCHAR(256) NOT NULL,
        `fill_color_hover`              VARCHAR(256) NOT NULL,
        `fill_opacity_hover`            VARCHAR(256) NOT NULL,
        `enable_info_window`            TINYINT(1)   NOT NULL,
        `published`      				TINYINT(1)   NOT NULL, 

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
    $wpdb->query($gmwd_circles);	


    $gmwd_polylines = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_polylines` (
        `id`                            INT(16) 	 NOT NULL AUTO_INCREMENT,
        `map_id`                        INT(16) 	 NOT NULL,
        `title`                         VARCHAR(256) NOT NULL,
        `line_width`                    VARCHAR(256) NOT NULL,
        `line_color`                    VARCHAR(256) NOT NULL,
        `line_opacity`                  VARCHAR(256) NOT NULL,
        `line_color_hover`              VARCHAR(256) NOT NULL,
        `line_opacity_hover`            VARCHAR(256) NOT NULL,
        `data`                          TEXT         NOT NULL,
        `show_markers`                  TINYINT(1)   NOT NULL ,
        `enable_info_windows`           TINYINT(1)   NOT NULL,
        `published`      				TINYINT(1)   NOT NULL DEFAULT '1', 

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
    $wpdb->query($gmwd_polylines);


    $gmwd_themes = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_themes` (
        `id`                                                    INT(16) 	 NOT NULL AUTO_INCREMENT,
        `title`                                                 VARCHAR(256) NOT NULL,
        `map_style_id`                                          INT(16)      NOT NULL,
        `map_style_code`                                        LONGTEXT     NOT NULL,
        `map_border_radius`                                     VARCHAR(16)  NOT NULL,        
        `directions_title_color`                                VARCHAR(16)  NOT NULL,
        `directions_window_background_color`                    VARCHAR(16)  NOT NULL,
        `directions_window_border_radius`                       VARCHAR(16)  NOT NULL,
        `directions_input_border_radius`                        VARCHAR(16)  NOT NULL,
        `directions_input_border_color`                         VARCHAR(16)  NOT NULL,
        `directions_label_color`                                VARCHAR(16)  NOT NULL,
        `directions_label_background_color`                     VARCHAR(16)  NOT NULL,
        `directions_label_border_radius`                        VARCHAR(16)  NOT NULL,
        `directions_button_width`                               VARCHAR(16)  NOT NULL,
        `directions_button_border_radius`                       VARCHAR(16)  NOT NULL,
        `directions_button_background_color`                    VARCHAR(16)  NOT NULL,
        `directions_button_color`                               VARCHAR(16)  NOT NULL,	
        `directions_button_alignment`                           TINYINT(1)   NOT NULL, 	
        `directions_columns`                                    TINYINT(1)   NOT NULL, 	
        `store_locator_title_color`                             VARCHAR(16)  NOT NULL,
        `store_locator_window_bgcolor`                          VARCHAR(16)  NOT NULL,
        `store_locator_window_border_radius`                    VARCHAR(16)  NOT NULL,
        `store_locator_input_border_radius`                     VARCHAR(16)  NOT NULL,
        `store_locator_input_border_color`                      VARCHAR(16)  NOT NULL,
        `store_locator_label_color`                             VARCHAR(16)  NOT NULL,
        `store_locator_label_background_color`                  VARCHAR(16)  NOT NULL,
        `store_locator_label_border_radius`                     VARCHAR(16)  NOT NULL,
        `store_locator_buttons_alignment`                       TINYINT(1)   NOT NULL, 
        `store_locator_button_width`                            VARCHAR(16)  NOT NULL,
        `store_locator_button_border_radius`                    VARCHAR(16)  NOT NULL,
        `store_locator_search_button_background_color`          VARCHAR(16)  NOT NULL,
        `store_locator_search_button_color`                     VARCHAR(16)  NOT NULL,
        `store_locator_reset_button_background_color`           VARCHAR(16)  NOT NULL,
        `store_locator_reset_button_color`                      VARCHAR(16)  NOT NULL,
        `store_locator_columns`                                 TINYINT(1)   NOT NULL, 
        `marker_listsing_basic_title_color`                     VARCHAR(16)  NOT NULL,
        `marker_listsing_basic_bgcolor`                         VARCHAR(16)  NOT NULL,
        `marker_listsing_basic_marker_title_color`              VARCHAR(16)  NOT NULL,
        `marker_listsing_basic_marker_desc_color`               VARCHAR(16)  NOT NULL,
        `marker_listsing_basic_dir_border_radius`      			VARCHAR(16)  NOT NULL,
        `marker_listsing_basic_dir_width`      			        VARCHAR(16)  NOT NULL,
        `marker_listsing_basic_dir_height`      			    VARCHAR(16)  NOT NULL,
        `marker_listsing_basic_dir_background_color`            VARCHAR(16)  NOT NULL,
        `marker_listsing_basic_dir_color`                       VARCHAR(16)  NOT NULL,
        `marker_advanced_title_color`                           VARCHAR(16)  NOT NULL,
        `marker_advanced_table_background`                      VARCHAR(16)  NOT NULL,
        `marker_advanced_table_border_radius`                   VARCHAR(16)  NOT NULL,
        `marker_advanced_table_color`                           VARCHAR(16)  NOT NULL,
        `marker_advanced_table_header_background`               VARCHAR(16)  NOT NULL,	
        `marker_advanced_table_header_color`                    VARCHAR(16)  NOT NULL,	
        `advanced_info_window_background`                       VARCHAR(16)  NOT NULL,
        `advanced_info_window_title_color`                      VARCHAR(16)  NOT NULL,
        `advanced_info_window_title_background_color`           VARCHAR(16)  NOT NULL,     
        `advanced_info_window_desc_color`                       VARCHAR(16)  NOT NULL,
        `advanced_info_window_dir_color`                        VARCHAR(16)  NOT NULL,
        `advanced_info_window_dir_background_color`             VARCHAR(16)  NOT NULL,
        `advanced_info_window_dir_border_radius`                VARCHAR(16)  NOT NULL,
        `carousel_item_height`                                  INT(16)      NOT NULL,
        `carousel_item_border_radius`                           INT(16)      NOT NULL,
        `carousel_items_count`                                  INT(16)      NOT NULL,
        `carousel_color`                                        VARCHAR(16)  NOT NULL,
        `carousel_background_color`                             VARCHAR(16)  NOT NULL,
        `carousel_hover_color`                                  VARCHAR(16)  NOT NULL,
        `carousel_hover_background_color`                       VARCHAR(16)  NOT NULL,
        `marker_listsing_inside_map_color`                      VARCHAR(16)  NOT NULL,
        `marker_listsing_inside_map_bgcolor`                    VARCHAR(16)  NOT NULL,
        `marker_listsing_inside_map_width`                      VARCHAR(16)  NOT NULL,
        `marker_listsing_inside_map_height`                     VARCHAR(16)  NOT NULL,
        `marker_listsing_inside_map_border_radius`              VARCHAR(16)  NOT NULL,
        `auto_generate_style_code`                              TINYINT(1)   NOT NULL, 
        `default`                                               TINYINT(1)   NOT NULL, 
        `published`                                             TINYINT(1)   NOT NULL DEFAULT '1', 

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
    $wpdb->query($gmwd_themes);

    $gmwd_shortcodes = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "gmwd_shortcodes` (
        `id`            INT(16) 	 NOT NULL AUTO_INCREMENT,
        `tag_text`      LONGTEXT     NOT NULL, 

        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;";
    $wpdb->query($gmwd_shortcodes);
    
    $exist_options = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'gmwd_options');
    if(!$exist_options){
        $gmwd_options_insert = "INSERT INTO  `" . $wpdb->prefix . "gmwd_options` (`id`,  `name`, `value`, `default_value`) VALUES
        ('', 'map_api_key', '', '' ),	
        ('', 'map_language', '', '' ),	
        ('', 'choose_marker_icon', '', '1' ),
        ('', 'marker_default_icon', '', '' ),	
        ('', 'center_address', '', 'New York, NY, United States' ),	
        ('', 'center_lat', '', '40.7127837' ),	
        ('', 'center_lng', '', '-74.00594130000002' ),	
        ('', 'zoom_level', '', '7' ),	
        ('', 'whell_scrolling', '', '0' ),	
        ('', 'map_draggable', '', '1' )	
        ";
        $wpdb->query($gmwd_options_insert);
    }

    if(!get_option("gmwd_version") || (get_option("gmwd_version") && get_option("gmwd_pro") == "no")){

        // marker categories 
        $gmwd_map_marker_categories_insert = "INSERT INTO `". $wpdb->prefix ."gmwd_markercategories` (`id`, `title`, `parent`, `level`, `published`) VALUES
        ('', 'For Sale', 0, 0, 1),
        ('', 'To Rent', 0, 0, 1)";              
        $wpdb->query($gmwd_map_marker_categories_insert);  

        $gmwd_map_styles_insert = "INSERT INTO `". $wpdb->prefix ."gmwd_mapstyles` (`id`, `styles`, `image`)  VALUES
        ('', '[]', ''),
        ('',  '[{&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#ff4400&quot;},{&quot;saturation&quot;:-68},{&quot;lightness&quot;:-4},{&quot;gamma&quot;:0.72}]},{&quot;featureType&quot;:&quot;road&quot;,&quot;elementType&quot;:&quot;labels.icon&quot;},{&quot;featureType&quot;:&quot;landscape.man_made&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#0077ff&quot;},{&quot;gamma&quot;:3.1}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#00ccff&quot;},{&quot;gamma&quot;:0.44},{&quot;saturation&quot;:-33}]},{&quot;featureType&quot;:&quot;poi.park&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#44ff00&quot;},{&quot;saturation&quot;:-23}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;elementType&quot;:&quot;labels.text.fill&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#007fff&quot;},{&quot;gamma&quot;:0.77},{&quot;saturation&quot;:65},{&quot;lightness&quot;:99}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;elementType&quot;:&quot;labels.text.stroke&quot;,&quot;stylers&quot;:[{&quot;gamma&quot;:0.11},{&quot;weight&quot;:5.6},{&quot;saturation&quot;:99},{&quot;hue&quot;:&quot;#0091ff&quot;},{&quot;lightness&quot;:-86}]},{&quot;featureType&quot;:&quot;transit.line&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;lightness&quot;:-48},{&quot;hue&quot;:&quot;#ff5e00&quot;},{&quot;gamma&quot;:1.2},{&quot;saturation&quot;:-23}]},{&quot;featureType&quot;:&quot;transit&quot;,&quot;elementType&quot;:&quot;labels.text.stroke&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-64},{&quot;hue&quot;:&quot;#ff9100&quot;},{&quot;lightness&quot;:16},{&quot;gamma&quot;:0.47},{&quot;weight&quot;:2.7}]}]', '&style=feature:all|element:geometry|hue:0xff4400|saturation:-68|lightness:-4|gamma:0.72&style=feature:road|element:labels.icon&style=feature:landscape.man_made|element:geometry|hue:0x0077ff|gamma:3.1&style=feature:water|element:all|hue:0x00ccff|gamma:0.44|saturation:-33&style=feature:poi.park|element:all|hue:0x44ff00|saturation:-23&style=feature:water|element:labels.text.fill|hue:0x007fff|gamma:0.77|saturation:65|lightness:99&style=feature:water|element:labels.text.stroke|gamma:0.11|weight:5.6|saturation:99|hue:0x0091ff|lightness:-86&style=feature:transit.line|element:geometry|lightness:-48|hue:0xff5e00|gamma:1.2|saturation:-23&style=feature:transit|element:labels.text.stroke|saturation:-64|hue:0xff9100|lightness:16|gamma:0.47|weight:2.7'),
        ('',  '[{&quot;featureType&quot;:&quot;landscape&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-100},{&quot;lightness&quot;:65},{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;poi&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-100},{&quot;lightness&quot;:51},{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-100},{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;road.arterial&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-100},{&quot;lightness&quot;:30},{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;road.local&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-100},{&quot;lightness&quot;:40},{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;transit&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-100},{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;administrative.province&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;elementType&quot;:&quot;labels&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;on&quot;},{&quot;lightness&quot;:-25},{&quot;saturation&quot;:-100}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#ffff00&quot;},{&quot;lightness&quot;:-25},{&quot;saturation&quot;:-97}]}]', '&style=feature:landscape|element:all|saturation:-100|lightness:65|visibility:on&style=feature:poi|element:all|saturation:-100|lightness:51|visibility:simplified&style=feature:road.highway|element:all|saturation:-100|visibility:simplified&style=feature:road.arterial|element:all|saturation:-100|lightness:30|visibility:on&style=feature:road.local|element:all|saturation:-100|lightness:40|visibility:on&style=feature:transit|element:all|saturation:-100|visibility:simplified&style=feature:administrative.province|element:all|visibility:off&style=feature:water|element:labels|visibility:on|lightness:-25|saturation:-100&style=feature:water|element:geometry|hue:0xffff00|lightness:-25|saturation:-97'),
        ('',  '[{&quot;featureType&quot;:&quot;all&quot;,&quot;elementType&quot;:&quot;labels.text.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#ffffff&quot;}]},{&quot;featureType&quot;:&quot;all&quot;,&quot;elementType&quot;:&quot;labels.text.stroke&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#000000&quot;},{&quot;lightness&quot;:13}]},{&quot;featureType&quot;:&quot;administrative&quot;,&quot;elementType&quot;:&quot;geometry.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#000000&quot;}]},{&quot;featureType&quot;:&quot;administrative&quot;,&quot;elementType&quot;:&quot;geometry.stroke&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#144b53&quot;},{&quot;lightness&quot;:14},{&quot;weight&quot;:1.4}]},{&quot;featureType&quot;:&quot;landscape&quot;,&quot;elementType&quot;:&quot;all&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#08304b&quot;}]},{&quot;featureType&quot;:&quot;poi&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#0c4152&quot;},{&quot;lightness&quot;:5}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;elementType&quot;:&quot;geometry.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#000000&quot;}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;elementType&quot;:&quot;geometry.stroke&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#0b434f&quot;},{&quot;lightness&quot;:25}]},{&quot;featureType&quot;:&quot;road.arterial&quot;,&quot;elementType&quot;:&quot;geometry.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#000000&quot;}]},{&quot;featureType&quot;:&quot;road.arterial&quot;,&quot;elementType&quot;:&quot;geometry.stroke&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#0b3d51&quot;},{&quot;lightness&quot;:16}]},{&quot;featureType&quot;:&quot;road.local&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#000000&quot;}]},{&quot;featureType&quot;:&quot;transit&quot;,&quot;elementType&quot;:&quot;all&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#146474&quot;}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;elementType&quot;:&quot;all&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#021019&quot;}]}]', '&style=feature:all|element:labels.text.fill|color:0xffffff&style=feature:all|element:labels.text.stroke|color:0x000000|lightness:13&style=feature:administrative|element:geometry.fill|color:0x000000&style=feature:administrative|element:geometry.stroke|color:0x144b53|lightness:14|weight:1.4&style=feature:landscape|element:all|color:0x08304b&style=feature:poi|element:geometry|color:0x0c4152|lightness:5&style=feature:road.highway|element:geometry.fill|color:0x000000&style=feature:road.highway|element:geometry.stroke|color:0x0b434f|lightness:25&style=feature:road.arterial|element:geometry.fill|color:0x000000&style=feature:road.arterial|element:geometry.stroke|color:0x0b3d51|lightness:16&style=feature:road.local|element:geometry|color:0x000000&style=feature:transit|element:all|color:0x146474&style=feature:water|element:all|color:0x021019'),
        ('',  '[{&quot;featureType&quot;:&quot;administrative&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]},{&quot;featureType&quot;:&quot;poi&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;road&quot;,&quot;elementType&quot;:&quot;labels&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;transit&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;landscape&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]},{&quot;featureType&quot;:&quot;road.local&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#84afa3&quot;},{&quot;lightness&quot;:52}]},{&quot;stylers&quot;:[{&quot;saturation&quot;:-17},{&quot;gamma&quot;:0.36}]},{&quot;featureType&quot;:&quot;transit.line&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#3f518c&quot;}]}]', '&style=feature:administrative|element:all|visibility:off&style=feature:poi|element:all|visibility:simplified&style=feature:road|element:labels|visibility:simplified&style=feature:water|element:all|visibility:simplified&style=feature:transit|element:all|visibility:simplified&style=feature:landscape|element:all|visibility:simplified&style=feature:road.highway|element:all|visibility:off&style=feature:road.local|element:all|visibility:on&style=feature:road.highway|element:geometry|visibility:on&style=feature:water|element:all|color:0x84afa3|lightness:52&style=feature:all|element:all|saturation:-17|gamma:0.36&style=feature:transit.line|element:geometry|color:0x3f518c'),
        ('',  '[{&quot;featureType&quot;:&quot;water&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#ffdfa6&quot;}]},{&quot;featureType&quot;:&quot;landscape&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#b52127&quot;}]},{&quot;featureType&quot;:&quot;poi&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#c5531b&quot;}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;elementType&quot;:&quot;geometry.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#74001b&quot;},{&quot;lightness&quot;:-10}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;elementType&quot;:&quot;geometry.stroke&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#da3c3c&quot;}]},{&quot;featureType&quot;:&quot;road.arterial&quot;,&quot;elementType&quot;:&quot;geometry.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#74001b&quot;}]},{&quot;featureType&quot;:&quot;road.arterial&quot;,&quot;elementType&quot;:&quot;geometry.stroke&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#da3c3c&quot;}]},{&quot;featureType&quot;:&quot;road.local&quot;,&quot;elementType&quot;:&quot;geometry.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#990c19&quot;}]},{&quot;elementType&quot;:&quot;labels.text.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#ffffff&quot;}]},{&quot;elementType&quot;:&quot;labels.text.stroke&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#74001b&quot;},{&quot;lightness&quot;:-8}]},{&quot;featureType&quot;:&quot;transit&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#6a0d10&quot;},{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;administrative&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#ffdfa6&quot;},{&quot;weight&quot;:0.4}]},{&quot;featureType&quot;:&quot;road.local&quot;,&quot;elementType&quot;:&quot;geometry.stroke&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]}]', '&style=feature:water|element:geometry|color:0xffdfa6&style=feature:landscape|element:geometry|color:0xb52127&style=feature:poi|element:geometry|color:0xc5531b&style=feature:road.highway|element:geometry.fill|color:0x74001b|lightness:-10&style=feature:road.highway|element:geometry.stroke|color:0xda3c3c&style=feature:road.arterial|element:geometry.fill|color:0x74001b&style=feature:road.arterial|element:geometry.stroke|color:0xda3c3c&style=feature:road.local|element:geometry.fill|color:0x990c19&style=feature:all|element:labels.text.fill|color:0xffffff&style=feature:all|element:labels.text.stroke|color:0x74001b|lightness:-8&style=feature:transit|element:geometry|color:0x6a0d10|visibility:on&style=feature:administrative|element:geometry|color:0xffdfa6|weight:0.4&style=feature:road.local|element:geometry.stroke|visibility:off'),
        ('',  '[{&quot;featureType&quot;:&quot;all&quot;,&quot;elementType&quot;:&quot;all&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#52FFD1&quot;}]}]', '&style=feature:all|element:all|hue:0x52FFD1'),
        ('',  '[{&quot;featureType&quot;:&quot;all&quot;,&quot;elementType&quot;:&quot;all&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#DBB3FF&quot;},{&quot;weight&quot;:3}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;elementType&quot;:&quot;geometry.fill&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#D8ABFF&quot;}]},{&quot;featureType&quot;:&quot;road&quot;,&quot;elementType&quot;:&quot;labels.text&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#CE2BFF&quot;},{&quot;gamma&quot;:0},{&quot;hue&quot;:&quot;#5EFFC4&quot;}]}]', '&style=feature:all|element:all|hue:0xDBB3FF|weight:3&style=feature:water|element:geometry.fill|hue:0xD8ABFF&style=feature:road|element:labels.text|color:0xCE2BFF|gamma:0|hue:0x5EFFC4'),
        ('',  '[{&quot;featureType&quot;:&quot;all&quot;,&quot;elementType&quot;:&quot;all&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#A8C5E2&quot;}]}]', '&style=feature:all|element:all|hue:0xA8C5E2')";

        $wpdb->query($gmwd_map_styles_insert); 
        
        if(get_option("gmwd_pro") == "yes" || !get_option("gmwd_version")){
           $gmwd_themes_insert = "INSERT INTO `" . $wpdb->prefix . "gmwd_themes` (`id`, `title`, `map_style_id`, `map_style_code`, `map_border_radius`, `directions_title_color`, `directions_window_background_color`, `directions_window_border_radius`, `directions_input_border_radius`, `directions_input_border_color`, `directions_label_color`, `directions_label_background_color`, `directions_label_border_radius`, `directions_button_width`, `directions_button_border_radius`, `directions_button_background_color`, `directions_button_color`, `directions_button_alignment`, `directions_columns`, `store_locator_title_color`, `store_locator_window_bgcolor`, `store_locator_window_border_radius`, `store_locator_input_border_radius`, `store_locator_input_border_color`, `store_locator_label_color`, `store_locator_label_background_color`, `store_locator_label_border_radius`, `store_locator_buttons_alignment`, `store_locator_button_width`, `store_locator_button_border_radius`, `store_locator_search_button_background_color`, `store_locator_search_button_color`, `store_locator_reset_button_background_color`, `store_locator_reset_button_color`, `store_locator_columns`, `marker_listsing_basic_title_color`, `marker_listsing_basic_bgcolor`, `marker_listsing_basic_marker_title_color`, `marker_listsing_basic_marker_desc_color`, `marker_listsing_basic_dir_border_radius`, `marker_listsing_basic_dir_width`, `marker_listsing_basic_dir_height`, `marker_listsing_basic_dir_background_color`, `marker_listsing_basic_dir_color`, `marker_advanced_title_color`, `marker_advanced_table_background`, `marker_advanced_table_border_radius`, `marker_advanced_table_color`, `marker_advanced_table_header_background`, `marker_advanced_table_header_color`, `advanced_info_window_background`, `advanced_info_window_title_color`, `advanced_info_window_title_background_color`, `advanced_info_window_desc_color`, `advanced_info_window_dir_color`, `advanced_info_window_dir_background_color`, `advanced_info_window_dir_border_radius`, `carousel_item_height`, `carousel_item_border_radius`, `carousel_items_count`, `carousel_color`, `carousel_background_color`, `carousel_hover_color`, `carousel_hover_background_color`, `marker_listsing_inside_map_color`, `marker_listsing_inside_map_bgcolor`, `marker_listsing_inside_map_width`, `marker_listsing_inside_map_height`, `marker_listsing_inside_map_border_radius`, `auto_generate_style_code`, `default`, `published`) VALUES
            ('', 'Default', 1, '', '', '000000', 'F2F2F2', '', '', '000000', '000000', 'F2F2F2', '', '100', '', '000000', 'FFFFFF', 0, 0, '000000', 'F2F2F2', '', '', '000000', '000000', 'F2F2F2', '', 0, '', '', '000000', 'FFFFFF', '000000', 'FFFFFF', 0, '000000', 'F2F2F2', '000000', '000000', '', '130', '30', '000000', 'FFFFFF', '000000', 'F2F2F2', '', '000000', '000000', 'FFFFFF', 'FFFFFF', '000000', 'F2F2F2', '000000', 'FFFFFF', '000000', '', 45, 0, 3, '000000', 'F2F2F2', 'F2F2F2', '000000', '000000', 'F2F2F2', '250', '', '', 1, 1, 1)";
            
            $wpdb->query($gmwd_themes_insert); 
        }
        $gmwd_themes_insert = "INSERT INTO `" . $wpdb->prefix . "gmwd_themes` (`id`, `title`, `map_style_id`, `map_style_code`, `map_border_radius`, `directions_title_color`, `directions_window_background_color`, `directions_window_border_radius`, `directions_input_border_radius`, `directions_input_border_color`, `directions_label_color`, `directions_label_background_color`, `directions_label_border_radius`, `directions_button_width`, `directions_button_border_radius`, `directions_button_background_color`, `directions_button_color`, `directions_button_alignment`, `directions_columns`, `store_locator_title_color`, `store_locator_window_bgcolor`, `store_locator_window_border_radius`, `store_locator_input_border_radius`, `store_locator_input_border_color`, `store_locator_label_color`, `store_locator_label_background_color`, `store_locator_label_border_radius`, `store_locator_buttons_alignment`, `store_locator_button_width`, `store_locator_button_border_radius`, `store_locator_search_button_background_color`, `store_locator_search_button_color`, `store_locator_reset_button_background_color`, `store_locator_reset_button_color`, `store_locator_columns`, `marker_listsing_basic_title_color`, `marker_listsing_basic_bgcolor`, `marker_listsing_basic_marker_title_color`, `marker_listsing_basic_marker_desc_color`, `marker_listsing_basic_dir_border_radius`, `marker_listsing_basic_dir_width`, `marker_listsing_basic_dir_height`, `marker_listsing_basic_dir_background_color`, `marker_listsing_basic_dir_color`, `marker_advanced_title_color`, `marker_advanced_table_background`, `marker_advanced_table_border_radius`, `marker_advanced_table_color`, `marker_advanced_table_header_background`, `marker_advanced_table_header_color`, `advanced_info_window_background`, `advanced_info_window_title_color`, `advanced_info_window_title_background_color`, `advanced_info_window_desc_color`, `advanced_info_window_dir_color`, `advanced_info_window_dir_background_color`, `advanced_info_window_dir_border_radius`, `carousel_item_height`, `carousel_item_border_radius`, `carousel_items_count`, `carousel_color`, `carousel_background_color`, `carousel_hover_color`, `carousel_hover_background_color`, `marker_listsing_inside_map_color`, `marker_listsing_inside_map_bgcolor`, `marker_listsing_inside_map_width`, `marker_listsing_inside_map_height`, `marker_listsing_inside_map_border_radius`, `auto_generate_style_code`, `default`, `published`) VALUES        
        ('', 'Theme 1', 1, '', '', '00A0D2', 'FFFFFF', '', '', '00A0D2', 'FFFFFF', '00A0D2', '', '50', '', '00A0D2', 'FFFFFF', 0, 0, '00A0D2', 'FFFFFF', '', '', '00A0D2', 'FFFFFF', '00A0D2', '', 0, '100', '5', '00A0D2', 'FFFFFF', '8A8A8A', 'FFFFFF', 0, '00A0D2', 'EEEEEE', '000000', '000000', '', '150', '30', '00A0D2', 'FFFFFF', '00A0D2', 'EEEEEE', '', '545454', '00A0D2', 'FFFFFF', 'FFFFFF', 'FFFFFF', '00A0D2', '545454', 'FFFFFF', '00A0D2', '', 60, 0, 3, 'FFFFFF', '00A0D2', 'FFFFFF', '00C2FF', '000000', 'FFFFFF', '250', '250', '', 1, 0, 1),
        ('', 'Theme 2', 1, '', '0', '0E3245', 'CBE1F2', '', '', '0E3245', '0E3245', 'CBE1F2', '', '100', '', '0E3245', 'FFFFFF', 0, 1, '0E3245', 'CBE1F2', '', '', '0E3245', '0E3245', 'CBE1F2', '', 0, '', '', '0E3245', 'FFFFFF', '0E3245', 'FFFFFF', 0, '0E3245', 'CBE1F2', '0E3245', '4D4D4D', '', '130', '30', '0E3245', 'FFFFFF', '0E3245', 'CBE1F2', '', '0E3245', '0E3245', 'FFFFFF', 'FFFFFF', '0E3245', 'CBE1F2', '0E3245', 'FFFFFF', '0E3245', '', 45, 0, 3, 'FFFFFF', '0E3245', 'FFFFFF', 'CBE1F2', '0E3245', 'CBE1F2', '200', '', '', 1, 0, 1),
        ('', 'Theme 3', 2, '[{&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#ff4400&quot;},{&quot;saturation&quot;:-68},{&quot;lightness&quot;:-4},{&quot;gamma&quot;:0.72}]},{&quot;featureType&quot;:&quot;road&quot;,&quot;elementType&quot;:&quot;labels.icon&quot;},{&quot;featureType&quot;:&quot;landscape.man_made&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#0077ff&quot;},{&quot;gamma&quot;:3.1}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#00ccff&quot;},{&quot;gamma&quot;:0.44},{&quot;saturation&quot;:-33}]},{&quot;featureType&quot;:&quot;poi.park&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#44ff00&quot;},{&quot;saturation&quot;:-23}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;elementType&quot;:&quot;labels.text.fill&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#007fff&quot;},{&quot;gamma&quot;:0.77},{&quot;saturation&quot;:65},{&quot;lightness&quot;:99}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;elementType&quot;:&quot;labels.text.stroke&quot;,&quot;stylers&quot;:[{&quot;gamma&quot;:0.11},{&quot;weight&quot;:5.6},{&quot;saturation&quot;:99},{&quot;hue&quot;:&quot;#0091ff&quot;},{&quot;lightness&quot;:-86}]},{&quot;featureType&quot;:&quot;transit.line&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;lightness&quot;:-48},{&quot;hue&quot;:&quot;#ff5e00&quot;},{&quot;gamma&quot;:1.2},{&quot;saturation&quot;:-23}]},{&quot;featureType&quot;:&quot;transit&quot;,&quot;elementType&quot;:&quot;labels.text.stroke&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-64},{&quot;hue&quot;:&quot;#ff9100&quot;},{&quot;lightness&quot;:16},{&quot;gamma&quot;:0.47},{&quot;weight&quot;:2.7}]}]', '', '4C615A', 'DBE3E5', '', '', 'AFC4C9', 'FFFFFF', 'AFC4C9', '', '50', '', '4C615A', 'FFFFFF', 0, 0, '4C615A', 'DBE3E5', '', '', 'AFC4C9', 'FFFFFF', 'AFC4C9', '', 0, '100', '', '4C615A', 'FFFFFF', '8C9DA1', 'FFFFFF', 0, '4C615A', 'DBE3E5', '4C615A', '4C615A', '', '150', '30', '4C615A', 'FFFFFF', '4C615A', 'DBE3E5', '', '242E2B', '4C615A', 'FFFFFF', 'DBE3E5', 'FFFFFF', '4C615A', '242E2B', 'FFFFFF', '4C615A', '', 60, 0, 3, 'FFFFFF', '4C615A', '242E2B', 'AFC4C9', '242E2B', 'DBE3E5', '250', '200', '', 1, 0, 1),
        ('', 'Theme 4', 5, '[{&quot;featureType&quot;:&quot;administrative&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]},{&quot;featureType&quot;:&quot;poi&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;road&quot;,&quot;elementType&quot;:&quot;labels&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;transit&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;landscape&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]},{&quot;featureType&quot;:&quot;road.local&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#84afa3&quot;},{&quot;lightness&quot;:52}]},{&quot;stylers&quot;:[{&quot;saturation&quot;:-17},{&quot;gamma&quot;:0.36}]},{&quot;featureType&quot;:&quot;transit.line&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#3f518c&quot;}]}]', '', '3C280D', 'C8B191', '', '', '3C280D', 'FFFFFF', '65350F', '', '50', '', '3C280D', 'FFFFFF', 0, 0, '3C280D', 'C8B191', '', '', '3C280D', 'FFFFFF', '65350F', '', 0, '100', '', '3C280D', 'FFFFFF', '5C3D14', 'FFFFFF', 0, '3C280D', 'C8B191', 'FFFFFF', 'FFFFFF', '', '150', '30', '3C280D', 'FFFFFF', '3C280D', 'C8B191', '', '3C280D', '3C280D', 'FFFFFF', 'C8B191', 'FFFFFF', '3C280D', '3C280D', 'FFFFFF', '3C280D', '', 60, 0, 3, 'FFFFFF', '3C280D', '3C280D', 'C8B191', '3C280D', 'C8B191', '250', '200', '', 1, 0, 1),
        ('', 'Theme 5', 9, '[{&quot;featureType&quot;:&quot;all&quot;,&quot;elementType&quot;:&quot;all&quot;,&quot;stylers&quot;:[{&quot;hue&quot;:&quot;#A8C5E2&quot;}]}]', '', 'FFFFFF', 'A8C5E2', '', '', '003366', 'FFFFFF', '003366', '', '50', '', '000044', 'FFFFFF', 0, 0, 'FFFFFF', 'A8C5E2', '', '', '003366', 'FFFFFF', '003366', '', 0, '100', '', '000044', 'FFFFFF', '003366', 'FFFFFF', 0, 'FFFFFF', 'A8C5E2', 'FFFFFF', 'FFFFFF', '', '150', '30', '000044', 'FFFFFF', '000044', 'A8C5E2', '', 'FFFFFF', '003366', 'FFFFFF', 'A8C5E2', 'FFFFFF', '003366', 'FFFFFF', 'FFFFFF', '000044', '', 60, 0, 3, 'FFFFFF', '003366', 'FFFFFF', 'A8C5E2', 'FFFFFF', 'A8C5E2', '250', '200', '', 1, 0, 1),
        ('', 'Theme 6', 5, '[{&quot;featureType&quot;:&quot;administrative&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]},{&quot;featureType&quot;:&quot;poi&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;road&quot;,&quot;elementType&quot;:&quot;labels&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;transit&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;landscape&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]},{&quot;featureType&quot;:&quot;road.local&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#84afa3&quot;},{&quot;lightness&quot;:52}]},{&quot;stylers&quot;:[{&quot;saturation&quot;:-17},{&quot;gamma&quot;:0.36}]},{&quot;featureType&quot;:&quot;transit.line&quot;,&quot;elementType&quot;:&quot;geometry&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#3f518c&quot;}]}]', '', 'FFFFFF', '3D7665', '', '', 'CDEAE0', '02696E', 'CDEAE0', '', '50', '', '74C3A3', 'FFFFFF', 0, 0, 'FFFFFF', '3D7665', '', '', 'CDEAE0', '02696E', 'CDEAE0', '', 0, '100', '', '74C3A3', 'FFFFFF', '5D9C82', 'FFFFFF', 0, 'FFFFFF', '3D7665', 'CDEAE0', 'CDEAE0', '', '150', '30', '74C3A3', 'FFFFFF', '3D7665', 'CDEAE0', '', '02696E', '3D7665', 'FFFFFF', 'CDEAE0', 'FFFFFF', '3D7665', '02696E', 'FFFFFF', '02696E', '', 60, 0, 3, 'FFFFFF', '3D7665', '02696E', 'CDEAE0', 'FFFFFF', '3D7665', '250', '200', '', 1, 0, 1)";

		$wpdb->query($gmwd_themes_insert);                        
    }
    

    if(defined('GMWDMC_NAME') && is_plugin_active(GMWDMC_NAME.'/'.GMWDMC_NAME.'.php')){
        $marker_cluster_fields = $wpdb->get_row("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'enable_marker_clustering'");
        if(!$marker_cluster_fields){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `enable_marker_clustering`  TINYINT(1) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `marker_cluster_zoom`  INT(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `marker_cluster_size`  INT(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `marker_cluster_style`  INT(16) NOT NULL ");
            
            $wpdb->query("UPDATE  `" . $wpdb->prefix . "gmwd_maps` SET  marker_cluster_zoom='9', marker_cluster_size='-1', marker_cluster_style ='-1' ");

        }
    }
    if(defined('GMWDUGM_NAME') && is_plugin_active(GMWDUGM_NAME.'/'.GMWDUGM_NAME.'.php')){
        $enable_user_gen_markers = $wpdb->get_row("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'enable_user_gen_markers'");
        if(!$enable_user_gen_markers){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `enable_user_gen_markers`  TINYINT(1)   NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `ugm_user_permissions`  VARCHAR(16)   NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `enable_ugm_description`  TINYINT(1)   NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `enable_ugm_category`  TINYINT(1)   NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `enable_ugm_animation`  TINYINT(1)   NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `enable_ugm_image`  TINYINT(1)   NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `enable_ugm_link`  TINYINT(1)   NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `ugm_publish`  TINYINT(1)   NOT NULL ");

        }
        $ugm_strip_tags = $wpdb->get_row('SELECT id FROM ' . $wpdb->prefix . 'gmwd_options WHERE name="ugm_strip_tags"');
        if(!$ugm_strip_tags){
            $gmwd_options_insert = "INSERT INTO  `" . $wpdb->prefix . "gmwd_options` (`id`,  `name`, `value`, `default_value`) VALUES
            ('', 'ugm_strip_tags', '', '' ),	
			('', 'ugm_enable_recaptcha', '', '' ),	
			('', 'ugm_recaptcha_site_key', '', '' ),					
			('', 'ugm_recaptcha_secret_key', '', '' ),				
            ('', 'ugm_send_email', '', '' ),	
            ('', 'ugm_email', '". get_option('admin_email')."', '". get_option('admin_email')."' ),	
            ('', 'ugm_email_subject', 'New Marker', 'New Marker' ),	
            ('', 'ugm_email_body', 
			'A new marker  has been submitted for your map.<br>
			%%marker_data%%
			To edit or view the marker, please click the following link: ".site_url()."/wp-admin/admin.php?page=maps_gmwd&task=edit&id=%%map_id%%',
			'A new marker  has been submitted for your map.<br>
			%%marker_data%%
			To edit or view the marker, please click the following link: ".site_url()."/wp-admin/admin.php?page=maps_gmwd&task=edit&id=%%map_id%%' )	
            ";
            $wpdb->query($gmwd_options_insert);
        }
        $user_id = $wpdb->get_row("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_markers LIKE 'user_id'");
        if(!$user_id){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_markers ADD  `user_id` INT(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_markers ADD  `user_gen` TINYINT(1) NOT NULL ");
        }

        $theme_fields = $wpdb->get_row("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_themes LIKE 'ugm_title_color'"); 

        if(!$theme_fields){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_title_color` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_form_background_color` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `usg_form_border_radius` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_input_border_radius` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_input_border_color` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_label_color` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_label_background_color` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_label_border_radius` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_button_alignment` TINYINT(1) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_button_width` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_button_border_radius` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_button_background_color` VARCHAR(16) NOT NULL ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_themes ADD  `ugm_button_color` VARCHAR(16) NOT NULL ");
			$wpdb->query("UPDATE " . $wpdb->prefix . "gmwd_themes SET ugm_title_color='000000', ugm_form_background_color='F2F2F2', ugm_input_border_color='000000', ugm_label_background_color='F2F2F2', ugm_label_color='000000', ugm_button_background_color='000000', ugm_button_alignment='0', ugm_button_width='150', ugm_button_color='FFFFFF'");			

        }        
    }    
	
}
?>