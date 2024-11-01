<?php

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wptec
 * @subpackage Wptec/admin
 * @author     javmah <jaedmah@gmail.com>
 */
class Wptec_Users
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
     * list of User table wptec extra fields 
     * @since    1.0.0
     * @access   private 
     * @array    extra fields key name array 
     */
    private  $user_tab_extra_columns = array() ;
    /**
     * list of User table Meta  fields 
     * @since    1.0.0
     * @access   private 
     * @array    extra fields key name array 
     */
    private  $wptec_users_metaKeys = array() ;
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
        $this->user_tab_extra_columns = $common->user_tab_extra_columns;
        # user table meta fields
        $this->wptec_users_metaKeys = ( $common->wptec_users_metaKeys()[0] ? $common->wptec_users_metaKeys()[1] : array() );
    }
    
    /**
     * Bismillah Hir ramhaner rahim. testing Process.
     * @since    1.0.0
     * @param    string    $plugin_name   The name of this plugin.
     * @param    string    $version    	The version of this plugin.
     */
    public function wptec_users_admin_notices()
    {
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * admin_init Hook OR  generating CSV file for Download.
     * This Function Will initiate User Download User List 
     * @since    1.0.0
     */
    public function wptec_user_section_filter()
    {
        # Adding Link Download Button
        echo  "<span class='button wptecDownload' id='wptec_userCSV' data-action='wptec_userCSV' style='text-decoration: none; margin-left: 5px;'>  Export User CSV </span>" ;
    }
    
    /**
     * wptec_user.
     * @since    1.0.0
     * @param    string    $plugin_name   The name of this plugin.
     * @param    string    $version    	  The version of this plugin.
     */
    public function wptec_user_columns( $columns )
    {
        #user table saved data
        $user_table = get_user_option( 'wptec_user', get_current_user_id() );
        # check and Balance
        
        if ( !empty($user_table) ) {
            # unseating all columns
            unset( $columns );
            $columns['cb'] = 'select all';
            # Looping the column list
            foreach ( $user_table as $key => $columnArray ) {
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
     * Display Content From DB.
     * @since    1.0.0
     * @param    string    $content    Empty content.
     * @param    string    $column     column ID.
     * @param    string    $user_id    user_id.
     */
    public function wptec_user_columns_content( $content, $column, $user_id )
    {
        # Hidden Column's
        $hidden_columns = get_hidden_columns( 'users.php' );
        # User Id
        $user = get_userdata( $user_id );
        # Wp Default fields || not all just selected Few  || echo "data-associatedID=''";
        # wptec extra column
        
        if ( $column == 'wptec_user_id' ) {
            $rtData = "<span class='wptecDisplay'>";
            $rtData .= esc_html( $user_id );
            $rtData .= "</span>";
            $content = $rtData;
        }
        
        # First name
        
        if ( $column == 'wptec_user_firstName' ) {
            # Input span Starts
            $rtData = "<span class='wptecInput' style='display:none;'";
            $rtData .= "data-id='" . esc_attr( $user_id ) . "'";
            $rtData .= "data-name='" . esc_attr( $column ) . "'";
            $rtData .= "data-type='text'";
            $rtData .= "data-status='wptecExtra'";
            $rtData .= "data-editable='" . $this->wptec_editable . "'>";
            # input field
            $rtData .= "<input type='text' id='" . esc_attr( $user_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $user->first_name ) . "' class='large-text'>";
            # action button
            $rtData .= "<button type='button' data-from='user' class='yes'><span class='dashicons dashicons-yes'></span></button>";
            $rtData .= "<button type='button' data-from='user' class='no'><span class='dashicons dashicons-no-alt'></span></button>";
            $rtData .= "</span>";
            # Display span Starts
            $rtData .= "<span class='wptecDisplay' >";
            $rtData .= esc_html( $user->first_name );
            $rtData .= "</span>";
            $content = $rtData;
        }
        
        # Last Name
        
        if ( $column == 'wptec_user_lastName' ) {
            # Input span Starts
            $rtData = "<span class='wptecInput' style='display:none;'";
            $rtData .= "data-id='" . esc_attr( $user_id ) . "'";
            $rtData .= "data-name='" . esc_attr( $column ) . "'";
            $rtData .= "data-type='text'";
            $rtData .= "data-status='wptecExtra'";
            $rtData .= "data-editable='" . $this->wptec_editable . "'>";
            # input field
            $rtData .= "<input type='text' id='" . esc_attr( $user_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $user->last_name ) . "' class='large-text'>";
            # action button
            $rtData .= "<button type='button' data-from='user' class='yes'><span class='dashicons dashicons-yes'></span></button>";
            $rtData .= "<button type='button' data-from='user' class='no'><span class='dashicons dashicons-no-alt'></span></button>";
            $rtData .= "</span>";
            # Display span Starts
            $rtData .= "<span class='wptecDisplay'>";
            $rtData .= esc_html( $user->last_name );
            $rtData .= "</span>";
            $content = $rtData;
        }
        
        # Nick Name
        
        if ( $column == 'wptec_user_nickName' ) {
            # Input span Starts
            $rtData = "<span class='wptecInput' style='display:none;'";
            $rtData .= "data-id='" . esc_attr( $user_id ) . "'";
            $rtData .= "data-name='" . esc_attr( $column ) . "'";
            $rtData .= "data-type='text'";
            $rtData .= "data-status='wptecExtra'";
            $rtData .= "data-editable='" . $this->wptec_editable . "'>";
            # input field
            $rtData .= "<input type='text' id='" . esc_attr( $user_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $user->user_nicename ) . "' class='large-text'>";
            # action button
            $rtData .= "<button type='button' data-from='user' class='yes'><span class='dashicons dashicons-yes'></span></button>";
            $rtData .= "<button type='button' data-from='user' class='no'><span class='dashicons dashicons-no-alt'></span></button>";
            $rtData .= "</span>";
            # Display span Starts
            $rtData .= "<span class='wptecDisplay'>";
            $rtData .= esc_html( $user->user_nicename );
            $rtData .= "</span>";
            $content = $rtData;
        }
        
        # Nick Name
        
        if ( $column == 'wptec_user_email' ) {
            # Input span Starts
            $rtData = "<span class='wptecInput' style='display:none;'";
            $rtData .= "data-id='" . esc_attr( $user_id ) . "'";
            $rtData .= "data-name='" . esc_attr( $column ) . "'";
            $rtData .= "data-type='text'";
            $rtData .= "data-status='wptecExtra'";
            $rtData .= "data-editable='" . $this->wptec_editable . "'>";
            # input field
            $rtData .= "<input type='email' id='" . esc_attr( $user_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $user->user_email ) . "' class='large-text'>";
            # action button
            $rtData .= "<button type='button' data-from='user' class='yes'><span class='dashicons dashicons-yes'></span></button>";
            $rtData .= "<button type='button' data-from='user' class='no'><span class='dashicons dashicons-no-alt'></span></button>";
            $rtData .= "</span>";
            # Display span Starts
            $rtData .= "<span class='wptecDisplay'>";
            $rtData .= esc_html( $user->user_email );
            $rtData .= "</span>";
            $content = $rtData;
        }
        
        # Website
        
        if ( $column == 'wptec_user_url' ) {
            # Input span Starts
            $rtData = "<span class='wptecInput' style='display:none;'";
            $rtData .= "data-id='" . esc_attr( $user_id ) . "'";
            $rtData .= "data-name='" . esc_attr( $column ) . "'";
            $rtData .= "data-type='text'";
            $rtData .= "data-status='wptecExtra'";
            $rtData .= "data-editable='" . $this->wptec_editable . "'>";
            # input field
            $rtData .= "<input type='url' id='" . esc_attr( $user_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $user->user_url ) . "' class='large-text'>";
            # action button
            $rtData .= "<button type='button' data-from='user' class='yes'><span class='dashicons dashicons-yes'></span></button>";
            $rtData .= "<button type='button' data-from='user' class='no'><span class='dashicons dashicons-no-alt'></span></button>";
            $rtData .= "</span>";
            # Display span Starts
            $rtData .= "<span class='wptecDisplay'>";
            $rtData .= esc_html( $user->user_url );
            $rtData .= "</span>";
            $content = $rtData;
        }
        
        # Post Count
        
        if ( $column == 'wptec_user_postCount' ) {
            $user_posts = count_user_posts( $user_id );
            # Display span Starts
            $rtData = "<span class='wptecDisplay'>";
            $rtData .= esc_html( $user_posts );
            $rtData .= "</span>";
            $content = $rtData;
        }
        
        # Comment Count
        
        if ( $column == 'wptec_user_commentCount' ) {
            global  $wpdb ;
            $count = $wpdb->get_var( 'SELECT COUNT(comment_ID) FROM ' . $wpdb->comments . ' WHERE user_id = "' . $user_id . '"' );
            # Display span Starts
            $rtData = "<span class='wptecDisplay'>";
            $rtData .= esc_html( $count );
            $rtData .= "</span>";
            $content = $rtData;
        }
        
        # Descriptions
        
        if ( $column == 'wptec_user_description' ) {
            $data = get_user_meta( $user_id, 'description' );
            
            if ( isset( $data[0] ) and $data[0] ) {
                $user_description = $data[0];
            } else {
                $user_description = " -- ";
            }
            
            # Input span Starts
            $rtData = "<span class='wptecInput' style='display:none;'";
            $rtData .= "data-id='" . esc_attr( $user_id ) . "'";
            $rtData .= "data-name='" . esc_attr( $column ) . "'";
            $rtData .= "data-type='text'";
            $rtData .= "data-status='wptecExtra'";
            $rtData .= "data-editable='" . $this->wptec_editable . "'>";
            # input field
            $rtData .= "<input type='text' id='" . esc_attr( $user_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $user_description ) . "' class='large-text'>";
            # action button
            $rtData .= "<button type='button' data-from='user' class='yes'><span class='dashicons dashicons-yes'></span></button>";
            $rtData .= "<button type='button' data-from='user' class='no'><span class='dashicons dashicons-no-alt'></span></button>";
            $rtData .= "</span>";
            # Display span Starts
            $rtData .= "<span class='wptecDisplay'>";
            $rtData .= esc_html( $user_description );
            $rtData .= "</span>";
            $content = $rtData;
        }
        
        return $content;
    }
    
    /**
     * Below is a Call back function of [manage_users_sortable_columns] 
     * This Function will Make column sortable 
     * @since    1.0.0
     * @param    string    $columns       The name of this plugin.
     */
    public function wptec_user_sortable_columns( $columns )
    {
        # wptec
        $extra_columns = $this->user_tab_extra_columns;
        # unseating items
        unset( $extra_columns['wptec_user_description'] );
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
    public function wptec_user_sortable_columns_query( $query )
    {
        # check isset or not
        
        if ( isset( $query->query_vars['orderby'], $query->query_vars['order'] ) ) {
            # getting order by
            $orderby = sanitize_sql_orderby( $query->query_vars['orderby'] );
            # getting ascending and descending value
            $order = ( $query->query_vars['order'] == 'asc' ? 'ASC' : 'DESC' );
            # looking for the keys
            switch ( $orderby ) {
                case 'wptec_user_id':
                    $query->query_vars['orderby'] = 'ID';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_user_firstName':
                    $query->query_vars = array_merge( $query->query_vars, array(
                        'meta_key' => 'first_name',
                        'orderby'  => 'first_name',
                    ) );
                    break;
                case 'wptec_user_lastName':
                    $query->query_vars = array_merge( $query->query_vars, array(
                        'meta_key' => 'first_name',
                        'orderby'  => 'first_name',
                    ) );
                    break;
                case 'wptec_user_nickName':
                    $query->query_vars['orderby'] = 'user_nicename';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_user_email':
                    $query->query_vars['orderby'] = 'user_email';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_user_url':
                    $query->query_vars['orderby'] = 'user_url';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_user_commentCount':
                    global  $wpdb ;
                    $query->query_orderby = "ORDER BY ( SELECT COUNT(*) FROM {$wpdb->comments} WHERE {$wpdb->users}.ID = {$wpdb->comments}.user_id ) {$order}";
                    break;
                default:
                    # default action
            }
        }
        
        # return
        return $query;
    }
    
    /**
     * Update user information from admin user table via AJAX
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_userAJAX()
    {
        # exit the AJAX
        exit;
    }
    
    /**
     * User Table CSV download via AJAX
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_userCSV()
    {
        
        if ( is_user_logged_in() and current_user_can( 'administrator' ) ) {
            #------------------------------------------------------------------------------------------------------------------------
            # declaring empty holder
            $columnList = array();
            # This will be the First Row of the CSV sheet
            $columnListFirstRow = array();
            # getting the Saved options of User Table.
            $saved_column = get_user_option( 'wptec_user', get_current_user_id() );
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
                foreach ( $this->common->user_tab_default_columns as $key => $value ) {
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
            $hiddenColumns = get_user_meta( get_current_user_id(), 'manageuserscolumnshidden', true );
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
            $paginationNumber = get_user_option( 'wptec_user_pagination', get_current_user_id() );
            ####### Done ###########
            # if no pagination number then set 0 ; also redeeming  existing directory
            
            if ( !$paginationNumber ) {
                # base URL of the Upload  directory
                $upload_basedir = wp_upload_dir()['basedir'];
                # Creating a Directory  if not exist
                
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
            # Getting User list Query
            global  $wpdb ;
            # Creating Query string
            # running query and  getting data from database; // $wpdb->
            $result = $wpdb->get_results( "SELECT * FROM {$wpdb->users} LIMIT 23 OFFSET " . $offset, ARRAY_A );
            ####### half done ###########
            # if result is Empty that means you are at the end of your database row;
            
            if ( empty($result) ) {
                # Deleting [wptec_user_page_no] options
                delete_user_option( get_current_user_id(), 'wptec_user_pagination' );
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
                        if ( isset( array_merge( $this->common->user_tab_default_columns, $this->user_tab_extra_columns, $this->wptec_users_metaKeys )[$colArray['name']] ) ) {
                            # populating $columnListFirstRow array() || CSV first row
                            $columnListFirstRow[$colArray['name']] = $colArray['title'];
                        }
                        # -------------------------------- Default columns ---------------------------------------------------------								  ####### Change this lines ###########
                        # title
                        if ( $colArray['name'] == "username" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['user_nicename'] ) ? $rowArray['user_nicename'] : "--" );
                        }
                        # author
                        if ( $colArray['name'] == "name" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['display_name'] ) ? $rowArray['display_name'] : "--" );
                        }
                        # categories
                        if ( $colArray['name'] == "email" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['user_email'] ) ? $rowArray['user_email'] : "--" );
                        }
                        # tags
                        
                        if ( $colArray['name'] == "role" ) {
                            $rows[$rowKey][$colArray['name']] = get_user_meta( $rowArray['ID'], 'wp_capabilitiesn', true );
                            // Didn't show on csv file
                        }
                        
                        # comments
                        if ( $colArray['name'] == "posts" ) {
                            $rows[$rowKey][$colArray['name']] = '--@--';
                        }
                        # --------------------------------- WPTEC columns -----------------------------------------------------------								  ####### Change this lines ###########
                        # title
                        if ( $colArray['name'] == "wptec_user_id" ) {
                            $rows[$rowKey][$colArray['name']] = $rowArray['ID'];
                        }
                        # author
                        if ( $colArray['name'] == "wptec_user_firstName" ) {
                            $rows[$rowKey][$colArray['name']] = get_user_meta( $rowArray['ID'], 'first_name', true );
                        }
                        # categories
                        if ( $colArray['name'] == "wptec_user_lastName" ) {
                            $rows[$rowKey][$colArray['name']] = get_user_meta( $rowArray['ID'], 'last_name', true );
                        }
                        # tags
                        if ( $colArray['name'] == "wptec_user_nickName" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['user_nicename'] ) ? $rowArray['user_nicename'] : "--" );
                        }
                        # comments
                        if ( $colArray['name'] == "wptec_user_email" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['user_email'] ) ? $rowArray['user_email'] : "--" );
                        }
                        # date
                        if ( $colArray['name'] == "wptec_user_url" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['user_url'] ) ? $rowArray['user_url'] : "--" );
                        }
                        # date
                        if ( $colArray['name'] == "wptec_user_postCount" ) {
                            $rows[$rowKey][$colArray['name']] = count( get_posts( array(
                                'author' => $rowArray['ID'],
                            ) ) );
                        }
                        # date
                        if ( $colArray['name'] == "wptec_user_commentCount" ) {
                            $rows[$rowKey][$colArray['name']] = get_comments( array(
                                'user_id' => 1,
                                'count'   => true,
                            ) );
                        }
                        # date
                        if ( $colArray['name'] == "wptec_user_description" ) {
                            $rows[$rowKey][$colArray['name']] = get_user_meta( $rowArray['ID'], 'description', true );
                        }
                        # --------------------------------- META columns ---------------------------------------------------------------								####### Change this lines ###########
                        
                        if ( $colArray['type'] == "meta" ) {
                            $metaData = get_user_meta( $rowArray['ID'], $colArray['name'], true );
                            $rows[$rowKey][$colArray['name']] = ( is_array( $metaData ) ? json_encode( $metaData ) : $metaData );
                        }
                    
                    }
                
                }
            }
            #----------------------------------------------------------------------------------------------------------------------------
            # base URL of the Upload  directory
            $upload_basedir = wp_upload_dir()['basedir'];
            # Creating a Directory if not exist
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
            update_user_option( get_current_user_id(), 'wptec_user_pagination', $paginationNumber );
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
# There Will be Error is Field are in Two Field Group OR
# ACF Field Group Collection Error +