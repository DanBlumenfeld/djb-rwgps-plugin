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
		0.0.1		1/12/2015	created initial plugin, includes map and elevation img shortcodes
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
   /* User Shortcodes                                                     */ 
   /**************************************************************************/ 
   /*
   Add a link to the RideWithGPS profile page for the given user
   [rwgps-profile id=12345 name=&quot;Joe Biker&quot; ], resolves to  <a href="http://ridewithgps.com/users/12345">Joe Biker</a>
   */





?>


