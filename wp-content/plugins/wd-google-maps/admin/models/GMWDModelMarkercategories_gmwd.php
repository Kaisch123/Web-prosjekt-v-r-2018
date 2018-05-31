<?php

class GMWDModelMarkercategories_gmwd extends GMWDModel {
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
        $id = (int)$id;
		global $wpdb;
		if($id){
			$query = "SELECT T_CATS.*, T_CATS_PARENTS.title AS parent_title, T_CATS_PARENTS.id AS parent_id  FROM " . $wpdb->prefix . "gmwd_markercategories AS T_CATS LEFT JOIN " . $wpdb->prefix . "gmwd_markercategories AS  T_CATS_PARENTS ON T_CATS.parent = T_CATS_PARENTS.id WHERE T_CATS.id = '%d'" ;	
			$row = 	$wpdb->get_row($wpdb->prepare($query, $id ));	
		}
		else{
			$row = new StdClass();
			$row->id = 0;
			$row->title = "";
			$row->parent = 0;
			$row->level = 1;
			$row->parent_title = "";
			$row->category_picture = "";
			$row->published = 1;
		}	
		return $row;
	}
	public function get_rows(){
		global $wpdb;
		$where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE T_CATS.title LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
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
		$query = "SELECT  T_CATS.*, T_CATS_PARENTS.title AS parent_title  FROM " . $wpdb->prefix . "gmwd_markercategories AS T_CATS LEFT JOIN " . $wpdb->prefix . "gmwd_markercategories AS  T_CATS_PARENTS ON T_CATS.parent = T_CATS_PARENTS.id ". $where . $order_by ;

		$rows = $wpdb->get_results($query);

		$parent_id = $this->get_root_parents( $rows );
        $sorted_rows = $this->parent_child_sort($rows, $parent_id);
        $sorted_rows = array_slice($sorted_rows, $limit, $this->per_page);
				
		return $sorted_rows;	

	
	}
    
    public function get_all_categories(){
        global $wpdb;
		$query = "SELECT  T_CATS.*, T_CATS_PARENTS.title AS parent_title  FROM " . $wpdb->prefix . "gmwd_markercategories AS T_CATS LEFT JOIN " . $wpdb->prefix . "gmwd_markercategories AS  T_CATS_PARENTS ON T_CATS.parent = T_CATS_PARENTS.id WHERE T_CATS.published='1'" ;				
		$rows = $wpdb->get_results($query);
		
		$parent_id = $this->get_root_parents( $rows );
        $sorted_rows = $this->parent_child_sort($rows, $parent_id);
				
		return $sorted_rows;    
    }
	public function page_nav() {
		global $wpdb;
		$where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE title LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
		$query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "gmwd_markercategories " . $where;
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
	
	public function parent_child_sort($rows, $parent_id, &$sorted_rows = array()) {	
        foreach ($rows as $key => $row) {
            if ($row->parent == $parent_id) {
                array_push($sorted_rows, $row);
                unset($rows[$key]);
                $this->parent_child_sort($rows, $row->id, $sorted_rows);
            }
        }
        return $sorted_rows;
    }
	
	public function get_root_parents( $rows ){
		$ids = array();
		foreach ($rows as $key => $row)
			$ids[] = $row->parent;			
		return $ids ? min($ids) : 0;		
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