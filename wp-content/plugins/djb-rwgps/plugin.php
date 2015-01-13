<?php
       /*
   Plugin Name: RideWithGPS Utilities
   Plugin URI: http://danieljblumenfeld.com/ridewithgps-wordpress-plugin/
   Description: A plugin to provide utility functions to aid in integrating RideWithGPS data with Wordpress
   Version: 0.0.1
   Author: Dan Blumenfeld
   Author URI: http://danieljblumenfeld.com
   License: GPL2
   */

   /**************************************************************************/
   /* Version History                                                        */ 
   /**************************************************************************/ 
   /*
		0.0.2		1/12/2015	created initial plugin, includes user, map and elevation img shortcodes
   */

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


