<?php
/**
 * This is a Common utility Methods class.
 * All those Methods are used in many classes
 * @link       javmah.com
 * @since      1.0.0
 * @package    wptec
 * @subpackage wptec/admin
 * @author     javmah <jaedmah@gmail.com>
 */
class Wptec_Common {
	/**
	 * The ID of this plugin.
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	*/
	private $version;

	/**
	 * list of User table wptec extra default and extra  fields 
	 * @since    1.0.0
	 * @access   public 
	 * @array    extra fields key name array 
	*/
	public $user_tab_default_columns = array(
										'username'					=> 'Username',
										'name'						=> 'Name', 		// Editable
										'email'						=> 'Email', 	// Editable
										'role'						=> 'Role', 		// Editable
										'posts'						=> 'Posts', 	// Editable
								);

	public $user_tab_extra_columns = array(
										'wptec_user_id'				=> 'User ID',
										'wptec_user_firstName'		=> 'First name', 	// Editable
										'wptec_user_lastName'		=> 'Last name',  	// Editable
										'wptec_user_nickName'		=> 'Nick name',  	// Editable
										'wptec_user_email'			=> 'Email',      	// Editable
										'wptec_user_url'			=> 'Website',    	// Editable
										'wptec_user_postCount'		=> 'Post Count', 	
										'wptec_user_commentCount'	=> 'Comment Count',
										'wptec_user_description'	=> 'Description'	//Editable
								);

								

	/**
	 * list of post table default and extra columns.
	 * @since    1.0.0
	 * @access   public
	 * @array    extra fields key name array 
	*/
	public $post_tab_default_columns = array(
									'title'							=> 'Title',
									'author'						=> 'Author',
									'categories'					=> 'Categories',
									'tags'							=> 'Tags',
									'comments'						=> 'Comments',
									'date'							=> 'Date',
								);

	public $post_tab_extra_columns = array(
									'wptec_post_id'					=> 'Post ID',
									'wptec_author_id'				=> 'Author ID',
									'wptec_post_er_time'			=> 'Estimated Reading Time',
									'wptec_post_excerpt'			=> 'Excerpt',                 	// This is Editable 
									'wptec_post_attachment'			=> 'Attachment',
									'wptec_post_attachment_count'	=> 'Attachment Count',
									'wptec_post_author_name'		=> 'Author name',
									'wptec_post_comments'			=> 'Comments',
									'wptec_post_comment_count'		=> 'Comment Count',
									'wptec_post_comment_status'		=> 'Comment Status',          	// This is Editable 
									'wptec_post_parent'				=> 'Parent',				    // This is Editable 
									'wptec_post_word_count'			=> 'Word Count'
								);

								
	/**
	 * list of  page  table default and extra  columns.
	 * @since    1.0.0
	 * @access   public
	 * @array    extra fields key name array 
	*/
	public $pages_tab_default_columns = array(
										'title' 				  	=> 'Title',
										'author' 					=> 'Author',
										'comments' 					=> 'Comments',
										'date' 						=> 'Date',
									);
	public $pages_tab_extra_columns = array(
										'wptec_page_id' 			=> 'Page ID',
										'wptec_page_ping_status' 	=> 'Ping status',
										'wptec_page_comment_status' => 'Comment status', // User editable
										'wptec_page_comment_count' 	=> 'Comment count',
										'wptec_page_menu_order' 	=> 'Menu order'		 // Change page menu Order 
									);

	/**
	 * list of comment table default and extra fields.
	 * @since    1.0.0
	 * @access   public
	 * @array    extra fields key name array 
	*/
	public $comments_tab_default_columns = array(
									'author' 					=> 'Author',
									'comment' 					=> 'Comment',
									'response' 					=> 'In response to',
									'date' 						=> 'Date',
								);
	public $comments_tab_extra_columns = array(
									'wptec_comment_id' 			=> 'Comment ID',
									'wptec_comment_agent' 		=> 'Agent', 			// editable    
									'wptec_comment_approved'	=> 'Approved',			// editable
									'wptec_comment_author' 		=> 'Author Name',    
									'wptec_comment_email' 		=> 'Author Email',		// editable
									'wptec_comment_ip' 			=> 'Author IP',			// editable
									'wptec_comment_url' 		=> 'Author URL',
									'wptec_comment_date' 		=> 'Date',
									'wptec_comment_excerpt' 	=> 'Excerpt',  
									'wptec_comment_post' 		=> 'Post ID',
									'wptec_comment_word_count' 	=> 'Word Count'
								);

