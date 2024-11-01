<?php

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 * @since      1.0.0
 * @package    Wptec
 * @subpackage Wptec/admin
 * @author     javmah <jaedmah@gmail.com>
 */
class Wptec_Admin
{
    /**
     * plugin_name of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Common methods used in the all the classes 
     * @since    3.6.0
     * @var      object    $version    The current version of this plugin.
     */
    public  $common ;
    /**
     * List of meta keys that are Assigned, Those value come from  common 
     * @since    1.0.0
     * @var      object    $version    The current version of this plugin.
     */
    private  $wptec_users_metaKeys = array() ;
    private  $wptec_posts_metaKeys = array() ;
    private  $wptec_pages_metaKeys = array() ;
    private  $wptec_comments_metaKeys = array() ;
    private  $wptec_media_metaKeys = array() ;
    private  $wptec_wooCommerce_product_metaKeys = array() ;
    private  $wptec_wooCommerce_order_metaKeys = array() ;
    /**
     * Initialize the class __construct and set its properties.
     * @since      1.0.0
     * @param      string    $plugin_name   The name of this plugin.
     * @param      string    $version    	The version of this plugin.
     * @param      string    $common    	common class object 
     */
    public function __construct( $plugin_name, $version, $common )
    {
        # Plugin version.
        $this->plugin_name = $plugin_name;
        # Plugin version.
        $this->version = $version;
        # Plugin common reused properties and method class.
        $this->common = $common;
    }
    
    /**
     * Register the stylesheets for the admin area.
     * @since    1.0.0
     */
    public function enqueue_styles( $hook )
    {
    }
    
    /**
     * Register the JavaScript for the admin area.
     * @since    1.0.0
     */
    public function enqueue_scripts( $hook )
    {
        # sending data to Main JS script.
        $wptec_local_data = array(
            "wptecAjaxURL"       => admin_url( 'admin-ajax.php' ),
            "wptecSecurity"      => wp_create_nonce( 'wptec-ajax-nonce' ),
            "wptecDownloadURL"   => wp_upload_dir()['baseurl'] . '/wptec/download.csv',
            "wptecCurrentScreen" => get_current_screen()->id,
        );
        # only include this JS scripts when page id is settings_page_wptec
        
        if ( get_current_screen()->id == 'settings_page_wptec' ) {
            # include vue 2
            wp_enqueue_script(
                'vue',
                plugin_dir_url( __FILE__ ) . 'js/vue_v2_5_17.js',
                array(),
                '2.5.17'
            );
            # Javascript sortable library.
            wp_enqueue_script(
                'Sortable',
                plugin_dir_url( __FILE__ ) . 'js/sortable_v1.7.0.js',
                array(),
                '1.7.0'
            );
            # Vue draggable.
            wp_enqueue_script(
                'vueDraggable',
                plugin_dir_url( __FILE__ ) . 'js/vue_draggable_v2_16_0.js',
                array(),
                '2.16.0'
            );
            # Plugin script.
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/wptec-admin.js',
                array( 'vue', 'Sortable', 'vueDraggable' ),
                $this->version,
                TRUE
            );
            # localizing
            wp_localize_script( $this->plugin_name, 'wptec', $wptec_local_data );
        }
        
        # Including frontend js,  Sending data to the Javascript
        
