<?php

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wptec
 * @subpackage Wptec/admin
 * @author     javmah <jaedmah@gmail.com>
 */
class Wptec_Pages
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
     * list of wptec page extra fields.
     * @since    1.0.0
     * @access   private
     * @array    extra fields key name array 
     */
    private  $pages_tab_extra_columns = array() ;
    /**
     * list of wptec page extra fields.
     * @since    1.0.0
     * @access   private
     * @array    extra fields key name array 
     */
    private  $wptec_pages_metaKeys = array() ;
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
     * @param      string    $plugin_name  The name of this plugin.
     * @param      string    $version      The version of this plugin.
     * @param      string    $common       common class object 
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
        $this->pages_tab_extra_columns = $common->pages_tab_extra_columns;
        # user table meta fields
        $this->wptec_pages_metaKeys = ( $common->wptec_pages_metaKeys()[0] ? $common->wptec_pages_metaKeys()[1] : array() );
    }
    
    public function wptec_pages_admin_notices()
    {
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * Initialize the class and set its properties.
     * @since    1.0.0
     */
    public function wptec_pages_table_manage_posts()
    {
        if ( is_admin() and get_current_screen()->id == 'edit-page' ) {
            # Adding Download Button
            echo  "<span class='button wptecDownload' id='wptec_pagesCSV' data-action='wptec_pagesCSV' style='text-decoration: none; margin-left: 5px;'> Export pages CSV </span>" ;
        }
    }
    
    /**
     * Initialize the class and set its properties.
     * @since    1.0.0
     * @param    string    $plugin_name    The name of this plugin.
     */
    public function wptec_pages_columns( $columns )
    {
        #user table saved data
        $page_table = get_user_option( 'wptec_page', get_current_user_id() );
        # check and Balance
        
        if ( !empty($page_table) ) {
            # unseating the columns
            unset( $columns );
            $columns['cb'] = 'select all';
            # Looping the column list
            foreach ( $page_table as $key => $columnArray ) {
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
     * @since    1.0.0
     * @param      string    $column_name       The name of this plugin.
     * @param      string    $page_ID           The version of this plugin.
     */
    public function wptec_pages_columns_content( $column, $page_id )
    {
        # lock the display page
        if ( get_current_screen()->id != "edit-page" ) {
            return;
        }
        # getting page information
        $page_details = get_page( $page_id );
        # for page id column
        
        if ( 'wptec_page_id' == $column ) {
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $page_id ) ;
            echo  "</span>" ;
        }
        
        # for page ping status
        
        if ( 'wptec_page_ping_status' == $column ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $page_details->ping_status ) ;
            echo  "</span>" ;
        }
        
        # for page comment status column || Editable column
        
        if ( 'wptec_page_comment_status' == $column ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $page_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<select id='" . esc_attr( $page_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $page_details->comment_status ) . "' class='large-text' >" ;
            echo  ( $page_details->comment_status == 'open' ? "<option value='open' selected>  open    </option>" : "<option value='open'> open </option>" ) ;
            echo  ( $page_details->comment_status == 'closed' ? "<option value='closed' selected> closed </option>" : "<option value='closed'> closed </option>" ) ;
            echo  "</select>" ;
            # action button
            echo  "<button type='button' data-from='post' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='post' class='no'> <span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display outputs
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $page_details->comment_status ) ;
            echo  "</span>" ;
        }
        
        # for page comment count
        
        if ( 'wptec_page_comment_count' == $column ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $page_details->comment_count ) ;
            echo  "</span>" ;
        }
        
        # for Page menu order column || Editable field || need to change
        
        if ( 'wptec_page_menu_order' == $column ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $page_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='number' id='" . esc_attr( $page_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $page_details->menu_order ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='page' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='page' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $page_details->menu_order ) ;
            echo  "</span>" ;
        }
    
    }
    
    /**
     * Below is a Call back function of [manage_users_sortable_columns] 
     * This Function will Make column sortable 
     * @since    1.0.0
     * @param    string    $columns       The name of this plugin.
     */
    public function wptec_pages_sortable_columns( $columns )
    {
        # wptec
        $extra_columns = $this->pages_tab_extra_columns;
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
    public function wptec_pages_sortable_columns_query( $query )
    {
        # check isset or not
        
        if ( isset( $query->query_vars['orderby'], $query->query_vars['order'] ) ) {
            # getting order by
            $orderby = sanitize_sql_orderby( $query->query_vars['orderby'] );
            # getting ascending and descending value
            $order = ( $query->query_vars['order'] == 'asc' ? 'ASC' : 'DESC' );
            # looking for the keys
            switch ( $orderby ) {
                case 'wptec_page_id':
                    $query->query_vars['orderby'] = 'ID';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_page_ping_status':
                    $query->query_vars['orderby'] = 'ping_status';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_page_comment_status':
                    global  $wpdb ;
                    $query->query_orderby = "ORDER BY ( SELECT COUNT(*) FROM {$wpdb->comments} WHERE {$wpdb->posts}.ID = {$wpdb->comments}.comment_post_ID ) {$order}";
                    break;
                case 'wptec_post_author_name':
                    global  $wpdb ;
                    $query->query_orderby = "ORDER BY {$wpdb->users}.user_nicename WHERE {$wpdb->usermeta}.user_id = {$wpdb->posts}.post_author ) {$order}";
                    break;
                case 'wptec_page_menu_order':
                    $query->query_vars['orderby'] = 'menu_order';
                    $query->query_vars['order'] = $order;
                    break;
                default:
                    # default action
            }
        }
        
        # return
        return $query;
    }
    
    /**
     * Update Page information from admin user table via AJAX
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_pageAJAX()
    {
        # exit the AJAX
        exit;
    }
    
    /**
     * Update post information from admin user table via AJAX
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_pagesCSV()
    {
        
        if ( is_user_logged_in() and current_user_can( 'administrator' ) ) {
            #------------------------------------------------------------------------------------------------------------------------
            # declaring empty holder
            $columnList = array();
            # This will be the First Row of the CSV sheet
            $columnListFirstRow = array();
            # getting the Saved options of User Table.
            $saved_column = get_user_option( 'wptec_page', get_current_user_id() );
            ####### Done ###########
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
                foreach ( $this->common->pages_tab_default_columns as $key => $value ) {
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
            $hiddenColumns = get_user_meta( get_current_user_id(), 'manageedit-pagecolumnshidden', true );
            ####### Done ###########
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
            #--------------------------------------------------------------------------------------------------------------------------
            # getting pagination numbers
            $paginationNumber = get_user_option( 'wptec_page_pagination', get_current_user_id() );
            ####### Done ###########
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
            # Getting User list Query
            global  $wpdb ;
            # Creating Query string
            # running query and  getting data from database; // $wpdb->
            $result = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'page' LIMIT 23 OFFSET " . $offset, ARRAY_A );
            ####### half done ###########
            # if result is Empty that means you are at the end of your database row;
            
            if ( empty($result) ) {
                # Deleting [wptec_user_page_no] options
                delete_user_option( get_current_user_id(), 'wptec_page_pagination' );
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
            
            #------------------------------------------------------------------------------------------------------------------------
            # Row placeholder resting every time
            $rows = array();
            # looping the result
            foreach ( $result as $rowKey => $rowArray ) {
                # looping column list
                foreach ( $columnList as $colArray ) {
                    # Also removing disabled colum.
                    
                    if ( $colArray['status'] ) {
                        # removing 3rd party columns
                        if ( isset( array_merge( $this->common->pages_tab_default_columns, $this->pages_tab_extra_columns, $this->wptec_pages_metaKeys )[$colArray['name']] ) ) {
                            # populating $columnListFirstRow array() || CSV first row
                            $columnListFirstRow[$colArray['name']] = $colArray['title'];
                        }
                        # -------------------------------- Default columns ---------------------------------------------------------								  ####### Change this lines ###########
                        #  title
                        if ( $colArray['name'] == "title" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_title'] ) ? $rowArray['post_title'] : "--" );
                        }
                        #  author
                        if ( $colArray['name'] == "author" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_author'] ) ? $rowArray['post_author'] : "--" );
                        }
                        #  categories
                        if ( $colArray['name'] == "comments" ) {
                            $rows[$rowKey][$colArray['name']] = "--@--";
                        }
                        #  tags
                        if ( $colArray['name'] == "date" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_date'] ) ? $rowArray['post_date'] : "--" );
                        }
                        # --------------------------------- WPTEC columns -----------------------------------------------------------								  ####### Change this lines ###########
                        #  title
                        if ( $colArray['name'] == "wptec_page_id" ) {
                            $rows[$rowKey][$colArray['name']] = $rowArray['ID'];
                        }
                        #  author
                        if ( $colArray['name'] == "wptec_page_ping_status" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ping_status'] ) ? $rowArray['ping_status'] : "--" );
                        }
                        #  categories
                        if ( $colArray['name'] == "wptec_page_comment_status" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_status'] ) ? $rowArray['comment_status'] : "--" );
                        }
                        #  tags
                        if ( $colArray['name'] == "wptec_page_comment_count" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_count'] ) ? $rowArray['comment_count'] : "" );
                        }
                        #  comments
                        if ( $colArray['name'] == "wptec_page_menu_order" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['menu_order'] ) ? $rowArray['menu_order'] : "--" );
                        }
                        # --------------------------------- META columns ---------------------------------------------------------------								####### Change this lines ###########
                        
                        if ( $colArray['type'] == "meta" ) {
                            $metaData = get_post_meta( $rowArray['ID'], $colArray['name'], true );
                            $rows[$rowKey][$colArray['name']] = ( is_array( $metaData ) ? json_encode( $metaData ) : $metaData );
                        }
                    
                    }
                
                }
            }
            #----------------------------------------------------------------------------------------------------------------------------
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
            update_user_option( get_current_user_id(), 'wptec_page_pagination', $paginationNumber );
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