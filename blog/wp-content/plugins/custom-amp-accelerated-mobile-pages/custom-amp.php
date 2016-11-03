<?php
/**
 * Plugin Name: Custom (AMP) Accelerated Mobile Pages
 * Plugin URI: http://lamvt.vn
 * Description: Custom Accelerated Mobile Pages (AMP) on your WordPress site.
 * Version: 1.0.6
 * Author: Lamvt
 * Author URI: http://lamvt.vn
 * License: GPL2
 */
if ( is_admin() ){
	add_action('admin_notices', 'LamvtshowAdminMessages');
}
function LamvtshowAdminMessages()
{
	$plugin_messages = array();

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// Download the Yoast WordPress SEO plugin
	if(!is_plugin_active( 'amp/amp.php' ))
	{
		$plugin_messages[] = 'Custom AMP plugin requires you to install and active the AMP plugin, <a href="https://wordpress.org/plugins/amp/">download it from here</a>.';
	}

	if(count($plugin_messages) > 0)
	{
		echo '<div id="message" class="error">';

			foreach($plugin_messages as $message)
			{
				echo '<p><strong>'.$message.'</strong></p>';
			}

		echo '</div>';
	}
}


/*start AMP*/
function custom_amp_settings_page()
{
    ?>
	    <div class="wrap">
	    <h1>Custom AMP Panel</h1>
	    <form method="post" action="options.php" enctype="multipart/form-data">
	        <?php
	            settings_fields("section");
	            do_settings_sections("custom-amp-options");      
	            submit_button(); 
	        ?>          
	    </form>
		</div>
	<?php
}

function add_custom_amp_menu_item()
{
	add_menu_page("Custom AMP Panel", "Custom AMP Panel", "manage_options", "custom-amp-panel", "custom_amp_settings_page", null, 99);
}

add_action("admin_menu", "add_custom_amp_menu_item");

/*start fields*/
function display_custom_css()
{
	?>
	 <textarea name="custom_css" rows="6" cols="40"><?php echo trim(get_option('custom_css')); ?></textarea>
	<?php
}
function display_add_code_footer()
{
	?>
	 <textarea name="add_code_footer" rows="6" cols="40"><?php echo trim(get_option('add_code_footer')); ?></textarea>
	<?php
}

function show_menu(){
	//$locations = get_nav_menu_locations();
	$locations = get_registered_nav_menus();
	?>
	<select name="show_main_amp_menu">
		<option value="" >Select your menu</option>
		<?php foreach($locations as $k=>$v){ ?>
			<option value="<?php echo $k; ?>" <?php if ( get_option('show_main_amp_menu') == $k ) echo 'selected="selected"'; ?>><?php echo $v; ?></option>
		<?php } ?>
	</select>
<?php }

function content_width()
{
	?>
    	<input type="number" min="1" name="custom_content_width" id="custom_content_width" value="<?php echo get_option('custom_content_width',600); ?>" />
    <?php
}

function display_endpoint_element()
{
	?>
    	<input type="text" name="endpoint" id="endpoint" value="<?php echo get_option('endpoint','amp'); ?>" />
    <?php
}
function display_google_analytic_element()
{
	?>
    	<input type="text" name="google_analytic" id="google_analytic" value="<?php echo get_option('google_analytic'); ?>" />
    <?php
}

function display_featured_image()
{
	?>
		<input type="checkbox" name="featured_image" value="1" <?php checked(1, get_option('featured_image'), true); ?> /> 
	<?php
}
function display_comments()
{
	?>
		<input type="checkbox" name="show_comments" value="1" <?php checked(1, get_option('show_comments'), true); ?> /> 
	<?php
}
function display_woocommerce()
{
	?>
		<input type="checkbox" name="show_woocommerce" value="1" <?php checked(1, get_option('show_woocommerce'), true); ?> /> 
	<?php
}
function display_related_post()
{
	?>
		<input type="checkbox" name="show_related_post" value="1" <?php checked(1, get_option('show_related_post'), true); ?> /> 
	<?php
}
function display_right_to_left()
{
	?>
		<input type="checkbox" name="show_right_to_left" value="1" <?php checked(1, get_option('show_right_to_left'), true); ?> /> 
	<?php
}
function logo_display()
{
	?>
    <label for="upload_image">
    <input id="upload_image" type="text" size="36" name="ad_image" value="<?php echo esc_url( get_option('ad_image') ); ?>" /> 
    <input id="upload_image_button" class="button" type="button" value="Upload Image" />
    <br />Enter a URL or upload an image for icon 32x32
</label>
   <?php
}