	/**
	 * List of media table default and extra columns
	 * @since    1.0.0
	 * @access   public
	 * @array    extra fields key name array 
	*/
	public $media_tab_default_columns = array(
									'title'							=> 'File',
									'author'						=> 'Author',
									'parent'						=> 'Uploaded to',
									'comments'						=> 'Comments',
									'date'							=> 'Date',
								);
	public $media_tab_extra_columns = array(
									'wptec_media_id'				=> 'Media ID',
									'wptec_media_filename'			=> 'File name', 		
									'wptec_media_author'			=> 'Author',
									'wptec_media_file_sizes'		=> 'file Sizes', 
									'wptec_media_alternate_text'	=> 'Alternate Text', 	
									'wptec_media_caption'			=> 'Caption',        	// editable
									'wptec_media_description'		=> 'Description',    	// editable
									'wptec_media_file_author_id'	=> 'File Author Id',      
									'wptec_media_full_path'			=> 'Full Path',
									'wptec_media_mime'				=> 'Mime / type',
									'wptec_media_date'				=> 'Upload file Date',
									'wptec_media_modified_date'		=> 'Upload file modified Date',
									'wptec_media_height'			=> 'Height',
									'wptec_media_width'				=> 'Width',
								);

	/**
	 * list of woocommerce product table default and extra columns
	 * @since    1.0.0
	 * @access   public
	 * @array    extra fields key name array 
	*/
	public $products_tab_default_columns = array(
									'name' 							=> 'Name',
									'sku' 							=> 'SKU',
									'is_in_stock' 					=> 'Stock',
									'price' 						=> 'Price',
									'product_cat' 					=> 'Categories',
									'product_tag' 					=> 'Tags',
									'featured' 						=> 'Featured',
									'date' 							=> 'Date',
								);
	public $products_tab_extra_columns = array(
									'wptec_product_id' 				=> 'Product ID',
									'wptec_product_status' 			=> 'Status',     			// Frontend Editable 
									'wptec_product_weight' 			=> 'Weight',     			// Frontend Editable
									'wptec_product_length' 			=> 'Length',     			// Frontend Editable
									'wptec_product_width' 			=> 'Width',      			// Frontend Editable
									'wptec_product_height' 			=> 'Height',     			// Frontend Editable
									'wptec_product_virtual' 		=> 'Virtual / physical',    // Frontend Editable
									'wptec_product_tax_status' 		=> 'Tax status',	 		// Frontend Editable
									'wptec_product_average_rating' 	=> 'Average rating',
									'wptec_product_review_count' 	=> 'Review count'
								);

	/**
	 * list of wooCommerce order table default and extra columns
	 * @since    1.0.0
	 * @access   public
	 * @array    extra fields key name array 
	*/
	public $orders_tab_default_columns = array(
									'order_number' 						=> 'Order',
									'order_date' 						=> 'Date',
									'order_status' 						=> 'Status',
									'billing_address' 					=> 'Billing address',
									'shipping_address' 					=> 'shipping address',
									'order_total' 						=> 'Order total',
								);
	public $orders_tab_extra_columns = array(
									'wptec_order_id' 					=> 'Order ID',
									'wptec_order_user_name' 			=> 'User name',       			// Frontend Editable
									
									'wptec_order_billing_first_name' 	=> 'billing first name', 
									'wptec_order_billing_last_name' 	=> 'billing last name', 
									'wptec_order_billing_company' 		=> 'billing company', 
									'wptec_order_billing_address_1' 	=> 'billing address 1', 
									'wptec_order_billing_address_2' 	=> 'billing address 2', 
									'wptec_order_billing_city' 			=> 'billing city', 
									'wptec_order_billing_state' 		=> 'billing state', 
									'wptec_order_billing_postcode' 		=> 'billing postcode', 
									'wptec_order_billing_country' 		=> 'billing country', 
									'wptec_order_billing_email' 		=> 'billing email', 
									'wptec_order_billing_phone' 		=> 'billing phone', 

									'wptec_order_shipping_first_name' 	=> 'shipping_first_name', 
									'wptec_order_shipping_last_name' 	=> 'shipping_last_name', 
									'wptec_order_shipping_company' 		=> 'shipping company', 
									'wptec_order_shipping_address_1' 	=> 'shipping address 1', 
									'wptec_order_shipping_address_2' 	=> 'shipping address 2', 
									'wptec_order_shipping_city' 		=> 'shipping city', 
									'wptec_order_shipping_state' 		=> 'shipping state', 
									'wptec_order_shipping_postcode' 	=> 'shipping postcode', 
									'wptec_order_shipping_country' 		=> 'shipping country', 

									'wptec_order_products' 				=> 'Products',
									'wptec_order_currency' 				=> 'order currency', 
									'wptec_order_shipping_total' 		=> 'Shipping total',  			// Frontend Editable
									'wptec_order_discount_total' 		=> 'Discount total',  			// Frontend Editable
									'wptec_order_payment_method' 		=> 'Order payment method',   	// Frontend Editable
									'wptec_order_payment_method_title' 	=> 'Order Payment title'    	// Frontend Editable
								);


