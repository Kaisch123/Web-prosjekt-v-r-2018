<?php

class GMWDViewMarkercategories_gmwd extends GMWDView{

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
	public function display(){	
		$rows = $this->model->get_rows();
		$page_nav = $this->model->page_nav();
		$search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'id');
		$order_class = 'manage-column column-title sorted ' . $asc_or_desc;
		
		$per_page = $this->model->per_page();
		$pager = 0;
	?>	
		<div class="gmwd">
        
			<form method="post" action="" id="adminForm">
                <?php wp_nonce_field('nonce_gmwd', 'nonce_gmwd'); ?>            
				<!-- header -->
				<h2>
					<img src="<?php echo GMWD_URL . '/images/markercategories.png';?>" width="30" style="vertical-align: middle;">
					<span><?php _e("Marker Categories","gmwd");?></span>
					<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-addnew" onclick="gmwdFormSubmit('edit');return false;"><?php _e("Add new","gmwd");?></button>
				</h2>
				<!-- filters and actions -->
				<div class="wd_filters_actions wd-clear">
					<!-- filters-->
					<div class="wd-left">
						<?php echo GMWDHelper::search(__('Title',"gmwd"), $search_value, 'adminForm'); ?>
					</div>
					<!-- actions-->
					<div class="wd-right">
						<div class="wd-table gmwd_btns">
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-publish" onclick="gmwdFormInputSet('task', 'publish');gmwdFormInputSet('publish_unpublish', '1')"><?php _e("Publish","gmwd");?></button>
							</div>
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-unpublish" onclick="gmwdFormInputSet('task', 'publish');gmwdFormInputSet('publish_unpublish', '0')"><?php _e("Unpublish","gmwd");?></button>
							</div>	
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-dublicate" onclick="gmwdFormInputSet('task', 'dublicate');"><?php _e("Duplicate","gmwd");?></button>
							</div>	
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary-red wd-btn-icon wd-btn-delete" onclick="if (confirm('<?php _e("Do you want to delete selected items?","gmwd"); ?>')) { gmwdFormInputSet('task', 'remove');} else { return false;}"><?php _e("Delete","gmwd");?></button>
										  
							</div>							
						</div>
					</div>
				</div>
				<!-- pagination-->
				<div class="wd-right wd-clear">
					<?php GMWDHelper::html_page_nav($page_nav['total'], $pager++, $page_nav['limit'], 'adminForm', $per_page);?>
				</div>
				<!-- rows-->
				<table class="wp-list-table widefat fixed pages gmwd_list_table">
					<thead>
						<tr class="gmwd_alternate">
							<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
								<label class="screen-reader-text" for="cb-select-all-1"><?php _e("Select All","gmwd"); ?></label>
								<input id="cb-select-all-1" type="checkbox">
							</th>
							<th class="col <?php if ($order_by == 'id') {echo $order_class;} ?>" width="10%">
								<a onclick="gmwdFormInputSet('order_by', 'id');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span>ID</span><span class="sorting-indicator"></span>
								</a>
							</th>							
							<th class="col"  width="10%">
								  <span><?php _e("Image","gmwd"); ?></span><span class="sorting-indicator"></span>								
							</th>	
							<th class="col <?php if ($order_by == 'title') {echo $order_class;} ?>">
								<a onclick="gmwdFormInputSet('order_by', 'title');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'title') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Title","gmwd"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>								
							<th class="col <?php if ($order_by == 'parent_title') {echo $order_class;} ?>">
								<a onclick="gmwdFormInputSet('order_by', 'parent_title');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'parent_title') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Parent Category","gmwd"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>	
							<th class="col <?php if ($order_by == 'published') {echo $order_class;} ?>" width="10%">
								<a onclick="gmwdFormInputSet('order_by', 'published');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'published') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Published","gmwd"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>								
						</tr>					
					</thead>
					<tbody>
					<?php
						if(empty($rows) === false){
							$iterator = 0;
							foreach($rows as $row){
								$alternate = $iterator%2 != 0 ? "class='gmwd_alternate'" : "";
								$published_image = (($row->published) ? 'publish-blue' : 'unpublish-blue');
								$published = (($row->published) ? 0 : 1);
								
						?>
								<tr id="tr_<?php echo $iterator; ?>" <?php echo $alternate; ?>>
									<th scope="row" class="check-column">
										<input type="checkbox" name="ids[]" value="<?php echo $row->id; ?>">
									</th>
									<td class="id column-id">
										<?php echo $row->id;?>
									</td>
                                    <td class="category_picture column-id">
										<?php echo $row->category_picture ? '<img src="'.$row->category_picture.'" height="25">' : "";?>
									</td>
                                    
									<td class="title column-title">
										<a href="admin.php?page=markercategories_gmwd&task=edit&id=<?php echo $row->id;?>">
											<?php echo str_repeat('<span class="gi">|&mdash;</span>', $row->level ) ?>
											<?php echo $row->title;?>
										</a>
									</td>
									<td class="price column-width">
										<?php echo $row->parent_title;?>
									</td>										
								
									<td class="table_big_col">
										<a onclick="gmwdFormInputSet('task', 'publish');gmwdFormInputSet('publish_unpublish', '<?php echo $published ; ?>');gmwdFormInputSet('current_id', '<?php echo $row->id; ?>');document.getElementById('adminForm').submit();return false;" href="">
											<img src="<?php echo GMWD_URL . '/images/css/' . $published_image . '.png'; ?>"></img>
										</a>
									</td>
									
								</tr>
						<?php
								$iterator++;
								}
							}	
						?>
					</tbody>
				</table>
				
