<?php
       /*
   Plugin Name: RideWithGPS Utilities
   Plugin URI: http://danieljblumenfeld.com/ridewithgps-wordpress-plugin/
   Description: A plugin to provide utility functions to aid in integrating RideWithGPS data with Wordpress
   Version: 0.0.3
   Author: Dan Blumenfeld
   Author URI: http://danieljblumenfeld.com
   License: GPL2
   */

   /**************************************************************************/
   /* Version History                                                        */ 
   /**************************************************************************/ 
   /*
		0.0.2		1/12/2015	created initial plugin, includes user, map and elevation img shortcodes
        0.0.3       1/12/2015   Added shortcode and logic to render a RideWithGPS widget
   */

   /**************************************************************************/
   /* Ride Widget                                                            */ 
   /**************************************************************************/
   /*
   Based on http://ridewithgps.com/widgets/new

   Minimum, displaying only a link:
   <a class="rwgps-widget" href="http://ridewithgps.com/users/1746" data-rwgps-width="300" data-rwgps-user-id="1746">Activities for Dan Blumenfeld</a>
   Displaying last 3 recent activities:
   <a class="rwgps-widget" href="http://ridewithgps.com/users/1746" data-rwgps-width="300" data-rwgps-user-id="1746" data-rwgps-activities-count="3">Activities for Dan Blumenfeld</a>
   Displaying summary stats for last 7 days, last 30 days, and year to date:
   <a class="rwgps-widget" href="http://ridewithgps.com/users/1746" data-rwgps-width="300" data-rwgps-user-id="1746" data-rwgps-include="week month year">Activities for Dan Blumenfeld</a>

   So, variable data is user id (1746 in example above), width (may be px or %) and the base activity string ("Activities for Dan Blumenfeld" in examples above)
   To display in metric, add data-rwgps-metric="1" attribute
   To display recent activities, add data-rwgps-activities-count="3" attribute. Limit it to 0-7
   To display summary stats (7 days, 30 days, year to date), add data-rwgps-include="week month year" attribute. If none specified, add week by default


   All are then followed by:
    <script>
    (function(d,s) { 
      if(!d.getElementById('rwgps-sdk')) {
        var el = d.getElementsByTagName(s)[0],
            js = d.createElement(s);
        js.id = 'rwgps-sdk';
        js.src = "//ridewithgps.com/sdk.min.js";
        el.parentNode.insertBefore(js, el);
      }
    })(document, 'script');
    </script>


   Wordpress widget plugin info :http://www.wpexplorer.com/create-widget-plugin-wordpress/

   */

   //Do we need a separate JS file? Or can we inline it, as shown in the RWGPS widget editor?
    function rwgps_admin_load_js(){
       wp_enqueue_script( 'custom_js', plugins_url( '/js/djb-rwgps.js', __FILE__ ));
    }
    add_action('admin_enqueue_scripts', 'rwgps_admin_load_js');


    function render_rwgps_widget($rwgps_userid, $width, $activity_link_text, $use_metric, $num_recent_activities, $show_summary_week, $show_summary_month, $show_summary_year){
        ?><a class="rwgps-widget" href="http://ridewithgps.com/users/<?php 
            echo esc_attr($rwgps_userid) ?>" data-rwgps-width="<?php 
            echo esc_attr($width) ?>" data-rwgps-user-id="<?php 
            echo esc_attr($rwgps_userid) ?>" <?php 
            if($num_recent_activities > 0) 
            {
                echo ' data-rwgps-activities-count="'; 
                echo $num_recent_activities; 
                echo '"';
            } 

            //TODO: probably a better way to do this. Populate an array?
            if($show_summary_week == TRUE || $show_summary_month == TRUE || $show_summary_year == TRUE)
            {
                echo ' data-rwgps-include="';
                if($show_summary_week == TRUE) echo 'week';
                if($show_summary_month == TRUE) echo ' month';
                if($show_summary_year == TRUE) echo ' year';
                echo '"';
            }

            if($use_metric == TRUE) 
            {
               echo ' data-rwgps-metric="1"'; 
            }?>><?php 
            echo esc_attr($activity_link_text) ?></a>
    <script>
    (function(d,s) { 
      if(!d.getElementById('rwgps-sdk')) {
        var el = d.getElementsByTagName(s)[0],
            js = d.createElement(s);
        js.id = 'rwgps-sdk';
        js.src = "//ridewithgps.com/sdk.min.js";
        el.parentNode.insertBefore(js, el);
      }
    })(document, 'script');
    </script>

   <?php
    }

    function rwgps_widget_shortcode($atts) {
        extract( shortcode_atts( array(
            'rwgps_userid' => '0000000',
            'width' => '300px',
            'activity_link_text' => 'RideWithGPS Activities',
            'use_metric' => FALSE,
            'num_recent_activities' => 3,
            'show_summary_week' => FALSE,
            'show_summary_month' => FALSE,
            'show_summary_year' => FALSE,
        ), $atts, 'rwgps_widget'));

        if($rwgps_userid == '0000000')
        {
            //TODO: Try to get the id of the current user from usermeta
        }
        //TODO: validate num_recent_activities as being a number between 0 and 7 inclusive

        render_rwgps_widget($rwgps_userid, $width, $activity_link_text, $use_metric, $num_recent_activities, $show_summary_week, $show_summary_month, $show_summary_year);
    }
    add_shortcode('rwgps-widget', 'rwgps_widget_shortcode');

   /**************************************************************************/
   /* Mapping Shortcodes                                                     */ 
   /**************************************************************************/ 
   /*
   Embed the elevation profile image for the supplied route id
   [rwgps-elevation route=1234567], resolves to <img src="http://ridewithgps.com/routes/1234567/elevation_profile" />
   */
    function rwgps_elevation_img_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
        ), $atts, 'elevation'));

        return sprintf('<img src="http://ridewithgps.com/routes/%1$s/elevation_profile" />', $route);
    }
    add_shortcode('rwgps-elevation', 'rwgps_elevation_img_shortcode');


   /*
   Embed the map image for the supplied route id
   [rwgps-map route=1234567], resolves to <img src="http://ridewithgps.com/routes/1234567/full.png" />
   */
   function rwgps_map_img_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
        ), $atts, 'map'));

        return sprintf('<img src="http://ridewithgps.com/routes/%1$s/full.png" />', $route);
    }
    add_shortcode('rwgps-map', 'rwgps_map_img_shortcode');

   /**************************************************************************/
   /* User Shortcodes                                                        */ 
   /**************************************************************************/ 
   /*
   Add a link to the RideWithGPS profile page for the given user
   [rwgps-profile id=12345 name=&quot;Joe Biker&quot; ], resolves to  <a href="http://ridewithgps.com/users/12345">Joe Biker</a>
   */
   function rwgps_profile_shortcode($atts) {
        extract( shortcode_atts( array(
            'id' => '000000',
            'name' => '',
        ), $atts, 'user'));

        return sprintf('<a href="http://ridewithgps.com/users/%1$s">%2$s</a>', $id, $name);
    }
    add_shortcode('rwgps-profile', 'rwgps_profile_shortcode');


   /**************************************************************************/
   /* User Metadata - RWGPS id                                               */ 
   /**************************************************************************/ 

   function display_rwgps_user_profile_info($user){
       ?>
            <h3>RideWithGPS Profile</h3>
            <table class="form-table">
                <tr>
                    <th><label for="id">User Id</label></th>
                    <td>
                        <input type="text" name="rwgps_userid" value="<?php echo esc_attr( get_the_author_meta( 'rwgps_userid', $user->ID ) ); ?>" class="regular-text" />
                        <br/>
                        <span class="description">Please enter your RideWithGPS user id.</span>
                    </td>
                </tr>
                <tr>
                    <th><label for="id">Profile Link Label</label></th>
                    <td>
                        <input type="text" name="rwgps_user_profile_text" value="<?php echo esc_attr( get_the_author_meta( 'rwgps_user_profile_text', $user->ID ) ); ?>" class="regular-text" />
                        <br/>
                        <span class="description">Please enter the text you wish to display for your RideWithGPS profile link.</span>
                    </td>
                </tr>
            </table>
    <?php }

   add_action('show_user_profile', 'display_rwgps_user_profile_info');
   add_action('edit_user_profile', 'display_rwgps_user_profile_info');

   add_action('personal_options_update', 'update_rwgps_user_profile_info');
   add_action('edit_user_profile_update', 'update_rwgps_user_profile_info');

   function update_rwgps_user_profile_info( $user_id ){
         
       //if ( !current_user_can( 'edit_user', $user_id ) )
       // return FALSE;

       //global $current_user;
       //if(!user_can($current_user->ID, 'edit_user'));
		//return FALSE;
	
	update_usermeta( $user_id, 'rwgps_userid', $_POST['rwgps_userid'] );
    update_usermeta( $user_id, 'rwgps_user_profile_text', $_POST['rwgps_user_profile_text'] );
   }

   
?>