        if ( in_array( get_current_screen()->id, array(
            'users',
            'edit-post',
            'edit-page',
            'edit-comments',
            'upload',
            'edit-product',
            'edit-shop_order',
            'settings_page_wptec'
        ) ) ) {
            # including Jquery frontend update script
            wp_enqueue_script(
                'wptec-admin-tables',
                plugin_dir_url( __FILE__ ) . 'js/wptec-admin-tables.js',
                array( 'jquery' ),
                $this->version,
                TRUE
            );
            # localizing the data
            wp_localize_script( 'wptec-admin-tables', 'wptec', $wptec_local_data );
        }
    
    }
    
    # Admin Message
    public function wptec_admin_notices()
    {
        
        if ( isset( get_current_screen()->base ) and get_current_screen()->base == 'settings_page_wptec' ) {
            # Plugin Conflict Message
            
            if ( is_plugin_active( 'codepress-admin-columns/codepress-admin-columns.php' ) ) {
                echo  "<div class='notice notice-error is-dismissible inline'><p>" ;
                echo  "This <a href='https://wordpress.org/plugins/codepress-admin-columns'> codepress-admin-columns/codepress-admin-columns.php </a> plugin will create conflict with <code> Admin Extra Column Plugin </code>. Better Deactivate that plugin." ;
                echo  "</p></div>" ;
            }
            
            # Download Start Message
            echo  "<div id='wptecDownloadMessage' class='notice notice-success is-dismissible inline' style='display:none;'><p>" ;
            echo  "<i>The download action is processing. It will take a few minutes. Please <b> don't close this browser window </b> until the file is downloaded.</i>" ;
            echo  "</p></div>" ;
        }
        
        # Creating a dialog box to get edit data
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * Adding a settings link at Plugin page after activate deactivate.
     * @since    1.0.0
     */
    public function wptec_action_link( $links_array, $plugin_file_name )
    {
        # check and balance
        if ( $plugin_file_name == 'wptec/wptec.php' ) {
            $links_array[] = '<a href="' . esc_url( get_admin_url( null, 'options-general.php?page=wptec' ) ) . '">Settings</a>';
        }
        #
        return $links_array;
    }
    
    /**
     * Admin menu init.
     * @since    1.0.0
     * @param    string $value The value.
     * Admin tables extra columns
     */
    public function wptec_admin_menu()
    {
        # adding sub-menu to settings main.
        add_submenu_page(
            'options-general.php',
            __( 'Tables Extra Columns', 'wptec' ),
            __( 'Tables Extra Columns', 'wptec' ),
            'manage_options',
            'wptec',
            array( $this, 'wptec_view_func' )
        );
    }
    
    /**
     * Main view function also Plugin URL router;
     * @since    1.0.0_
     * @param    string $value The value.
     */
    public function wptec_view_func( $value = '' )
    {
        # data holder
        $columnData = array();
        # columnListFrom is status thing || { "user" : false , "post" : false , "page" : false , "comment" : false, "media" : false, "product" : false, "order" : false }, it will show where data came from ! DB or WP class data.
        $columnListFrom = array(
            "user"    => true,
            "post"    => true,
            "page"    => true,
            "comment" => true,
            "media"   => true,
            "product" => true,
            "order"   => true,
        );
        # USER
        $userColumnList = get_user_option( 'wptec_user', get_current_user_id() );
        # Check and Balance
        
        if ( empty($userColumnList) ) {
            # Holder for data
            $tmpData = array();
            # Getting Default Fields
            require_once ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php';
            $user_list_table = new WP_Users_List_Table( array(
                'screen' => 'users',
            ) );
            # Processing the data
            if ( !empty($user_list_table->get_column_info()[0]) ) {
                foreach ( $user_list_table->get_column_info()[0] as $key => $value ) {
                    
                    if ( $key != 'cb' ) {
                        $tmpData['name'] = wp_kses_post( $key );
                        $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                        $tmpData['width'] = "";
                        $tmpData['type'] = "default";
                        $tmpData['status'] = true;
                        # Populating the data
                        $userColumnList[] = $tmpData;
                    }
                
                }
            }
            # Processing the WPTEC fields
            if ( !empty($this->common->user_tab_extra_columns) ) {
                foreach ( $this->common->user_tab_extra_columns as $key => $value ) {
                    $tmpData['name'] = wp_kses_post( $key );
                    $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                    $tmpData['width'] = "";
                    $tmpData['type'] = "wptec";
                    $tmpData['status'] = false;
                    # Populating the data
                    $userColumnList[] = $tmpData;
                }
            }
            # Json encoding
            $userColumnList = json_encode( $userColumnList, TRUE );
            # Changing status from
            $columnListFrom["user"] = false;
        } else {
            # escape HTML
            foreach ( $userColumnList as $rowKey => $rowArray ) {
                # now escape every array item
                foreach ( $rowArray as $itemKey => $itemValue ) {
                    $userColumnList[$rowKey][wp_kses_post( $itemKey )] = wp_kses_post( $itemValue );
                }
            }
            # converting to JSON
            $userColumnList = json_encode( $userColumnList, TRUE );
        }
        
        # POST
        $postColumnList = get_user_option( 'wptec_post', get_current_user_id() );
        # Check and Balance
        
        if ( empty($postColumnList) ) {
            # Holder for data
            $tmpData = array();
            # Getting Default Fields
            require_once ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php';
            $post_list_table = new WP_Posts_List_Table( array(
                'screen' => 'edit-post',
            ) );
            # Processing the data
            if ( !empty($post_list_table->get_column_info()[0]) ) {
                foreach ( $post_list_table->get_column_info()[0] as $key => $value ) {
                    
                    if ( $key != 'cb' ) {
                        $tmpData['name'] = wp_kses_post( $key );
                        $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                        $tmpData['width'] = "";
                        $tmpData['type'] = "default";
                        $tmpData['status'] = true;
                        # Populating the data
                        $postColumnList[] = $tmpData;
                    }
                
                }
            }
            # Processing the WPTEC fields
            if ( !empty($this->common->post_tab_extra_columns) ) {
                foreach ( $this->common->post_tab_extra_columns as $key => $value ) {
                    $tmpData['name'] = wp_kses_post( $key );
                    $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                    $tmpData['width'] = "";
                    $tmpData['type'] = "wptec";
                    $tmpData['status'] = false;
                    # Populating the data
                    $postColumnList[] = $tmpData;
                }
            }
            # Json encoding
            $postColumnList = json_encode( $postColumnList, TRUE );
            # Changing status from
            $columnListFrom["post"] = false;
        } else {
            # escape HTML
            foreach ( $postColumnList as $rowKey => $rowArray ) {
                # now escape every array item
                foreach ( $rowArray as $itemKey => $itemValue ) {
                    $postColumnList[$rowKey][wp_kses_post( $itemKey )] = wp_kses_post( $itemValue );
                }
            }
            # converting to JSON
            $postColumnList = json_encode( $postColumnList, TRUE );
        }
        
        # PAGE
        $pageColumnList = get_user_option( 'wptec_page', get_current_user_id() );
        # Check and Balance
        
        if ( empty($pageColumnList) ) {
            # Holder for data
            $tmpData = array();
            # Getting Default Fields
            require_once ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php';
            $page_list_table = new WP_Posts_List_Table( array(
                'screen' => 'edit-page',
            ) );
            # Processing the data default data
            if ( !empty($page_list_table->get_column_info()[0]) ) {
                foreach ( $page_list_table->get_column_info()[0] as $key => $value ) {
                    
                    if ( $key != 'cb' ) {
                        $tmpData['name'] = wp_kses_post( $key );
                        $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                        $tmpData['width'] = "";
                        $tmpData['type'] = "default";
                        $tmpData['status'] = true;
                        # Populating the data
                        $pageColumnList[] = $tmpData;
                    }
                
                }
            }
            # Processing the WPTEC fields
            if ( !empty($this->common->pages_tab_extra_columns) ) {
                foreach ( $this->common->pages_tab_extra_columns as $key => $value ) {
                    $tmpData['name'] = wp_kses_post( $key );
                    $tmpData['title'] = wp_kses_post( $value );
                    $tmpData['width'] = "";
                    $tmpData['type'] = "wptec";
                    $tmpData['status'] = false;
                    # Populating the data
                    $pageColumnList[] = $tmpData;
                }
            }
            # Json encoding
            $pageColumnList = json_encode( $pageColumnList, TRUE );
            # Changing status from
            $columnListFrom["page"] = false;
        } else {
            # escape HTML
            foreach ( $pageColumnList as $rowKey => $rowArray ) {
                # now escape every array item
                foreach ( $rowArray as $itemKey => $itemValue ) {
                    $pageColumnList[$rowKey][wp_kses_post( $itemKey )] = wp_kses_post( $itemValue );
                }
            }
            # converting to JSON
            $pageColumnList = json_encode( $pageColumnList, TRUE );
        }
        
        # COMMENT
        $commentColumnList = get_user_option( 'wptec_comment', get_current_user_id() );
        # Check and Balance
        
        if ( empty($commentColumnList) ) {
            # Holder for data
            $tmpData = array();
            # Getting Default Fields
            require_once ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php';
            $comment_list_table = new WP_Comments_List_Table( array(
                'screen' => 'edit-comments',
            ) );
            # Processing the data
            if ( !empty($comment_list_table->get_column_info()[0]) ) {
                foreach ( $comment_list_table->get_column_info()[0] as $key => $value ) {
                    
                    if ( $key != 'cb' ) {
                        $tmpData['name'] = wp_kses_post( $key );
                        $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                        $tmpData['width'] = "";
                        $tmpData['type'] = "default";
                        $tmpData['status'] = true;
                        # Populating the data
                        $commentColumnList[] = $tmpData;
                    }
                
                }
            }
            # Processing the WPTEC fields
            if ( !empty($this->common->comments_tab_extra_columns) ) {
                foreach ( $this->common->comments_tab_extra_columns as $key => $value ) {
                    $tmpData['name'] = wp_kses_post( $key );
                    $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                    $tmpData['width'] = "";
                    $tmpData['type'] = "wptec";
                    $tmpData['status'] = false;
                    # Populating the data
                    $commentColumnList[] = $tmpData;
                }
            }
            # Json encoding
            $commentColumnList = json_encode( $commentColumnList, TRUE );
            # Changing status from
            $columnListFrom["comment"] = false;
        } else {
            # escape HTML
            foreach ( $commentColumnList as $rowKey => $rowArray ) {
                # now escape every array item
                foreach ( $rowArray as $itemKey => $itemValue ) {
                    $commentColumnList[$rowKey][wp_kses_post( $itemKey )] = wp_kses_post( $itemValue );
                }
            }
            # converting to JSON
            $commentColumnList = json_encode( $commentColumnList, TRUE );
        }
        
        # MEDIA
        $mediaColumnList = get_user_option( 'wptec_media', get_current_user_id() );
        # Check and Balance
        
        if ( empty($mediaColumnList) ) {
            # Holder for data
            $tmpData = array();
            # Getting Default Fields
            require_once ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php';
            $media_list_table = new WP_Media_List_Table( array(
                'screen' => 'upload',
            ) );
            # Processing the data
            if ( !empty($media_list_table->get_column_info()[0]) ) {
                foreach ( $media_list_table->get_column_info()[0] as $key => $value ) {
                    
                    if ( $key != 'cb' ) {
                        $tmpData['name'] = wp_kses_post( $key );
                        $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                        $tmpData['width'] = "";
                        $tmpData['type'] = "default";
                        $tmpData['status'] = true;
                        # Populating the data
                        $mediaColumnList[] = $tmpData;
                    }
                
                }
            }
            # Processing the WPTEC fields
            if ( !empty($this->common->media_tab_extra_columns) ) {
                foreach ( $this->common->media_tab_extra_columns as $key => $value ) {
                    $tmpData['name'] = wp_kses_post( $key );
                    $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                    $tmpData['width'] = "";
                    $tmpData['type'] = "wptec";
                    $tmpData['status'] = false;
                    # Populating the data
                    $mediaColumnList[] = $tmpData;
                }
            }
            # Json encoding
            $mediaColumnList = json_encode( $mediaColumnList, TRUE );
            # Changing status from
            $columnListFrom["media"] = false;
        } else {
            # escape HTML
            foreach ( $mediaColumnList as $rowKey => $rowArray ) {
                # now escape every array item
                foreach ( $rowArray as $itemKey => $itemValue ) {
                    $mediaColumnList[$rowKey][wp_kses_post( $itemKey )] = wp_kses_post( $itemValue );
                }
            }
            # converting to JSON
            $mediaColumnList = json_encode( $mediaColumnList, TRUE );
        }
        
        # PRODUCT
        $productColumnList = get_user_option( 'wptec_product', get_current_user_id() );
        # Check and Balance
        
        if ( empty($productColumnList) ) {
            # Holder for data
            $tmpData = array();
            # Getting Default Fields
            require_once ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php';
            $product_list_table = new WP_Posts_List_Table( array(
                'screen' => 'edit-product',
            ) );
            # Processing the data
            if ( !empty($product_list_table->get_column_info()[0]) ) {
                foreach ( $product_list_table->get_column_info()[0] as $key => $value ) {
                    
                    if ( $key != 'cb' ) {
                        $tmpData['name'] = wp_kses_post( $key );
                        $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                        $tmpData['width'] = "";
                        $tmpData['type'] = "default";
                        $tmpData['status'] = true;
                        # Populating the data
                        $productColumnList[] = $tmpData;
                    }
                
                }
            }
            # Processing the WPTEC fields
            if ( !empty($this->common->products_tab_extra_columns) ) {
                foreach ( $this->common->products_tab_extra_columns as $key => $value ) {
                    $tmpData['name'] = wp_kses_post( $key );
                    $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                    $tmpData['width'] = "";
                    $tmpData['type'] = "wptec";
                    $tmpData['status'] = false;
                    # Populating the data
                    $productColumnList[] = $tmpData;
                }
            }
            # Json encoding
            $productColumnList = json_encode( $productColumnList, TRUE );
            # Changing status from
            $columnListFrom["product"] = false;
        } else {
            # escape HTML
            foreach ( $productColumnList as $rowKey => $rowArray ) {
                # now escape every array item
                foreach ( $rowArray as $itemKey => $itemValue ) {
                    $productColumnList[$rowKey][wp_kses_post( $itemKey )] = wp_kses_post( $itemValue );
                }
            }
            # converting to JSON
            $productColumnList = json_encode( $productColumnList, TRUE );
        }
        
        # ORDER
        $orderColumnList = get_user_option( 'wptec_order', get_current_user_id() );
        # Check and Balance
        
        if ( empty($orderColumnList) ) {
            # Holder for data
            $tmpData = array();
            # Getting Default Fields
            require_once ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php';
            $order_list_table = new WP_Posts_List_Table( array(
                'screen' => 'edit-shop_order',
            ) );
            # Processing the data
            if ( !empty($order_list_table->get_column_info()[0]) ) {
                foreach ( $order_list_table->get_column_info()[0] as $key => $value ) {
                    
                    if ( $key != 'cb' ) {
                        $tmpData['name'] = wp_kses_post( $key );
                        $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                        $tmpData['width'] = "";
                        $tmpData['type'] = "default";
                        $tmpData['status'] = true;
                        # Populating the data
                        $orderColumnList[] = $tmpData;
                    }
                
                }
            }
            # Processing the WPTEC fields
            foreach ( $this->common->orders_tab_extra_columns as $key => $value ) {
                $tmpData['name'] = wp_kses_post( $key );
                $tmpData['title'] = ( empty($value) ? wp_kses_post( $key ) : wp_kses_post( $value ) );
                $tmpData['width'] = "";
                $tmpData['type'] = "wptec";
                $tmpData['status'] = false;
                # Populating the data
                $orderColumnList[] = $tmpData;
            }
            # Json encoding
            $orderColumnList = json_encode( $orderColumnList, TRUE );
            # Changing status from
            $columnListFrom["order"] = false;
        } else {
            # escape HTML
            foreach ( $orderColumnList as $rowKey => $rowArray ) {
                # now escape every array item
                foreach ( $rowArray as $itemKey => $itemValue ) {
                    $orderColumnList[$rowKey][wp_kses_post( $itemKey )] = wp_kses_post( $itemValue );
                }
            }
            # converting to JSON
            $orderColumnList = json_encode( $orderColumnList, TRUE );
        }
        
        # Escaping $columnListFrom Boolean array || this array will output on JS
        foreach ( $columnListFrom as $tableName => $tableBoolValue ) {
            $columnListFrom[esc_attr( $tableName )] = esc_attr( $tableBoolValue );
            // name and TRUE / FALSE value
        }
        # Sending data to the Frontend in JSON formate
        ?>
			<script type="text/javascript">
				var wptecUserList    =  <?php 
        echo  $userColumnList ;
        ?>;
				var wptecPostList    = 	<?php 
        echo  $postColumnList ;
        ?>;
				var wptecPageList    =  <?php 
        echo  $pageColumnList ;
        ?>;
				var wptecCommentList = 	<?php 
        echo  $commentColumnList ;
        ?>;
				var wptecMediaList 	 = 	<?php 
        echo  $mediaColumnList ;
        ?>;
				var wptecProductList = 	<?php 
        echo  $productColumnList ;
        ?>;
				var wptecOrderList 	 = 	<?php 
        echo  $orderColumnList ;
        ?>;
				//  data display from array() from database or table
				var columnListFrom   = 	<?php 
        echo  json_encode( $columnListFrom ) ;
        ?>;
			</script>
		<?php 
        # getting action
        $action = ( isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '' );
        # Plugin URL routing
        
        if ( $action == 'log' ) {
            # Display Log function
            $this->wptec_log();
        } elseif ( $action == 'measurement' and isset( $_GET['value'] ) ) {
            # setting option value
            
            if ( $_GET['value'] == 'Pixel' ) {
                # Updating user option
                update_user_option( get_current_user_id(), 'wptec_measurement', 'Pixel' );
                # keeping the log
                $this->common->wptec_log(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: table column with measurement unit set to Pixel !"
                );
            } else {
                # Updating user option
                update_user_option( get_current_user_id(), 'wptec_measurement', 'Percent' );
                # keeping the log
                $this->common->wptec_log(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: table column with measurement unit set to Percent !"
                );
            }
            
            # redirect user to same page.
            wp_redirect( admin_url( '/admin.php?page=wptec&rms=done' ) );
        } else {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wptec-admin-display.php';
        }
        
        //  This function ends here.
    }
    
    /**
     * Admin menu init.
     * @since    1.0.0
     * @param    string $value The value.
     * Admin tables extra columns.
     */
    public function wptec_log()
    {
        echo  "<div class='wrap'>" ;
        # Displaying message
        echo  "<h3 class='wp-heading-inline'>  Log Page " ;
        echo  "<code>Last 200 log</code> &#32;&#32; " ;
        echo  "<code>V" . esc_html( $this->version ) . "</code> &#32;&#32; " ;
        echo  "</h3>" ;
        # getting log post
        $wptec_log = get_posts( array(
            'post_type'      => 'wptec_log',
            'order'          => 'DESC',
            'posts_per_page' => -1,
        ) );
        # Counter
        $i = 1;
        # Looping log posts
        foreach ( $wptec_log as $key => $log ) {
            $post_excerpt = json_decode( $log->post_excerpt );
            
            if ( $log->post_title == 200 ) {
                echo  "<div class='notice notice-success inline'>" ;
            } else {
                echo  "<div class='notice notice-error inline'>" ;
            }
            
            echo  "<p><span class='wpgsi-circle'>" . esc_html( $log->ID ) ;
            echo  " .</span>" ;
            echo  "<code>" . esc_html( $log->post_title ) . "</code>" ;
            echo  "<code>" ;
            if ( isset( $post_excerpt->file_name, $post_excerpt->function_name ) ) {
                echo  esc_html( $post_excerpt->file_name ) . " | " . esc_html( $post_excerpt->function_name ) ;
            }
            echo  "</code>" ;
            echo  esc_html( $log->post_content ) ;
            echo  " <code>" . esc_html( $log->post_date ) . "</code>" ;
            echo  "</p>" ;
            echo  "</div>" ;
            $i++;
        }
        # done!
        echo  "</div>" ;
    }
    
    /**
     * AJAX Handler. Save table information to the DB
     * This method will Save Admin Settings Page Actions 
     * @since    1.0.0
     */
    public function wptecAdminAJAX()
    {
        # Nonce Check
        
        if ( !wp_verify_nonce( $_POST['security'], 'wptec-ajax-nonce' ) ) {
            # return for js script
            echo  '[false,"ERROR: invalid nonce!"]' ;
            # keeping the log
            $this->common->wptec_log(
                get_class( $this ),
                __METHOD__,
                "300",
                "ERROR: invalid nonce !"
            );
            # die
            die;
        }
        
        # ERROR : get_current_user_id is empty;
        
        if ( empty(get_current_user_id()) ) {
            # return for js script
            echo  '[false,"ERROR: No current user ID."]' ;
            # keeping the log
            $this->common->wptec_log(
                get_class( $this ),
                __METHOD__,
                "300",
                "ERROR: No current user ID."
            );
            # die
            die;
        }
        
        # check to see is table exist
        
        if ( !isset( $_POST['tableName'] ) or empty($_POST['tableName']) ) {
            # return for js script
            echo  '[false,"ERROR: There is no associated table !"]' ;
            # keeping the log
            $this->common->wptec_log(
                get_class( $this ),
                __METHOD__,
                "300",
                "ERROR: There is no associated table !"
            );
            # die
            die;
        }
        
        # if event is resetDefault
        
        if ( $_POST['EventName'] == 'resetDefault' and in_array( $_POST['tableName'], array(
            'user',
            'post',
            'page',
            'media',
            'comment',
            'product',
            'order'
        ) ) ) {
            # Updating aka emptying certain tab option data
            delete_user_option( get_current_user_id(), "wptec_" . sanitize_text_field( $_POST['tableName'] ) );
            # keeping the log
            $this->common->wptec_log(
                get_class( $this ),
                __METHOD__,
                "200",
                "SUCCESS: table [ wptec_" . sanitize_text_field( $_POST['tableName'] ) . "] is restore to default. saved value removed from user ID: " . sanitize_text_field( get_current_user_id() ) . " options."
            );
            # return for JS
            echo  json_encode( array( true, "SUCCESS: Successfully reset table." ) ) ;
            # ----\----
            # die
            die;
        }
        
        # if event is resetDefaultAllTables
        
        if ( $_POST['EventName'] == 'resetDefaultAll' and in_array( $_POST['tableName'], array(
            'user',
            'post',
            'page',
            'media',
            'comment',
            'product',
            'order'
        ) ) ) {
            #deleting tables
            delete_user_option( get_current_user_id(), "wptec_user" );
            delete_user_option( get_current_user_id(), "wptec_post" );
            delete_user_option( get_current_user_id(), "wptec_page" );
            delete_user_option( get_current_user_id(), "wptec_media" );
            delete_user_option( get_current_user_id(), "wptec_comment" );
            delete_user_option( get_current_user_id(), "wptec_product" );
            delete_user_option( get_current_user_id(), "wptec_order" );
            # keeping the log
            $this->common->wptec_log(
                get_class( $this ),
                __METHOD__,
                "200",
                "SUCCESS: All tables are reset Default,  user ID: " . sanitize_text_field( get_current_user_id() ) . " options."
            );
            # return for JS
            echo  '[true,"SUCCESS: Successfully reset ALL tables."]' ;
            # die
            die;
        }
        
        # check to see is table exist
        
        if ( !isset( $_POST['data'] ) or empty($_POST['data']) ) {
            # return for js script
            echo  '[false,"ERROR: There is no table data !"]' ;
            # keeping the log
            $this->common->wptec_log(
                get_class( $this ),
                __METHOD__,
                "300",
                "ERROR: There is no table data !"
            );
            # die
            die;
        }
        
        # if event is edit;
        
        if ( $_POST['EventName'] == 'save' and in_array( $_POST['tableName'], array(
            'user',
            'post',
            'page',
            'media',
            'comment',
            'product',
            'order'
        ) ) ) {
            # getting table column list
            $data = @json_decode( stripslashes( $_POST['data'] ), TRUE );
            # if JSON Compiled SUCCESSfully
            
            if ( is_array( $data ) and !empty($data) ) {
                $sanitizeData = array();
                # Processing data
                foreach ( $data as $key => $colArray ) {
                    $sanitizeData[] = array(
                        "name"   => wp_kses_post( $colArray["name"] ),
                        "title"  => wp_kses_post( $colArray["title"] ),
                        "width"  => sanitize_text_field( $colArray["width"] ),
                        "type"   => sanitize_text_field( $colArray["type"] ),
                        "status" => ( $colArray["status"] ? true : false ),
                    );
                }
                # updating
                update_user_option( get_current_user_id(), "wptec_" . sanitize_text_field( $_POST['tableName'] ), $sanitizeData );
                # keeping the log on database
                $this->common->wptec_log(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: Done from the edit save! table name is : " . 'wptec_' . sanitize_text_field( $_POST['tableName'] )
                );
                # return for js script
                echo  '[true,"SUCCESS: saved on user option"]' ;
                # die
                die;
            } else {
                # return for js script
                echo  '[false,"ERROR: json_decode is not working!"]' ;
                # keeping the log
                $this->common->wptec_log(
                    get_class( $this ),
                    __METHOD__,
                    "300",
                    "ERROR: json_decode is not working!"
                );
                # die
                die;
            }
        
        }
        
        # ultimate you will  die. no way to Escape it man.
        die;
    }
    
    /**
     * Footer function || this is a Function for content editable;
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_adminFooter()
    {
        $columnData = array();
        $status = false;
        $measurementUnit = get_user_option( 'wptec_measurement', get_current_user_id() );
        
        if ( get_current_screen()->id == "users" ) {
            # user table saved data
            $columnData = get_user_option( 'wptec_user', get_current_user_id() );
            if ( !empty($columnData) ) {
                $status = true;
            }
        } elseif ( get_current_screen()->id == "edit-post" ) {
            # Post table saved data
            $columnData = get_user_option( 'wptec_post', get_current_user_id() );
            if ( !empty($columnData) ) {
                $status = true;
            }
        } elseif ( get_current_screen()->id == "edit-page" ) {
            # User table saved data
            $columnData = get_user_option( 'wptec_page', get_current_user_id() );
            if ( !empty($columnData) ) {
                $status = true;
            }
        } elseif ( get_current_screen()->id == "edit-comments" ) {
            # User table saved data
            $columnData = get_user_option( 'wptec_comment', get_current_user_id() );
            if ( !empty($columnData) ) {
                $status = true;
            }
        } elseif ( get_current_screen()->id == "upload" ) {
            # Media table saved data
            $columnData = get_user_option( 'wptec_media', get_current_user_id() );
            if ( !empty($columnData) ) {
                $status = true;
            }
        } elseif ( get_current_screen()->id == "edit-product" ) {
            # User table saved data
            $columnData = get_user_option( 'wptec_product', get_current_user_id() );
            if ( !empty($columnData) ) {
                $status = true;
            }
        } elseif ( get_current_screen()->id == "edit-shop_order" ) {
            # Order table saved data
            $columnData = get_user_option( 'wptec_order', get_current_user_id() );
            if ( !empty($columnData) ) {
                $status = true;
            }
        } elseif ( get_current_screen()->id == "toplevel_page_wptec" ) {
            # Its the Settings Page
        } else {
            # Not our Page.
        }
        
        # if status is TRUE AND column data array is not empty;
        
        if ( $status and !empty($columnData) ) {
            # escape / sanitize $columnData array with esc_html() function, that i got data from user options.
            foreach ( $columnData as $key => $value ) {
                $columnData[$key]['name'] = ( (isset( $value['name'] ) and !empty($value['name'])) ? esc_html( $value['name'] ) : "" );
                $columnData[$key]['title'] = ( (isset( $value['name'] ) and !empty($value['name'])) ? esc_html( $value['title'] ) : "" );
                $columnData[$key]['width'] = ( (isset( $value['name'] ) and !empty($value['name'])) ? esc_html( $value['width'] ) : "" );
                $columnData[$key]['type'] = ( (isset( $value['name'] ) and !empty($value['name'])) ? esc_html( $value['type'] ) : "" );
                $columnData[$key]['status'] = ( (isset( $value['name'] ) and !empty($value['name'])) ? esc_html( $value['status'] ) : "" );
            }
            # outputting on table footer
            ?>
				<script>
					if("<?php 
            echo  $status ;
            ?>") {
						// Parsing the Data 
						var data = JSON.parse('<?php 
            echo  @json_encode( $columnData ) ;
            ?>');
						// Looping the list 
						if(data){
							for(var key in data){
								// if status is true AND width is not empty 
								if(data[key].status && data[key].width){
									if( "<?php 
            echo  esc_html( $measurementUnit ) ;
            ?>" == "Percent"){
										document.getElementById(data[key].name).style.width = data[key].width + "%";
									}else{
										document.getElementById(data[key].name).style.width = data[key].width + "px";
									}			
								}
							}	
						} else {
							//  error check;
						}
					}
				</script>
			<?php 
            # job done, Now unset the column data array to Free the memory
            unset( $columnData );
        }
    
    }

}
# Thank you for your patient . if you have any question Please let me know !
# Please enclose the code Snippets with your question .
# I am Looking for a Job If you Have One Please let me Know , Thank you so Much.