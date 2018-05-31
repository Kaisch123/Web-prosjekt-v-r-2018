<?php

class GMWDModelThemes_gmwd extends GMWDModel {
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
	public function get_row($id){
		global $wpdb;
        $id = (int)$id;
		$row = parent::get_row_by_id($id);
		
		if(!$id){
			$row->directions_title_color = '000000';
			$row->directions_window_background_color = 'F2F2F2';
			$row->directions_input_border_color = '000000';
			$row->directions_label_background_color = 'F2F2F2';
			$row->directions_label_color = '000000';
			$row->directions_button_background_color = '000000';
			$row->directions_button_alignment = '0';
			$row->directions_button_width = '50';
			$row->directions_button_color = 'FFFFFF';
			$row->store_locator_title_color = '000000';
			$row->store_locator_window_bgcolor = 'F2F2F2';
			$row->store_locator_input_border_color = '000000';
			$row->store_locator_label_background_color = 'F2F2F2';
			$row->store_locator_label_color = '000000';
			$row->store_locator_buttons_alignment = '0';
			$row->store_locator_button_width = '100';
			$row->store_locator_reset_button_background_color = '8A8A8A';
			$row->store_locator_search_button_background_color = '000000';
			$row->store_locator_search_button_color = 'FFFFFF';
			$row->store_locator_reset_button_color = 'FFFFFF';
			$row->marker_listsing_basic_title_color = '000000';
			$row->marker_listsing_basic_bgcolor = 'EEEEEE';
			$row->marker_listsing_basic_marker_title_color = '242424';
			$row->marker_listsing_basic_marker_desc_color = '242424';
			$row->marker_listsing_basic_dir_width = '150';
			$row->marker_listsing_basic_dir_height = '30';
			$row->marker_listsing_basic_dir_color = 'FFFFFF';
			$row->marker_listsing_basic_dir_background_color = '000000';
			$row->marker_advanced_title_color = '000000';
			$row->marker_advanced_table_background = 'EEEEEE';
			$row->marker_advanced_table_color = '545454';
			$row->marker_advanced_table_header_background = '000000';
			$row->marker_advanced_table_header_color = 'FFFFFF';
			$row->advanced_info_window_title_background_color = '000000';
			$row->advanced_info_window_background = 'FFFFFF';
			$row->advanced_info_window_title_color = 'FFFFFF';
			$row->advanced_info_window_dir_color = 'FFFFFF';
			$row->advanced_info_window_desc_color = '545454';
			$row->advanced_info_window_dir_background_color = '000000';
			$row->carousel_background_color = '000000';
			$row->carousel_hover_background_color = 'F2F2F2';
			$row->carousel_items_count = '3';
			$row->carousel_item_height = '60';
			$row->carousel_color = 'FFFFFF';
			$row->carousel_hover_color = '000000';
			$row->marker_listsing_inside_map_bgcolor = 'F2F2F2';
			$row->marker_listsing_inside_map_color = '000000';
			$row->marker_listsing_inside_map_width = '250';
			$row->marker_listsing_inside_map_height = '200';
			$row->published = 1;
			$row->auto_generate_style_code = 1;
            $row->map_style_code = $wpdb->get_var("SELECT styles FROM ". $wpdb->prefix . "gmwd_mapstyles ORDER BY id");           			
            $row->map_style_id = $wpdb->get_var("SELECT MIN(id) FROM ". $wpdb->prefix . "gmwd_mapstyles");           			
		}

		return $row;
	}
    
	public function get_rows(){
		global $wpdb;
      
		$where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE title LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$asc_or_desc = ($asc_or_desc != 'asc') ? 'desc' : 'asc';
		$order_by = ' ORDER BY ' . ((isset($_POST['order_by']) && esc_html(stripslashes($_POST['order_by'])) != '') ? esc_html(stripslashes($_POST['order_by'])) : 'id') . ' ' . $asc_or_desc;
		if (isset($_POST['page_number']) && $_POST['page_number']) {
		  $limit = ((int) $_POST['page_number'] - 1) * $this->per_page;
		}
		else {
		  $limit = 0;
		}
		// get rows
		$query = "SELECT id, title, published, `default`  FROM " . $wpdb->prefix . "gmwd_themes ". $where . $order_by . " LIMIT " . $limit . ",".$this->per_page ;				
		$rows = $wpdb->get_results($query);
		
		return $rows;
	
	}
	public function page_nav() {
		global $wpdb;
		$where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE title LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
		$query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "gmwd_themes " . $where;
		$total = $wpdb->get_var($query);
		$page_nav['total'] = $total;
		if (isset($_POST['page_number']) && $_POST['page_number']) {
			$limit = ((int) $_POST['page_number'] - 1) * $this->per_page;
		}
		else {
			$limit = 0;
		}
		$page_nav['limit'] = (int) ($limit / $this->per_page + 1);
		return $page_nav;
	}

