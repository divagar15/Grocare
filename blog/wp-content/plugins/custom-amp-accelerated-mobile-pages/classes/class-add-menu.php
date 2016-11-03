<?php/** * Plugin Name: Custom (AMP) Accelerated Mobile Pages * Plugin URI: http://lamvt.vn * Description: Custom Accelerated Mobile Pages (AMP) on your WordPress site. * Version: 1.0.1 * Author: Lamvt * Author URI: http://lamvt.vn * License: GPL2 */class LAMVT_AMP_Mobile_Menu_Embed extends AMP_Base_Embed_Handler {    public function register_embed() {        // If we have an existing callback we are overriding, remove it.        remove_filter( 'the_content', 'lamvt_add_mobile_menu' );        // Add our new callback        add_filter( 'the_content', array( $this, 'add_mobile_menu' ) );        add_filter( 'amp_post_template_css', array( $this, 'lamvt_amp_light_box_css_styles' ) );    }    public function unregister_embed() {        // Let's clean up after ourselves, just in case.        add_filter( 'the_content', 'lamvt_add_mobile_menu' );        remove_filter( 'the_content', array( $this, 'add_mobile_menu' ) );    }    public function get_scripts() {        return array( 'amp-lightbox' => 'https://cdn.ampproject.org/v0/amp-lightbox-0.1.js' );    }    public function add_mobile_menu( $content ) {        $show_main_amp_menu = get_option('show_main_amp_menu');		$htmls = '';		$htmls .= '<button class="amp-button" on="tap:my-lightbox" role="button" tabindex="0">&#9776;</button><amp-lightbox id="my-lightbox" layout="nodisplay"><div class="lightbox" on="tap:my-lightbox.close" role="button" tabindex="0">';		$htmls .= $this->clean_custom_menu($show_main_amp_menu);				$htmls .='</div></amp-lightbox>';		//$htmls .= get_option('custom_content_width');		$content .= $htmls;	        return $content;    }		//add_action( 'amp_post_template_css', 'xyz_amp_my_additional_css_styles' );	function lamvt_amp_light_box_css_styles( $amp_template ) {		// only CSS here please...		?>		.lightbox {		  background: rgba(0,0,0,0.8);		  width: 100%;		  height: 100%;		  position: absolute;		}		.lightbox h1 {		  color: white;		}		.amp-button{			position: fixed;			right:0;			top:0;			z-index:999;			font-size:20px;			padding:5px 10px;		}		#my-lightbox{		}		ul#mobile_menu{			list-style:none;			padding-top:40px;		}		#mobile_menu a {			padding: 5px;			text-decoration: none;			font-weight:600;		}		#mobile_menu > li {			padding: 7px 0;		}		<?php	}			function clean_custom_menu( $menu_name ) {		$locations = get_nav_menu_locations();		if ( isset( $locations[ $menu_name ] ) ) {		 			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] ); 			$menu_items = wp_get_nav_menu_items($menu->term_id);		 			$menu_list = '<ul id="mobile_menu" class="menu-' . $menu_name . '">';		 			foreach ( (array) $menu_items as $key => $menu_item ) {				$title = $menu_item->title;				$url = $menu_item->url;				$menu_list .= '<li><a href="' . $url . '">' . $title . '</a></li>';			}			$menu_list .= '</ul>';		} else {			$menu_list = '<ul><li>Menu "' . $menu_name . '" not defined.</li></ul>';		}			return $menu_list;	}		}