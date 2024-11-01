<?php
/**
 * The core plugin class.
 * This is used to define internationalization, admin-specific hooks, and public-facing site hooks.
 * Also maintains the unique identifier of this plugin as well as the current version of the plugin.
 *
 * @since      1.0.0
 * @package    Wptec
 * @subpackage Wptec/includes
 * @author     javmah <jaedmah@gmail.com>
 */
class Wptec {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin.
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wptec_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct(){
		if(defined('WPTEC_VERSION')){
			$this->version = WPTEC_VERSION;
		}else{
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wptec';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wptec_Loader. Orchestrates the hooks of the plugin.
	 * - Wptec_i18n. Defines internationalization functionality.
	 * - Wptec_Admin. Defines all hooks for the admin area.
	 * - Wptec_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks  with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class for common methods that are used in many different Classes.
		*/ 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wptec-common.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the core plugin.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wptec-loader.php';

		/**
		 * The class responsible for defining internationalization functionality  of the plugin.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wptec-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wptec-admin.php';
		
		/**
		 * The class responsible for defining all actions on Users Table 
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wptec-users.php';

		/**
		 * The class responsible for defining all actions on Users Table 
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wptec-posts.php';

		/**
		 * The class responsible for defining all actions on Users Table 
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wptec-media.php';

		/**
		 * The class responsible for defining all actions on Users Table 
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wptec-comments.php';

		/**
		 * The class responsible for defining all actions on Users Table 
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wptec-pages.php';

		/**
		 * The class responsible for defining all actions on Users Table 
		*/
		if(function_exists('is_plugin_active') AND is_plugin_active( 'woocommerce/woocommerce.php')){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wptec-products.php';
		}

		/**
		 * The class responsible for defining all actions on Users Table 
		*/
		if(function_exists('is_plugin_active') AND is_plugin_active( 'woocommerce/woocommerce.php')){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wptec-orders.php';
		}
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing side of the site.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wptec-public.php';

		$this->loader = new Wptec_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wptec_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wptec_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		# assigning  common class 
		$common 	  = new Wptec_common($this->get_plugin_name(), $this->get_version());	
		#.......................................... settings & main nav Starts ..........................................
		$plugin_admin = new Wptec_Admin( $this->get_plugin_name(), $this->get_version(), $common);
		# General enqueue
		$this->loader->add_action('admin_enqueue_scripts', 			$plugin_admin, 'enqueue_styles', 1);
		$this->loader->add_action('admin_enqueue_scripts', 			$plugin_admin, 'enqueue_scripts', 1);
		# Admin Menu 
		$this->loader->add_action('admin_menu', 					$plugin_admin, 'wptec_admin_menu');
		# admin notice for testing and edit
		$this->loader->add_action('admin_notices', 					$plugin_admin, 'wptec_admin_notices');
		# plugin action links	
		$this->loader->add_filter( 'plugin_action_links',			$plugin_admin, 'wptec_action_link', 10, 2 );																		

		# AJAX Request Handler 
		$this->loader->add_action('wp_ajax_wptecAdminAJAX', 		$plugin_admin, 'wptecAdminAJAX');
		$this->loader->add_action('wp_ajax_nopriv_wptecAdminAJAX',  $plugin_admin, 'wptecAdminAJAX');
		# Admin Page Footer Hook for table content edit 
		$this->loader->add_action('admin_footer', 					$plugin_admin, 'wptec_adminFooter');

		#.......................................... settings & main nav ends .............................................

		#.......................................... User Table Starts .....................................................
		$plugin_users = new Wptec_Users( $this->get_plugin_name(), $this->get_version(), $common);
		# Testing Admin Message
		$this->loader->add_action('admin_notices', 					$plugin_users,  'wptec_users_admin_notices');
		# Filter  On User Table  OR I Will Include Search option in Here 
		$this->loader->add_action('restrict_manage_users', 			$plugin_users,	'wptec_user_section_filter');
		# user table extra Columns
		$this->loader->add_action('manage_users_columns', 			$plugin_users, 	'wptec_user_columns', 100);
		# user table extra Columns content 
		$this->loader->add_action('manage_users_custom_column',		$plugin_users, 	'wptec_user_columns_content', 10, 3);
		# creating sortable columns 
		$this->loader->add_action('manage_users_sortable_columns',  $plugin_users,  'wptec_user_sortable_columns');
		# Preparing Query for sortable columns
		$this->loader->add_action('pre_get_users', 					$plugin_users,  'wptec_user_sortable_columns_query');
		# AJAX Request Handler for User Table update content 
		$this->loader->add_action('wp_ajax_wptec_userAJAX', 		$plugin_users, 	'wptec_userAJAX');
		$this->loader->add_action('wp_ajax_nopriv_wptec_userAJAX', 	$plugin_users, 	'wptec_userAJAX');
		# AJAX Request Handler for User Table CSV download 
		$this->loader->add_action('wp_ajax_wptec_userCSV', 			$plugin_users, 	'wptec_userCSV');
		$this->loader->add_action('wp_ajax_nopriv_wptec_userCSV',  	$plugin_users, 	'wptec_userCSV');
 
		#.......................................... User Table Ends ........................................................

		#.......................................... Posts Table Starts ......................................................
		$plugin_posts = new Wptec_Posts( $this->get_plugin_name(), $this->get_version(), $common);
		$this->loader->add_action('admin_notices', 					$plugin_posts, 'wptec_posts_admin_notices');
		# Add Download Button.
		$this->loader->add_action('restrict_manage_posts', 			$plugin_posts,	'wptec_posts_table_manage_posts');
		# post table extra Columns
		$this->loader->add_action('manage_posts_columns', 			$plugin_posts, 	'wptec_posts_columns', 100);
		# post table extra Columns content 
		$this->loader->add_action('manage_posts_custom_column',		$plugin_posts, 	'wptec_posts_columns_content', 10, 2);
		# creating sortable columns  ************
		$this->loader->add_action('manage_edit-post_sortable_columns', $plugin_posts, 'wptec_posts_sortable_columns');
		# Preparing Query for sortable columns ************
		$this->loader->add_action('pre_get_posts', 					$plugin_posts,  'wptec_posts_sortable_columns_query');
		# AJAX Request Handler for Post Table.
		$this->loader->add_action('wp_ajax_wptec_postAJAX', 		$plugin_posts, 	'wptec_postAJAX');
		$this->loader->add_action('wp_ajax_nopriv_wptec_postAJAX', 	$plugin_posts,  'wptec_postAJAX'); // wptec_postsCSV
		# AJAX Request Handler for Posts Table CSV download.
		$this->loader->add_action('wp_ajax_wptec_postsCSV', 		$plugin_posts, 	'wptec_postsCSV');
		$this->loader->add_action('wp_ajax_nopriv_wptec_postsCSV', 	$plugin_posts,  'wptec_postsCSV');
		#......................................... Posts Table Ends ........................................................

		#.......................................... Pages Table Starts ......................................................
		$plugin_pages = new Wptec_Pages( $this->get_plugin_name(), $this->get_version(), $common);
		$this->loader->add_action('admin_notices', 					$plugin_pages, 'wptec_pages_admin_notices');
		# Add Download Button 
		$this->loader->add_action('restrict_manage_posts', 			$plugin_pages, 'wptec_pages_table_manage_posts');
		# Pages Table 
		$this->loader->add_action('manage_pages_columns', 			$plugin_pages, 'wptec_pages_columns', 100);
		$this->loader->add_action('manage_pages_custom_column',		$plugin_pages, 'wptec_pages_columns_content', 10, 2);
		# creating sortable columns  ************
		$this->loader->add_action('manage_edit-page_sortable_columns',$plugin_pages, 'wptec_pages_sortable_columns');
		# Preparing Query for sortable columns ************
		$this->loader->add_action('pre_get_posts', 					$plugin_pages, 'wptec_pages_sortable_columns_query');
		# AJAX Request Handler for page Table 
		$this->loader->add_action('wp_ajax_wptec_pageAJAX', 		$plugin_pages, 'wptec_pageAJAX');
		$this->loader->add_action('wp_ajax_nopriv_wptec_pageAJAX', 	$plugin_pages, 'wptec_pageAJAX');
		# AJAX Request Handler for page Table CSV download.
		$this->loader->add_action('wp_ajax_wptec_pagesCSV', 	    $plugin_pages, 	'wptec_pagesCSV');
		$this->loader->add_action('wp_ajax_nopriv_wptec_pagesCSV', 	$plugin_pages,  'wptec_pagesCSV');
		#.......................................... Pages Table Starts ......................................................

		#.......................................... Media Table Starts ......................................................
		$plugin_media = new Wptec_Media( $this->get_plugin_name(), $this->get_version(), $common);
		$this->loader->add_action('admin_notices', 					$plugin_media, 'wptec_media_admin_notices');
		# Add Download Button 
		$this->loader->add_action('restrict_manage_posts', 			$plugin_media, 'wptec_media_table_manage_posts');
		# Media  Table   table extra Columns
		$this->loader->add_action('manage_media_columns', 			$plugin_media, 'wptec_media_columns', 100);
		# post table extra Columns content 
		$this->loader->add_action('manage_media_custom_column',		$plugin_media, 'wptec_media_columns_content', 10, 2);
		# creating sortable columns  ************
		$this->loader->add_action('manage_upload_sortable_columns', $plugin_media, 'wptec_manage_media_sortable_columns');
		# Preparing Query for sortable columns ************
		$this->loader->add_action('request', 						$plugin_media, 'wptec_manage_media_sortable_columns_query');
		# AJAX Request Handler for Media Table 
		$this->loader->add_action('wp_ajax_wptec_mediaAJAX', 		$plugin_media, 'wptec_mediaAJAX');
		$this->loader->add_action('wp_ajax_nopriv_wptec_mediaAJAX',	$plugin_media, 'wptec_mediaAJAX');
		# AJAX Request Handler for media Table CSV download.
		$this->loader->add_action('wp_ajax_wptec_mediaCSV', 		$plugin_media, 'wptec_mediaCSV');
		$this->loader->add_action('wp_ajax_nopriv_wptec_mediaCSV', 	$plugin_media, 'wptec_mediaCSV');
		#.......................................... Media Table Ends ........................................................

		#.......................................... Comments Table Starts ...................................................
		$plugin_comments = new Wptec_Comments( $this->get_plugin_name(), $this->get_version(), $common);
		$this->loader->add_action('admin_notices', 				  	   $plugin_comments, 'wptec_comments_admin_notices');
		# Add Download Button 
		$this->loader->add_action('restrict_manage_comments', 		   $plugin_comments, 'wptec_comments_table_manage_posts');
		# Comments Table 
		$this->loader->add_action('manage_edit-comments_columns', 	   $plugin_comments, 'wptec_comments_columns', 100);
		$this->loader->add_action('manage_comments_custom_column',	   $plugin_comments, 'wptec_comments_columns_content', 10, 2);
		# creating sortable columns  ************
		$this->loader->add_action('manage_edit-comments_sortable_columns', $plugin_comments, 'wptec_comments_sortable_columns' );
		# Preparing Query for sortable columns ************
		$this->loader->add_action('pre_get_comments', 				    $plugin_comments, 'wptec_comments_sortable_columns_query');
		# AJAX Request Handler for comment Table 
		$this->loader->add_action('wp_ajax_wptec_commentAJAX', 	   	    $plugin_comments, 'wptec_commentAJAX');
		$this->loader->add_action('wp_ajax_nopriv_wptec_commentAJAX',   $plugin_comments, 'wptec_commentAJAX');
		# AJAX Request Handler for comment Table CSV download. 
		$this->loader->add_action('wp_ajax_wptec_commentsCSV', 	   		$plugin_comments, 'wptec_commentsCSV');
		$this->loader->add_action('wp_ajax_nopriv_wptec_commentsCSV',   $plugin_comments, 'wptec_commentsCSV');
		#.......................................... Comments Table Ends .....................................................

		#.......................................... Products Table Starts ...................................................
		if(function_exists('is_plugin_active') AND is_plugin_active('woocommerce/woocommerce.php')){
			$plugin_products = new Wptec_Products( $this->get_plugin_name(),$this->get_version(), $common);
			$this->loader->add_action('admin_notices', 						$plugin_products, 'wptec_product_admin_notices');
			# Add Download Button 
			$this->loader->add_action('restrict_manage_posts', 				$plugin_products, 'wptec_product_table_manage_posts');
			# Product Columns
			$this->loader->add_action('manage_edit-product_columns', 		$plugin_products, 'wptec_product_columns', 100);
			$this->loader->add_action('manage_product_posts_custom_column',	$plugin_products, 'wptec_product_columns_content', 10, 2);

			// # creating sortable columns  ************
			$this->loader->add_action('manage_edit-product_sortable_columns',$plugin_products,'wptec_product_sortable_columns');
			// # Preparing Query for sortable columns ************
			$this->loader->add_action('pre_get_posts', 						$plugin_products, 'wptec_product_sortable_columns_query');

			# AJAX Request Handler for wooCommerce Product Table 
			$this->loader->add_action('wp_ajax_wptec_productAJAX', 			$plugin_products, 'wptec_productAJAX');
			$this->loader->add_action('wp_ajax_nopriv_wptec_productAJAX',  	$plugin_products, 'wptec_productAJAX');
			# AJAX Request Handler for page Table CSV download.
			$this->loader->add_action('wp_ajax_wptec_productsCSV', 	   		$plugin_products, 'wptec_productsCSV');
			$this->loader->add_action('wp_ajax_nopriv_wptec_productsCSV',	$plugin_products, 'wptec_productsCSV');
		}
		#.......................................... Products Table Starts ...................................................

		#.......................................... Orders Table Starts ......................................................
		if(function_exists('is_plugin_active') AND is_plugin_active( 'woocommerce/woocommerce.php')){
			$plugin_orders = new Wptec_Orders( $this->get_plugin_name(),$this->get_version(), $common); 
			$this->loader->add_action('admin_notices', 					$plugin_orders, 'wptec_order_admin_notices');
			# Add Download Button 
			$this->loader->add_action('restrict_manage_posts', 			$plugin_orders, 'wptec_orders_table_manage_posts');
			# Order Columns
			$this->loader->add_action('manage_edit-shop_order_columns',	$plugin_orders, 'wptec_order_columns', 100);
			$this->loader->add_action('manage_posts_custom_column', 	$plugin_orders, 'wptec_order_columns_content', 20, 2);

			# creating sortable columns  ************
			$this->loader->add_action('manage_edit-shop_order_sortable_columns', $plugin_orders, 'wptec_orders_sortable_columns');
			# Preparing Query for sortable columns ************
			$this->loader->add_action('pre_get_posts', 					$plugin_orders,  'wptec_orders_sortable_columns_query');

			# AJAX Request Handler for wooCommerce order Table 
			$this->loader->add_action('wp_ajax_wptec_orderAJAX', 		$plugin_orders, 'wptec_orderAJAX');
			$this->loader->add_action('wp_ajax_nopriv_wptec_orderAJAX',	$plugin_orders, 'wptec_orderAJAX');
			# AJAX Request Handler for page Table CSV download.
			$this->loader->add_action('wp_ajax_wptec_ordersCSV', 	   	$plugin_orders, 'wptec_ordersCSV');
			$this->loader->add_action('wp_ajax_nopriv_wptec_ordersCSV',	$plugin_orders, 'wptec_ordersCSV');
			# removing <td> tag links, tr will be tr no more hyper-link 
			$this->loader->add_action('post_class',						$plugin_orders, 'wptec_add_no_link');
		}
		#.......................................... Orders Table Starts ......................................................
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks(){
		$plugin_public = new Wptec_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run(){
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name(){
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wptec_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader(){
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