	public function get_lists(){
		$lists = array();
		$map_alignment_list = array("left" => __("Left","gmwd"), "center" => __("Center","gmwd"), "right" => __("Right","gmwd"), "none" => __("None","gmwd"));
		$map_types_list = array( __("Roadmap","gmwd"),  __("Satellite","gmwd"),  __("Hybrid","gmwd"), __("Terrain","gmwd"));
		$map_markers_list = array(__("None","gmwd"), __("Basic Table","gmwd"),  __("Advanced Table","gmwd"),   __("Carousel","gmwd"));
		

		$map_type_control_styles_list = array( __("Default ","gmwd"), __("Horizontal Bar","gmwd"), __("Dropdown Menu","gmwd"));
		
		$map_controls_positions_list = array(  __("Default ","gmwd"), __("Top Center","gmwd"), __("Top Left","gmwd"), __("Top Right","gmwd"), __("Left Top","gmwd"),__("Right Top","gmwd"),__("Left Center","gmwd"),__("Right Center","gmwd"),__("Left Bottom","gmwd"),__("Right Bottom","gmwd"),__("Bottom Center","gmwd"),__("Bottom Left","gmwd"),__("Bottom Right","gmwd"));
		
		
		$map_direction_positions_list = array( __("Top Left","gmwd"), __("Top Right","gmwd"), __("Bottom Left","gmwd"), __("Bottom Right","gmwd"));
        
        
	
		$map_style_feature_types_list = array("all"=>"all","administrative"=>"administrative", "administrative.country"=>"administrative.country","administrative.land_parcel"=>"administrative.land_parcel","administrative.locality"=>"administrative.locality","administrative.neighborhood"=>"administrative.neighborhood","administrative.province"=>"administrative.province","landscape"=>"landscape","landscape.man_made"=>"landscape.man_made","landscape.natural"=>"landscape.natural","landscape.natural.landcover"=>"landscape.natural.landcover","landscape.natural.terrain"=>"landscape.natural.terrain","poi"=>"poi" ,"poi.attraction"=>"poi.attraction","poi.business"=>"poi.business","poi.government"=>"poi.government","poi.medical"=>"poi.medical","poi.park"=>"poi.park","poi.place_of_worship"=>"poi.place_of_worship","poi.school"=>"poi.school","poi.sports_complex"=>"poi.sports_complex","road"=>"road","road.arterial"=>"road.arterial","road.highway"=>"road.highway","road.highway.controlled_access"=>"road.highway.controlled_access","road.local"=>"road.local","transit"=>"transit","transit.line"=>"transit.line","transit.station"=>"transit.station","transit.station.airport"=>"transit.station.airport","transit.station.bus"=>"transit.station.bus","transit.station.rail"=>"transit.station.rail","water"=>"water");
		
		$map_style_element_types_list = array("all"=>"all","geometry"=>"geometry","geometry.fill"=>"geometry.fill","geometry.stroke"=>"geometry.stroke","labels"=>"labels","labels.icon"=>"labels.icon","labels.text"=>"labels.text","labels.text.fill"=>"labels.text.fill","labels.text.stroke"=>"labels.text.stroke");

		$lists["map_alignment"] = $map_alignment_list;
		$lists["map_types"] = $map_types_list;
		$lists["map_markers"] = $map_markers_list;
		$lists["map_type_control_styles"] = $map_type_control_styles_list;
		$lists["map_controls_positions"] = $map_controls_positions_list;

        $lists["map_style_feature_types"] = $map_style_feature_types_list;
		$lists["map_style_element_types"] = $map_style_element_types_list;

		return $lists;
	
	}
	
	public function get_map_styles(){
		global $wpdb;	
		// get rows
		$query = "SELECT * FROM " . $wpdb->prefix . "gmwd_mapstyles ";				
		$rows = $wpdb->get_results($query);	       
		return $rows;	
	
	}
    
    public function get_map_style($style_id){
		global $wpdb;
        $style_id = (int)$style_id;
        if(isset($_POST["style_code"])){
            $styles_array = json_decode(stripslashes(htmlspecialchars_decode ($_POST["style_code"])));
        }
        else{
            $styles_array = array();        
            if($style_id){    
                $query = "SELECT styles FROM " . $wpdb->prefix . "gmwd_mapstyles WHERE id='%d'";				
                $styles = $wpdb->get_var($wpdb->prepare($query, $style_id));
               
                if($styles){
                    $styles_array = json_decode(stripslashes(htmlspecialchars_decode ($styles)));
                }             
            }
        }        
        return  $styles_array;  
	
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