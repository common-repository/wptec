<?php

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wptec
 * @subpackage Wptec/admin
 * @author     javmah <jaedmah@gmail.com>
 */
class Wptec_Media
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
     * List of wptec media extra fields 
     * @since    1.0.0
     * @access   private
     * @array    extra fields key name array 
     */
    private  $media_tab_extra_columns = array() ;
    /**
     * List of wptec media extra fields 
     * @since    1.0.0
     * @access   private
     * @array    extra fields key name array 
     */
    private  $wptec_media_metaKeys = array() ;
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
        $this->media_tab_extra_columns = $common->media_tab_extra_columns;
        # user table meta fields
        $this->wptec_media_metaKeys = ( $common->wptec_media_metaKeys()[0] ? $common->wptec_media_metaKeys()[1] : array() );
    }
    
    public function wptec_media_admin_notices()
    {
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * adding download button on the media table top.
     * @since    1.0.0
     */
    public function wptec_media_table_manage_posts( $value = '' )
    {
        if ( is_admin() and get_current_screen()->id == 'upload' ) {
            # Adding Download Button
            echo  "<span class='button wptecDownload' id='CSVdownloadButton' data-action='wptec_mediaCSV' style='text-decoration: none; margin-left: 5px;'> Export media CSV </span>" ;
        }
    }
    
    /**
     * Media table columns.
     * @since    1.0.0
     * @param    array    $columns      list of columns with  key and name.
     */
    public function wptec_media_columns( $columns )
    {
        #media table saved data
        $media_table = get_user_option( 'wptec_media', get_current_user_id() );
        # check and Balance
        
        if ( !empty($media_table) ) {
            # unseating all columns
            unset( $columns );
            $columns['cb'] = 'select all';
            # Looping the column list
            foreach ( $media_table as $key => $columnArray ) {
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
     * column content func.
     * @since      1.0.0
     * @param      string    $column_name    Column name.
     * @param      string    $post_id   	 media post id .
     */
    public function wptec_media_columns_content( $column, $post_id )
    {
        # lock the display page
        // if(get_current_screen()->id != "upload"){
        // 	return;
        // }
        $attachment_data = wp_prepare_attachment_for_js( $post_id );
        # media id column
        
        if ( $column == 'wptec_media_id' ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $post_id ) ;
            echo  "</span>" ;
        }
        
        # media file name column
        
        if ( $column == 'wptec_media_filename' ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['filename'] ) ;
            echo  "</span>" ;
        }
        
        # Media file author ID column
        
        if ( $column == 'wptec_media_author' ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['authorName'] ) ;
            echo  "</span>" ;
        }
        
        # media file size column
        if ( $column == 'wptec_media_file_sizes' ) {
            
            if ( isset( $attachment_data['filesizeHumanReadable'] ) and !empty($attachment_data['filesizeHumanReadable']) ) {
                echo  "<span class='wptecDisplay'>" ;
                echo  esc_html( $attachment_data['filesizeHumanReadable'] ) ;
                echo  "</span>" ;
            }
        
        }
        # media alternate text column
        
        if ( $column == 'wptec_media_alternate_text' ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['alt'] ) ;
            echo  "</span>" ;
        }
        
        # media caption column
        
        if ( $column == 'wptec_media_caption' ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $post_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' id='" . esc_attr( $post_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $attachment_data['caption'] ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='media' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='media' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['caption'] ) ;
            echo  "</span>" ;
        }
        
        # Media Description
        
        if ( $column == 'wptec_media_description' ) {
            # Input span Starts
            echo  "<span class='wptecInput' style='display:none;'" ;
            echo  "data-id='" . esc_attr( $post_id ) . "'" ;
            echo  "data-name='" . esc_attr( $column ) . "'" ;
            echo  "data-type='text'" ;
            echo  "data-status='wptecExtra'" ;
            echo  "data-editable='" . $this->wptec_editable . "'>" ;
            echo  "<input type='text' id='" . esc_attr( $post_id ) . esc_attr( $column ) . "' name='" . esc_attr( $column ) . "' value='" . esc_attr( $attachment_data['description'] ) . "' class='large-text' >" ;
            # action button
            echo  "<button type='button' data-from='media' class='yes'><span class='dashicons dashicons-yes'></span></button>" ;
            echo  "<button type='button' data-from='media' class='no'><span class='dashicons dashicons-no-alt'></span></button>" ;
            echo  "</span>" ;
            # Display span Starts
            echo  "<span  class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['description'] ) ;
            echo  "</span>" ;
        }
        
        # media file full path column
        
        if ( $column == 'wptec_media_file_author_id' ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['url'] ) ;
            echo  "</span>" ;
        }
        
        # media file full path column
        
        if ( $column == 'wptec_media_full_path' ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['url'] ) ;
            echo  "</span>" ;
        }
        
        # Media mime column
        
        if ( $column == 'wptec_media_mime' ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['mime'] ) ;
            echo  "</span>" ;
        }
        
        # Media date column
        
        if ( $column == 'wptec_media_date' ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['date'] ) ;
            echo  date( 'Y-m-d', $attachment_data['date'] ) ;
            echo  "</span>" ;
        }
        
        # media modified date column
        
        if ( $column == 'wptec_media_modified_date' ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  date_i18n( $attachment_data['dateFormatted'], $attachment_data['modified'] ) ;
            echo  date( 'Y-m-d', esc_html( $attachment_data['modified'] ) ) ;
            echo  "</span>" ;
        }
        
        # media height column
        
        if ( $column == 'wptec_media_height' and isset( $attachment_data['height'] ) ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['height'] ) ;
            echo  "</span>" ;
        }
        
        # Media width column
        
        if ( $column == 'wptec_media_width' and isset( $attachment_data['width'] ) ) {
            echo  "<span class='wptecDisplay'>" ;
            echo  esc_html( $attachment_data['width'] ) ;
            echo  "</span>" ;
        }
    
    }
    
    /**
     * Below is a Call back function of [manage_users_sortable_columns] 
     * This Function will Make column sortable 
     * @since    1.0.0
     * @param    string    $columns       The name of this plugin.
     */
    public function wptec_manage_media_sortable_columns( $columns )
    {
        # wptec
        $extra_columns = $this->media_tab_extra_columns;
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
    public function wptec_manage_media_sortable_columns_query( $query )
    {
        # check isset or not
        
        if ( isset( $query->query_vars['orderby'], $query->query_vars['order'] ) ) {
            # getting order by
            $orderby = sanitize_sql_orderby( $query->query_vars['orderby'] );
            # getting ascending and descending value
            $order = ( $query->query_vars['order'] == 'asc' ? 'ASC' : 'DESC' );
            # looking for the keys
            switch ( $orderby ) {
                case 'wptec_media_id':
                    $query->query_vars['orderby'] = 'ID';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_media_filename':
                    $query->query_vars['orderby'] = 'post_title';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_media_author':
                    $query->query_vars['orderby'] = 'post_author';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_media_caption':
                    $query->query_vars['orderby'] = 'post_excerpt';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_media_description':
                    $query->query_vars['orderby'] = 'post_content';
                    $query->query_vars['order'] = $order;
                    break;
                case 'wptec_media_mime':
                    $query->query_vars['orderby'] = 'post_mime_type';
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
     * Update Media information from admin user table via AJAX
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_mediaAJAX()
    {
        # exit the AJAX
        exit;
    }
    
    /**
     * Update post information from admin user table via AJAX
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wptec_mediaCSV()
    {
        
        if ( is_user_logged_in() and current_user_can( 'administrator' ) ) {
            #------------------------------------------------------------------------------------------------------------
            # declaring empty holder
            $columnList = array();
            # This will be the First Row of the CSV sheet
            $columnListFirstRow = array();
            # getting the Saved options of User Table.
            $saved_column = get_user_option( 'wptec_media', get_current_user_id() );
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
                foreach ( $this->common->media_tab_default_columns as $key => $value ) {
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
            $paginationNumber = get_user_option( 'wptec_media_pagination', get_current_user_id() );
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
            $result = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'attachment' LIMIT 23 OFFSET " . $offset, ARRAY_A );
            ####### Change this line ###########
            # if result is Empty that means you are at the end of your database row;
            
            if ( empty($result) ) {
                # Deleting [wptec_user_page_no] options
                delete_user_option( get_current_user_id(), 'wptec_media_pagination' );
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
                        if ( isset( array_merge( $this->common->media_tab_default_columns, $this->media_tab_extra_columns, $this->wptec_media_metaKeys )[$colArray['name']] ) ) {
                            # populating $columnListFirstRow array() || CSV first row
                            $columnListFirstRow[$colArray['name']] = $colArray['title'];
                        }
                        # getting attachment Details
                        $attachment_data = wp_prepare_attachment_for_js( $rowArray['ID'] );
                        # -------------------------------- Default columns ----------------------------------------
                        #  title
                        if ( $colArray['name'] == "title" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_name'] ) ? $rowArray['post_name'] : "" );
                        }
                        #  author
                        if ( $colArray['name'] == "author" ) {
                            $rows[$rowKey][$colArray['name']] = ( ($rowArray['post_author'] and !empty($rowArray['post_author'])) ? get_userdata( $rowArray['post_author'] )->display_name : "" );
                        }
                        #  categories
                        if ( $colArray['name'] == "parent" ) {
                            $rows[$rowKey][$colArray['name']] = ( ($rowArray['post_parent'] and !empty($rowArray['post_parent'])) ? get_post( $rowArray['post_parent'] )->post_name : "" );
                        }
                        #  comments
                        if ( $colArray['name'] == "comments" ) {
                            $rows[$rowKey][$colArray['name']] = '--@--';
                        }
                        #  date
                        if ( $colArray['name'] == "date" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_date'] ) ? $rowArray['post_date'] : "" );
                        }
                        # --------------------------------- WPTEC columns ---------------------------------------
                        #  wptec_post_id
                        if ( $colArray['name'] == "wptec_media_id" ) {
                            $rows[$rowKey][$colArray['name']] = $rowArray['ID'];
                        }
                        #  wptec_author_id
                        if ( $colArray['name'] == "wptec_media_filename" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_title'] ) ? $rowArray['post_title'] : "" );
                        }
                        #  wptec_post_er_time
                        if ( $colArray['name'] == "wptec_media_author" ) {
                            $rows[$rowKey][$colArray['name']] = get_userdata( $rowArray['ID'] )->display_name;
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_file_sizes" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['filesizeHumanReadable'] ) ? $rowArray['filesizeHumanReadable'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_alternate_text" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['alt'] ) ? $rowArray['alt'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_caption" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['caption'] ) ? $rowArray['caption'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_description" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['description'] ) ? $rowArray['description'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_file_author_id" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['ID'] ) ? $rowArray['ID'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_full_path" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['guid'] ) ? $rowArray['guid'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_mime" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_mime_type'] ) ? $rowArray['post_mime_type'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_date" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_date'] ) ? $rowArray['post_date'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_modified_date" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['post_modified'] ) ? $rowArray['post_modified'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_height" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['height'] ) ? $rowArray['height'] : "" );
                        }
                        #  wptec_post_excerpt
                        if ( $colArray['name'] == "wptec_media_width" ) {
                            $rows[$rowKey][$colArray['name']] = ( isset( $rowArray['width'] ) ? $rowArray['width'] : "" );
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
            update_user_option( get_current_user_id(), 'wptec_media_pagination', $paginationNumber );
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