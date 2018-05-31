<?php

class GMWDControllerFrontendMap extends  GMWDControllerFrontend{
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
	public function get_ajax_markers(){
		global $wpdb;
        $categories = isset($_POST["categories"]) ? $_POST["categories"] : array();
        array_walk($categories, create_function('&$value', '$value = (int)$value;'));
        
        $filter_categories =  count( $categories ) > 0 ? " AND category REGEXP '(^|,)(".implode("|", $categories ).")(,|$)' " : "";


        $searched_value = isset($_POST["search"]) && esc_html(stripslashes($_POST["search"]))!= "" ? " AND ( T_MARKERS.title LIKE '%".esc_html(stripslashes($_POST["search"]))."%' OR T_MARKERS.description LIKE '%".esc_html(stripslashes($_POST["search"]))."%' OR T_MARKERS.address LIKE '%".esc_html(stripslashes($_POST["search"]))."%')" : "";
		$id = (int)$_POST["map_id"];
		
		$markers = $wpdb->get_results("SELECT T_MARKERS.* FROM  " . $wpdb->prefix . "gmwd_markers AS T_MARKERS  WHERE T_MARKERS.published = '1' AND T_MARKERS.map_id= '".$id."' ".$searched_value.$filter_categories." ORDER BY id");
        $row_all_markers = array();
		foreach($markers as $marker){
            $marker->description = $marker->description ? preg_split('/\r\n|\r|\n/', $marker->description) : array();  
            $row_all_markers[$marker->id] = $marker;			
		}
		echo json_encode($row_all_markers);
		die();	
	}
 	public function get_ajax_store_loactor(){
		global $wpdb;

		$id = (int)$_POST["map_id"];
		$radius = floatval($_POST["radius"]);
		$lat = floatval($_POST["lat"]);
		$lng = floatval($_POST["lng"]);
		$distance_in = esc_html(stripslashes($_POST["distance_in"]));
		$categories = isset($_POST["categories"]) ? $_POST["categories"] : array();
        $distance_in =  $distance_in == "km" ? 6371 : 3959;

        array_walk($categories, create_function('&$value', '$value = (int)$value;'));

        $filter_categories = count($categories) > 0 ? " AND category REGEXP '(^|,)(".implode("|", $categories ).")(,|$)' " : "";


		$markers = $wpdb->get_results("SELECT T_MARKERS.*, ( ".$distance_in." * acos( cos( radians(".$lat.") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(".$lng.") ) + sin( radians(".$lat.") ) * sin( radians( lat ) ) ) ) AS distance FROM (SELECT * FROM " . $wpdb->prefix . "gmwd_markers WHERE published = '1' AND map_id= '".$id."' ".$filter_categories." ) AS T_MARKERS HAVING distance<".$radius." " );
        
        foreach($markers as $marker){
            $marker->description = $marker->description ? preg_split('/\r\n|\r|\n/', $marker->description) : array();  		
		}
  

		echo json_encode($markers);
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