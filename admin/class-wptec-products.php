<?php

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wptec
 * @subpackage Wptec/admin
 * @author     javmah <jaedmah@gmail.com>
 */
class Wptec_Products
{
    /**
     * The ID of this plugin.
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
     * list of woocommerce wptec extra fields 
     * @since    1.0.0
     * @access   private
     * @array    extra fields key name array 
     */
    private  $products_tab_extra_columns = array() ;
    /**
     * list of woocommerce wptec extra fields 
     * @since    1.0.0
     * @access   private
     * @array    extra fields key name array 
     */
    private  $wptec_wooCommerce_product_metaKeys = array() ;
    /**
     * Content editable, for Professional code 
     * @since    1.0.0
     * @access   private 
     * @array    extra fields key name array 
     */
    private  $wptec_editable = false ;
    /**
     * Initialize the class __construct and set its properties.
     * @since      1.0.0
     * @param      string    $plugin_name   The name of this plugin.
     * @param      string    $version    	The version of this plugin.
     * @param      string    $common    	common class object 
     */
    public function __construct( $plugin_name, $version, $common )
    {
        # Plugin name.
        $this->plugin_name = $plugin_name;
        # Plugin version.
        $this->version = $version;
        # Plugin common reused properties and method class.
        $this->common = $common;
        # user table extra fields
        $this->products_tab_extra_columns = $common->products_tab_extra_columns;
        # product table meta fields
        $this->wptec_wooCommerce_product_metaKeys = ( $common->wptec_wooCommerce_product_metaKeys()[0] ? $common->wptec_wooCommerce_product_metaKeys()[1] : array() );
    }
    
