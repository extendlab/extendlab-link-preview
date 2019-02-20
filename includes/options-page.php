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

	$page_title = 'Extendlab - Link Preview Settings';
	$menu_title = 'Extendlab - Link Preview';
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
		<h1>Extendlab - Link Preview Settings</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'extlb-options' ); ?>
			<?php do_settings_sections( 'extlb-options' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">Appearance</th>
						<td>
							<fieldset>
								<label for="extlb_darkmode">
										<input type="checkbox" id="extlb_darkmode" name="extlb_darkmode" <?php echo (get_option('extlb_darkmode') == 'on') ? 'checked' : 'false'; ?>/>
										Darkmode on
								</label>
								<p class="description">You can choose if you want a dark grey or a white popup</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<fieldset>
								<label for="extlb_hide_thumbnails">
										<input type="checkbox" id="extlb_hide_thumbnails" name="extlb_hide_thumbnails"<?php echo (get_option('extlb_hide_thumbnails') == 'on') ? 'checked' : 'false'; ?>/>
										Hide Thumbnails
								</label>
								<p class="description">You can toggle the visibility of the article thumbnails inside the popup</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Visibility</th>
						<td>
							<fieldset>
								<label for="extlb_disable_mobile">
										<input type="checkbox" id="extlb_disable_mobile" name="extlb_disable_mobile"<?php echo (get_option('extlb_disable_mobile') == 'on') ? 'checked' : 'false'; ?>/>
										Hide on mobile (< 720px)
								</label>
								<p class="description">You can choose if you want to deactivate the popup on mobile devices</p>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">Selector</th>
						<td>
							<fieldset>
								<label for="extlb_link_selector">
										<input type="text" id="extlb_link_selector" name="extlb_link_selector" value="<?php echo esc_attr( get_option('extlb_link_selector') ); ?>" />
										Your custom css selector
								</label>
								<p class="description">Add your custom CSS selector here, the default is .entry-content a</p>
							</fieldset>
						</td>
					</tr>
				</tbody>

			</table>

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

// Add selector as data-attribute to body
function extlbBodyClasses( $classes )
{
	$classes[] = '" data-link-preview-selector="' . esc_attr( get_option('extlb_link_selector') ) . '"';
    return $classes;
}
add_filter( 'body_class','extlbBodyClasses', 999 );