<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://selmamariudottir.com
 * @since      1.0.0
 *
 * @package    Wp_Date_Remover
 * @subpackage Wp_Date_Remover/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Date_Remover
 * @subpackage Wp_Date_Remover/public
 * @author     Selma Mariudottir <selma@selmamariudottir.com>
 */
class Wp_Date_Remover_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->wp_date_remover_options = get_option($this->plugin_name);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Date_Remover_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Date_Remover_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-date-remover-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Date_Remover_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Date_Remover_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-date-remover-public.js', array( 'jquery' ), $this->version, false );

	}
	
	public function wp_date_remover_remove() {
		global $wdr_cat_arry,$wdr_post_arry;
		$wdr_cat_arry=array();
		$wdr_post_arry=array();
		$wdr_args = array(
		  'orderby' => 'name',
		  'order' => 'ASC'
		  );
    	$wdr_categories = get_categories($wdr_args);
		if(is_array($wdr_categories) && !empty($wdr_categories))
		{
			foreach($wdr_categories as $wdr_cat)
			{
				//Remove This OLD CODE WP DATE REMOVER
				//if($this->wp_date_remover_options['removedt_'.$wdr_cat->term_id])
				if(get_term_meta($wdr_cat->term_id, 'wp-date-remover', true) == 1)
				{	
					 if(!in_array($wdr_cat->term_id,$wdr_cat_arry))
					{
						array_push($wdr_cat_arry,$wdr_cat->term_id);
					}
				 }
			}
		}
       
    }
	// remove date/time using php.
	public function wp_date_remover_remove_date_php() {
		global $wdr_cat_arry,$wdr_post_arry;
		remove_filter('the_date', '__return_false');
		remove_filter('the_time', '__return_false');
		remove_filter('the_modified_date', '__return_false');
		remove_filter('get_the_date', '__return_false');
		remove_filter('get_the_time', '__return_false');
		remove_filter('get_the_modified_date', '__return_false');
		
		if(is_array($wdr_cat_arry) && !empty($wdr_cat_arry))
		{
			foreach($wdr_cat_arry as $term_id)
			{
				if(in_category($term_id,get_the_id()))
				{
					if(!in_array(get_the_id(),$wdr_post_arry))
					{
						array_push($wdr_post_arry,get_the_id());
					}
					add_filter('the_date', '__return_false');
					add_filter('the_time', '__return_false');
					add_filter('the_modified_date', '__return_false');
					add_filter('get_the_date', '__return_false');
					add_filter('get_the_time', '__return_false');
					add_filter('get_the_modified_date', '__return_false');
					break;
				}
			}
		}
		
	} 
	public function wp_date_remover_custom_script()
	{
	?>
		<script type="text/javascript">
		<?php
			global $wdr_post_arry;
			if(is_array($wdr_post_arry) && !empty($wdr_post_arry))
			{
				foreach($wdr_post_arry as $wdr_post_id)
				{
		?>
					jQuery("#post-<?php echo $wdr_post_id?> .entry-meta .date").css("display","none");
					jQuery("#post-<?php echo $wdr_post_id?> .entry-date").css("display","none");
					jQuery("#post-<?php echo $wdr_post_id?> .posted-on").css("display","none");
		<?php
				}
				
			}
		?>
		</script>
	<?php
	}
	

}
