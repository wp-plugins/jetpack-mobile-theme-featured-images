<?php
/*
 * Plugin Name: Jetpack Mobile Theme Featured images
 * Plugin URI: http://wordpress.org/extend/plugins/jetpack-mobile-theme-featured-images/
 * Description: Adds Featured Images before the content on the home page, in Jetpack Mobile theme
 * Author: Jeremy Herve
 * Version: 1.5
 * Author URI: http://jeremyherve.com
 * License: GPL2+
 * Text Domain: jetpack
 */


/*
 * Options page
 */

add_action( 'admin_init', 'jp_mini_featured_init' );

// Init plugin options
function jp_mini_featured_init() {
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'jp_mini_featured_action_links' );
	
	register_setting( 'jp_mini_featured_options', 'jp_mini_featured_strings', 'jp_mini_featured_validate' );
	
	// Add settings to the Minileven Options page
	add_action( 'jetpack_module_configuration_screen_minileven', 'jp_mini_featured_configuration_load' );
	add_action( 'jetpack_module_configuration_screen_minileven', 'jp_mini_featured_do_page' );
}

// Plugin settings link
function jp_mini_featured_action_links( $actions ) {
	return array_merge(
		array( 'settings' => sprintf( '<a href="admin.php?page=jetpack&configure=minileven">%s</a>', __( 'Settings', 'jetpack' ) ) ),
		$actions
	);
	return $actions;
}

// Prepare option page
function jp_mini_featured_configuration_load() {
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'save_options' && $_POST['_wpnonce'] == wp_create_nonce( 'jp_mini_featured' ) ) {

		update_option( 'wp_mobile_featured_images', ( isset( $_POST['wp_mobile_featured_images'] ) ) ? '1' : '0' );

		Jetpack::state( 'message', 'module_configured' );
		wp_safe_redirect( Jetpack::module_configuration_url( 'minileven' ) );
		exit;
	}
}

// Draw the menu page itself
function jp_mini_featured_do_page() {
	$feat_home = ( 1 == get_option( 'wp_mobile_featured_images' ) ) ? 1 : 0;
	?>
	<h3>Featured Images</h3>
	<form method="post">
		<input type="hidden" name="action" value="save_options" />
		<?php wp_nonce_field( 'jp_mini_featured' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Show featured images on front page and archive pages', 'jetpack' ); ?></th>
			<td>
				<label for="wp_mobile_featured_images">
					<input name="wp_mobile_featured_images" type="radio" value="1" class="code" <?php checked( 1, $feat_home, true ); ?> /> 
					<?php _e( 'Yes' ); ?> 
					<input name="wp_mobile_featured_images" type="radio" value="0" class="code" <?php checked( 0, $feat_home, true ); ?> /> 
					<?php _e( 'No' ); ?> 
				</label>
			</td>
			</tr>
		</table>
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e( 'Save Configuration', 'jetpack' ) ?>" />
		</p>
	</form> 
<?php 	
}
