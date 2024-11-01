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
class Wptec_Comments
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
     * list of wptec comment extra fields.
     * @since    1.0.0
     * @access   private
     * @array    extra fields key name array 
     */
    private  $comments_tab_extra_columns = array() ;
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
        $this->comments_tab_extra_columns = $common->comments_tab_extra_columns;
        # user table meta fields
        $this->wptec_comments_metaKeys = ( $common->wptec_comments_metaKeys()[0] ? $common->wptec_comments_metaKeys()[1] : array() );
    }
    
    public function wptec_comments_admin_notices()
    {
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * adding download button on comment table header 
     * @since    1.0.0
     */
    public function wptec_comments_table_manage_posts( $value = '' )
    {
        if ( is_admin() and get_current_screen()->id == 'edit-comments' ) {
            # Adding Download Button
            echo  "<span  class='button wptecDownload' id='CSVdownloadButton' data-action='wptec_commentsCSV' style='text-decoration: none; margin-left: 5px;'>  Export Comments CSV </span>" ;
        }
    }
    
    /**
     * comment column 
     * @since      1.0.0
     * @param      array    $columns      List of comment table columns 
     */
    public function wptec_comments_columns( $columns )
    {
        # user table saved data
        $comment_table = get_user_option( 'wptec_comment', get_current_user_id() );
        # check and Balance
        
        if ( !empty($comment_table) ) {
            # unseating all columns
            unset( $columns );
            $columns['cb'] = 'select all';
            # Looping the column list
            foreach ( $comment_table as $key => $columnArray ) {
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
     * comment column Content Row 
     * @since      1.0.0
     * @param      string    $column_name   Column name.
     * @param      string    $comment_ID    comment ID.
     */
    public function wptec_comments_columns_content( $column, $comment_id )
    {
        # lock the display page
        if ( get_current_screen()->id != "edit-comments" ) {
            return;
        }
        # getting comment details
        $comment_details = get_comment( $comment_id, 'ARRAY_A' );
        # comment id column
        
        if ( $column == 'wptec_comment_id' ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $comment_id ) ;
            echo  "</span>" ;
        }
        
        # comment user agent column
        
        if ( $column == 'wptec_comment_agent' ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $comment_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='true'>" ;
            echo  "<textarea name='" . esc_attr( $column ) . "'  id='" . esc_attr( $comment_id ) . esc_attr( $column ) . "' class='large-text' > " . esc_attr( $comment_details['comment_agent'] ) . " </textarea>" ;
            # action button
            echo  "<button type='button' data-from='post' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='post' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display outputs
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $comment_details['comment_agent'] ) ;
            echo  "</span>" ;
        }
        
        # Comment approve status column
        
        if ( $column == 'wptec_comment_approved' ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $comment_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='true'>" ;
            echo  "<select name='" . esc_attr( $column ) . "'  id='" . esc_attr( $comment_id ) . esc_attr( $column ) . "'>" ;
            echo  ( $comment_details['comment_approved'] == 1 ? "<option value='Approve' selected >Approve</option>" : "<option value='Approve'>Approve</option>" ) ;
            echo  ( $comment_details['comment_approved'] == 0 ? "<option value='Unapprov' selected >Unapprov</option>" : "<option value='Unapprov'>Unapprov</option>" ) ;
            echo  "<option value='Spam'>Spam</option>" ;
            echo  "</select>" ;
            # action button
            echo  "<button type='button' data-from='comment' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='comment' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            
            if ( $comment_details['comment_approved'] ) {
                echo  "Approved" ;
            } else {
                echo  "Unapproved" ;
            }
            
            echo  "</span>" ;
        }
        
        # Comment author column
        
        if ( $column == 'wptec_comment_author' ) {
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $comment_details['comment_author'] ) ;
            echo  "</span>" ;
        }
        
        # comment user email address column
        
        if ( $column == 'wptec_comment_email' ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $comment_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='true'>" ;
            echo  "<input type='email' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $comment_id ) . esc_attr( $column ) . "' value='" . esc_html( $comment_details['comment_author_email'] ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='comment' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='comment' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $comment_details['comment_author_email'] ) ;
            echo  "</span>" ;
        }
        
        # Comment user IP address column
        
        if ( $column == 'wptec_comment_ip' ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $comment_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='true'>" ;
            echo  "<input type='text' name='" . esc_attr( $column ) . "'  id='" . esc_attr( $comment_id ) . esc_attr( $column ) . "' value='" . esc_attr( $comment_details['comment_author_IP'] ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='comment' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='comment' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $comment_details['comment_author_IP'] ) ;
            echo  "</span>" ;
        }
        
        # Comment URL column
        
        if ( $column == 'wptec_comment_url' ) {
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $comment_details['comment_author_url'] ) ;
            echo  "</span>" ;
        }
        
        # Comment date column
        
        if ( $column == 'wptec_comment_date' ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $comment_details['comment_date'] ) ;
            echo  "</span>" ;
        }
        
        # Comment excerpt column
        
        if ( $column == 'wptec_comment_excerpt' ) {
            $str = $comment_details['comment_content'];
            
            if ( str_word_count( $str ) > 10 ) {
                $pieces = explode( " ", $str );
                $first_part = implode( " ", array_splice( $pieces, 0, 10 ) );
                # Display span Starts
                echo  "<span class='wptecDisplay'>" ;
                echo  esc_html( $first_part ) ;
                echo  "</span>" ;
            } else {
                echo  esc_html( $str ) ;
            }
        
        }
        
        # Comment Post column
        
        if ( $column == 'wptec_comment_post' ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $comment_details['comment_post_ID'] ) ;
            echo  "</span>" ;
        }
        
        # Comment wWord count column
        
        if ( $column == 'wptec_comment_word_count' ) {
            # Display span Starts
            echo  "<span class='wptecDisplay'>" ;
            echo  str_word_count( $comment_details['comment_content'] ) ;
            echo  "</span>" ;
        }
    
    }
    
    /**
     * Below is a Call back function of [manage_users_sortable_columns] 
     * This Function will Make column sortable 
     * @since    1.0.0
     * @param    string    $columns       The name of this plugin.
     */
    public function wptec_comments_sortable_columns( $columns )
    {
        # wptec
        $extra_columns = $this->comments_tab_extra_columns;
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
    public function wptec_comments_sortable_columns_query( $query )
    {
        # check isset or not
        
        if ( isset( $query->query_vars['orderby'], $query->query_vars['order'] ) ) {
            # getting order by
            $orderby = sanitize_sql_orderby( $query->query_vars['orderby'] );
            # getting ascending and descending value
            $order = ( $query->query_vars['order'] == 'asc' ? 'ASC' : 'DESC' );
            # looking for the keys
            switch ( $orderby ) {
                case 'wptec_comment_id':
                    $query->query_vars['orderby'] = 'comment_ID';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_comment_agent':
                    $query->query_vars['orderby'] = 'comment_agent';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_comment_approved':
                    $query->query_vars['orderby'] = 'comment_approved';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_comment_author':
                    $query->query_vars['orderby'] = 'comment_author';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_comment_email':
                    $query->query_vars['orderby'] = 'comment_author_email';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_comment_ip':
                    $query->query_vars['orderby'] = 'comment_author_IP';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_comment_url':
                    $query->query_vars['orderby'] = 'comment_author_IP';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_comment_date':
                    $query->query_vars['orderby'] = 'comment_date';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_comment_post':
                    $query->query_vars['orderby'] = 'comment_content';
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
     * Update comment information from admin user table via AJAX
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_commentAJAX()
    {
        # exit the AJAX
        exit;
    }
    
    /**
     * Exporting CSV file this Function Will Create a CSV file and Than Download the File to User PC.
     * @since    1.0.0
     */
    public function wptec_commentsCSV()
    {
        
        if ( is_user_logged_in() and current_user_can( 'administrator' ) ) {
            #------------------------------------------------------------------------------------------------------------
            # declaring empty holder
            $columnList = array();
            # This will be the First Row of the CSV sheet
            $columnListFirstRow = array();
            # getting the Saved options of User Table.
            $saved_column = get_user_option( 'wptec_comment', get_current_user_id() );
            ####### Change this line ###########
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
                foreach ( $this->common->comments_tab_default_columns as $key => $value ) {
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
            $hiddenColumns = get_user_meta( get_current_user_id(), 'manageedit-commentscolumnshidden', true );
            ####### Change this line ###########
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
            $paginationNumber = get_user_option( 'wptec_comment_pagination', get_current_user_id() );
            ####### Change this line ###########
            # if no pagination number then set 0 ; also redeeming  existing directory
            
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
            $result = $wpdb->get_results( "SELECT * FROM  {$wpdb->comments} LIMIT 23 OFFSET  " . $offset, ARRAY_A );
            # if result is Empty that means you are at the end of your database row;
            
            if ( empty($result) ) {
                # Deleting [wptec_user_page_no] options
                delete_user_option( get_current_user_id(), 'wptec_comment_pagination' );
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
                        if ( isset( array_merge( $this->common->comments_tab_default_columns, $this->comments_tab_extra_columns, $this->wptec_comments_metaKeys )[$colArray['name']] ) ) {
                            # populating $columnListFirstRow array() || CSV first row
                            $columnListFirstRow[$colArray['name']] = $colArray['title'];
                        }
                        # -------------------------------- Default columns ----------------------------------------
                        # author
                        if ( $colArray['name'] == "author" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_author'] ) ? $rowArray['comment_author'] : "" );
                        }
                        # comments
                        if ( $colArray['name'] == "comment" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_content'] ) ? $rowArray['comment_content'] : "" );
                        }
                        # categories
                        if ( $colArray['name'] == "response" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_post_ID'] ) ? $rowArray['comment_post_ID'] : "" );
                        }
                        # categories
                        if ( $colArray['name'] == "date" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_date'] ) ? $rowArray['comment_date'] : "" );
                        }
                        # --------------------------------- WPTEC columns ---------------------------------------
                        #  wptec_post_id
                        if ( $colArray['name'] == "wptec_comment_id" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_ID'] ) ? $rowArray['comment_ID'] : "" );
                        }
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_comment_agent" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_agent'] ) ? $rowArray['comment_agent'] : "" );
                        }
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_comment_approved" ) {
                            $rows[$rowKey][$colArray['name']] = ( (isset( $rowArray['comment_approved'] ) and $rowArray['comment_approved']) ? "approved" : "pending" );
                        }
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_comment_author" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['user_id'] ) ? $rowArray['user_id'] : "" );
                        }
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_comment_email" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_author_email'] ) ? $rowArray['comment_author_email'] : "" );
                        }
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_comment_ip" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_author_IP'] ) ? $rowArray['comment_author_IP'] : "" );
                        }
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_comment_url" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_author_url'] ) ? $rowArray['comment_author_url'] : "" );
                        }
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_comment_date" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_date'] ) ? $rowArray['comment_date'] : "" );
                        }
                        #  wptec_author_id
                        
                        if ( $colArray['name'] == "wptec_comment_excerpt" ) {
                            $pieces = explode( " ", $rowArray['comment_content'] );
                            $excerpt = implode( " ", array_splice( $pieces, 0, 10 ) );
                            $rows[$rowKey][$colArray['name']] = $excerpt;
                        }
                        
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_comment_post" ) {
                            $rows[$rowKey][$colArray['name']] = $rowArray['comment_post_ID'];
                        }
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_comment_word_count" ) {
                            $rows[$rowKey][$colArray['name']] = str_word_count( $rowArray['comment_content'] );
                        }
                        # --------------------------------- META columns ---------------------------------------
                        
                        if ( $colArray['type'] == "meta" ) {
                            $metaData = get_comment_meta( $rowArray['ID'], $colArray['name'], true );
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
            update_user_option( get_current_user_id(), 'wptec_comment_pagination', $paginationNumber );
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