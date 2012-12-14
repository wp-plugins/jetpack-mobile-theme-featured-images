<?php
/*
 * Plugin Name: Jetpack Mobile Theme Featured images
 * Plugin URI: http://wordpress.org/extend/plugins/jetpack-mobile-theme-featured-images/
 * Description: Adds Featured Images before the content on the home page, in Jetpack Mobile theme
 * Author: Jeremy Herve
 * Version: 1.2
 * Author URI: http://jeremyherve.com
 * License: GPL2+
 * Text Domain: jetpack
 */
 
// Check if we are on mobile
// Props @saracannon http://ran.ge/2012/12/05/parallax-and-mobile/
function tweakjp_is_mobile_or_tablet() {
    if ( ! class_exists( 'Jetpack_User_Agent_Info' ) )
    	return false;

    $ua_info = new Jetpack_User_Agent_Info();
    return ( jetpack_is_mobile() || $ua_info->is_tablet() );
}

// On Mobile? Let's add the Featured Image
function tweakjp_maybe_add_filter() {
	if ( tweakjp_is_mobile_or_tablet() )
		add_filter( 'the_title', 'tweakjp_minileven_featuredimage' );
}
add_action( 'wp_head', 'tweakjp_maybe_add_filter' );

function tweakjp_minileven_featuredimage( $title ) {
	$tweak = has_post_thumbnail() && is_home() && in_the_loop();
	$featured_content = ( $tweak ) ? get_the_post_thumbnail() : '';

	return $title . $featured_content;
}
