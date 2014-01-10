<?php



require_once('color-picker.php');


/* Create Settings Menu */

add_action( 'admin_menu', 'fftw_admin_admin_menu' );
function fftw_admin_admin_menu() {
    add_menu_page( 'FF Tab Widget', 'FF Tab Widget', 'manage_options', 'fftw_admin', 'fftw_admin_options_page' );
}


/* Create Sections and Fields */

add_action( 'admin_init', 'fftw_admin_admin_init' );
function fftw_admin_admin_init() {

	/* Register setting */
	register_setting( 'fftw_admin-group-1', 'fftw_nav_bg' ); 
	register_setting( 'fftw_admin-group-1', 'fftw_nav_color' ); 
	register_setting( 'fftw_admin-group-1', 'fftw_nav_border' ); 
	register_setting( 'fftw_admin-group-1', 'fftw_nav_bg_active' ); 
	register_setting( 'fftw_admin-group-1', 'fftw_nav_color_active' ); 
	register_setting( 'fftw_admin-group-1', 'fftw_pane_bg' ); 
	register_setting( 'fftw_admin-group-1', 'fftw_pro_feature' ); 
	
	/* Create setting section */ 
    add_settings_section( 'fftw_admin-section-one', '', 'section_one_callback', 'fftw_admin-section-1' );
	
	/* Create fields */
    add_settings_field( 'fftw_nav_bg', 'Tab navigation background', 'fftw_nav_bg_callback', 'fftw_admin-section-1', 'fftw_admin-section-one' );
    add_settings_field( 'fftw_nav_color', 'Tab navigation text color', 'fftw_nav_color_callback', 'fftw_admin-section-1', 'fftw_admin-section-one' );
    add_settings_field( 'fftw_nav_border', 'Tab navigation border color', 'fftw_nav_border_callback', 'fftw_admin-section-1', 'fftw_admin-section-one' );
    add_settings_field( 'fftw_nav_bg_active', 'Tab navigation active background', 'fftw_nav_bg_active_callback', 'fftw_admin-section-1', 'fftw_admin-section-one' );
    add_settings_field( 'fftw_nav_color_active', 'Tab navigation active color', 'fftw_nav_color_active_callback', 'fftw_admin-section-1', 'fftw_admin-section-one' );
    add_settings_field( 'fftw_pane_bg', 'Tab pane background', 'fftw_pane_bg_callback', 'fftw_admin-section-1', 'fftw_admin-section-one' );
    add_settings_field( 'fftw_pro_feature', 'Upcoming Pro Features', 'fftw_pro_feature_callback', 'fftw_admin-section-1', 'fftw_admin-section-one' );
}


/* 
* Callback 
* Contains front end fileds and descriptions
*/
	function section_one_callback() {
		// echo 'Help text of group 1.';
	}
	
	
	function fftw_nav_bg_callback(){ 
	
		$setting = esc_attr( get_option( 'fftw_nav_bg' ) );
		
		$color_value = isset($setting) ? $setting : '#f7f7f7';
	
	?>
		
		<input class="fftw-color-picker" name="fftw_nav_bg" type="text" value="<?php echo esc_html($color_value); ?>" />	
	
	<?php }
	
	
	function fftw_nav_color_callback(){ 
	
		$setting = esc_attr( get_option( 'fftw_nav_color' ) );
		
		$color_value = isset($setting) ? $setting : '#ffffff';
	
	?>
		
		<input class="fftw-color-picker" name="fftw_nav_color" type="text" value="<?php echo esc_html($color_value); ?>" />	
	
	<?php }
	
	
	function fftw_nav_border_callback(){ 
	
		$setting = esc_attr( get_option( 'fftw_nav_border' ) );
		
		$color_value = isset($setting) ? $setting : '#e6e6e6';
	
	?>
		
		<input class="fftw-color-picker" name="fftw_nav_border" type="text" value="<?php echo esc_html($color_value); ?>" />	
	
	<?php }
	
	
	function fftw_nav_bg_active_callback(){ 
	
		$setting = esc_attr( get_option( 'fftw_nav_bg_active' ) );
		
		$color_value = isset($setting) ? $setting : '#255a8c';
	
	?>
		
		<input class="fftw-color-picker" name="fftw_nav_bg_active" type="text" value="<?php echo esc_html($color_value); ?>" />	
	
	<?php }
	
	
	function fftw_nav_color_active_callback(){ 
	
		$setting = esc_attr( get_option( 'fftw_nav_color_active' ) );
		
		$color_value = isset($setting) ? $setting : '#ffffff';
	
	?>
		
		<input class="fftw-color-picker" name="fftw_nav_color_active" type="text" value="<?php echo esc_html($color_value); ?>" />	
	
	<?php }
	
	
	function fftw_pane_bg_callback(){ 
	
		$setting = esc_attr( get_option( 'fftw_pane_bg' ) );
		
		$color_value = isset($setting) ? $setting : '#d6eebd';
	
	?>
		
		<input class="fftw-color-picker" name="fftw_pane_bg" type="text" value="<?php echo esc_html($color_value); ?>" />	
	
	<?php }
	
	
	function fftw_pro_feature_callback(){ 
	
	?>
		<span>Here are some features will be added to the next PRO version:</span>
		<ul style="list-style:disc;">
			<li>Carousel</li>
			<li>Predefined styles</li>
			<li>Custom image thumbnail size</li>
		</ul>	
		
		<span><a href="http://www.kharissulistiyono.com/ff-tab-widget-pro" title="FF Tab Widget PRO">More info</a> about PRO version</span>
	
	<?php }
	
	
	
/* Settings callback */
function fftw_admin_options_page() {
    ?>
    <div class="wrap">
        <h2><?php _e('FF Tab Widget Settings'); ?></h2>
		
		<?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'  ) {   ?>
		
			<div id="message" class="updated">
				<p><strong><?php _e('Settings saved.') ?></strong></p>
			</div>
		
		<?php } ?>
		
        <form action="options.php" method="POST">
            <?php settings_fields( 'fftw_admin-group-1' ); ?>
            <?php do_settings_sections( 'fftw_admin-section-1' ); ?>		
            <?php submit_button(); ?>
        </form>
		
		<div class="plugin-message">
			<p><em><?php _e('Thank you for using this plugin. If you need WordPress developer to develop your WP-based sites, <a href="http://kharissulistiyono.com/contact" title="Hire this WordPress plugin developer">contact me</a>. Yes, I do freelance works.') ?></em></p>
		</div>			
		
    </div>
    <?php
}	