	/**
	 * The common object.
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	*/
	public function __construct( $plugin_name, $version){
		$this->plugin_name 		= $plugin_name;				# Name of the Plugin setting for this Class
		$this->version 			= $version;					# Version of this Plugin setting for this Class
	}

	/**
	 * User Meta keys.
	 * @since      1.0.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wptec_users_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query = "SELECT DISTINCT( $wpdb->usermeta.meta_key ) FROM $wpdb->usermeta";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if(empty($meta_keys)){
			return array( FALSE, 'ERROR: Empty! No Meta key exist of users.');
		}else{
			# key == value || reversing the key vale that got from database 
			$r = array();
			foreach($meta_keys as $key => $value){
				# also removing some array item that are overlapping with default wptec fields
				if( ! in_array($value, array('nickname','first_name','last_name','description'))){
					$r[$value] =  $value;
				}
			}
			# yep not yep
			return array(TRUE, $r);
		}
	}

	/**
	 * This Function will return [wordPress Posts] Meta keys.
	 * @since      1.0.0
	 * @return     array  This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wptec_posts_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query  =  "SELECT DISTINCT( $wpdb->postmeta.meta_key ) 
				  	FROM  $wpdb->posts 
					LEFT JOIN $wpdb->postmeta 
					ON    $wpdb->posts.ID = $wpdb->postmeta.post_id 
					WHERE $wpdb->posts.post_type  = 'post' 
					AND   $wpdb->postmeta.meta_key != ''";
		# execute Query
		$meta_keys = $wpdb->get_col($query);
		# return Depend on the Query result 
		if(empty($meta_keys)){
			return array(FALSE, 'ERROR: Empty! No Meta key exist of the Post.');
		}else{
			# key == value
			$r = array();
			foreach ($meta_keys as $key => $value) {
				$r[$value] =  $value;
			}
			# yep not yep
			return array(TRUE, $r);
		}
	}

	/**
	 * This Function will return [wordPress Pages] Meta keys.
	 * @since      1.0.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wptec_pages_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query  =  "SELECT DISTINCT($wpdb->postmeta.meta_key) 
					FROM  $wpdb->posts 
					LEFT JOIN $wpdb->postmeta 
					ON    $wpdb->posts.ID = $wpdb->postmeta.post_id 
					WHERE $wpdb->posts.post_type  = 'page' 
					AND   $wpdb->postmeta.meta_key != '' ";
		# execute Query
		$meta_keys = $wpdb->get_col($query);
		# return Depend on the Query result 
		if(empty($meta_keys)){
			return array( FALSE, 'Error: Empty! No Meta key exist of the Post type page.');
		}else{
			# key == value
			$r = array();
			foreach($meta_keys as $key => $value){
				$r[$value] =  $value;
			}
			# yep not yep
			return array(TRUE, $r);
		}
	}

	/**
	 * This Function will return [wordPress Users] Meta keys.
	 * @since      1.0.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wptec_comments_metaKeys(){
		# Global Db object;
		global $wpdb;
		# Query;
		$query = "SELECT DISTINCT( $wpdb->commentmeta.meta_key ) FROM $wpdb->commentmeta";
		# execute Query;
		$meta_keys = $wpdb->get_col($query);
		# return Depend on the Query result;
		if(empty($meta_keys)){
			return array( FALSE, 'ERROR: Empty! No Meta key exist on comment meta.');
		}else{
			# key == value
			$r = array();
			foreach($meta_keys as $key => $value){
				$r[$value]  =  $value;
			}
			# yep not yep
			return array(TRUE, $r);
		}
	}

	

	/**
	 * This Function will return [wordPress Posts] Meta keys.
	 * @since      1.0.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wptec_media_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query  =  "SELECT DISTINCT( $wpdb->postmeta.meta_key ) 
		FROM  $wpdb->posts, $wpdb->postmeta 
		WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id  
		AND post_type = 'attachment'";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if ( empty( $meta_keys ) ){
			return array( FALSE, 'ERROR: Empty! No Meta key exist of the Post.');
		} else {
			# key == value
			$r = array();
			foreach ($meta_keys as $key => $value) {
				$r[$value] =  $value;
			}
			# yep not yep
			return array( TRUE, $r );
		}
	}

	/**
	 * This Function will return [WooCommerce product] Meta keys.
	 * @since      1.0.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wptec_wooCommerce_product_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query = "SELECT DISTINCT( $wpdb->postmeta.meta_key ) 
		FROM  wp_posts, wp_postmeta 
		WHERE wp_posts.ID = wp_postmeta.post_id 
		AND   wp_posts.post_type = 'product'";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if(empty($meta_keys)){
			return array( FALSE, 'ERROR: Empty! No Meta key exist of the Post type WooCommerce Product.');
		}else{
			# key == value
			$r = array();
			foreach ($meta_keys as $key => $value) {
				# removing some items 
				if( ! in_array( $value, array( "_weight","_length","_width","_height","_virtual","_tax_status" )) ){
					$r[$value] =  $value;
				}
			}
			# yep not yep
			return array( TRUE, $r );
		}
	}

	/**
	 * This Function will return [WooCommerce Order] Meta keys.
	 * @since      1.0.0
	 * @return     array  This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wptec_wooCommerce_order_metaKeys(){
		# Global Db object
		global $wpdb;
		# Query
		$query  =  "SELECT DISTINCT($wpdb->postmeta.meta_key) 
					FROM  $wpdb->posts 
					LEFT JOIN $wpdb->postmeta 
					ON    $wpdb->posts.ID = $wpdb->postmeta.post_id 
					WHERE $wpdb->posts.post_type = 'shop_order' 
					AND   $wpdb->postmeta.meta_key != '' ";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if ( empty( $meta_keys ) ){
			return array( FALSE, 'ERROR: Empty! No Meta key exist of the post type WooCommerce Order.');
		} else {
			# key == value
			$r = array();
			foreach ($meta_keys as $key => $value) {
				# remove list from meta data 
				$removeList = array( 
										"_billing_first_name",
										"_billing_last_name",
										"_billing_company",
										"_billing_address_1",
										"_billing_city",
										"_billing_state", 
										"_billing_postcode", 
										"_billing_country", 
										"_billing_email", 
										"_billing_phone", 
										"_shipping_first_name", 
										"_shipping_last_name", 
										"_shipping_company", 
										"_shipping_address_1", 
										"_shipping_city", 
										"_shipping_state", 
										"_shipping_postcode", 
										"_shipping_country", 
										"_order_shipping", 
										"_cart_discount", 
										"_payment_method_title", 
									);
				# removing some items 
				if( ! in_array( $value, $removeList ) ){
					$r[$value] =  $value;
				}
			}
			# yep not yep
			return array( TRUE, $r );
		}
	}

	/**
	 * LOG ! For Good , This the log Method 
	 * @since      1.0.0
	 * @param      string    $file_name       	File Name . Use  [ get_class($this) ]
	 * @param      string    $function_name     Function name.	 [  __METHOD__  ]
	 * @param      string    $status_code       The name of this plugin.
	 * @param      string    $status_message    The version of this plugin.
	*/
	public function wptec_log($file_name = '', $function_name = '', $status_code = '', $status_message = ''){
		# Log status
		$logStatusOption = get_option( 'wpgsi_logStatus', false );
		# check log status 
		if($logStatusOption  AND  $logStatusOption == 'disable'){
			return  array( FALSE, "ERROR: Log is disable." ); 
		} 
		# Check and Balance 
		if(empty( $status_code ) or empty( $status_message )){
			return  array( FALSE, "ERROR: status_code OR status_message is Empty");
		}
		# Post Excerpt 
		$post_excerpt  = json_encode(array( "file_name" => esc_sql($file_name), "function_name" => esc_sql($function_name)));
		# Inserting into the DB
		global $wpdb;
		$sql 	 = "INSERT INTO  {$wpdb->posts} (post_content, post_title, post_excerpt, post_type) VALUES ( '" . esc_sql($status_message) . "','" . esc_sql($status_code) . "','" . esc_sql($post_excerpt) . "', 'wptec_log' )";
		$results = $wpdb->get_results( $sql );
		
		return  array( TRUE, "SUCCESS: Successfully inserted to the Log" ); 
	}

	/**
     * Testing Common Class; this Method is a variadic Method so it can get all kind of data;
     * @param array  		Data  or Data array optional.
     * @param string  		Data  or Data array optional.
     * @param int  			Data  or Data array optional.
     * @uses 			    Wp Admin Footer Hook
    */
	public function wptec_common_test( ...$data ){
		?>
			<div class="notice notice-success is-dismissible">
				<?php
					if(! empty($data)){
						echo"<pre>";
							print_r($data);
						echo"</pre>";
					}else{	
						echo"<br>Common test function successfully called.<br><br>";
					}
				?>
			</div>
    	<?php
	}

}