function metadata_logo_display()
{
	?>
    <label for="upload_meta_image">
    <input id="upload_meta_image" type="text" size="36" name="upload_meta_image" value="<?php echo esc_url( get_option('upload_meta_image') ); ?>" /> 
    <input id="upload_meta_image_button" class="button" type="button" value="Upload Image" />
    <br />Enter a URL or upload an image for metadata 60x600
</label>
   <?php
}

/*script to upload files*/
add_action('admin_enqueue_scripts', 'my_admin_scripts');
 
function my_admin_scripts() {
    if (isset($_GET['page']) && $_GET['page'] == 'custom-amp-panel') {
        wp_enqueue_media();
        wp_register_script('my-admin-js', plugin_dir_url( __FILE__ ).'js/my-admin.js', array('jquery'));
        wp_enqueue_script('my-admin-js');
    }
}


/*reg fields*/

function display_custom_amp_panel_fields()
{
	add_settings_section("section", "All Settings", null, "custom-amp-options");
	
	
	add_settings_field("ad_image", "Logo", "logo_display", "custom-amp-options", "section");
	add_settings_field("upload_meta_image", "Metadata Logo", "metadata_logo_display", "custom-amp-options", "section");
	
	add_settings_field("featured_image", "Do you want to add featured image?", "display_featured_image", "custom-amp-options", "section");
	add_settings_field("show_comments", "Do you want to show comments?", "display_comments", "custom-amp-options", "section");
	add_settings_field("show_woocommerce", "Do you want to add Woocommerce APM?", "display_woocommerce", "custom-amp-options", "section");
	add_settings_field("show_related_post", "Do you want to show Related Posts?", "display_related_post", "custom-amp-options", "section");
	add_settings_field("show_right_to_left", "Do you want to add RighttoLeft Direction?", "display_right_to_left", "custom-amp-options", "section");
	
	add_settings_field("custom_content_width", "Custom content Width", "content_width", "custom-amp-options", "section");
	add_settings_field("show_main_amp_menu", "Show menu", "show_menu", "custom-amp-options", "section");
    //add_settings_field("endpoint", "Facebook Profile Url", "display_endpoint_element", "custom-amp-options", "section"); 
	add_settings_field("google_analytic", "Google Analytic Code example: UA-XXXXX-Y", "display_google_analytic_element", "custom-amp-options", "section");
    add_settings_field("custom_css", "Do you want to append to the existing CSS", "display_custom_css", "custom-amp-options", "section");\
	add_settings_field("add_code_footer", "Do you want to add html Code to footer", "display_add_code_footer", "custom-amp-options", "section");
	  

    
	register_setting("section", "ad_image");
	register_setting("section", "upload_meta_image");
	
	register_setting("section", "custom_css");
	register_setting("section", "add_code_footer");
	register_setting("section", "featured_image");
	register_setting("section", "show_comments");
	register_setting("section", "show_woocommerce");
	register_setting("section", "show_related_post");
	register_setting("section", "show_right_to_left");
	
    register_setting("section", "custom_content_width");
    register_setting("section", "show_main_amp_menu");
    //register_setting("section", "endpoint");
    register_setting("section", "google_analytic");
    
	
}

add_action("admin_init", "display_custom_amp_panel_fields");

/* custom AMP*/
/*Icon*/
add_filter( 'amp_post_template_data', 'lamvt_amp_set_site_icon_url' );

