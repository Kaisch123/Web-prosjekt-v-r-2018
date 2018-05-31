<?php
        
class GMWDModelFrontendMap extends GMWDModelFrontend{
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
	public function get_map(){

		global $wpdb;
		$params = $this->params;
    
		$id = isset($params["map"]) ? (int)$params["map"] : 0;
		$shortcode_id = isset($params["id"]) ? $params["id"] : '';
        if(!$shortcode_id){
            echo "<h2>". __("Invalid Request","gmwd"). "</h2>";
        } 
        elseif(!$id){
            echo "<h2>". __("Please Select Map","gmwd"). "</h2>";
        }
        else{ 
            $row = parent::get_row_by_id($id, "maps");   
         
            if($row && $row->published == 1) {
                $row->height = $row->height ? $row->height : 300;
                $row->advanced_table_columns = explode(",",$row->advanced_table_columns);
                // params for widget
                $row->width = isset($params["width"])  ? esc_html(stripslashes($params["width"])) : $row->width;
                $row->height = isset($params["height"]) ? esc_html(stripslashes($params["height"])) : $row->height;
                $row->width_percent = isset($params["width_unit"]) ? esc_html(stripslashes($params["width_unit"])) : $row->width_percent;                
                $row->zoom_level = isset($params["zoom_level"]) && $params["zoom_level"] ? esc_html(stripslashes($params["zoom_level"])) : $row->zoom_level;
                $row->type = isset($params["type"]) &&  $params["type"] ? esc_html(stripslashes($params["type"])) : $row->type;
               
                return $row;
            }
            else{
               echo "<h2>". __("Invalid Request","gmwd"). "</h2>";
            }
        }
	
	}
	
	public function get_overlays( $order_by = "title", $order_dir = "asc"){
		global $wpdb;
		$params = $this->params;
		$id = (int)$params["map"];
		$overlays = new StdClass();
        $overlays->markers = array();
        $overlays->circles = array();
        $overlays->rectangles = array();
        $overlays->polygons = array();
        $overlays->polylines = array();
		if($id){
			
			$order_by = isset($_POST["order_by"]) ?  esc_html(stripslashes($_POST["order_by"])) : $order_by;
			$order_dir = isset($_POST["order_dir"]) ? esc_html(stripslashes($_POST["order_dir"])) : $order_dir;
            $categories = isset($_POST["categories"]) ? $_POST["categories"] : array();            
            array_walk($categories, create_function('&$value', '$value = (int)$value;'));

            
            $radius = isset($_POST["radius"]) ? esc_html(stripslashes($_POST["radius"])) : "";
            $lat = isset($_POST["lat"]) ? esc_html(stripslashes($_POST["lat"])) : "";
            $lng = isset($_POST["lng"]) ? esc_html(stripslashes($_POST["lng"])) : "";
            $distance_in = isset($_POST["distance_in"]) ? esc_html(stripslashes($_POST["distance_in"])) : "";
            $distance_in = $distance_in == "km" ? 6371 : 3959;
            
            $filter_categories =  count( $categories ) > 0 ? " AND category REGEXP '(^|,)(".implode("|", $categories ).")(,|$)' " : "";

			$searched_value = isset($_POST["search"]) && $_POST["search"]!= "" ? " AND (T_MARKERS.title LIKE '%".esc_html(stripslashes($_POST["search"]))."%' OR T_MARKERS.description LIKE '%".esc_html(stripslashes($_POST["search"]))."%' OR T_MARKERS.address LIKE '%".esc_html(stripslashes($_POST["search"]))."%')" : "";
            $select_distance = "";
            $having_distance = "";
            if($distance_in && $radius && $lat && $lng){
                $select_distance = ", ( ".$distance_in." * acos( cos( radians(".$lat.") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(".$lng.") ) + sin( radians(".$lat.") ) * sin( radians( lat ) ) ) ) AS distance";
                $having_distance = "HAVING distance<".$radius;
            }
            
            $limit = isset($_POST["limit"]) ? esc_html(stripslashes($_POST["limit"])) : 20;      
            $limit_by = " LIMIT 0, ". (int)$limit;
			
      
            $markers = $wpdb->get_results("SELECT T_MARKERS.* ".$select_distance." FROM " . $wpdb->prefix . "gmwd_markers  AS T_MARKERS WHERE T_MARKERS.published = '1' AND T_MARKERS.map_id= '".$id."' ".$searched_value.$filter_categories. " ".$having_distance." ORDER BY ".$order_by." ".$order_dir. " ".$limit_by);			

			$row_markers = array();
			foreach($markers as $marker){
                $marker->description = $marker->description ? preg_split('/\r\n|\r|\n/', $marker->description) : array();  
				$row_markers[$marker->id] = $marker;
				$marker_cats = array();
				if($marker->category){
					$marker_cats = $wpdb->get_col("SELECT title FROM " . $wpdb->prefix . "gmwd_markercategories WHERE id IN (".$marker->category.")");					
				}

				$marker->cat_title = implode("<br>",$marker_cats);
			}
			$overlays->markers  = $row_markers;
            
            
			$all_markers = $wpdb->get_results("SELECT T_MARKERS.* ".$select_distance." FROM " . $wpdb->prefix . "gmwd_markers  AS T_MARKERS WHERE T_MARKERS.published = '1' AND T_MARKERS.map_id= '".$id."' ".$searched_value.$filter_categories. " ".$having_distance." ORDER BY ".$order_by." ".$order_dir);			

			$row_all_markers = array();
			foreach($all_markers as $marker){
                $marker->description = $marker->description ? preg_split('/\r\n|\r|\n/', $marker->description) : array();  
				$row_all_markers[$marker->id] = $marker;			
			}
			$overlays->all_markers  = $row_all_markers;            
			
			$circles = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "gmwd_circles WHERE map_id= '".$id."'  AND published = '1' ORDER BY id ");	
			$row_circles = array();
			foreach($circles as $circle){
				$row_circles[$circle->id] = $circle;			
			}
			$overlays->circles = $row_circles;
            
			$rectangles = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "gmwd_rectangles WHERE map_id= '".$id."'  AND published = '1' ORDER BY id ");	
			$row_rectangles = array();
			foreach($rectangles as $rectangle){
				$row_rectangles[$rectangle->id] = $rectangle;			
			}
			$overlays->rectangles = $row_rectangles;            
					
