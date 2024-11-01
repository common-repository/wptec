<?php

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wptec
 * @subpackage Wptec/admin
 * @author     javmah <jaedmah@gmail.com>
 */
class Wptec_Posts
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
     * list of post wptec fields.
     * @since    1.0.0
     * @access   private
     * @array    extra fields key name array 
     */
    public  $post_tab_extra_columns = array() ;
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
     * @param      string    $common    	  common class object 
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
        $this->post_tab_extra_columns = $common->post_tab_extra_columns;
        # product  table meta fields
        $this->wptec_posts_metaKeys = ( $common->wptec_posts_metaKeys()[0] ? $common->wptec_posts_metaKeys()[1] : array() );
    }
    
    /**
     * Initialize the class and set its properties.
     * @since      1.0.0
     * @param      string    $plugin_name  The name of this plugin.
     * @param      string    $version      The version of this plugin.
     */
    public function wptec_posts_admin_notices( $value = '' )
    {
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * Initialize the class and set its properties.
     * @since    1.0.0
     * @param    string    $value   The value.
     */
    public function wptec_posts_table_manage_posts( $value = '' )
    {
        if ( get_current_screen()->id == 'edit-post' ) {
            # Adding Link Download Button
            echo  "<span  class='button wptecDownload' id='wptec_postsCSV' data-action='wptec_postsCSV' style='text-decoration: none; margin-left: 5px;'> Export Posts CSV </span>" ;
        }
    }
    
    /**
     * wptec_posts_columns will add and remove the columns 
     * @since      1.0.0
     * @param      string    $column       The name of column.
     * @param      string    $post_id    	The post_id.
     */
    public function wptec_posts_columns( $columns )
    {
        #post table saved data
        $post_table = get_user_option( 'wptec_post', get_current_user_id() );
        # check and Balance
        
        if ( !empty($post_table) ) {
            # unseating all columns
            unset( $columns );
            $columns['cb'] = 'select all';
            # Looping the column list
            foreach ( $post_table as $key => $columnArray ) {
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
     * Column content.
     * @since      1.0.0
     * @param      string    $column     The name of column.
     * @param      string    $post_id    The post_id.
     */
    public function wptec_posts_columns_content( $column, $post_id )
    {
        if ( get_current_screen()->id != "edit-post" ) {
            return;
        }
        # getting post information
        $post_data = get_post( $post_id );
        # post id column
        
        if ( $column == 'wptec_post_id' ) {
            # Display outputs
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $post_id ) ;
            echo  "</span>" ;
        }
        
        # Post author id column
        
        if ( $column == 'wptec_author_id' ) {
            # Display outputs
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $post_data->post_author ) ;
            echo  "</span>" ;
        }
        
        # Post estimated reading time column
        
        if ( $column == 'wptec_post_er_time' ) {
            $my_content = $post_data->post_content;
            $word = str_word_count( strip_tags( $my_content ) );
            $m = floor( $word / 200 );
            $s = floor( $word % 200 / (200 / 60) );
            $est = $m . ' minute' . (( $m == 1 ? '' : 's' )) . ', ' . $s . ' second' . (( $s == 1 ? '' : 's' ));
            # Display outputs
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $est ) ;
            echo  "</span>" ;
        }
        
        # Post excerpt column || Editable fields
        
        if ( $column == 'wptec_post_excerpt' ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $post_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' id='" . esc_attr( $post_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $post_data->post_excerpt ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='post' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='post' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display outputs
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $post_data->post_excerpt ) ;
            echo  "</span>" ;
        }
        
        # Post attachment column
        
        if ( $column == 'wptec_post_attachment' ) {
            $attachments = get_children( array(
                'post_parent' => $post_id,
            ) );
            
            if ( !empty($attachments) ) {
                # Display outputs
                echo  "<span  class='wptecDisplay'>" ;
                echo  esc_html( json_decode( $attachments ) ) ;
                echo  "</span>" ;
            }
        
        }
        
        # Post attachment count column
        
        if ( $column == 'wptec_post_attachment_count' ) {
            $attachments = get_children( array(
                'post_parent' => $post_id,
            ) );
            $count = count( $attachments );
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $count ) ;
            echo  "</span>" ;
        }
        
        # Post author name column
        
        if ( $column == 'wptec_post_author_name' ) {
            $user_data = get_user_by( 'id', $post_data->post_author );
            # Display outputs
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $user_data->user_nicename ) ;
            echo  "</span>" ;
        }
        
        # Post comment list column
        
        if ( $column == 'wptec_post_comments' ) {
            $comments = get_comments( array(
                'post_id' => $post_id,
            ) );
            # Display outputs
            echo  "<span class='wptecDisplay'>" ;
            foreach ( $comments as $comment ) {
                echo  substr( $comment->comment_content, 0, 10 ) ;
                echo  "<br>" ;
            }
            echo  "</span>" ;
        }
        
        # Post comment count column
        
        if ( $column == 'wptec_post_comment_count' ) {
            # Display outputs
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $post_data->comment_count ) ;
            echo  "</span>" ;
        }
        
        # Post comment status
        
        if ( $column == 'wptec_post_comment_status' ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $post_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<select id='" . esc_attr( $post_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $post_data->comment_status ) . "'>" ;
            echo  ( $post_data->comment_status == 'open' ? "<option value='open' selected>  open    </option>" : "<option value='open'> open </option>" ) ;
            echo  ( $post_data->comment_status == 'closed' ? "<option value='closed' selected> closed </option>" : "<option value='closed'> closed </option>" ) ;
            echo  "</select>" ;
            # action button
            echo  "<button type='button' data-from='post' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='post' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display outputs
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $post_data->comment_status ) ;
            echo  "</span>" ;
        }
        
        # Post parent
        
        if ( $column == 'wptec_post_parent' ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $post_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' id='" . esc_attr( $post_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $post_data->post_parent ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='post' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='post' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display outputs
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $post_data->post_parent ) ;
            echo  "</span>" ;
        }
        
        # Post Word Count column
        
        if ( $column == 'wptec_post_word_count' ) {
            $my_content = $post_data->post_content;
            $word = str_word_count( strip_tags( $my_content ) );
            # Display outputs
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $word ) ;
            echo  "</span>" ;
        }
    
    }
    
    /**
     * Below is a Call back function of [manage_users_sortable_columns] 
     * This Function will Make column sortable 
     * @since    1.0.0
     * @param    string    $columns       The name of this plugin.
     */
    public function wptec_posts_sortable_columns( $columns )
    {
        # wptec
        $extra_columns = $this->post_tab_extra_columns;
        # unseating items
        unset( $extra_columns['wptec_post_word_count'] );
        unset( $extra_columns['wptec_post_attachment'] );
        unset( $extra_columns['wptec_post_attachment_count'] );
        unset( $extra_columns['wptec_post_comments'] );
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
    public function wptec_posts_sortable_columns_query( $query )
    {
        # check isset or not
        
        if ( isset( $query->query_vars['orderby'], $query->query_vars['order'] ) ) {
            # getting order by
            $orderby = sanitize_sql_orderby( $query->query_vars['orderby'] );
            # getting ascending and descending value
            $order = ( $query->query_vars['order'] == 'asc' ? 'ASC' : 'DESC' );
            # looking for the keys
            switch ( $orderby ) {
                case 'wptec_post_id':
                    $query->query_vars['orderby'] = 'ID';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_author_id':
                    $query->query_vars['orderby'] = 'post_author';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_post_er_time':
                    global  $wpdb ;
                    $query->query_orderby = "ORDER BY {$wpdb->posts}.post_content ) {$order}";
                    break;
                case 'wptec_post_excerpt':
                    $query->query_vars['orderby'] = 'post_excerpt';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_post_author_name':
                    global  $wpdb ;
                    $query->query_orderby = "ORDER BY {$wpdb->posts}.user_nicename ) {$order}";
                    break;
                case 'wptec_post_comment_count':
                    global  $wpdb ;
                    $query->query_orderby = "ORDER BY ( SELECT COUNT(*) FROM {$wpdb->comments} WHERE {$wpdb->posts}.ID = {$wpdb->comments}.comment_post_ID ) {$order}";
                    break;
                case 'wptec_post_comment_status':
                    $query->query_vars['orderby'] = 'comment_status';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_post_parent':
                    $query->query_vars['orderby'] = 'post_parent';
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
     * Update post information from admin user table via AJAX
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_postAJAX()
    {
        # exit the AJAX
        exit;
    }
    
    /**
     * Exporting CSV file this Function Will Create a CSV file and Than Download the File to User PC.
     * @since    1.0.0
     */
    public function wptec_postsCSV()
    {
        
        if ( is_user_logged_in() and current_user_can( 'administrator' ) ) {
            #------------------------------------------------------------------------------------------------------------
            # declaring empty holder
            $columnList = array();
            # This will be the First Row of the CSV sheet
            $columnListFirstRow = array();
            # getting the Saved options of User Table.
            $saved_column = get_user_option( 'wptec_post', get_current_user_id() );
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
                foreach ( $this->common->post_tab_default_columns as $key => $value ) {
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
            $hiddenColumns = get_user_meta( get_current_user_id(), 'manageedit-postcolumnshidden', true );
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
            $paginationNumber = get_user_option( 'wptec_post_pagination', get_current_user_id() );
            ####### Change this line ###########
            # if no pagination number then set 0 ; also redeeming  existing directory
            
            if ( !$paginationNumber ) {
                # base URL of the Upload directory
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
            # Getting User list Query;
            global  $wpdb ;
            # Creating Query string
            # running query and getting data from database; // $wpdb->
            $result = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status != 'auto-draft' LIMIT 23 OFFSET " . $offset, ARRAY_A );
            ####### Change this line ###########
            # if result is Empty that means you are at the end of your database row;
            
            if ( empty($result) ) {
                # Deleting [wptec_user_page_no] options
                delete_user_option( get_current_user_id(), 'wptec_post_pagination' );
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
                        if ( isset( array_merge( $this->common->post_tab_default_columns, $this->post_tab_extra_columns, $this->wptec_posts_metaKeys )[$colArray['name']] ) ) {
                            # populating $columnListFirstRow array() || CSV first row
                            $columnListFirstRow[$colArray['name']] = $colArray['title'];
                        }
                        # -------------------------------- Default columns ----------------------------------------
                        # title
                        if ( $colArray['name'] == "title" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_title'] ) ? $rowArray['post_title'] : "" );
                        }
                        # author
                        if ( $colArray['name'] == "author" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_author'] ) ? $rowArray['post_author'] : "" );
                        }
                        # categories
                        
                        if ( $colArray['name'] == "categories" ) {
                            $post_categories = wp_get_post_categories( $rowArray['ID'] );
                            $rows[$rowKey][$colArray['name']] = ( (is_array( $post_categories ) or is_object( $post_categories )) ? json_encode( $post_categories ) : "" );
                        }
                        
                        # tags
                        if ( $colArray['name'] == "tags" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_type'] ) ? $rowArray['post_type'] : "" );
                        }
                        # comments
                        if ( $colArray['name'] == "comments" ) {
                            $rows[$rowKey][$colArray['name']] = '--@--';
                        }
                        # date
                        if ( $colArray['name'] == "date" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_date'] ) ? $rowArray['post_date'] : "" );
                        }
                        # --------------------------------- WPTEC columns ---------------------------------------
                        # wptec_post_id
                        if ( $colArray['name'] == "wptec_post_id" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? $rowArray['ID'] : "" );
                        }
                        # wptec_author_id
                        if ( $colArray['name'] == "wptec_author_id" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_author'] ) ? $rowArray['post_author'] : "" );
                        }
                        # wptec_post_er_time
                        
                        if ( $colArray['name'] == "wptec_post_er_time" ) {
                            $word = str_word_count( strip_tags( $rowArray['post_content'] ) );
                            $m = floor( $word / 200 );
                            $s = floor( $word % 200 / (200 / 60) );
                            $est = $m . ' minute' . (( $m == 1 ? '' : 's' )) . ', ' . $s . ' second' . (( $s == 1 ? '' : 's' ));
                            $rows[$rowKey][$colArray['name']] = $est;
                        }
                        
                        # wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_post_excerpt" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_excerpt'] ) ? $rowArray['post_excerpt'] : "" );
                        }
                        # wptec_post_attachment_count
                        
                        if ( $colArray['name'] == "wptec_post_attachment_count" ) {
                            $attachments = get_children( array(
                                'post_parent' => $rowArray['ID'],
                            ) );
                            $count = count( $attachments );
                            $rows[$rowKey][$colArray['name']] = $count;
                        }
                        
                        # wptec_post_author_name
                        if ( $colArray['name'] == "wptec_post_author_name" ) {
                            $rows[$rowKey][$colArray['name']] = ( ($rowArray['ID'] and !empty($rowArray['ID'])) ? get_userdata( $rowArray['ID'] )->display_name : "" );
                        }
                        # wptec_post_comment_count
                        if ( $colArray['name'] == "wptec_post_comment_count" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_count'] ) ? $rowArray['comment_count'] : "" );
                        }
                        # wptec_post_comment_status
                        if ( $colArray['name'] == "wptec_post_comment_status" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['comment_status'] ) ? $rowArray['comment_status'] : "" );
                        }
                        # wptec_post_parent
                        if ( $colArray['name'] == "wptec_post_parent" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_parent'] ) ? $rowArray['post_parent'] : "" );
                        }
                        # wptec_post_word_count
                        
                        if ( $colArray['name'] == "wptec_post_word_count" ) {
                            $word = str_word_count( strip_tags( $rowArray['post_content'] ) );
                            $rows[$rowKey][$colArray['name']] = esc_html( $word );
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
            update_user_option( get_current_user_id(), 'wptec_post_pagination', $paginationNumber );
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