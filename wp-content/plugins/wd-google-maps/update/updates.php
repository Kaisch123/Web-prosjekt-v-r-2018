<?php
if (!defined('ABSPATH')) {
	exit;
}

class GMWD_Update{
	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
    protected $remote_data_path = 'https://api.web-dorado.com/v1/_id_/allversions';
    protected $updates = array();
    protected $main_plugin_id = 147;
    protected $prefix = 'gmwd';
    protected $plugin_name = 'wd-google-maps'; 
    protected $plugin_dir = GMWD_DIR; 
    protected $plugin_url = GMWD_URL; 
    protected $gmwd_plugins = array();          
    protected $userhash;    
        

	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->userhash = $this->get_userhash();
    }
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////


    public function check_for_update() {
        @ob_start();
        //$this->plugin_updated();
        global $menu;
        $update_bubble = '';
        $gmwd_plugins = array();
        $request_ids = array();
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();

        foreach ($all_plugins as $name => $plugin) {
     
            if (strpos($name, $this->prefix) !== false || strpos($name, $this->plugin_name) !== false) {
                $data = $this->get_plugin_data($name);                  
                if($data){
                    if ($data['id'] > 0) {
                        $request_ids[] = $data['id'] . ':' . $plugin['Version'];
                        $gmwd_plugins[$data['id']] = $plugin;
                        $gmwd_plugins[$data['id']]['gmwd_data'] = $data;
                    }
                }
            }
        }

        $this->gmwd_plugins = $gmwd_plugins;

        if (false === $remote_data = get_transient($this->prefix.'_remote_data')) {
            $updates_available = array();
            $agreements = array();
            if (count($request_ids) > 0) {
                $remote_version = $this->get_remote_data(implode('_', $request_ids));
        
                if (isset($remote_version["body"])) {
                    foreach ($remote_version["body"] as $id => $updated_plugins) {
                        if (count($updated_plugins) == 0) {
                            continue;
                        }
                        $updates = array();

                        foreach ($updated_plugins as $updated_plugin) {
                            if (version_compare($gmwd_plugins[$id]['Version'], $updated_plugin['version'], '<')) {               
                                if (strpos(strtolower($updated_plugin['note']), 'important') !== false) {
                                    $updates = $updated_plugins;
                                    break;
                                }
                            }
                        }
                       
                        if (!empty($updates)) {
                            $updates_available [$id] = $updates;
                        }
                    }
                }
            }
            if(isset($remote_version["body"]["agreements"])){
                $agreements = $remote_version["body"]["agreements"];
            }
         
            $remote_data = array("updates_available" => $updates_available, "agreements" => $agreements);
           
            set_transient($this->prefix.'_remote_data', $remote_data, 12 * 60 * 60);
        }
        $updates_available = $remote_data["updates_available"];
         
        $this->updates = $updates_available;
       
        $updates_count = is_array($updates_available) ? count($updates_available) : 0;

        $update_page = add_submenu_page('maps_gmwd', __('Updates', 'gmwd'), __('Updates', $this->prefix) . ' ' . '<span class="update-plugins count-' . $updates_count . '" title="title">
                                                    <span class="update-count">' . $updates_count . '</span></span>',
         'manage_options', $this->prefix . '_updates', array( $this,'display_updates_page'));
        add_action('admin_print_styles-' . $update_page, array($this, 'update_styles'));
        if ($updates_count > 0) {
            foreach ($menu as $key => $value) {

                if ($menu[$key][2] == 'maps_gmwd' ) {
                    $menu[$key][0] .= ' ' . '<span class="update-plugins count-' . $updates_count . '" title="title">
                                                    <span class="update-count">' . $updates_count . '</span></span>';


                    return;
                }
            }
        }
    } 
   
    public function plugin_updated() {
        delete_transient($this->prefix.'_remote_data');
    }  
 
    public function get_remote_data($id) {
        $this->remote_data_path .= '/' . $this->userhash;
        $request = wp_remote_get(( str_replace('_id_', $id, $this->remote_data_path)));  
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return json_decode($request['body'], true);
        }
        return false;
    } 

    public function display_updates_page(){
        $gmwd_plugins = $this->gmwd_plugins;
		$updates = $this->updates;
		$id = $this->main_plugin_id;
        $main_notes = array();
        foreach ($updates as $plugin_id => $plugin_update){
            $main_notes[$plugin_id] = $plugin_update[0]['note'];
            foreach($plugin_update as $update){
                if(strpos(strtolower($update['note']), 'important') !== false ){            
                    $main_notes[$plugin_id] = $update['note'];
                    break;
                }
            }
        }

        $update_url = add_query_arg( array('action' => $this->prefix.'-upgrade-plugin', 'page' => $this->prefix, 'nonce_'.$this->prefix => wp_create_nonce('nonce_'.$this->prefix)),admin_url('admin-ajax.php'));
    
            
    ?>
        <div class="wrap">
            <?php settings_errors(); ?>
            <div id="settings">
                <div id="settings-content">
                    <h2 id="add_on_title"><?php echo esc_html(get_admin_page_title()); ?></h2>
                    <?php if($this->userhash == "nohash"){ ?>
                        <div class="main-plugin_desc-cont">
                           <div> - You can download the latest version of Google Maps WD from your <a href="https://web-dorado.com/subscriptions.html" target="_blank">Web-Dorado.com </a> account,</div>
                           <div> - Deactivate and delete the current version, </div>
                           <div> - Install the downloaded latest version of the plugin.</div>
                            <!--You can download the latest version of your plugins from your <a href="https://web-dorado.com" target="_blank"> Web-Dorado.com</a> account.   After deactivating and deleting the current version, install the downloaded version of the plugin.	-->			
                        </div>

                        <br/>
                        <br/>

                    <?php
                    }
                    if ($gmwd_plugins) {
                        $update = 0;
                        if (isset($gmwd_plugins[$id])) {

                            $project = $gmwd_plugins[$id];
                            unset($gmwd_plugins[$id]);
                            if (isset($updates[$id])) {
                                $update = 1;
                            }
                            ?>
                            <div class="main-plugin">
                                <div class="add-on">
                                    <?php if ($project['gmwd_data']['image']) { ?>
                                        <div class="figure-img">
                                            <a href="<?php echo $project['gmwd_data']['url'] ?>" target="_blank">
                                                <img src="<?php echo $project['gmwd_data']['image'] ?>"/>
                                            </a>
                                        </div>
                                    <?php } ?>

                                </div>
                                <div class="main-plugin-info">
                                    <h2>
                                        <a href="<?php echo $project['gmwd_data']['url'] ?>"
                                           target="_blank"><?php echo $project['Title'] ?></a>
                                    </h2>

                                    <div class="main-plugin_desc-cont">
                                        <div class="main-plugin-desc"><?php echo $project['gmwd_data']['description'] ?></div>
                                        <div class="main-plugin-desc main-plugin-desc-info">
                                            <p><a href="<?php echo $project['gmwd_data']['url'] ?>"
                                                  target="_blank">Version <?php echo $project['Version'] ?></a></p>
                                        </div>
        

                                        <?php if (isset($updates[$id][0])) { ?>
                                            <span class="update-info">There is an new  <?php echo $updates[$id][0]['version'] ?>
                                                version</span>
 
                                            <p><span>What's new:</span></p>
                                            <div class="last_update"><b><?php echo $updates[$id][0]['version'] ?></b>
                                                <?php echo strip_tags(str_ireplace('Important', '', $main_notes[$id]), '<p>') ?></div>
          
                                            <?php if (count($updates[$id]) > 0) { ?>

                                                <div class="more_updates">
                                                    <?php foreach ($updates[$id] as $update) {
                                                        ?>
                                                        <div class="update"><b><?php echo $update['version'] ?></b>
                                                            <?php echo strip_tags(str_ireplace('Important', '', $update['note']), '<p>') ?></div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <a href="#" class="show_more_updates" onclick="jQuery('.more_updates').toggle();jQuery('.last_update').toggle(); if(jQuery(this).html() == 'More updates') jQuery(this).html('Less updates'); else jQuery(this).html('More updates'); return false;">More updates</a>
                                                <?php
                                            }
                                            ?>


                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        <?php }
                        ?>
                        <div class="addons_updates">
                            <?php
                            foreach ($gmwd_plugins as $id => $project) {
                                $last_index = 0;
                                if (isset($updates[$id]) && !empty($updates[$id])) {                            
                                    //$last_index = count($updates[$id]) - 1;
                                }
                             
                                ?>
                                <div class="add-on">
                                    <figure class="figure">
                                        <div class="figure-img">
                                            <a href="<?php echo $project['gmwd_data']['url'] ?>" target="_blank">
                                                <?php if ($project['gmwd_data']['image']) { ?>
                                                    <img src="<?php echo $project['gmwd_data']['image'] ?>"/>
                                                <?php } ?>
                                            </a>
                                        </div>
                                        <figcaption class="addon-descr figcaption">
                                            <?php if (isset($updates[$id][0])) { ?>
                                                <p>What's new:</p>
                                                <?php echo strip_tags(str_ireplace('Important', '',$main_notes[$id]), '<p>') ?>
                                            <?php } else { ?><?php echo $project['Title'] ?> is up to date
                                            <?php } ?>
                                        </figcaption>
                                    </figure>
                                    <h2><?php echo $project['Title'] ?></h2>

                                    <div class="main-plugin-desc-info">
                                        <p><a href="<?php echo $project['gmwd_data']['url'] ?>"
                                              target="_blank"><?php echo $project['Version'] ?></a> | Web-Dorado</p>
                                    </div>
                                    <?php if (isset($updates[$id]) && isset($updates[$id][0]['version'])) { ?>
                                        <div class="addon-descr-update">
                                            <span
                                                class="update-info">There is an new  <?php echo $updates[$id][0]['version'] ?>
                                                version</span><br/>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php }
                            ?>
                        </div>
                        <?php
                    }
                    ?>

                </div>
                <!-- #gmwd-settings-content -->
            </div>
            <!-- #gmwd-settings -->
        </div><!-- .wrap -->

    <?php    
    }
    public function update_styles() {
        $version = get_option("wd_" . $this->prefix . "_version");
        wp_enqueue_style($this->prefix . '_updates', $this->plugin_url . '/update/style.css', array(), $version);
       // wp_enqueue_style($this->prefix . 'admin_main', $this->plugin_url . '/css/admin_main.css', array(), $version);
    }
    ////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
  
    private function get_plugin_data($name) {
		$plugins = array(
			$this->plugin_name . '/' . $this->plugin_name . '.php' => array(
				'id'          => $this->main_plugin_id,
				'url'         => 'https://web-dorado.com/subscriptions.html',
				'description' => __('Plugin for creating Google maps with advanced markers, custom layers and overlays for   your website.', $this->prefix),
				'icon'        => '',
				'image'       => $this->plugin_url . '/update/images/main_plugin.png'
			), 
          'wd-google-maps-marker-clustering/wd-google-maps-marker-clustering.php' => array(
                'id'          => 160,
                'url'         => 'https://web-dorado.com/products/wordpress-google-maps-plugin/add-ons/marker-clustering.html',
                'description' => __('GMWD Marker Clustering is designed for grouping close markers for more user-friendly display over the map.', $this->prefix),
                'icon'        => '',
                'image'       => $this->plugin_url . '/addons/images/marker_clusters.png'
          ),            
		);
        
		return isset($plugins[$name]) ? $plugins[$name] : '';
	}
    private function get_userhash(){
        $userhash = 'nohash';
        if (file_exists($this->plugin_dir . '/.keep') && is_readable($this->plugin_dir . '/.keep')) {
            $f = fopen($this->plugin_dir . '/.keep', 'r');
            $userhash = fgets($f);
            fclose($f);
        }    
        return $userhash;
    }
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
    
}  