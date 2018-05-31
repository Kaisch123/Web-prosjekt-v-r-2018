<?php

class GMWDControllerMaps_gmwd extends GMWDController{
	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	private $map;
	private $shortcode_id = null;
	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function display_pois(){
		$this->view->display_pois();
		
	}  
	public function remove($table_name = ""){
		global $wpdb;
		$ids = isset($_POST["ids"]) ? $_POST["ids"] :(isset($_POST["id"]) ? array($_POST["id"]) :  array());
      
		if(empty($ids) === false){
			foreach($ids as $id){	
                $id = (int)$id;
				$where = array("map_id" => $id);
				$where_format = array('%d');
				$wpdb->delete( $wpdb->prefix ."gmwd_markers", $where, $where_format);
				$wpdb->delete( $wpdb->prefix ."gmwd_polygons", $where, $where_format);
				$wpdb->delete( $wpdb->prefix ."gmwd_polylines", $where, $where_format);
				$wpdb->delete( $wpdb->prefix ."gmwd_circles", $where, $where_format);
				$wpdb->delete( $wpdb->prefix ."gmwd_rectangles", $where, $where_format);
			}			
		}
		parent::remove($table_name);		
	}
	
    public function export(){
		//ini_set("display_errors", "1");
		//error_reporting(E_ALL);
		error_reporting(0);
        global $wpdb;
        $map_id = (int)GMWDHelper::get('map_id');
        //get cell letters
       	$letters = range('A', 'Z');
		$_letters = array();
		for($i=0;$i<count($letters);$i++){
			for($j=0;$j<count($letters);$j++){
				$_letters[] = $letters[$i].$letters[$j];
			}
		}
        $letters = array_merge($letters,$_letters);

		// Include PHPExcel library			
		require_once GMWD_DIR . '/libraries/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        //PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        $tables = array("markers", "circles", "rectangles", "polygons", "polylines");

        for($i=0; $i<count($tables); $i++){
            $table = $tables[$i];
            // get overlay data
            $overlays = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix .'gmwd_'.$table.' WHERE map_id="'.$map_id.'"');
            
            // get each table's fields names
            $columns = $wpdb->get_results('SHOW COLUMNS FROM '.$wpdb->prefix .'gmwd_'.$table.'');

            // create Excel worksheet
			$worksheet = $objPHPExcel->createSheet($i);
			$objPHPExcel->getSheet($i)->setTitle(ucfirst($table));
            
            $l = 2;
            // define number of cells in worksheet
			$indexes = array();
			for( $k=0; $k<count($columns)-1; $k++ ){
				$indexes[] = $letters[$k];
				// set cells width
				$objPHPExcel->getSheet($i)->getColumnDimension($letters[$k])->setWidth(50);				
			}
            
            $j = 0;
            // define headers
			foreach( $columns as $column ){
                if($column->Field == "id"){
                    continue;
                }
				$header = ucwords(str_replace('_',' ', $column->Field));
				$worksheet->getCell($indexes[$j].'1')->setValue($header);
				$j++;
			}
            
            // define rest rows
			foreach( $overlays as $overlay ){
				$j = 0;
				foreach( $columns as $column){
                    if($column->Field == "id"){
                        continue;
                    }
					$field_name = $column->Field;			
					$worksheet->getCell($indexes[$j].$l)->setValue( $overlay->$field_name);
					$j++;
				}
			
				$l++;
			}
            
        }
        
