<?php

class GMWDControllerOptions_gmwd extends GMWDController{
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
	public function apply(){
		global $wpdb;
		$query = "SELECT name FROM ". $wpdb->prefix . "gmwd_options";
        // get option names		
        $names =  $wpdb->get_col( $query , 0 );		

        // update options
  
		for ($i = 0; $i < count($names); $i++) {
			$name = $names[$i];
			$value = isset($_POST[$name]) ? $_POST[$name] : null;
		
			if ($value !== null  ) {
                if($name == "marker_default_icon" && strpos($value,"data:image/png;") !== false){
                    $filename = 'marker_'.time().'.png';                    
                    $uri =  substr($value, strpos($value,",")+1);			
                    file_put_contents(GMWD_UPLOAD_DIR.'/markers/custom/'.$filename,  base64_decode($uri));
                    $value = GMWD_UPLOAD_URL.'/markers/custom/'.$filename;
                }
				$data = array();
				$data["value"] = esc_html(stripslashes($value));
				$where = array("name"=>$name);
				$where_format = $format = array('%s');
				$wpdb->update( $wpdb->prefix . "gmwd_options", $data, $where, $format, $where_format );
			}
		}
	
		GMWDHelper::gmwd_redirect("admin.php?page=options_gmwd&message_id=10&active_tab=".GMWDHelper::get('active_tab'));

	}
    
    public function setup(){
        $this->view->gmwd_setup();
    }
	public function setup_general(){
        $this->view->gmwd_setup_general();
    }
	public function setup_ready(){
        $this->view->gmwd_setup_ready();
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