				<input id="page" name="page" type="hidden" value="<?php echo GMWDHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
				<input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />

				<input id="current_id" name="current_id" type="hidden" value="" />

				<input id="publish_unpublish" name="publish_unpublish" type="hidden" value="" />
				
			</form>
		</div>	

	<?php
	 
	}
	
	public function edit($id){

		$row = $this->model->get_row($id);
		$query_url =  admin_url('admin-ajax.php');

		$query_url_select_parent = add_query_arg(array('action' => 'select_parent_category', 'page' => 'markercategories_gmwd', 'task' => 'explore', 'width' => '800', 'height' => '600', 'callback' => 'selectParentCategory', 'current_id' => $id, 'nonce_gmwd' => wp_create_nonce('nonce_gmwd'), 'TB_iframe' => '1' ), $query_url);

		?>
        
		<div class="gmwd_edit">	
             <h2>
                <img src="<?php echo GMWD_URL . '/images/markercategories.png';?>" width="30" style="vertical-align:middle;">
                <span>
                    <?php 
                        if($id == 0) {
                            _e("Add Marker Category","gmwd");
                        }	
                        else{
                            _e("Edit Marker Category","gmwd");
                        }	
                    ?>
                </span>

            </h2>	       
			<form method="post" action="" id="adminForm">
                <?php wp_nonce_field('nonce_gmwd', 'nonce_gmwd'); ?>
				<div class="wd-clear wd-row">
					<div class="wd-right">
						<div class="wd-table gmwd_btns">
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save" onclick="gmwdFormSubmit('save');"><?php _e("Save","gmwd");?></button>
							</div>
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" onclick="gmwdFormSubmit('apply');"><?php _e("Apply","gmwd");?></button>
							</div>							
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save2copy" onclick="gmwdFormSubmit('save2copy');"><?php _e("Save as Copy","gmwd");?></button>
							</div>											  
							<div class="wd-cell wd-cell-valign-middle">
								<button class="wd-btn wd-btn-primary wd-btn-icon wd-btn-cancel" onclick="gmwdFormSubmit('cancel');"><?php _e("Cancel","gmwd");?></button>
							</div>															
						</div>
					</div>
				</div>
             <div class="gmwd">       
                <table class="gmwd_edit_table" style="width:100%;">
                    <tr>
                        <td style="width:15%;"><label for="title" title="<?php _e("Set marker category title.","gmwd");?>"><?php _e("Title","gmwd");?>:</label></td>
                        <td>
                            <input type="text" name="title" id="title" value="<?php echo $row->title;?>">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="parent_title" title="<?php _e("Select parent category.","gmwd");?>"><?php _e("Select Parent Category","gmwd");?>:</label></td>
                        <td>
                            <input type="text" name="parent_title" id="parent_title" readonly value="<?php echo $row->parent_title ? $row->parent_title : "Root";?>">
                            <input type="hidden" name="parent" id="parent" value="<?php echo $row->parent;?>">
                            <a href="<?php echo $query_url_select_parent;?>" class="wd-btn wd-btn-primary thickbox thickbox-preview" onclick=""><?php _e("Select Parent","gmwd");?></a>
                        </td>
                    </tr>
                    <tr>					
                        <td><label for="category_picture" title="<?php _e("Upload category icon.","gmwd");?>"><?php _e("Category Icon","gmwd");?>:</label></td>
                        <td>
                            <button class="wd-btn wd-btn-primary" onclick="gmwdOpenMediaUploader(event,'category_picture');return false;"><?php _e("Upload Image","gmwd"); ?></button>
                            <input type="hidden" name="category_picture" id="category_picture" value="<?php echo $row->category_picture; ?>" class="wd-form-field">
                            <div class="category_picture_view upload_view">
                               <?php if($row->category_picture){
                                    echo '<img src="'.$row->category_picture.'" height="18">';
                                    echo '<span class="category_picture_delete" onclick="jQuery(\'#category_picture\').val(\'\');jQuery(\'.category_picture_view\').html(\'\');">x</span>';
                                }
                                ?>               
                            </div>  
                       </td>							
                    </tr>                    
                    <tr>
                    
                        <td><label title="<?php _e("Publish marker category.","gmwd");?>"><?php _e("Published:","gmwd"); ?></label></td>
                        <td>
                          <input type="radio" class="inputbox" id="published1" name="published" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
                          <label for="published1"><?php _e("Yes","gmwd"); ?></label>
                          <input type="radio" class="inputbox" id="published0" name="published" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" >
                          <label for="published0"><?php _e("No","gmwd"); ?></label>

                        </td>
                    </tr>				
                </table>
            </div>            
				<input id="page" name="page" type="hidden" value="<?php echo GMWDHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="id" name="id" type="hidden" value="<?php echo $row->id;?>" />	
			</form>
           
		</div>

		<?php
		
	}
	
	public  function explore() {
		wp_print_scripts('jquery');   
        wp_print_styles('admin-bar');
        wp_print_styles('wp-admin');
        wp_print_styles('dashicons');
        wp_print_styles('buttons');
        wp_print_styles('wp-auth-check');          
		$page =  esc_html(stripslashes($_GET["page"]));
		$rows = $this->model->get_rows();
		$page_nav = $this->model->page_nav();
		$search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'id');
		$order_class = 'manage-column column-title sorted ' . $asc_or_desc;
		
		$per_page = $this->model->per_page();
		$pager = 0;
	?>	

        <?php         
        if (get_bloginfo('version') < '3.9') { ?>
		<link media="all" type="text/css" href="<?php echo get_admin_url(); ?>css/colors<?php echo ((get_bloginfo('version') < '3.8') ? '-fresh' : ''); ?>.min.css" id="colors-css" rel="stylesheet">
		<?php } ?>
		<link media="all" type="text/css" href="<?php echo GMWD_URL . '/css/admin_main.css'; ?>" rel="stylesheet">
		<script src="<?php echo GMWD_URL . '/js/admin_main.js'; ?>" type="text/javascript"></script>	
		<script src="<?php echo GMWD_URL . '/js/'.$page.'.js'; ?>" type="text/javascript"></script>

		<div class="gmwd">				
			<form method="post" action="" id="adminForm">	
				<!-- header -->
				<h2>					
					<span><?php _e("Select Marker Category","gmwd");?></span>			
				</h2>
				<!-- filters and actions -->
				<div class="wd_filters_actions wd-clear">
					<!-- filters-->
					<div class="wd-left">
						<?php echo GMWDHelper::search(__('Title',"gmwd"), $search_value, 'adminForm'); ?>
					</div>
				</div>
				<!-- pagination-->
				<div class="wd-right wd-clear">
					<?php GMWDHelper::html_page_nav($page_nav['total'], $pager++, $page_nav['limit'], 'adminForm', $per_page);?>
				</div>
				<!-- rows-->
				<table class="wp-list-table widefat fixed pages gmwd_list_table">
					<thead>
						<tr class="gmwd_alternate">
							<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
								<label class="screen-reader-text" for="cb-select-all-1"><?php _e("Select All","gmwd"); ?></label>
								<input id="cb-select-all-1" type="checkbox">
							</th>
							<th class="col <?php if ($order_by == 'id') {echo $order_class;} ?>" width="10%">
								<a onclick="gmwdFormInputSet('order_by', 'id');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span>ID</span><span class="sorting-indicator"></span>
								</a>
							</th>							

							<th class="col <?php if ($order_by == 'title') {echo $order_class;} ?>">
								<a onclick="gmwdFormInputSet('order_by', 'title');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'title') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Title","gmwd"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>								
							<th class="col <?php if ($order_by == 'parent_title') {echo $order_class;} ?>">
								<a onclick="gmwdFormInputSet('order_by', 'parent_title');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'parent_title') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Parent Category","gmwd"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>	
							<th class="col <?php if ($order_by == 'published') {echo $order_class;} ?>" width="15%">
								<a onclick="gmwdFormInputSet('order_by', 'published');
											gmwdFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'published') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Published","gmwd"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>								
						</tr>					
					</thead>
					<tbody>
						<tr id="tr_1" class='' data-id="0" data-title="Root">
							<th scope="row" class="check-column">
								<input type="checkbox" name="ids[]" value="0">
							</th>
							<td class="id column-id">								
							</td>
							<td class="title column-title">
								<a href="#" onclick="selectCategory(this)">
									Root
								</a>
							</td>
							<td class="price column-width">								
							</td>														
							<td class="table_big_col">															
							</td>
							
						</tr>					
					<?php 

						if(empty($rows ) == false){
							$iterator = 1;
							foreach($rows as $row){
								if($row->id == GMWDHelper::get("current_id")){
									continue;
								}
                                $alternate = $iterator%2 != 0 ? "class='gmwd_alternate'" : "";
								$published_image = (($row->published) ? 'publish-blue' : 'unpublish-blue');
								$published = (($row->published) ? 0 : 1);
						?>
								<tr id="tr_<?php echo $iterator; ?>" <?php echo $alternate; ?> data-id="<?php echo $row->id;?>" data-title="<?php echo $row->title;?>">
									<th scope="row" class="check-column">
										<input type="checkbox" name="ids[]" value="<?php echo $row->id; ?>">
									</th>
									<td class="id column-id">
										<?php echo $row->id;?>
									</td>
									<td class="title column-title">
										<a href="#" onclick="selectCategory(this)">
											<?php echo str_repeat('<span class="gi">|&mdash;</span>',  $row->level ) ?>
											<?php echo $row->title;?>
										</a>
									</td>
									<td class="price column-width">
										<?php echo $row->parent_title;?>
									</td>										
								
									<td class="table_big_col">					
                                        <img src="<?php echo GMWD_URL . '/images/css/' . $published_image . '.png'; ?>"></img>										
									</td>
									
								</tr>
						<?php
								$iterator++;
								}
							}	
						?>
					</tbody>
				</table>
				
				<input id="page" name="page" type="hidden" value="<?php echo GMWDHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="explore" />	
				<input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
				<input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />

				<input id="current_id" name="current_id" type="hidden" value="" />
				<input id="publish_unpublish" name="publish_unpublish" type="hidden" value="" />
				
			</form>
		</div>	


	<?php
	die();
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