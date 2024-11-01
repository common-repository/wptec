<div class='wrap' id='wptecApp'>
    <!-- TESTING  -->
   <!-- <pre>  -->
        <!-- {{ mediaList }} -->
   <!-- </pre>  -->

	<div id='icon-options-general' class='icon32'></div>
	<h1>WordPress Admin Tables Extra Columns Settings.</h1>
	<div id='poststuff'>
        <div id='post-body' class='metabox-holder columns-2' >
            <!-- main content  -->
            <div id='post-body-content'>
                <!-- New Code Starts  -->
                    <div  v-if="notification != '' " @click="notification = '' " class="notice notice-success is-dismissible inline"><p>{{notification}}</p></div>
                <!-- New Code Ends  -->

                <div class='meta-box-sortables ui-sortable'>
                    <div class='postbox'>
                        <h2><span><?php esc_attr_e('List of tables.', 'wptec');?></span></h2>
                        <div class='inside'>
                            <h2 class='nav-tab-wrapper' style='padding: unset;'>
                                <a href='#' id='userTab' @click="activeTab = 'user'" :class="[activeTab == 'user' ? 'nav-tab nav-tab-active' : 'nav-tab']">
                                    User Table
                                    <span v-if='columnListFrom.user' @dblclick="actionPanelResetBtnClicked()" style='margin-top: 3px;' title='Display from saved list. Double-click cross icon for reset default.' class="dashicons dashicons-dismiss"></span>  
                                </a>
                                <a href='#' id='postTab' @click="activeTab = 'post'" :class="[activeTab == 'post' ? 'nav-tab nav-tab-active' : 'nav-tab']"> 
                                    Post Table
                                    <span v-if='columnListFrom.post' @dblclick="actionPanelResetBtnClicked()"  style='margin-top: 3px;' title='Display from saved list. Double-click cross icon for reset default.' class="dashicons dashicons-dismiss"></span>
                                </a>
                                <a href='#' id='pageTab' @click="activeTab = 'page'" :class="[activeTab == 'page' ? 'nav-tab nav-tab-active' : 'nav-tab']"> 
                                    Page Table
                                    <span v-if='columnListFrom.page' @dblclick="actionPanelResetBtnClicked()"  style='margin-top: 3px;' title='Display from saved list. Double-click cross icon for reset default.' class="dashicons dashicons-dismiss"></span>
                                </a>
                                <a href='#' id='commentTab' @click="activeTab = 'comment'" :class="[activeTab == 'comment' ? 'nav-tab nav-tab-active' : 'nav-tab']">
                                    Comment Table
                                    <span v-if='columnListFrom.comment' @dblclick="actionPanelResetBtnClicked()" style='margin-top: 3px;' title='Display from saved list. Double-click cross icon for reset default.' class="dashicons dashicons-dismiss"></span>
                                </a>
                                <a href='#' id='mediaTab' @click="activeTab = 'media'" :class="[activeTab == 'media' ? 'nav-tab nav-tab-active' : 'nav-tab']"> 
                                    Media Table
                                    <span v-if='columnListFrom.media' @dblclick="actionPanelResetBtnClicked()" style='margin-top: 3px;'  title='Display from saved list. Double-click cross icon for reset default.' class="dashicons dashicons-dismiss"></span>
                                </a>
                                <a href='#' id='productTab' @click="activeTab = 'product'" :class="[activeTab == 'product' ? 'nav-tab nav-tab-active' : 'nav-tab']"> 
                                    Product Table
                                    <span  v-if='columnListFrom.product' @dblclick="actionPanelResetBtnClicked()" style='margin-top: 3px;' title='Display from saved list. Double-click cross icon for reset default.' class="dashicons dashicons-dismiss"></span>
                                </a>
                                <a href='#' id='orderTab' @click="activeTab = 'order'" :class="[activeTab == 'order' ? 'nav-tab nav-tab-active' : 'nav-tab']"> 
                                    Order Table 
                                    <span v-if='columnListFrom.order' @dblclick="actionPanelResetBtnClicked()" style='margin-top: 3px;' title='Display from saved list. Double-click cross icon for reset default.' class="dashicons dashicons-dismiss"></span>
                                </a>
                            </h2>

                            <!-- # userPanel starts -->
                            <div id='userTabPanel' class='panels' v-if="activeTab == 'user'">
                                <ul id='userTabList' class='wptec_list' is="draggable" :list="userList" @change="listOrderChange" style="padding: 20px 20px 20px 20px;">
                                    <li v-for="property , key in userList" :key="key" class='wptecListLi'>
                                        <span v-if="property.type === 'meta' " v-html="'[M] ' + property.title" class='liDispaly'></span>
                                        <span v-else-if="property.type === 'wptec'"  v-html="'[E] ' + property.title" class='liDispaly'></span>
                                        <span v-else class='liDispaly' v-html="property.title"></span>

                                        <code v-if="property.status" title='field is active' style='cursor: default;'>Active</code>
                                        <span @click="settingsIconClicked(property, key)" title='settings' class='dashicons dashicons-admin-generic settIcon' style='cursor: default;'>&nbsp;</span>      
                                    </li>
                                </ul>
                            </div>
                            <!-- #  userPanel Ends  -->
                            
                            <!-- # postPanel starts -->
                            <div id='postTabPanel' class='panels' v-if="activeTab == 'post'">
                                <ul id='postTabList' class='wptec_list' is="draggable" :list="postList" @change="listOrderChange" style="padding: 20px 20px 20px 20px;">
                                    <li v-for="property , key in postList" :key="key" class='wptecListLi'>
                                        <span v-if="property.type === 'meta' " v-html="'[M] ' + property.title" class='liDispaly'></span>
                                        <span v-else-if="property.type === 'wptec'" v-html="'[E] ' + property.title" class='liDispaly'></span>
                                        <span v-else class='liDispaly' v-html="property.title"></span>

                                        <code v-if="property.status" title='field is active' style='cursor: default;'>Active</code>
                                        <span @click="settingsIconClicked(property, key)" title='settings' class='dashicons dashicons-admin-generic settIcon' style='cursor: default;'>&nbsp;</span>      
                                    </li>
                                </ul>
                            </div>
                            <!-- #  userPanel Ends  -->
                            
                            <!-- # pagePanel starts -->
                            <div id='pageTabPanel' class='panels' v-if="activeTab == 'page'">
                                <ul id='pageTabList' class='wptec_list' is="draggable" :list="pageList" @change="listOrderChange" style="padding: 20px 20px 20px 20px;">
                                    <li v-for="property , key in pageList" :key="key" class='wptecListLi'>
                                        <span v-if="property.type === 'meta' " v-html="'[M] ' + property.title" class='liDispaly'></span>
                                        <span v-else-if="property.type === 'wptec'" v-html="'[E] ' + property.title" class='liDispaly'></span>
                                        <span v-else class='liDispaly' v-html="property.title"></span>

                                        <code v-if="property.status" title='field is active' style='cursor: default;'>Active</code>
                                        <span @click="settingsIconClicked(property, key)" title='settings' class='dashicons dashicons-admin-generic settIcon' style='cursor: default;'>&nbsp;</span>      
                                    </li>
                                </ul>
                            </div>   
                            <!-- #  pagePanel Ends  -->
                            
                            <!-- #commentPanel starts  -->
                            <div id='commentTabPanel' class='panels'  v-if="activeTab == 'comment'">
                                <ul id='commentTabList' class='wptec_list' is="draggable" :list="commentList" @change="listOrderChange" style="padding: 20px 20px 20px 20px;">
                                    <li v-for="property , key in commentList" :key="key" class='wptecListLi'>
                                        <span v-if="property.type === 'meta' " v-html="'[M] ' + property.title" class='liDispaly'></span>
                                        <span v-else-if="property.type === 'wptec'"  v-html="'[E] ' + property.title" class='liDispaly'></span>
                                        <span v-else class='liDispaly' v-html="property.title"></span>

                                        <code v-if="property.status" title='field is active' style='cursor: default;'>Active</code>
                                        <span @click="settingsIconClicked(property, key)" title='settings' class='dashicons dashicons-admin-generic settIcon' style='cursor: default;'>&nbsp;</span>      
                                    </li>
                                </ul>
                            </div>   
                            <!-- #  commentPanel Ends  -->
                            
                            <!-- #mediaPanel starts  -->
                            <div id='mediaTabPanel' class='panels' v-if="activeTab == 'media'">
                                <ul id='mediaTabList' class='wptec_list' is="draggable" :list="mediaList" @change="listOrderChange" style="padding: 20px 20px 20px 20px;">
                                    <li v-for="property , key in mediaList" :key="key" class='wptecListLi'>
                                        <span v-if="property.type === 'meta' " v-html="'[M] ' + property.title" class='liDispaly'></span>
                                        <span v-else-if="property.type === 'wptec'" v-html="'[E] ' + property.title" class='liDispaly'></span>
                                        <span v-else class='liDispaly' v-html="property.title"></span>

                                        <code v-if="property.status" title='field is active' style='cursor: default;'>Active</code>
                                        <span @click="settingsIconClicked(property, key)" title='settings' class='dashicons dashicons-admin-generic settIcon' style='cursor: default;'>&nbsp;</span>      
                                    </li>
                                </ul>
                            </div>   
                            <!-- #mediaPanel Ends  -->
                            
                            <?php if(is_plugin_active('woocommerce/woocommerce.php')){ ?>
                                <!-- #product Panel  -->
                                <div id='productTabPanel' class='panels' v-if="activeTab == 'product'">
                                    <ul id='productTabList' class='wptec_list' is="draggable" :list="productList" @change="listOrderChange" style="padding: 20px 20px 20px 20px;">
                                        <li v-for="property , key in productList" :key="key" class='wptecListLi'>
                                            <span v-if="property.type === 'meta' " v-html="'[M] ' + property.title" class='liDispaly'></span>
                                            <span v-else-if="property.type === 'wptec'"  v-html="'[E] ' + property.title" class='liDispaly'></span>
                                            <span v-else class='liDispaly' v-html="property.title"></span>

                                            <code v-if="property.status" title='field is active' style='cursor: default;'>Active</code>
                                            <span @click="settingsIconClicked(property, key)" title='settings' class='dashicons dashicons-admin-generic settIcon' style='cursor: default;'>&nbsp;</span>      
                                        </li>
                                    </ul>
                                </div>   
                                <!-- #product Panel  -->

                                <!-- #product Panel  -->
                                <div id='orderTabPanel' class='panels' v-if="activeTab == 'order'">
                                    <ul id='orderTabList' class='wptec_list' is="draggable" :list="orderList" @change="listOrderChange" style="padding: 20px 20px 20px 20px;">
                                        <li v-for="property , key in orderList" :key="key" class='wptecListLi'>
                                            <span v-if="property.type === 'meta' " v-html="'[M] ' + property.title" class='liDispaly'></span>
                                            <span v-else-if="property.type === 'wptec'" v-html="'[E] ' + property.title" class='liDispaly'></span>
                                            <span v-else class='liDispaly' v-html="property.title" ></span>

                                            <code v-if="property.status" title='field is active' style='cursor: default;'>Active</code>
                                            <span @click="settingsIconClicked(property, key)" title='settings' class='dashicons dashicons-admin-generic settIcon' style='cursor: default;'>&nbsp;</span>      
                                        </li>
                                    </ul>
                                </div>   
                                <!-- #product Panel  -->
                            <?php } ?>

                        </div>
                        <!-- .inside  -->
                    </div>
                    <!-- .postbox  -->
                </div>
                <!-- .meta-box-sortables .ui-sortable  -->
            </div>
            <!-- post-body-content  -->

            <!-- sidebar  -->
            <div id='postbox-container-1' class='postbox-container'>
                <div class='meta-box-sortables'>
                    <!-- This Part will show and Hide  -->
                    <div id='editPanel'  v-if="editPanelDisplay" class='postbox'>
                        <h2><span> Edit Panel. </span></h2>
                        <div class='inside'>
                            Column Label      : <input type='text' v-model="editProperty.title" id='wptecLabelEditField' style='width: 100%;'><br>
                            Column Width      : <input type='text' v-model="editProperty.width" id='wptecWidthEditField' style='width: 100%;'><br>
                            Enable / Disable  : <br><input type='checkbox' v-model="editProperty.status" id='wptecStatusEditField' name='status'><br><br>

                            <input class='button-primary'    @click="editPanelDisplay = false; actionPanelDisplay = true" type='submit' id='updateListItem' value='UPDATE'> &nbsp;
                            <input class='button-secondary'  @click="editPanelDisplay = false" type='submit' id='cancelListItem' value='CANCEL'>
                        </div>
                    </div>

                    <!-- .postbox  -->
                    <div id='actionPanel' v-if="actionPanelDisplay" class='postbox'>
                        <h2><span> Action Panel. </span></h2>
                        <div class='inside'>
                            <a href='#' @click="actionPanelSaveBtnClicked()"  id='saveList'   class='button-primary'>   SAVE </a>         &nbsp; 
                            <a href='#' @click="actionPanelDisplay = false"   id='cancelList' class='button-primary'>   CANCEL</a>        &nbsp; 
                            <a href='#' @click="actionPanelResetBtnClicked()" id='resetList'  class='button-secondary'> RESET DEFAULT</a> &nbsp; 
                        </div>
                    </div>

                    <!-- Width panel  -->
                    <div id='widthPanel' class='postbox'>
                        <h2><span> Columns Width Measurement Unit. </span></h2>
                        <div class='inside'>
                            <?php 
                                # getting $measurementUnit from user Options 
                                $measurementUnit  =  get_user_option('wptec_measurement',get_current_user_id());
                                # Displaying  and getting measurementUnit || Pixel is the Default here !
                                if( $measurementUnit == 'Percent'){
                                    echo"<i> <label onclick='window.location=\"admin.php?page=wptec&action=measurement&value=Pixel\"'   for='Pixel'>Pixel [px] <input type='radio' id='html' name='measurement' value='Pixel' ></label> </i> &nbsp; ";
                                    echo"<i> <label onclick='window.location=\"admin.php?page=wptec&action=measurement&value=Percent\"' for='Percent'>Percent [%] <input type='radio' id='html' name='measurement' value='Percent' checked='checked'></label> </i>";
                                } else {
                                    echo"<i> <label onclick='window.location=\"admin.php?page=wptec&action=measurement&value=Pixel\"'   for='Pixel'>Pixel [px] <input type='radio' id='html' name='measurement' value='Pixel' checked='checked'></label> </i> &nbsp; ";
                                    echo"<i> <label onclick='window.location=\"admin.php?page=wptec&action=measurement&value=Percent\"' for='Percent'>Percent [%] <input type='radio' id='html' name='measurement' value='Percent'></label> </i>";
                                }
                            ?>
                        </div>
                   </div>
                    <!-- .postbox  -->
                    <div class='postbox'>
                        <h2><span> Hello, Howdy. </span> <span class='dashicons dashicons-smiley'></span></h2>
                        <div class='inside'>
                            <p>
                                <i>  
                                    This Plugin has <b> 41 </b> files and  <b> 9371 </b> lines of code. Development, Testing, and Debugging takes a lot of time & patience.
                                    I tyred my best to give you the amazing experience. I hope you will appreciate my effort.  If you like this plugin, 
                                    please consider to leaves a 5-star review , It will inspire me to add more awesome feature.
                                    <br><br>
                                    Thankyou & Kindest regards.
                                    <br><br>
                                    <b>P.S : </b> let me know your questions & thoughts, jaedmah@gmail.com
                                </i> 
                            </p>
                            <br>
                            <?php echo"<a href='" . esc_url(admin_url('/admin.php?page=wptec&action=log')) . "'> <i> log ! log for Good. </i></a> &nbsp;  &nbsp;"; ?>
                            <i><a @click="resetDefaultTables()" href="#">Reset default all tables</a></i>
                            <br>
                        </div>
                    </div>
                    <!-- .postbox  -->
                </div>
                <!-- .meta-box-sortables -->
            </div>
            <!-- #postbox-container-1 .postbox-container  -->
        </div>
        <!-- #post-body .metabox-holder .columns-2  -->
        <br class='clear'>
	</div>
	 <!-- #poststuff  -->
</div>

<script>

</script>
<!--  Style of list! -->
<style>
    .nav-tab-active{
        background: white !important;
        border-bottom: 1px solid #fff !important;
    }   

    .wptecListLi{
        border: 2px dashed;
        border-color: #C8C8C8;
        padding: 10px 10px 10px 15px;
        cursor: move;
        width: 80%;
        margin-bottom: 5px;
        list-style-type:none;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }

    .liDispaly {
        margin-right: auto;
    }

    .column_list_title{
        padding-left: 20px;
    }
    
</style>




