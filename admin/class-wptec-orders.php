<?php

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 * 
 * @package    Wptec
 * @subpackage Wptec/admin
 * @author     javmah <jaedmah@gmail.com>
 * @since      1.0.0
*/
class Wptec_Orders
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
     * list of wooCommerce order wptec extra fields 
     * @since    1.0.0
     * @access   private
     * @array    extra fields key name array 
     */
    private  $orders_tab_extra_columns = array() ;
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
        $this->orders_tab_extra_columns = $common->orders_tab_extra_columns;
        # user table meta fields
        $this->wptec_wooCommerce_order_metaKeys = ( $common->wptec_wooCommerce_order_metaKeys()[0] ? $common->wptec_wooCommerce_order_metaKeys()[1] : array() );
    }
    
    public function wptec_order_admin_notices()
    {
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * Initialize the class and set its properties.
     * @since    1.0.0
     * @param    string    $plugin_name   The name of this plugin.
     * @param    string    $version       The version of this plugin.
     */
    public function wptec_orders_table_manage_posts( $value = '' )
    {
        if ( is_admin() and get_current_screen()->id == 'edit-shop_order' ) {
            # Adding Link Download Button
            echo  "<span class='button wptecDownload' id='CSVdownloadButton' data-action='wptec_ordersCSV' style='text-decoration: none; margin-left: 5px;'> Export Orders CSV </span>" ;
        }
    }
    
    /**
     * wptec_user.
     * @since    1.0.0
     * @param    string    $plugin_name   The name of this plugin.
     * @param    string    $version    	  The version of this plugin.
     */
    public function wptec_order_columns( $columns )
    {
        #Order table saved data
        $order_table = get_user_option( 'wptec_order', get_current_user_id() );
        # check and Balance
        
        if ( !empty($order_table) ) {
            # unseating all column
            unset( $columns );
            $columns['cb'] = 'select all';
            # Looping the column list
            foreach ( $order_table as $key => $columnArray ) {
                if ( isset( $columnArray['name'], $columnArray['title'], $columnArray['status'] ) ) {
                    # is status is true set the column
                    if ( $columnArray['status'] ) {
                        $columns[$columnArray['name']] = __( $columnArray['title'], "wptec" );
                    }
                }
            }
            # insert loop ends
        }
        
        # returning Column s
        return $columns;
    }
    
    /**
     * Initialize the class and set its properties.
     * @since      1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function wptec_order_columns_content( $column, $order_id )
    {
        # lock the display page
        if ( get_current_screen()->id != "edit-shop_order" ) {
            return;
        }
        # Getting Order Information
        $order = wc_get_order( $order_id );
        # Order ID column
        
        if ( $column == 'wptec_order_id' ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $order_id ) ;
            echo  "</span>" ;
        }
        
        # order_user_name
        if ( $column == 'wptec_order_user_name' and method_exists( $order, 'get_user' ) ) {
            
            if ( isset( $order->get_user()->user_nicename ) and $order->get_user()->user_nicename ) {
                # Display span Starts
                echo  "<span  class='wptecDisplay'>" ;
                echo  esc_html( $order->get_user()->user_nicename ) ;
                echo  "</span>" ;
            }
        
        }
        # billing first name
        
        if ( $column == 'wptec_order_billing_first_name' and method_exists( $order, 'get_billing_first_name' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "' value='" . esc_attr( $order->get_billing_first_name() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_first_name() ) ;
            echo  "</span>" ;
        }
        
        # billing last name
        
        if ( $column == 'wptec_order_billing_last_name' and method_exists( $order, 'get_billing_last_name' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_billing_last_name() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_last_name() ) ;
            echo  "</span>" ;
        }
        
        # billing company
        
        if ( $column == 'wptec_order_billing_company' and method_exists( $order, 'get_billing_company' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_billing_company() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_company() ) ;
            echo  "</span>" ;
        }
        
        # billing address 1
        
        if ( $column == 'wptec_order_billing_address_1' and method_exists( $order, 'get_billing_address_1' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_billing_address_1() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_address_1() ) ;
            echo  "</span>" ;
        }
        
        # billing address 2
        
        if ( $column == 'wptec_order_billing_address_2' and method_exists( $order, 'get_billing_address_2' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_billing_address_1() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_address_1() ) ;
            echo  "</span>" ;
        }
        
        # billing city
        
        if ( $column == 'wptec_order_billing_city' and method_exists( $order, 'get_billing_city' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_billing_city() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_city() ) ;
            echo  "</span>" ;
        }
        
        # billing state
        
        if ( $column == 'wptec_order_billing_state' and method_exists( $order, 'get_billing_state' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "' value='" . esc_attr( $order->get_billing_state() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_state() ) ;
            echo  "</span>" ;
        }
        
        #billing postcode
        
        if ( $column == 'wptec_order_billing_postcode' and method_exists( $order, 'get_billing_postcode' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "' value='" . esc_attr( $order->get_billing_postcode() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_postcode() ) ;
            echo  "</span>" ;
        }
        
        # billing country
        
        if ( $column == 'wptec_order_billing_country' and method_exists( $order, 'get_billing_country' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_billing_country() ) . "'  class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_country() ) ;
            echo  "</span>" ;
        }
        
        # billing email
        
        if ( $column == 'wptec_order_billing_email' and method_exists( $order, 'get_billing_email' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_billing_email() ) . "'  class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_email() ) ;
            echo  "</span>" ;
        }
        
        # billing phone
        
        if ( $column == 'wptec_order_billing_phone' and method_exists( $order, 'get_billing_phone' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_billing_phone() ) . "'  class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_billing_phone() ) ;
            echo  "</span>" ;
        }
        
        # shipping first name
        
        if ( $column == 'wptec_order_shipping_first_name' and method_exists( $order, 'get_shipping_first_name' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_shipping_first_name() ) . "'  class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_first_name() ) ;
            echo  "</span>" ;
        }
        
        # shipping last name
        
        if ( $column == 'wptec_order_shipping_last_name' and method_exists( $order, 'get_shipping_last_name' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "' value='" . esc_attr( $order->get_shipping_last_name() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_last_name() ) ;
            echo  "</span>" ;
        }
        
        # shipping company
        
        if ( $column == 'wptec_order_shipping_company' and method_exists( $order, 'get_shipping_company' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_shipping_company() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_company() ) ;
            echo  "</span>" ;
        }
        
        # shipping address 1
        
        if ( $column == 'wptec_order_shipping_address_1' and method_exists( $order, 'get_shipping_address_1' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "' value='" . esc_attr( $order->get_shipping_address_1() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_address_1() ) ;
            echo  "</span>" ;
        }
        
        # shipping address 2
        
        if ( $column == 'wptec_order_shipping_address_2' and method_exists( $order, 'get_shipping_address_2' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "' value='" . esc_attr( $order->get_shipping_address_1() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_address_1() ) ;
            echo  "</span>" ;
        }
        
        # shipping city
        
        if ( $column == 'wptec_order_shipping_city' and method_exists( $order, 'get_shipping_city' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_shipping_city() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_city() ) ;
            echo  "</span>" ;
        }
        
        #shipping state
        
        if ( $column == 'wptec_order_shipping_state' and method_exists( $order, 'get_shipping_state' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_shipping_state() ) . "'  class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_state() ) ;
            echo  "</span>" ;
        }
        
        #shipping postcode
        
        if ( $column == 'wptec_order_shipping_postcode' and method_exists( $order, 'get_shipping_postcode' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_shipping_postcode() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_postcode() ) ;
            echo  "</span>" ;
        }
        
        # shipping country
        
        if ( $column == 'wptec_order_shipping_country' and method_exists( $order, 'get_shipping_country' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "' value='" . esc_attr( $order->get_shipping_country() ) . "' class='large-text'>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_country() ) ;
            echo  "</span>" ;
        }
        
        # Order product column
        
        if ( $column == 'wptec_order_products' ) {
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            # Looping Products
            foreach ( $order->get_items() as $value ) {
                echo  "<a href='" . esc_url( add_query_arg( array(
                    'post'   => $value['product_id'],
                    'action' => 'edit',
                ), site_url( '/wp-admin/post.php' ) ) ) . "' >" . esc_html( $value['name'] ) . " * " . esc_html( $value['quantity'] ) . "</a>" ;
                echo  "<br>" ;
            }
            echo  "</span>" ;
        }
        
        # Order currency column
        
        if ( $column == 'wptec_order_currency' and method_exists( $order, 'get_currency' ) ) {
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_currency() ) ;
            echo  "</span>" ;
        }
        
        # Order Shipping total column
        
        if ( $column == 'wptec_order_shipping_total' and method_exists( $order, 'get_shipping_total' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'  value='" . esc_attr( $order->get_shipping_total() ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_shipping_total() ) ;
            echo  "</span>" ;
        }
        
        # Order discount total
        
        if ( $column == 'wptec_order_discount_total' and method_exists( $order, 'get_discount_total' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $order_id ) . esc_attr( $column ) . "' value='" . esc_attr( $order->get_discount_total() ) . "'  class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_discount_total() ) ;
            echo  "</span>" ;
        }
        
        # Order Payment method column
        
        if ( $column == 'wptec_order_payment_method' and method_exists( $order, 'get_payment_method' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            # Getting all payment methods
            $wc_gateways = new WC_Payment_Gateways();
            $payment_gateways = $wc_gateways->get_available_payment_gateways();
            # Looping the list
            echo  "<select name='" . $column . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "'>" ;
            foreach ( $payment_gateways as $gateway_id => $gateway ) {
                
                if ( $gateway->id == $order->get_payment_method() ) {
                    echo  "<option value='" . esc_attr( $gateway->id ) . "' selected>" . esc_attr( $gateway->get_title() ) . "</option>" ;
                } else {
                    echo  "<option value='" . esc_attr( $gateway->id ) . "'>" . esc_attr( $gateway->get_title() ) . "</option>" ;
                }
            
            }
            echo  "</select>" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $order->get_payment_method() ) ;
            echo  "</span>" ;
        }
        
        # Order payment title total
        
        if ( $column == 'wptec_order_payment_method_title' and method_exists( $order, 'get_payment_method_title' ) ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $order_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "' id='" . esc_attr( $order_id ) . esc_attr( $column ) . "' value='" . esc_attr( $order->get_payment_method_title() ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='order' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='order' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $order->get_payment_method_title() ) ;
            echo  "</span>" ;
        }
    
    }
    
    /**
     * Below is a Call back function of [manage_users_sortable_columns] 
     * This Function will Make column sortable 
     * @since    1.0.0
     * @param    string    $columns   The name of this plugin.
     */
    public function wptec_orders_sortable_columns( $columns )
    {
        # wptec
        $extra_columns = $this->orders_tab_extra_columns;
        # creating wptec list to sortable
        foreach ( $extra_columns as $key => $value ) {
            $columns[$key] = $key;
        }
        # return the columns
        return $columns;
    }
    
    /**
     * Below is a Call back function of [pre_get_users] 
     * This function will set orderby and order query parameters in  SQL 
     * @since    1.0.0
     * @param    string    $user_query    user Query .
     */
    public function wptec_orders_sortable_columns_query( $query )
    {
        # check isset or not
        
        if ( isset( $query->query_vars['orderby'], $query->query_vars['order'] ) ) {
            # getting order by
            $orderby = sanitize_sql_orderby( $query->query_vars['orderby'] );
            # getting ascending and descending value
            $order = ( $query->query_vars['order'] == 'asc' ? 'ASC' : 'DESC' );
            # looking for the keys
            switch ( $orderby ) {
                case 'wptec_order_id':
                    $query->query_vars['orderby'] = 'ID';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_order_user_name':
                    break;
                case 'wptec_order_currency':
                    break;
                case 'wptec_order_shipping_total':
                    break;
                case 'wptec_order_discount_total':
                    break;
                case 'wptec_order_payment_method_title':
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
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_orderAJAX()
    {
        # exit the AJAX
        exit;
    }
    
    /**
     * Update post information from admin user table via AJAX
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_ordersCSV()
    {
        
        if ( is_user_logged_in() and current_user_can( 'administrator' ) ) {
            #------------------------------------------------------------------------------------------------------------
            # declaring empty holder
            $columnList = array();
            # This will be the First Row of the CSV sheet
            $columnListFirstRow = array();
            # getting the Saved options of User Table.
            $saved_column = get_user_option( 'wptec_orders', get_current_user_id() );
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
                foreach ( $this->common->orders_tab_default_columns as $key => $value ) {
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
            $hiddenColumns = get_user_meta( get_current_user_id(), 'manageedit-shop_ordercolumnshidden', true );
            #######  done ###########
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
            $paginationNumber = get_user_option( 'wptec_order_pagination', get_current_user_id() );
            ####### Change this line ###########
            # if no pagination number then set 0 ; also redeeming  existing directory
            
            if ( !$paginationNumber ) {
                # base URL of the Upload  directory
                $upload_basedir = wp_upload_dir()['basedir'];
                # Creating a Directory  if not exist
                
                if ( file_exists( $upload_basedir . "/wptec/download.csv" ) ) {
                    # redeeming  existing CSV directory
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
            # running query and  getting data from database; // $wpdb->
            $result = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'shop_order' LIMIT 23 OFFSET " . $offset, ARRAY_A );
            ####### Change this line ###########
            # if result is Empty that means you are at the end of your database row;
            
            if ( empty($result) ) {
                # Deleting [wptec_user_page_no] options
                delete_user_option( get_current_user_id(), 'wptec_order_pagination' );
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
                        if ( isset( array_merge( $this->common->orders_tab_default_columns, $this->orders_tab_extra_columns, $this->wptec_wooCommerce_order_metaKeys )[$colArray['name']] ) ) {
                            # populating $columnListFirstRow array() || CSV first row
                            $columnListFirstRow[$colArray['name']] = $colArray['title'];
                        }
                        # Getting individual order information
                        $order = wc_get_order( $rowArray['ID'] );
                        # -------------------------------- Default columns ----------------------------------------
                        #  order_number
                        if ( $colArray['name'] == "order_number" ) {
                            $rows[$rowKey][$colArray['name']] = $rowArray['ID'];
                        }
                        #  order_date
                        if ( $colArray['name'] == "order_date" ) {
                            $rows[$rowKey][$colArray['name']] = $rowArray['post_date'];
                        }
                        #  order_status
                        if ( $colArray['name'] == "order_status" ) {
                            $rows[$rowKey][$colArray['name']] = $rowArray['post_status'];
                        }
                        #  billing_address
                        
                        if ( $colArray['name'] == "billing_address" ) {
                            $billing_address = get_post_meta( $rowArray['ID'], '_billing_address_1', true );
                            $billing_address .= ", " . get_post_meta( $rowArray['ID'], '_billing_city', true );
                            $billing_address .= ", " . get_post_meta( $rowArray['ID'], '_billing_state', true );
                            $billing_address .= ", " . get_post_meta( $rowArray['ID'], '_billing_postcode', true );
                            $billing_address .= ", " . get_post_meta( $rowArray['ID'], '_billing_country', true );
                            # inserting value
                            $rows[$rowKey][$colArray['name']] = $billing_address;
                        }
                        
                        #  shipping_address
                        
                        if ( $colArray['name'] == "shipping_address" ) {
                            $shipping_address = get_post_meta( $rowArray['ID'], '_shipping_address_1', true );
                            $shipping_address .= ", " . get_post_meta( $rowArray['ID'], '_shipping_city', true );
                            $shipping_address .= ", " . get_post_meta( $rowArray['ID'], '_shipping_state', true );
                            $shipping_address .= ", " . get_post_meta( $rowArray['ID'], '_shipping_postcode', true );
                            $shipping_address .= ", " . get_post_meta( $rowArray['ID'], '_shipping_country', true );
                            # inserting address
                            $rows[$rowKey][$colArray['name']] = $shipping_address;
                        }
                        
                        #  order_total
                        if ( $colArray['name'] == "order_total" ) {
                            $rows[$rowKey][$colArray['name']] = get_post_meta( $rowArray['ID'], '_order_total', true );
                        }
                        # --------------------------------- WPTEC columns ---------------------------------------
                        # wptec_order_id
                        if ( $colArray['name'] == "wptec_order_id" ) {
                            $rows[$rowKey][$colArray['name']] = $rowArray['ID'];
                        }
                        # wptec_order_user_name
                        if ( $colArray['name'] == "wptec_order_user_name" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_first_name() . "  " . $order->get_billing_last_name();
                        }
                        # wptec_order_billing_first_name
                        if ( $colArray['name'] == "wptec_order_billing_first_name" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_first_name();
                        }
                        # wptec_order_billing_last_name
                        if ( $colArray['name'] == "wptec_order_billing_last_name" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_last_name();
                        }
                        # wptec_order_billing_company
                        if ( $colArray['name'] == "wptec_order_billing_company" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_company();
                        }
                        # wptec_order_billing_address_1
                        if ( $colArray['name'] == "wptec_order_billing_address_1" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_address_1();
                        }
                        # wptec_order_billing_address_2
                        if ( $colArray['name'] == "wptec_order_billing_address_2" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_address_2();
                        }
                        # wptec_order_billing_city
                        if ( $colArray['name'] == "wptec_order_billing_city" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_city();
                        }
                        # wptec_order_billing_state
                        if ( $colArray['name'] == "wptec_order_billing_state" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_state();
                        }
                        # wptec_order_billing_postcode
                        if ( $colArray['name'] == "wptec_order_billing_postcode" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_postcode();
                        }
                        # wptec_order_billing_country
                        if ( $colArray['name'] == "wptec_order_billing_country" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_country();
                        }
                        # wptec_order_billing_email
                        if ( $colArray['name'] == "wptec_order_billing_email" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_email();
                        }
                        # wptec_order_billing_phone
                        if ( $colArray['name'] == "wptec_order_billing_phone" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_billing_phone();
                        }
                        # wptec_order_shipping_first_name
                        if ( $colArray['name'] == "wptec_order_shipping_first_name" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_shipping_first_name();
                        }
                        # wptec_order_shipping_last_name
                        if ( $colArray['name'] == "wptec_order_shipping_last_name" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_shipping_last_name();
                        }
                        # wptec_order_shipping_company
                        if ( $colArray['name'] == "wptec_order_shipping_company" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_shipping_company();
                        }
                        # wptec_order_shipping_address_1
                        if ( $colArray['name'] == "wptec_order_shipping_address_1" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_shipping_address_1();
                        }
                        # wptec_order_shipping_address_2
                        if ( $colArray['name'] == "wptec_order_shipping_address_2" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_shipping_address_2();
                        }
                        # wptec_order_shipping_city
                        if ( $colArray['name'] == "wptec_order_shipping_city" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_shipping_city();
                        }
                        # wptec_order_shipping_state
                        if ( $colArray['name'] == "wptec_order_shipping_state" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_shipping_state();
                        }
                        # wptec_order_shipping_postcode
                        if ( $colArray['name'] == "wptec_order_shipping_postcode" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_shipping_postcode();
                        }
                        # wptec_order_shipping_country
                        if ( $colArray['name'] == "wptec_order_shipping_country" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_shipping_country();
                        }
                        # wptec_order_products
                        if ( $colArray['name'] == "wptec_order_products" ) {
                            $rows[$rowKey][$colArray['name']] = "--@--";
                        }
                        # wptec_order_currency
                        if ( $colArray['name'] == "wptec_order_currency" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_currency();
                        }
                        # wptec_order_shipping_total
                        if ( $colArray['name'] == "wptec_order_shipping_total" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_total();
                        }
                        # wptec_order_discount_total
                        if ( $colArray['name'] == "wptec_order_discount_total" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_total_discount();
                        }
                        # wptec_order_payment_method
                        if ( $colArray['name'] == "wptec_order_payment_method" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_payment_method();
                        }
                        # wptec_order_payment_method_title
                        if ( $colArray['name'] == "wptec_order_payment_method_title" ) {
                            $rows[$rowKey][$colArray['name']] = $order->get_payment_method_title();
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
            # base URL of the Upload  directory
            $upload_basedir = wp_upload_dir()['basedir'];
            # Creating a Directory  if not exist
            if ( !is_dir( $upload_basedir . "/wptec" ) ) {
                # creating a directory
                mkdir( $upload_basedir . "/wptec", 0777 );
            }
            # Opening file if there is a file or Just create a New File;
            $file = fopen( $upload_basedir . "/wptec/download.csv", "a" ) or die( "Unable to open file!" );
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
            update_user_option( get_current_user_id(), 'wptec_order_pagination', $paginationNumber );
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
            #
            die;
        }
    
    }
    
    /**
     * removing links from table td row
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_add_no_link( $classes )
    {
        
        if ( is_admin() ) {
            $current_screen = get_current_screen();
            if ( $current_screen->base == 'edit' && $current_screen->post_type == 'shop_order' ) {
                $classes[] = 'no-link';
            }
        }
        
        return $classes;
    }

}