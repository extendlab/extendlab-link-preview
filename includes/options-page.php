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

	$page_title = esc_html__('Extendlab - Link Preview Settings', 'extendlab-link-preview');
	$menu_title = esc_html__('Extendlab - Link Preview', 'extendlab-link-preview');
	$capability = 'switch_themes';
	$menu_slug = 'extlb-options-page';
	$function = 'extlbOptionsPage';
	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
}

// The options-page function
function extlbOptionsPage() {
	if ( !current_user_can( 'switch_themes' ) )  {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'extendlab-link-preview' ) );
	}

	// HTML of the options-page
	?>

	<div class="wrap">
		<h1><?php _e('Extendlab - Link Preview Settings', 'extendlab-link-preview') ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'extlb-options' ); ?>
			<?php do_settings_sections( 'extlb-options' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php _e('Appearance', 'extendlab-link-preview') ?></th>
						<td>
							<fieldset>
								<label for="extlb_darkmode">
										<input type="checkbox" id="extlb_darkmode" name="extlb_darkmode" <?php echo (get_option('extlb_darkmode') == 'on') ? 'checked' : 'false'; ?>/>
										<?php _e('Darkmode on', 'extendlab-link-preview') ?>
								</label>
								<p class="description"><?php _e('You can choose if you want a dark grey or a white popup', 'extendlab-link-preview') ?></p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<fieldset>
								<label for="extlb_hide_thumbnails">
										<input type="checkbox" id="extlb_hide_thumbnails" name="extlb_hide_thumbnails"<?php echo (get_option('extlb_hide_thumbnails') == 'on') ? 'checked' : 'false'; ?>/>
										<?php _e('Hide Thumbnails', 'extendlab-link-preview') ?>
								</label>
								<p class="description"><?php _e('You can toggle the visibility of the article thumbnails inside the popup', 'extendlab-link-preview') ?></p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Visibility', 'extendlab-link-preview') ?></th>
						<td>
							<fieldset>
								<label for="extlb_disable_mobile">
										<input type="checkbox" id="extlb_disable_mobile" name="extlb_disable_mobile"<?php echo (get_option('extlb_disable_mobile') == 'on') ? 'checked' : 'false'; ?>/>
										<?php _e('Hide on mobile (< 720px)', 'extendlab-link-preview') ?>
								</label>
								<p class="description"><?php _e('You can choose if you want to deactivate the popup on mobile devices', 'extendlab-link-preview') ?></p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Selector', 'extendlab-link-preview') ?></th>
						<td>
							<fieldset>
								<label for="extlb_link_selector">
										<input type="text" id="extlb_link_selector" name="extlb_link_selector" value="<?php echo esc_attr( get_option('extlb_link_selector') ); ?>" />
										<?php _e('Your custom css selector', 'extendlab-link-preview') ?>
								</label>
								<p class="description"><?php _e('Add your custom CSS selector here, the default is .entry-content a', 'extendlab-link-preview') ?></p>
							</fieldset>
						</td>
					</tr>
				</tbody>

			</table>

			<?php submit_button( esc_html__('Save Settings', 'extendlab-link-preview') ); ?>
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

// Add selector as data-attribute to body
function extlbBodyClasses( $classes )
{
	$classes[] = '" data-link-preview-selector="' . esc_attr( get_option('extlb_link_selector') ) . '"';
    return $classes;
}
add_filter( 'body_class','extlbBodyClasses', 999 );