function lamvt_amp_set_site_icon_url( $data ) {
    // Ideally a 32x32 image
	if(get_option('ad_image')){
		$data[ 'site_icon_url' ] = get_option('ad_image');
	}else{
		$data[ 'site_icon_url' ] = plugin_dir_url( __FILE__ ) . 'images/wp-logo.png';
	}
    return $data;
}
/*show logo only
add_action( 'amp_post_template_css', 'lamvt_amp_additional_css_styles' );

function lamvt_amp_additional_css_styles( $amp_template ) {
    // only CSS here please...
    ?>
    nav.amp-wp-title-bar {
        padding: 12px 0;
        background: #000;
    }
    nav.amp-wp-title-bar a {
        background-image: url( '<?php echo get_option('ad_image'); ?>' );
        background-repeat: no-repeat;
        background-size: contain;
        display: block;
        height: 28px;
        width: 94px;
        margin: 0 auto;
        text-indent: -9999px;
    }
    <?php
}
*/
/* featured image*/
if(get_option('featured_image') == 1){
	add_action( 'pre_amp_render_post', 'lamvt_amp_add_custom_actions' );
	function lamvt_amp_add_custom_actions() {
		add_filter( 'the_content', 'lamvt_amp_add_featured_image' );
	}
	function lamvt_amp_add_featured_image( $content ) {
		if ( has_post_thumbnail() ) {
			$image = sprintf( '<p class="lamvt-featured-image">%s</p>', get_the_post_thumbnail($post_id, 'full') );
			$content = $image . $content;
		}
		return $content;
	}
}
/*Content Width*/
if(get_option('custom_content_width')){
	add_filter( 'amp_content_max_width', 'lamvt_amp_change_content_width' );
	function lamvt_amp_change_content_width( $content_max_width ) {
		return get_option('custom_content_width');
	}
}
/*meta data "Rich Snippet" support*/
if(get_option('upload_meta_image')){
	add_filter( 'amp_post_template_metadata', 'lamvt_amp_modify_json_metadata', 10, 2 );
	function lamvt_amp_modify_json_metadata( $metadata, $post ) {
		$metadata['@type'] = 'NewsArticle';
		$metadata['publisher']['logo'] = array(
			'@type' => 'ImageObject',
			'url' => get_option('upload_meta_image'),
			'height' => 60,
			'width' => 600,
		);
		return $metadata;
	}
}
/* add comment count*/
if(get_option('show_comments')==1){
	add_filter( 'amp_post_template_meta_parts', 'lamvt_amp_add_comment_count_meta' );
	function lamvt_amp_add_comment_count_meta( $meta_parts ) {
		$meta_parts[] = 'lamvt-meta-comment-count';
		return $meta_parts;
	}
	add_filter( 'amp_post_template_file', 'lamvt_amp_set_comment_count_meta_path', 10, 3 );
	function lamvt_amp_set_comment_count_meta_path( $file, $type, $post ) {
		if ( 'lamvt-meta-comment-count' === $type ) {
			$file = dirname( __FILE__ ) . '/templates/lamvt-meta-comment-count.php';
		}
		return $file;
	}
}

/*add footer code
add_action( 'amp_post_template_footer', 'lamvt_amp_add_pixel' );

function lamvt_amp_add_pixel( $amp_template ) {
    $post_id = $amp_template->get( 'post_id' );
    ?>
    <amp-pixel src="https://example.com/hi.gif?x=RANDOM"></amp-pixel>
    <?php
}
*/
/*changes EndPoint
if(get_option('endpoint')){
	add_filter( 'amp_query_var' , 'lamvt_amp_change_endpoint' );

	function lamvt_amp_change_endpoint( $amp_endpoint ) {
		//$amp_endpoint = 'amp';
		//if(get_option('endpoint') != 'amp'){
			//$amp_endpoint = get_option('endpoint');
		//}
		return 'fooo';
	}
}
*/
/*Load the Embed Handler*/
if(get_option('show_related_post')==1){
	add_filter( 'amp_content_embed_handlers', 'lamvt_amp_add_related_embed', 10, 2 );
	function lamvt_amp_add_related_embed( $embed_handler_classes, $post ) {
		require_once( dirname( __FILE__ ) . '/classes/class-amp-related-posts-embed.php' );
		$embed_handler_classes[ 'LAMVT_AMP_Related_Posts_Embed' ] = array();
		return $embed_handler_classes;
	}
}

