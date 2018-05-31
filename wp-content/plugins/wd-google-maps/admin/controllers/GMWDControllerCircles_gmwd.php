<?php

class GMWDControllerCircles_gmwd extends GMWDController{
	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	public $map;
	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
	public function __construct(){
		parent::__construct();
		$this->map = GMWDHelper::get("map_id");		
	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	protected function save(){		
		global $wpdb;
		$response = array();
		$data = array();
		$data["id"] = (int)GMWDHelper::get("id");
		$data["address"] = esc_html(GMWDHelper::get("address"));
		$data["lat"] = esc_html(GMWDHelper::get("lat"));	
		$data["lng"] = esc_html(GMWDHelper::get("lng"));	
		$data["title"] = esc_html(GMWDHelper::get("title"));	
		$data["description"] = esc_html(GMWDHelper::get("description"));	
		$data["info_window_open"] = esc_html(GMWDHelper::get("info_window_open"));	
		$data["animation"] = esc_html(GMWDHelper::get("animation"));	
		$data["link_url"] = esc_html(GMWDHelper::get("link_url"));	
		$data["pic_url"] = esc_html(GMWDHelper::get("pic_url"));	
		$data["category"] = esc_html(GMWDHelper::get("category"));	
		$data["marker_size"] = esc_html(GMWDHelper::get("marker_size"));	
		$data["custom_marker_url"] = esc_html(GMWDHelper::get("custom_marker_url"));	
		$data["map_id"] = esc_html(GMWDHelper::get("map_id")) ;	


		$format = array('%d','%s','%s','%s','%s','%s','%d','%s','%s','%s','%d','%s','%s');
		
		if( GMWDHelper::get("id") == NULL){		
			$data["published"] = 1;
			
			$wpdb->insert( $wpdb->prefix . "gmwd_markers", $data, $format );
			$id = $wpdb->get_var("SELECT MAX(id) FROM ". $wpdb->prefix . "gmwd_markers");
		}
		else{
			$data["published"] = esc_html(GMWDHelper::get("published"));
			$where = array("id"=>(int)GMWDHelper::get("id"));
			$where_format = array('%d');
			$wpdb->update( $wpdb->prefix . "gmwd_markers", $data, $where, $format, $where_format );
			$id =  GMWDHelper::get("id");
		}
		
		$response["id"] = (int)$id;
		echo json_encode($response);
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