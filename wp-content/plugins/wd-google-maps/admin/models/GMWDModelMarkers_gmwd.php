<?php

class GMWDModelMarkers_gmwd extends GMWDModel {
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

		if(isset($_POST["data"]) && $_POST["data"] != "{}"){

			$row = json_decode(htmlspecialchars_decode(stripslashes($_POST["data"])));

            $row = json_decode($row);
            if(is_array($row->description)){
                $row->description =  $row->description ? implode("\n", $row->description) : ''; 
                $row->title = str_replace("@@@",'&quot;',$row->title);
                $row->address = str_replace("@@@",'&quot;',$row->address);                
            }
            if(isset($_GET["dublicated"]) && $_GET["dublicated"] == 1){
                $row->id = "";
            }
		}
		else{
			$row = parent::get_row_by_id($id,"gmwd_markers");
            $row->published = 1;
            $row->marker_size = 32;
            $row->choose_marker_icon = 1;
            $row->enable_info_window = 1;
		}

		// get marker categories		
        $marker_categories_model = GMWDHelper::get_model("markercategories");		
		$categories = $marker_categories_model->get_all_categories();
        $categories_array = array();
        foreach($categories as $category){
			$categories_array[$category->id] = str_repeat('<span class="gi">|&mdash;</span>', $category->level ).$category->title;
		}
        $row->categories = $categories_array;      
		return $row;	
	}
	
	public function get_rows(){
		global $wpdb;

		$query = "SELECT *  FROM " . $wpdb->prefix . "gmwd_markers " ;				
		$rows = $wpdb->get_results($query);		
		return $rows;		
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