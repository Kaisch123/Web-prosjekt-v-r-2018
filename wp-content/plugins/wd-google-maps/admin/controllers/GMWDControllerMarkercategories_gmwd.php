<?php

class GMWDControllerMarkercategories_gmwd extends GMWDController{
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
	
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	protected function save(){		
		$this->store_data();	
		GMWDHelper::message(__("Item Succesfully Saved.","gmwd"),'updated');
		$this->display();			
	}

	protected function apply(){
		$id = $this->store_data();
		GMWDHelper::message(__("Item Succesfully Saved.","gmwd"),'updated');
		$this->view->edit($id);				
	}
   	protected function save2copy(){
        $this->store_data();
    	GMWDHelper::message(__("Item Succesfully Saved.","gmwd"),'updated');
		$this->display();
    } 

	private function store_data(){
		global $wpdb;
		$data = array();
		$data["id"] = (int)GMWDHelper::get("id");
		$data["title"] = stripslashes(GMWDHelper::get("title"));
		$data["category_picture"] = stripslashes(GMWDHelper::get("category_picture"));	        
		$data["parent"] = stripslashes(GMWDHelper::get("parent"));	

		$data["level"] = 0;
		if(GMWDHelper::get("parent") != 0){
			$parent_level = $wpdb->get_var($wpdb->prepare("SELECT `level` FROM ". $wpdb->prefix . "gmwd_markercategories WHERE id='%d'", GMWDHelper::get("parent")));
			
			$data["level"] = $parent_level + 1;
		}
		$format = array('%d','%s','%s','%d','%d','%d');
		
		if( GMWDHelper::get("id") == NULL || $this->task == "save2copy" ){		
			$data["published"] = 1;
			$data["id"] = "";	
			$wpdb->insert( $wpdb->prefix . "gmwd_markercategories", $data, $format );
			return $wpdb->get_var("SELECT MAX(id) FROM ". $wpdb->prefix . "gmwd_markercategories");
		}
		else{
			$data["published"] = esc_html(GMWDHelper::get("published"));
			$where = array("id"=>(int)GMWDHelper::get("id"));
			$where_format = array('%d');
			$wpdb->update( $wpdb->prefix . "gmwd_markercategories", $data, $where, $format, $where_format );
			return GMWDHelper::get("id");
		}

	}	

	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}