    /**
     * Initialize the class and set its properties.
     * @since    1.0.0
     * @param    string    $value    The name of this plugin.
     */
    public function wptec_product_admin_notices()
    {
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * Initialize the class and set its properties.
     * @since    1.0.0
     * @param    string    $value    The name of this plugin.
     */
    public function wptec_product_table_manage_posts( $value = '' )
    {
        if ( is_admin() and get_current_screen()->id == 'edit-product' ) {
            # Adding Download Button
            echo  "<span class='button wptecDownload' id='CSVdownloadButton' data-action='wptec_productsCSV' style='text-decoration: none; margin-left: 5px;'>  Export Products CSV </span>" ;
        }
    }
    
    /**
     * Initialize the class and set its properties.
     * @since      1.0.0
     * @param      string    $columns       The name of the columns.
     */
    public function wptec_product_columns( $columns )
    {
        #user table saved data
        $product_table = get_user_option( 'wptec_product', get_current_user_id() );
        # check and Balance
        
        if ( !empty($product_table) ) {
            # unseating all columns
            unset( $columns );
            $columns['cb'] = 'select all';
            # Looping the column list
            foreach ( $product_table as $key => $columnArray ) {
                if ( isset( $columnArray['name'], $columnArray['title'], $columnArray['status'] ) ) {
                    # is status is true set the column
                    if ( $columnArray['status'] ) {
                        $columns[$columnArray['name']] = __( $columnArray['title'], "wptec" );
                    }
                }
            }
            # insert loop ends
        }
        
        # returning Column
        return $columns;
    }
    
    /**
     * Initialize the class and set its properties.
     *
     * @since      1.0.0
     * @param      string    $column_name    The name of this plugin.
     * @param      string    $product_ID     The version of this plugin.
     */
    public function wptec_product_columns_content( $column, $product_id )
    {
        # lock the display page
        if ( get_current_screen()->id != "edit-product" ) {
            return;
        }
        # getting product information
        $product = wc_get_product( $product_id );
        # for Product id display
        
        if ( $column == 'wptec_product_id' ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $product_id ) ;
            echo  "</span>" ;
        }
        
        # for Product status display
        
        if ( $column == 'wptec_product_status' and method_exists( $product, 'get_status' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $product_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<select name='" . esc_attr( $column ) . "' id='" . esc_attr( $product_id ) . esc_attr( $column ) . "'>" ;
            echo  ( $product->get_status() == 'publish' ? "<option value='publish' selected >publish</option>" : "<option value='publish'>publish</option>" ) ;
            echo  ( $product->get_status() == 'pending' ? "<option value='pending' selected >pending</option>" : "<option value='pending'>pending</option>" ) ;
            echo  ( $product->get_status() == 'draft' ? "<option value='draft' selected >draft</option>" : "<option value='draft'>draft</option>" ) ;
            echo  "</select>" ;
            # action button
            echo  "<button type='button' data-from='product' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='product' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $product->get_status() ) ;
            echo  "</span>" ;
        }
        
        # for product weight Display
        
        if ( $column == 'wptec_product_weight' and method_exists( $product, 'get_weight' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $product_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='number' name='" . esc_attr( $column ) . "' id='" . esc_attr( $product_id ) . esc_attr( $column ) . "' value='" . esc_attr( $product->get_weight() ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='product' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='product' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $product->get_weight() ) ;
            echo  "</span>" ;
        }
        
        # foe Product length display
        
        if ( $column == 'wptec_product_length' and method_exists( $product, 'get_length' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $product_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='number' name='" . esc_attr( $column ) . "' id='" . esc_attr( $product_id ) . esc_attr( $column ) . "' value='" . esc_attr( $product->get_length() ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='product' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='product' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $product->get_length() ) ;
            echo  "</span>" ;
        }
        
        # for product Width display
        
        if ( $column == 'wptec_product_width' and method_exists( $product, 'get_width' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $product_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='number' name='" . esc_attr( $column ) . "' id='" . esc_attr( $product_id ) . esc_attr( $column ) . "' value='" . esc_attr( $product->get_width() ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='product' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='product' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $product->get_width() ) ;
            echo  "</span>" ;
        }
        
        # for product Height display
        
        if ( $column == 'wptec_product_height' and method_exists( $product, 'get_height' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $product_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='number' name='" . esc_attr( $column ) . "' id='" . esc_attr( $product_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $product->get_height() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='product' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='product' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $product->get_height() ) ;
            echo  "</span>" ;
        }
        
        #For product virtual or Physical status Display
        
        if ( $column == 'wptec_product_virtual' and method_exists( $product, 'get_virtual' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $product_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<select id='" . esc_attr( $product_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' >" ;
            
            if ( $product->get_virtual() ) {
                echo  "<option value='yes' selected>  virtual   </option>" ;
                echo  "<option value='no'>            physical  </option>" ;
            } else {
                echo  "<option value='yes' >   \t     virtual   </option>" ;
                echo  "<option value='no' selected>   physical  </option>" ;
            }
            
            echo  "</select>" ;
            # action button
            echo  "<button type='button' data-from='product' class='yes'><span class='dashicons dashicons-yes'  >   </span>   </button>" ;
            echo  "<button type='button' data-from='product' class='no'><span class='dashicons dashicons-no-alt'>   </span>   </button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  ( $product->get_virtual() ? "virtual" : "physical" ) ;
            echo  "</span>" ;
        }
        
        # For product tex status Display
        
        if ( $column == 'wptec_product_tax_status' and method_exists( $product, 'get_tax_status' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $product_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<select name='" . esc_attr( $column ) . "' id='" . esc_attr( $product_id ) . esc_attr( $column ) . "'>" ;
            echo  ( $product->get_tax_status() == "none" ? "<option value='none' selected>none</option>" : "<option value='none'>none</option>" ) ;
            echo  ( $product->get_tax_status() == "taxable" ? "<option value='taxable' selected>taxable</option>" : "<option value='none'>taxable</option>" ) ;
            echo  ( $product->get_tax_status() == "shipping" ? "<option value='shipping' selected>shipping</option>" : "<option value='none'>shipping</option>" ) ;
            echo  "</select>" ;
            # action button
            echo  "<button type='button' data-from='product' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='product' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $product->get_tax_status() ) ;
            echo  "</span>" ;
        }
        
        # for Product average rating display
        
        if ( $column == 'wptec_product_average_rating' and method_exists( $product, 'get_average_rating' ) ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $product->get_average_rating() ) ;
            echo  "</span>" ;
        }
        
        # for Product review count display
        
        if ( $column == 'wptec_product_review_count' and method_exists( $product, 'get_review_count' ) ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $product->get_review_count() ) ;
            echo  "</span>" ;
        }
    
    }
    
    /**
     * Below is a Call back function of [manage_users_sortable_columns] 
     * This Function will Make column sortable 
     * @since    1.0.0
     * @param    string    $columns The name of this plugin.
     */
    public function wptec_product_sortable_columns( $columns )
    {
        # wptec
        $extra_columns = $this->products_tab_extra_columns;
        # creating wptec list to sortable
        foreach ( $extra_columns as $key => $value ) {
            $columns[$key] = $key;
        }
        # return the columns
        return $columns;
    }
    
    /**
     * Below is a Call back function of [pre_get_users] 
     * This function will set orderby and order query parameters in SQL 
     * @since    1.0.0
     * @param    string    $user_query user Query .
     */
    public function wptec_product_sortable_columns_query( $query )
    {
        # check isset or not
        
        if ( isset( $query->query_vars['orderby'], $query->query_vars['order'] ) ) {
            # getting order by
            $orderby = sanitize_sql_orderby( $query->query_vars['orderby'] );
            # getting ascending and descending value
            $order = ( $query->query_vars['order'] == 'asc' ? 'ASC' : 'DESC' );
            # looking for the keys
            switch ( $orderby ) {
                case 'wptec_product_id':
                    break;
                case 'wptec_product_status':
                    break;
                case 'wptec_product_weight':
                    break;
                case 'wptec_product_length':
                    break;
                case 'wptec_product_width':
                    break;
                case 'wptec_product_height':
                    break;
                case 'wptec_product_virtual':
                    break;
                case 'wptec_product_tax_status':
                    break;
                case 'wptec_product_average_rating':
                    break;
                case 'wptec_product_review_count':
                    break;
                default:
                    # default action
            }
        }
        
        # return
        return $query;
    }
    
    /**
     * This Function will return [WooCommerce product] Meta keys.
     * @since      1.0.0
     * @return     array This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_productAJAX()
    {
        # exit the AJAX
        exit;
    }
    
    /**
     * Update post information from admin user table via AJAX
     * @since      1.0.0
     * @return     array This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_productsCSV()
    {
        
        if ( is_user_logged_in() and current_user_can( 'administrator' ) ) {
            #------------------------------------------------------------------------------------------------------------
            # declaring empty holder
            $columnList = array();
            # This will be the First Row of the CSV sheet
            $columnListFirstRow = array();
            # getting the Saved options of User Table.
            $saved_column = get_user_option( 'wptec_product', get_current_user_id() );
            ####### done ###########
            # testing is from saved or from default
            
            if ( $saved_column ) {
                # Convert user table JSON data into array if
                $tmp = @json_decode( $saved_column, TRUE );
                if ( is_array( $tmp ) and !empty($tmp) ) {
                    $columnList = $tmp;
                }
                # unsettling the Temp
                unset( $tmp );
            } else {
                # setting the default column
                foreach ( $this->common->products_tab_default_columns as $key => $value ) {
                    $columnList[] = array(
                        "name"   => $key,
                        "title"  => $value,
                        "width"  => "",
                        "type"   => "default",
                        "status" => 1,
                    );
                }
            }
            
            # User table hidden Columns.
            $hiddenColumns = get_user_meta( get_current_user_id(), 'manageedit-productcolumnshidden', true );
            ####### half done ###########
            # removing Hidden columns from column list.
            if ( is_array( $hiddenColumns ) and !empty($hiddenColumns) ) {
                # looping Hidden column.
                foreach ( $hiddenColumns as $value ) {
                    # looping
                    foreach ( $columnList as $key => $columnArray ) {
                        if ( $columnArray['name'] == $value ) {
                            unset( $columnList[$key] );
                        }
                    }
                }
            }
            #-----------------------------------------------------------------------------------------------
            # getting pagination numbers
            $paginationNumber = get_user_option( 'wptec_product_pagination', get_current_user_id() );
            ####### Change this line ###########
            # if no pagination number then set 0 ; also redeeming existing directory
            
            if ( !$paginationNumber ) {
                # base URL of the Upload directory
                $upload_basedir = wp_upload_dir()['basedir'];
                # Creating a Directory if not exist
                
                if ( file_exists( $upload_basedir . "/wptec/download.csv" ) ) {
                    # redeeming existing CSV directory
                    unlink( $upload_basedir . "/wptec/download.csv" );
                    # Deleting old file
                    $this->common->wptec_log(
                        get_class( $this ),
                        __METHOD__,
                        "200",
                        "SUCCESS: Deleting old file."
                    );
                }
                
                # setting pagination initial value
                $paginationNumber = 0;
            }
            
            # setting offset || offset is 20 row at a time
            $offset = $paginationNumber * 23;
            # Getting User list Query;
            global  $wpdb ;
            # Creating Query string
            # running query and getting data from database; // $wpdb->
            $result = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'product' LIMIT 23 OFFSET " . $offset, ARRAY_A );
            ####### Change this line ###########
            # if result is Empty that means you are at the end of your database row;
            
            if ( empty($result) ) {
                # Deleting [wptec_user_page_no] options
                delete_user_option( get_current_user_id(), 'wptec_product_pagination' );
                ####### Change this line ###########
                # return done
                echo  '[true,"wptec_post_csv_status is done !"]' ;
                # keeping the logs
                $this->common->wptec_log(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: wptec_user_csv_status is done !"
                );
                # close the PHP
                die;
            }
            
            #--------------------------------------------------------------------------------------------------
            # Row placeholder resting every time
            $rows = array();
            # looping the result
            foreach ( $result as $rowKey => $rowArray ) {
                # looping column list
                foreach ( $columnList as $colArray ) {
                    # Also removing disabled colum.
                    
                    if ( $colArray['status'] ) {
                        # removing 3rd party columns
                        if ( isset( array_merge( $this->common->products_tab_default_columns, $this->products_tab_extra_columns, $this->wptec_wooCommerce_product_metaKeys )[$colArray['name']] ) ) {
                            # populating $columnListFirstRow array() || CSV first row
                            $columnListFirstRow[$colArray['name']] = $colArray['title'];
                        }
                        # getting Product
                        # -------------------------------- Default columns ----------------------------------------
                        # Product name
                        if ( $colArray['name'] == "name" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_title'] ) ? $rowArray['post_title'] : "" );
                        }
                        # Product SKU
                        if ( $colArray['name'] == "sku" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_sku', true ) : "" );
                        }
                        # Product in Stock
                        if ( $colArray['name'] == "is_in_stock" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_stock_status', true ) : "" );
                        }
                        # Product Price
                        if ( $colArray['name'] == "price" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_price', true ) : "" );
                        }
                        # product Catagories
                        if ( $colArray['name'] == "product_cat" ) {
                            $rows[$rowKey][$colArray['name']] = '--@--';
                        }
                        # Product Tags
                        if ( $colArray['name'] == "product_tag" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['_____'] ) ? $rowArray['_____'] : "" );
                        }
                        # Is product is Featured
                        if ( $colArray['name'] == "featured" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['_____'] ) ? $rowArray['_____'] : "" );
                        }
                        # date
                        if ( $colArray['name'] == "date" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_date'] ) ? $rowArray['post_date'] : "" );
                        }
                        # --------------------------------- WPTEC columns ---------------------------------------
                        # product ID
                        if ( $colArray['name'] == "wptec_product_id" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? $rowArray['ID'] : "" );
                        }
                        # product Status
                        if ( $colArray['name'] == "wptec_product_status" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_status'] ) ? $rowArray['post_status'] : "" );
                        }
                        # Product Weight
                        if ( $colArray['name'] == "wptec_product_weight" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_weight', true ) : "" );
                        }
                        # product Length
                        if ( $colArray['name'] == "wptec_product_length" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_length', true ) : "" );
                        }
                        # Product Width
                        if ( $colArray['name'] == "wptec_product_width" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_width', true ) : "" );
                        }
                        # product Height
                        if ( $colArray['name'] == "wptec_product_height" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_height', true ) : "" );
                        }
                        # Is product Virtual
                        if ( $colArray['name'] == "wptec_product_virtual" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_virtual', true ) : "" );
                        }
                        # product Tex Status
                        if ( $colArray['name'] == "wptec_product_tax_status" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_tax_status', true ) : "" );
                        }
                        # product Rating
                        if ( $colArray['name'] == "wptec_product_average_rating" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? get_post_meta( $rowArray['ID'], '_wc_average_rating', true ) : "" );
                        }
                        # product review Count
                        
                        if ( $colArray['name'] == "wptec_product_review_count" ) {
                            # getting all reviews of this Product
                            $comments = get_comments( array(
                                'post_id' => $rowArray['ID'],
                            ) );
                            # counting and inserting the counted value in the review
                            $rows[$rowKey][$colArray['name']] = count( $comments );
                        }
                        
                        # --------------------------------- META columns ---------------------------------------
                        
                        if ( $colArray['type'] == "meta" ) {
                            $metaData = get_post_meta( $rowArray['ID'], $colArray['name'], true );
                            $rows[$rowKey][$colArray['name']] = ( is_array( $metaData ) ? json_encode( $metaData ) : $metaData );
                        }
                    
                    }
                
                }
            }
            #------------------------------------------------------------------------------------------------------------
            # base URL of the Upload directory
            $upload_basedir = wp_upload_dir()['basedir'];
            # Creating a Directory if not exist
            if ( !is_dir( $upload_basedir . "/wptec" ) ) {
                # creating a directory
                mkdir( $upload_basedir . "/wptec", 0777 );
            }
            # Opening file if there is a file or Just create a New File;
            $file = fopen( $upload_basedir . "/wptec/download.csv", "a" ) or die( "Unable to open file !" );
            # loop counting
            $i = 0;
            # inserting data to the File
            foreach ( $rows as $rowArray ) {
                # writing first row
                if ( $i == 0 and $paginationNumber == 0 ) {
                    fputcsv( $file, $columnListFirstRow );
                }
                # writing other rows
                fputcsv( $file, $rowArray );
                #
                $i++;
            }
            # closing the file
            fclose( $file );
            # increment the Pagination number
            $paginationNumber++;
            # set the Pagination number to user [wptec_user_page_no] option
            update_user_option( get_current_user_id(), 'wptec_product_pagination', $paginationNumber );
            ####### Change this line ###########
            # keeping the log
            $this->common->wptec_log(
                get_class( $this ),
                __METHOD__,
                "200",
                "Pagination increment ++: " . $paginationNumber
            );
            # Returning
            echo  '[false, "wptec_post_csv_status is working ! don\'t change the page."]' ;
            die;
        }
    
    }

}