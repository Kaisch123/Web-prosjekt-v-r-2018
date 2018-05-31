<?php
function gmwd_update(){
    global $wpdb;
    $fusion_table_where_filed_name = $wpdb->get_row("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'fusion_table_where_filed'");
    if(!$fusion_table_where_filed_name){
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `fusion_table_where_filed`  VARCHAR(256) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `fusion_table_where_operator`  VARCHAR(16) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `fusion_table_where_value`  VARCHAR(256) NOT NULL ");
    }

    $api_key = $wpdb->get_var('SELECT id FROM ' . $wpdb->prefix . 'gmwd_options WHERE name="map_api_key"');
    if(!$api_key){
        $wpdb->query("INSERT INTO  `" . $wpdb->prefix . "gmwd_options` (`id`,  `name`, `value`, `default_value`) VALUES ('', 'map_api_key', '', '' ) ");
    }


    $infowindow_type = $wpdb->get_row("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'infowindow_type'");
    if(!$infowindow_type){
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `infowindow_type`  TINYINT(1) NOT NULL ");
    }

    $header_titles = $wpdb->get_row("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'directions_header_title'");
    if(!$header_titles){
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `directions_header_title`  VARCHAR(256) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `listing_header_title`  VARCHAR(256) NOT NULL ");

    }
    $header_titles_store_loactor = $wpdb->get_row("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'store_locator_header_title'");
    if(!$header_titles_store_loactor){
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `store_locator_header_title`  VARCHAR(256) NOT NULL ");
    }

    $enable_searchbox = $wpdb->get_row("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'enable_searchbox'");
    if(!$enable_searchbox){
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `enable_searchbox`   TINYINT(1) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `searchbox_position`  INT(16) NOT NULL ");
    }


 
    $category_filter_fields = $wpdb->get_var("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'imcategory_filter_type'");
    if(!$category_filter_fields){
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `category_filter_type`  TINYINT(1) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `marker_categories_inside_map` TINYINT(1) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `imcategory_filter_type` TINYINT(1) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `category_filter_im_position` INT(16) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `advanced_table_columns` VARCHAR(32) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `show_cat_icon` TINYINT(1) NOT NULL ");
        
        $wpdb->query("UPDATE  `" . $wpdb->prefix . "gmwd_maps` SET advanced_table_columns='icon,title,category,address,desc'");   
    
    }
    $info_window_info = $wpdb->get_var("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'info_window_info'");
    if(!$info_window_info){
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `info_window_info`  VARCHAR(32) NOT NULL ");
        $wpdb->query("UPDATE  `" . $wpdb->prefix . "gmwd_maps` SET info_window_info='title,address,desc,pic'");   
    
    } 

	$enable_store_locator_cats = $wpdb->get_var("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'enable_store_locator_cats'");
    if(!$enable_store_locator_cats){
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `enable_store_locator_cats`  TINYINT(1) NOT NULL ");
    
    }
	$wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_markers CHANGE `category` `category` VARCHAR(256)");

    $marker_listing_order = $wpdb->get_var("SHOW COLUMNS FROM ".$wpdb->prefix . "gmwd_maps LIKE 'marker_listing_order'");
    if(!$marker_listing_order){
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `marker_listing_order`  VARCHAR(25) NOT NULL ");
        $wpdb->query("ALTER TABLE ".$wpdb->prefix . "gmwd_maps ADD  `marker_listing_order_dir`  VARCHAR(25) NOT NULL ");

    }


}


?>