/*Load the Sanitizer
add_filter( 'amp_content_sanitizers', 'lamvt_amp_add_ad_sanitizer', 10, 2 );

function lamvt_amp_add_ad_sanitizer( $sanitizer_classes, $post ) {
    require_once( dirname( __FILE__ ) . '/classes/class-ad-inject-sanitizer.php' );
    $sanitizer_classes[ 'LAMVT_AMP_Ad_Injection_Sanitizer' ] = array(); // the array can be used to pass args to your sanitizer and accessed within the class via `$this->args`
    return $sanitizer_classes;
}
*/
/*Gooogle analytic*/
if(get_option('google_analytic')){
	add_filter( 'amp_post_template_analytics', 'lamvt_amp_add_custom_analytics' );
	function lamvt_amp_add_custom_analytics( $analytics ) {
		if ( ! is_array( $analytics ) ) {
			$analytics = array();
		}

		// https://developers.google.com/analytics/devguides/collection/amp-analytics/
		$analytics['lamvt-googleanalytics'] = array(
			'type' => 'googleanalytics',
			'attributes' => array(
				// 'data-credentials' => 'include',
			),
			'config_data' => array(
				'vars' => array(
					'account' => get_option('google_analytic')
				),
				'triggers' => array(
					'trackPageview' => array(
						'on' => 'visible',
						'request' => 'pageview',
					),
				),
			),
		);
		/*
		// https://www.parsely.com/docs/integration/tracking/google-amp.html
		$analytics['xyz-parsely'] = array(
			'type' => 'parsely',
			'attributes' => array(),
			'config_data' => array(
				'vars' => array(
					'apikey' => 'YOUR APIKEY GOES HERE',
				)
			),
		);
		*/
		return $analytics;
	}
}
/*Custom Post Type for Woocommerce content*/
if(get_option('show_woocommerce')==1){
	add_action( 'amp_init', 'lamvt_amp_add_woocommerce_post_type' );
	function lamvt_amp_add_woocommerce_post_type() {
		add_post_type_support( 'product', AMP_QUERY_VAR );
	}
	add_filter( 'amp_post_template_file', 'lamvt_amp_set_woocmmerce_template', 10, 3 );
	function lamvt_amp_set_woocmmerce_template( $file, $type, $post ) {
		if ( 'single' === $type && 'product' === $post->post_type ) {
			$file = dirname( __FILE__ ) . '/templates/my-amp-woocmmerce-template.php';
		}
		return $file;
	}
}

/* add righ to left*/
if(get_option('show_right_to_left')==1){
	add_filter( 'amp_post_template_file', 'lamvt_amp_set_righ_to_left_path', 10, 3 );
	function lamvt_amp_set_righ_to_left_path( $file, $type, $post ) {
		if ( 'style' === $type ) {
			$file = dirname( __FILE__ ) . '/templates/style-rtl.php';
		}
		return $file;
	}
}

/* Hander menu*/
if(trim(get_option('show_main_amp_menu'))){
	add_filter( 'amp_content_embed_handlers', 'lamvt_amp_add_mobile_menu_embed', 10, 2 );
	function lamvt_amp_add_mobile_menu_embed( $embed_handler_classes, $post ) {
		require_once( dirname( __FILE__ ) . '/classes/class-add-menu.php' );
		$embed_handler_classes[ 'LAMVT_AMP_Mobile_Menu_Embed' ] = array();
		return $embed_handler_classes;
	}
}
if(trim(strip_tags(get_option('custom_css')))){
	add_action( 'amp_post_template_css', 'lamvt_amp_my_additional_css_styles' );

	function lamvt_amp_my_additional_css_styles( $amp_template ) {
		// only CSS here please...
		echo get_option('custom_css');
	}
}

if(trim(get_option('add_code_footer'))){
	add_action( 'amp_post_template_footer', 'lamvt_amp_add_pixel' );

	function lamvt_amp_add_pixel( $amp_template ) {
    $post_id = $amp_template->get( 'post_id' );
		echo trim(get_option('add_code_footer'));
	}
}
/*checklater*/
//is_amp_endpoint()
//add_shortcode
//amp_post_template_head