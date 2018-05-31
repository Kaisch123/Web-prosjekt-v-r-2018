<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://selmamariudottir.com
 * @since      1.0.0
 *
 * @package    Wp_Date_Remover
 * @subpackage Wp_Date_Remover/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

	<?php
	/*======== WP DATE REMOVER OPTION FIELD START =======*/
	$wp_date_option = get_option('wp-date-remover');
			
	if(is_array($wp_date_option) && !empty($wp_date_option))
	{
		foreach($wp_date_option as $key => $value)
		{
			$option_term_id = explode('_',$key);
			
			if($value=='1')
			{	
				if(is_array($option_term_id) && !empty($option_term_id))
				{
					update_term_meta($option_term_id[1], 'wp-date-remover', true);
					delete_option('wp-date-remover'); 
				}
			}
		}
	}
	/*========  \WP DATE REMOVER OPTION FIELD END =======*/
		
	/*======= WP DATE REMOVER SUBMIT CODE START ======= */
	global $wpdb;		
	if(isset($_POST['wp_date_btn_submit']))
	{
		$wdr_args = array(
		  'orderby' => 'name',
		  'order' => 'ASC'
		  );
		$wdr_categories = get_categories($wdr_args);
		if(is_array($wdr_categories) && !empty($wdr_categories))
		{
			foreach($wdr_categories as $wdr_cat)
			{
				$t_id=$wdr_cat->term_id;
				
				$t_val=$_POST['wp-date-remover-removedt'][$t_id];
				if($t_val==1)
				{
					update_term_meta($t_id, 'wp-date-remover', 1);
				}
				else
				{
					update_term_meta($t_id, 'wp-date-remover', 0);
				}
			}
		}
		echo '<div class="updated"><p>Settings saved.</p></div>';	
		
	}
	/*======= \WP DATE REMOVER SUBMIT CODE END ======= */
	?>
    
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <?php
	$wdr_args = array(
		'orderby' => 'name',
		'order' => 'ASC'
	);
    $wdr_categories = get_categories($wdr_args);
	if(is_array($wdr_categories) && !empty($wdr_categories))
	{
		$wdr_i=0;
	?>
    <form method="post" name="wdr_settings">
    <table class="form-table">
    <tbody>
        <!-- remove date and time from specific categories -->
        <?php
        	foreach($wdr_categories as $wdr_cat)
			{
				//Remove This OLD CODE WP DATE REMOVER
				//$wdr_category_option=$options['removedt_'.$wdr_cat->term_id];
				$wdr_category_option = get_term_meta($wdr_cat->term_id, 'wp-date-remover', true);
				$wdr_i++;
				if($wdr_i==1 || $wdr_i%3==1)
				{
		?>
		
					<tr>
        <?php
				}
						
		?>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e('Remove Date/Time', $this->plugin_name);?></span></legend>
								<label for="<?php echo $this->plugin_name; ?>-removedt_<?php echo $wdr_cat->term_id; ?>">
									<input type="checkbox" value="1" <?php if($wdr_category_option==1){echo "checked";}?> id="<?php echo $this->plugin_name; ?>-removedt_<?php echo $wdr_cat->term_id; ?>" name="<?php echo $this->plugin_name; ?>-removedt[<?php echo $wdr_cat->term_id; ?>]">
									 <span><?php esc_attr_e($wdr_cat->name, $this->plugin_name); ?></span>
								</label>
							</fieldset>
					   </td>

       	<?php
				if($wdr_i%3==0 || $wdr_i==count($wdr_categories))
				 {
		?>
        			</tr>
        <?php
				 }
			}
		?>
  	</tbody>
	</table>
       <?php //Remove This OLD BUTTON WP DATE REMOVER 
	   	 //submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>
      	<input type="submit" name="wp_date_btn_submit" id="submit" class="button button-primary" value="Save all changes"  />
    </form>
	<?php
		}
	?>
</div>