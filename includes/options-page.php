<?php
if ( is_admin() ){
	// admin actions
  	add_action( 'admin_menu', 'extlbRegisterMenuEntry' );
  	add_action( 'admin_init', 'extlbRegisterSettings' );
} else {
  	// non-admin enqueues, actions, and filters
}

// Adding page to admin-menu
function extlbRegisterMenuEntry() {
	// add_options_page( 'Link Preview Settings', 'Link Preview', 'switch_themes', 'extlb-options-page', 'extlbOptionsPage', $icon_url, $position );

	$page_title = 'Link Preview Settings';
	$menu_title = 'Link Preview';
	$capability = 'switch_themes';
	$menu_slug = 'extlb-options-page';
	$function = 'extlbOptionsPage';
	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
}

// The options-page function
function extlbOptionsPage() {
	if ( !current_user_can( 'switch_themes' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	// HTML of the options-page
	?>

	<div class="wrap">
		<h1>Link Preview Settings</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'extlb-options' ); ?>
			<?php do_settings_sections( 'extlb-options' ); ?>

			<label for="extlb_darkmode">Darkmode?</label>
			<input type="checkbox" id="extlb_darkmode" name="extlb_darkmode" <?php echo (get_option('extlb_darkmode') == 'on') ? 'checked' : 'false'; ?>/>
			<br><br>
			<label for="extlb_disable_mobile">Disable on mobile (< 720px)?</label>
			<input type="checkbox" id="extlb_disable_mobile" name="extlb_disable_mobile"<?php echo (get_option('extlb_disable_mobile') == 'on') ? 'checked' : 'false'; ?>/>
			<br><br>
			<label for="extlb_hide_thumbnails">Hide Thumbnails?</label>
			<input type="checkbox" id="extlb_hide_thumbnails" name="extlb_hide_thumbnails"<?php echo (get_option('extlb_hide_thumbnails') == 'on') ? 'checked' : 'false'; ?>/>
			<br><br>
			<label for="extlb_link_selector">Selector <span>Default is .entry-content a</span></label>
			<input type="text" id="extlb_link_selector" name="extlb_link_selector" value="<?php echo esc_attr( get_option('extlb_link_selector') ); ?>" />

			<?php submit_button(); ?>
		</form>
	</div>

	<?php
}

// Register settings
function extlbRegisterSettings() {
  register_setting( 'extlb-options', 'extlb_darkmode' );
  register_setting( 'extlb-options', 'extlb_disable_mobile' );
  register_setting( 'extlb-options', 'extlb_hide_thumbnails' );
  register_setting( 'extlb-options', 'extlb_link_selector' );
}