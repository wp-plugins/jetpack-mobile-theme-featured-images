<?php
/*
 * Plugin Name: Jetpack Mobile Theme Featured images
 * Plugin URI: http://wordpress.org/plugins/jetpack-mobile-theme-featured-images/
 * Description: Adds Featured Images before the content, in Jetpack's Mobile theme
 * Author: Jeremy Herve
 * Version: 1.6
 * Author URI: http://jeremy.hu/
 * License: GPL2+
 * Text Domain: jp_mini_featured
 */

// Load language files
function jp_mini_featured_textdomain() {
	load_plugin_textdomain( 'jp_mini_featured', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'jp_mini_featured_textdomain' );

// Change the filter thanks to our options
function jp_mini_featured_maybe_display() {

	$options = get_option( 'jp_mini_featured_options' );

	if ( isset( $options['show']['front'] ) && ( is_home() || is_front_page() || is_search() || is_archive() ) ) {
		return true;
	} elseif ( isset( $options['show']['post'] ) && is_single() ) {
		return true;
	} elseif ( isset( $options['show']['page'] ) && is_page() ) {
		return true;
	} else {
		return false;
	}

}
add_filter( 'minileven_show_featured_images', 'jp_mini_featured_maybe_display' );

/*
 * Options page
 */
// Init plugin options
function jp_mini_featured_init() {
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'jp_mini_featured_action_links' );

	register_setting( 'jp_mini_featured_group', 'jp_mini_featured_options' );
}
add_action( 'admin_init', 'jp_mini_featured_init' );

// Plugin settings link
function jp_mini_featured_action_links( $actions ) {
	return array_merge(
		array( 'settings' => sprintf( '<a href="options-general.php?page=jp_mini_featured">%s</a>', __( 'Settings', 'jetpack' ) ) ),
		$actions
	);
	return $actions;
}

// Add menu page
function jp_mini_featured_add_page() {
	add_options_page(
		__( 'Mobile Featured Images', 'jp_mini_featured' ),
		__( 'Mobile Featured Image Settings', 'jp_mini_featured' ),
		'manage_options',
		'jp_mini_featured',
		'jp_mini_featured_do_page'
	);
}
add_action( 'admin_menu', 'jp_mini_featured_add_page' );

// Draw the menu page itself
function jp_mini_featured_do_page() {
	?>
	<div class="wrap">
		<h2><?php _e( 'Featured Image Settings', 'jp_mini_featured' ); ?></h2>

		<?php if ( ! class_exists( 'Jetpack' ) || ! Jetpack::is_module_active( 'minileven' ) ) : ?>
			<div class="error"><p>
				<?php
				printf(__( 'To use the Featured Images for Jetpack plugin, you\'ll need to install and activate <a href="%1$s">Jetpack</a> first, and <a href="%2$s">activate the Mobile Theme module</a>.'),
				'plugin-install.php?tab=search&s=jetpack&plugin-search-input=Search+Plugins',
				'admin.php?page=jetpack_modules',
				'jp_mini_featured'
				);
				?>
			</p></div>
		<?php endif; // End check if Jetpack and the Mobile Theme are active ?>

		<form method="post" action="options.php">
			<?php

			settings_fields( 'jp_mini_featured_group' );
			$options = get_option( 'jp_mini_featured_options' );

			// Default to show Featured Images on the front page only
			if ( ! isset( $options['show'] ) ) {
				$options['show'] = array(
					'front' => 1,
					'post'  => 0,
					'page'  => 0,
				);
			}
			?>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e( 'Where do you want Featured Images to appear?', 'jp_mini_featured' ); ?></th>
					<td>
						<label>
						<input type="checkbox" name="jp_mini_featured_options[show][front]" value="1" <?php if ( isset( $options['show']['front'] ) ) echo 'checked="checked"'; ?> />
						<?php _e( 'Front Page, Archive Pages, and Search Results', 'jetpack' ); ?>
						</label>
						<br>
						<label>
						<input type="checkbox" name="jp_mini_featured_options[show][post]" value="1" <?php if ( isset( $options['show']['post'] ) ) echo 'checked="checked"'; ?> />
						<?php _e( 'Posts' ); ?>
						</label>
						<br>
						<label>
						<input type="checkbox" name="jp_mini_featured_options[show][page]" value="1" <?php if ( isset( $options['show']['page'] ) ) echo 'checked="checked"'; ?> />
						<?php _e( 'Pages' ); ?>
						</label>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}
