<?php

class GMWDControllerThemes_gmwd extends GMWDController{
	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	private $theme;
	private $map_new_style_id = 0;
	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function make_default(){
		global $wpdb;

		if(isset($_POST["current_id"])){
			$id = (int)$_POST["current_id"] ;	
			$wpdb->query("UPDATE ".$wpdb->prefix . "gmwd_themes SET `default`= IF(id='".$id."', 1, 0) " );	
			GMWDHelper::message(__("The Item Is Successfully Set as Default. ","gmwd"),'updated');				
		}
		
		$this->display();		
	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
 	protected function save(){		
		$this->store_data();	
		GMWDHelper::message(__("Theme Succesfully Saved.","gmwd"),'updated');
		$this->display();			
	}

	protected function apply(){	
		$this->store_data();		
		GMWDHelper::message(__("Theme Succesfully Saved.","gmwd"),'updated');
		$this->view->edit($this->theme);				
	}
   	protected function save2copy(){
        $this->store_data();
    	GMWDHelper::message(__("Theme Succesfully Saved.","gmwd"),'updated');
		$this->display();
    }   

	private function store_data(){
		global $wpdb;
		$this->store_map_styles();		
		$columns = GMWDModel::get_columns("gmwd_themes");
		$column_types = GMWDModel::column_types("gmwd_themes");
	
		$data = array();
		$format = array();
		foreach($columns as $column_name){
			$data[$column_name] = esc_html(stripslashes(GMWDHelper::get($column_name)));
			$format[] = $column_types[$column_name];		
		}	
        if($this->map_new_style_id != 0){
            $data["map_style_id"] = $this->map_new_style_id;
        }
		if( GMWDHelper::get("id") == NULL || $this->task == "save2copy"){		
			$data["published"] = 1;
			$data["id"] = "";
		
			$wpdb->insert( $wpdb->prefix . "gmwd_themes", $data, $format );
			//$wpdb->print_error(); exit;
			$id = $wpdb->get_var("SELECT MAX(id) FROM ". $wpdb->prefix . "gmwd_themes");
		}
		else{
			$data["published"] = esc_html(GMWDHelper::get("published"));
			$where = array("id"=>GMWDHelper::get("id"));
			$where_format = array('%d');
			$wpdb->update( $wpdb->prefix . "gmwd_themes", $data, $where, $format, $where_format );
			$id = GMWDHelper::get("id");
		}
       
		$this->theme = $id;
	}

	private function store_map_styles(){
		global $wpdb;
        $all_styles = GMWDHelper::get("all_styles");
       
        $all_styles = json_decode(stripslashes(htmlspecialchars_decode($all_styles)));

        foreach($all_styles as $id => $styles_array){
            $data = array();
            if(strpos($id, "tmp") !== false ){
                if($id == GMWDHelper::get("map_style_id")){
                    $this->map_new_style_id = $id;                     
                }
                $id =  "";
            }
            
           
            $data["styles"] = sanitize_text_field(esc_html($styles_array));
            
            $static_map_url = "";
            $styles_array = json_decode($styles_array);
            if($styles_array){
                foreach($styles_array as $style){
                    $style->featureType = isset($style->featureType) ? $style->featureType : "all";
                    $style->elementType = isset($style->elementType) ? $style->elementType : "all";
                    $static_map_url .= "&style=feature:".$style->featureType."|element:".$style->elementType;
                    if(isset($style->stylers)){
                        foreach($style->stylers as $styler){
                            foreach($styler as $key => $val){
                                $static_map_url .= "|".$key.":".str_replace("#","0x",$val);
                            }
                        }
                    }
                }
            }
            $data["image"] = $static_map_url;
            $format = array('%s','%s');
            
            if($id == ""){		
                $data["id"] = "";	
                $wpdb->insert( $wpdb->prefix . "gmwd_mapstyles", $data, $format );
                if($this->map_new_style_id){                
                    $this->map_new_style_id = $wpdb->get_var("SELECT MAX(id) FROM ". $wpdb->prefix . "gmwd_mapstyles");
                }                
            }
            else{
                $where = array("id"=>$id);
                $where_format = array('%d');
                $wpdb->update( $wpdb->prefix . "gmwd_mapstyles", $data, $where, $format, $where_format );
            }
        }

	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}