        //set active sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// remove last empty sheet
		$objPHPExcel->removeSheetByIndex( count($tables) );
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="overlays.xlsx"');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');
        // write file to the browser
        $objWriter->save('php://output');
        die();
   
    }
       
    public function import(){
        global $wpdb;
        $map_id = (int)GMWDHelper::get('id');
		$errors = array();
		// upload xlxs file	
		if (!$_FILES['import_overlays']["error"] > 0) {				
			if($_FILES['import_overlays']["tmp_name"]!=''){
				$path_info = pathinfo($_FILES['import_overlays']['name']);	
				$extension =  $path_info['extension'];				
				if($extension == 'xlsx' || $extension == 'xls'){
					move_uploaded_file($_FILES['import_overlays']["tmp_name"], GMWD_DIR."/import.xlsx");
				}
				else{
					GMWDHelper::gmwd_redirect("admin.php?page=maps_gmwd&task=edit&id=".$map_id."&message_id=9")	;					
				}				
			}	
		}
        
		// Include PHPExcel library			
		require_once GMWD_DIR . '/libraries/PHPExcel.php';
		require_once GMWD_DIR . '/libraries/PHPExcel/IOFactory.php';

		$objPHPExcel = new PHPExcel();
        $path = GMWD_DIR."/import.xlsx";        
		$objPHPExcel = PHPExcel_IOFactory::load($path);
        
        $tables = array("markers", "circles", "rectangles", "polygons", "polylines");
        
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet){
            $worksheet_title = strtolower($worksheet->getTitle());
            if(in_array($worksheet_title,$tables) == false){
                $errors[] = __("Unexpected Worksheet Title ".$worksheet->getTitle().".","gmwd");
                break;
            }
            $highest_row = $worksheet->getHighestRow(); 
			$highest_column = $worksheet->getHighestColumn();
			$highest_column_index = PHPExcel_Cell::columnIndexFromString($highest_column);            
			$nr_columns = ord($highest_column) - 64;
            
            $columns = GMWDModel::get_columns("gmwd_".$worksheet_title);
            $column_types = GMWDModel::column_types("gmwd_".$worksheet_title);
            

            $format = array();
                
            for ($row = 2; $row <= $highest_row; ++ $row) {
                $data = array();	
                for ($col = 0; $col < $highest_column_index; ++ $col) {
                    $cell_name = str_replace(" ","_",strtolower($worksheet->getCellByColumnAndRow($col, 1)));
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                 
					if($cell_name){
						$data[$cell_name] = $cell->getValue() ? sanitize_text_field(stripslashes(esc_html($cell->getValue())))  : "";
						$format[] = $column_types[$cell_name]; 						
					}				
                }
				if (strlen(implode($data)) == 0) {
					break;
				}              
                $data["map_id"] = $map_id ;
                $data["id"] = "" ;

                if(array_diff(array_keys($data), $columns) != array()){
                    $error = array_diff($columns,array_keys($data)) ? array_diff($columns,array_keys($data)) : array_diff(array_keys($data), $columns) ;
                  
                    $errors[] = __("Unexpected Worksheet Cell Headers ".implode(", ",$error).".","gmwd");
                    break;
                }
                
                $wpdb->insert( $wpdb->prefix . "gmwd_".$worksheet_title, $data, $format );
            }
            
        }
		if(file_exists(GMWD_DIR."/import.xlsx")){
            unlink(GMWD_DIR."/import.xlsx");
        }
		
        if(empty($errors) === false){
            $errors = array_unique($errors);
            $errors = implode("<br>",$errors);
			GMWDHelper::gmwd_redirect("admin.php?page=maps_gmwd&task=edit&id=".$map_id."&message_id=".$errors)	;			
        }
        else{
			GMWDHelper::gmwd_redirect("admin.php?page=maps_gmwd&task=edit&id=".$map_id."&message_id=8")	;
        }

    }
	
    public function download_markers(){
        update_option('gmwd_download_markers',1);
        $marker_categories = array("clothtexture", "coloring",  "modern", "papertexture", "retro", "standart", "woodtexture");
        foreach($marker_categories as $marker_category){
            if($marker_category == "standart"){
                $count = 52;
            }
            else{
                $count = 13;
            }
            for($i=1; $i<=$count; $i++){
                $file256_name = $marker_category."/".$marker_category."_".$i.".png";
                $file64_name = $marker_category."/".$marker_category."_".$i."_64.png";
     
                $file256 = file_get_contents("http://wpdemo.web-dorado.com/demoimages/markers/".$file256_name);
                $file64 = file_get_contents("http://wpdemo.web-dorado.com/demoimages/markers/".$file64_name);
                
                file_put_contents(GMWD_UPLOAD_DIR.'/markers/'.$file256_name, $file256);
                file_put_contents(GMWD_UPLOAD_DIR.'/markers/'.$file64_name, $file64);
            }
             
        }    
    }
   
    public function map_data(){
        $map_model = GMWDHelper::get_model("maps");
        $id = (int)$_POST["map"];

        if($id){
            $row = $map_model->get_row($id);
			$row->fusion_table_where_value = htmlspecialchars_decode(addslashes($row->fusion_table_where_value),ENT_QUOTES);
            echo json_encode($row);
            die();
        }		

    }

    ////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
    protected function cancel(){
		GMWDHelper::gmwd_redirect("admin.php?page=maps_gmwd");		
	}
 	protected function save(){		
		$this->store_data();	
		GMWDHelper::message(__("Item Succesfully Saved.","gmwd"),'updated');
		$this->display();			
	}


	public function apply(){	
		$this->store_data();	
		GMWDHelper::gmwd_redirect("admin.php?page=maps_gmwd&task=edit&id=".$this->map."&message_id=1&active_main_tab=".GMWDHelper::get('active_main_tab')."&active_settings_tab=".GMWDHelper::get('active_settings_tab')."&active_poi_tab=".GMWDHelper::get('active_poi_tab'));		
		GMWDHelper::message(__("Item Succesfully Saved.","gmwd"),'updated');
		//$this->view->edit($this->map);
		
	}
    public function for_preview(){  

        $response = array();
        $url = admin_url( 'index.php?page=gmwd_preview');
        $url = add_query_arg(array("map_id"=> $this->map), $url);
        $response["url"] = $url;
        $response["map_id"] = $this->map;
        echo json_encode($response);
        die();
        
    }
   	protected function save2copy(){
        $this->store_data();
    	GMWDHelper::message(__("Item Succesfully Saved.","gmwd"),'updated');
		$this->display();
    } 
    
    protected function dublicate($table_name = ""){  
        global $wpdb;
		$ids = array();
		
		if(isset($_POST["ids"])){
			$ids = $_POST["ids"] ;			
		}
        if(empty($ids) === false){
			$map_columns = GMWDModel::get_columns("gmwd_maps");
			$map_column_types = GMWDModel::column_types("gmwd_maps");
			
			$pois = array("markers", "polygons", "polylines", "circles", "rectangles");
			foreach($ids as $id){
				$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "gmwd_maps  WHERE id = '%d'", (int)$id ));
				$data = array();
				$format = array();
				foreach($map_columns as $column_name){
					$data[$column_name] = esc_html(stripslashes($row->$column_name));
					$format[] = $map_column_types[$column_name];		
				}
				$data["id"] = "";
				$max_shortcode_id = $wpdb->get_var("SELECT MAX(id) FROM ". $wpdb->prefix . "gmwd_shortcodes"); 
				$data["shortcode_id"] = $max_shortcode_id + 1;

				
				$wpdb->insert( $wpdb->prefix . "gmwd_maps", $data, $format );
				$last_map_id = $wpdb->get_var("SELECT MAX(id) FROM " . $wpdb->prefix . "gmwd_maps");
				
				$this->shortcode_id = $max_shortcode_id + 1;
				$this->map = $last_map_id;
				$this->store_shortcode();
				
				foreach($pois as $poi){
					$columns = GMWDModel::get_columns("gmwd_".$poi);
					unset($columns[0]);
					$inserted_columns = $columns;
					$inserted_columns[array_search("map_id",$inserted_columns)] = $last_map_id;
					$columns = implode(",", $columns);
					$inserted_columns = implode(",", $inserted_columns);
					$rows_poi = $wpdb->query("INSERT INTO  " . $wpdb->prefix . "gmwd_".$poi." (".$columns.")
					SELECT ".$inserted_columns." FROM " . $wpdb->prefix . "gmwd_".$poi." WHERE map_id = '". (int)$id."'");

				}
			}
			GMWDHelper::message(__("Item Succesfully Duplicated.","gmwd"),'updated');
		
		}
		else{
			GMWDHelper::message(__("You Must Select At Least One Item.","gmwd"),'updated');
		}
		
		$view = $this->view;
		$view->display();	
         
    }
    
    private function store_data(){
		$this->store_map_data();
        if($this->shortcode_id){
            $this->store_shortcode();
        }
        $markers_count = GMWDHelper::get("markers_count");
        
        $data_markers = array();
        for($i=0; $i<$markers_count; $i++){
        
            $data_markers = array_merge($data_markers,json_decode(htmlspecialchars_decode(stripslashes(GMWDHelper::get("main_markers".$i)))));
        }
             
		$data_circles = json_decode(htmlspecialchars_decode(stripslashes(GMWDHelper::get("circles"))));
		$data_rectangles = json_decode(htmlspecialchars_decode(stripslashes(GMWDHelper::get("rectangles"))));
		$data_polygons = json_decode(htmlspecialchars_decode(stripslashes(GMWDHelper::get("polygons"))));
		$data_polylines = json_decode(htmlspecialchars_decode(stripslashes(GMWDHelper::get("polylines"))));

		$this->store_poi_data("markers", $data_markers);
		$this->store_poi_data("circles", $data_circles);
		$this->store_poi_data("rectangles", $data_rectangles);
		$this->store_poi_data("polygons", $data_polygons);
		$this->store_poi_data("polylines", $data_polylines);
		
	}
	private function store_map_data(){
		global $wpdb;
		
		$columns = GMWDModel::get_columns("gmwd_maps");
		$column_types = GMWDModel::column_types("gmwd_maps");

		$data = array();
		$format = array();
		foreach($columns as $column_name){
			$data[$column_name] = esc_html(stripslashes(GMWDHelper::get($column_name)));
			$format[] = $column_types[$column_name];		
		}	

		if( GMWDHelper::get("id") == NULL || $this->task == "save2copy"){	
            $max_shortcode_id = $wpdb->get_var("SELECT MAX(id) FROM ". $wpdb->prefix . "gmwd_shortcodes");  
			$data["published"] = 1;
			$data["shortcode_id"] = $max_shortcode_id + 1;
			$data["id"] = "";
		
			$wpdb->insert( $wpdb->prefix . "gmwd_maps", $data, $format );
			//$wpdb->print_error(); exit;
			$id = $wpdb->get_var("SELECT MAX(id) FROM ". $wpdb->prefix . "gmwd_maps");
                  
            $this->shortcode_id = $max_shortcode_id + 1;
		}
		else{
			$data["published"] = esc_html(GMWDHelper::get("published"));
			$where = array("id"=>(int)GMWDHelper::get("id"));
			$where_format = array('%d');
			$wpdb->update( $wpdb->prefix . "gmwd_maps", $data, $where, $format, $where_format );
			$id = GMWDHelper::get("id");
		}		
		$this->map = $id;
       
        
	}

	private function store_poi_data($poi, $data_pois){		
		global $wpdb;
		$data_types = GMWDModel::column_types("gmwd_".$poi);

		foreach($data_pois as $_data){
				
			$data = array();
			$format = array();
			foreach($_data as $key => $value){	
                if($poi == "markers" && $key == "description" && is_array($value)){
                    $value = implode(PHP_EOL, $value);
                }
				$data[$key] = sanitize_text_field(esc_html(stripslashes($value)));
				$format[] = $data_types[$key];
			}

			//rewrite map id
			$data["map_id"] = $this->map;
			if($poi == "markers" && strpos($_data->custom_marker_url,"data:image/png;") !== false){
				$filename = 'marker_'.time().'.png';
				$data["custom_marker_url"] = GMWD_UPLOAD_URL.'/markers/custom/'.$filename;
			}
           
			
			if( $_data->id == "" || $this->task == "save2copy" ){		
				//$data["published"] = 1;			
				$data["id"] = "";			
				$wpdb->insert( $wpdb->prefix . "gmwd_".$poi, $data, $format );			   			
			}
			else{
				//$data["published"] = esc_html($_data->published);
				$where = array("id"=>$_data->id);
				$where_format = array('%d');
				$wpdb->update( $wpdb->prefix . "gmwd_".$poi, $data, $where, $format, $where_format );
			}
							
			if($poi == "markers" && strpos($_data->custom_marker_url,"data:image/png;") !== false){
				$uri =  substr($_data->custom_marker_url, strpos($_data->custom_marker_url,",")+1);			
				file_put_contents(GMWD_UPLOAD_DIR.'/markers/custom/'.$filename,  base64_decode($uri));
			}
			
		}
	
	}	
	
	private function store_shortcode(){
        global $wpdb;
        $data = array();
        $data["tag_text"] = 'id='.$this->shortcode_id.' map='.$this->map;
        $format = array('%s');
        $wpdb->insert( $wpdb->prefix . "gmwd_shortcodes", $data, $format );
    
    }
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}