			$polygons = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "gmwd_polygons WHERE map_id= '".$id."' AND published = '1'  ORDER BY id ");
			$row_polygons = array();
			foreach($polygons as $polygon){
				$row_polygons[$polygon->id] = $polygon;			
			}
			$overlays->polygons = $row_polygons;
			
			$polylines = $wpdb-> get_results("SELECT * FROM " . $wpdb->prefix . "gmwd_polylines WHERE map_id= '".$id."' AND published = '1' ORDER BY id ");
			$row_polylines = array();
			foreach($polylines as $polyline){
				$row_polylines[$polyline->id] = $polyline;			
			}
			$overlays->polylines = $row_polylines;

		}
        return $overlays;
	}
    
    public function get_markers_page_nav(){
		$params = $this->params;
		$id = (int)$params["map"];
        if($id){
			$overlays = $this->get_overlays();
			$markers = $overlays->all_markers;
            return count($markers);
        }
        return 0;
    
    }

	
	public function get_marker_categories($show_cat_icon, $ids = array()){
        global $wpdb;
        $qurey_ids = count($ids) ? " AND id IN (".implode(",", $ids).")" : ""; 
		// get rows
		$query = "SELECT  * FROM " . $wpdb->prefix . "gmwd_markercategories WHERE published='1' ". $qurey_ids ." ORDER BY id" ;
		$rows = $wpdb->get_results($query);
		$parent_id = $this->get_root_parents( $rows );
        
        $categories_html = $this->get_categories($parent_id, $show_cat_icon, $ids);
        $categories_store_locator_html = $this->get_categories_store_locator($parent_id, $ids);
		
		return array($categories_html, $categories_store_locator_html);
	}
	
	public function get_theme($theme_id){
		global $wpdb;
        $theme_id = (int)$theme_id;
		$theme = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "gmwd_themes WHERE id='%d'", $theme_id));	          
		return $theme;		
	}
    
    public function get_categories($parent, $show_cat_icon, $ids){
        global $wpdb;
        $params = $this->params;
       
        $html = '<ul class="gmwd_categories wd-clear">';
        $qurey_ids = count($ids) ? " AND id IN (".implode(",", $ids).")" : ""; 
        $query = "SELECT * FROM " . $wpdb->prefix . "gmwd_markercategories  WHERE published='%d' AND `parent`='%d' ".$qurey_ids." ORDER BY id" ;
		$rows = $wpdb->get_results($wpdb->prepare($query, 1, $parent));
        foreach($rows as $row)
        {
            $has_sub = NULL;
            $image = $show_cat_icon == 1 ? ( $row->category_picture ? '<img src="'.$row->category_picture.'" style="max-height:20px;margin-top: -4px;">' : '<img src="'.GMWD_URL.'/images/no-image-small.png" style="max-height:20px;margin-top: -4px;">') : "";
            
           
            $has_sub = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM " . $wpdb->prefix . "gmwd_markercategories  WHERE published='%d' AND `parent`='%d' " , 1, $row->id ));
          
            $image_arrow = $has_sub ? '<img src="'.GMWD_URL.'/images/arrow_down.png" style="max-height:20px;margin-top:-4px; margin-right: 4px;" class="gmwd_open_childs'.$params["id"].'" onclick="jQuery(this).closest(\'li\').find(\'>ul\').slideToggle(\'fast\')">' : '';
           
            $html .= '<li>  <div class="gmwd_category wd-clear">                          
								<div class="gmwd_category_check">
									<input type="checkbox" id="gmwd_marker_cat'.$params["id"].$row->id.'" class="gmwd_marker_cat'.$params["id"].'" value="'.$row->id.'" onchange="gmwdCategoryFilter('.$params["id"].');">
								</div> 								
								<div class="gmwd_category_label">
									'.$image.'
									<label for="gmwd_marker_cat'.$params["id"].$row->id.'" >'. $row->title.'</label>
                                    
                                    <div class="gmwd_arrow">
                                        '.$image_arrow.'								
                                    </div>	                                    
								</div>

                            </div>';


            if($has_sub){
                $html .= $this->get_categories($row->id, $show_cat_icon, $ids);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function get_categories_store_locator($parent, $ids){
        global $wpdb;
        $params = $this->params;
        $html = '<ul class="gmwd_categories wd-clear">';
        $qurey_ids = count($ids) ? " AND id IN (".implode(",", $ids).")" : ""; 
        $query = "SELECT * FROM " . $wpdb->prefix . "gmwd_markercategories  WHERE published='%d' AND `parent`='%d' " . $qurey_ids. " ORDER BY id" ;
		$rows = $wpdb->get_results($wpdb->prepare($query, 1, $parent));
        foreach($rows as $row){
        
            $has_sub = NULL;
           
            $has_sub = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM " . $wpdb->prefix . "gmwd_markercategories  WHERE published='%d' AND `parent`='%d'", 1, $row->id ));
          
            $image_arrow = $has_sub ? '<img src="'.GMWD_URL.'/images/arrow_down.png" style="max-height:20px;margin-top: -4px;    margin-left: 8px;margin-right: -4px; cursor:pointer;" class="gmwd_open_childs'.$params["id"].'" onclick="jQuery(this).closest(\'li\').find(\'>ul\').slideToggle(\'fast\')">' : '';
           
            $html .= '<li> 
                         <div class="wd-form-row">
                            <label for="gmwd_marker_categoriy'.$row->id.$params["id"].'">'.$row->title.'</label>
                            <input type="checkbox" class="gmwd_marker_categories'.$params["id"].'" value="'.$row->id.'" id="gmwd_marker_categoriy'.$row->id.$params["id"].'">'.$image_arrow.'
                        </div>';            
            if($has_sub){
                $html .= $this->get_categories_store_locator($row->id, $ids);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }    
	
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////

	
	private function get_root_parents( $rows ){
		$ids = array();
		foreach ($rows as $key => $row)
			$ids[] = $row->parent;			
		return $ids ? min($ids) : 0;		
	}
    
	
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}