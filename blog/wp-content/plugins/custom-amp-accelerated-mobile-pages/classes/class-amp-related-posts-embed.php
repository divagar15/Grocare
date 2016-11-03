<?php
/**
 * Plugin Name: Custom (AMP) Accelerated Mobile Pages
 * Plugin URI: http://lamvt.vn
 * Description: Custom Accelerated Mobile Pages (AMP) on your WordPress site.
 * Version: 1.0.1
 * Author: Lamvt
 * Author URI: http://lamvt.vn
 * License: GPL2
 */
class LAMVT_AMP_Related_Posts_Embed extends AMP_Base_Embed_Handler {
    public function register_embed() {
        // If we have an existing callback we are overriding, remove it.
        remove_filter( 'the_content', 'lamvt_add_related_posts' );
        // Add our new callback
        add_filter( 'the_content', array( $this, 'add_related_posts' ) );
    }

    public function unregister_embed() {
        // Let's clean up after ourselves, just in case.
        add_filter( 'the_content', 'lamvt_add_related_posts' );
        remove_filter( 'the_content', array( $this, 'add_related_posts' ) );
    }

    //public function get_scripts() {
        //return array( 'amp-mustache' => 'https://cdn.ampproject.org/v0/amp-mustache-0.1.js' );
    //}

    public function add_related_posts( $content ) {
        // See https://github.com/ampproject/amphtml/blob/master/extensions/amp-list/amp-list.md for details on amp-list
		global $post;
		$related_posts_list = '';
		$CurUlr .= get_permalink($post->ID);
		
		$taxs = get_object_taxonomies( $post );
		if ( ! $taxs ) {
			return;
		}
	 
		// ignoring post formats
		if( ( $key = array_search( 'post_format', $taxs ) ) !== false ) {
			unset( $taxs[$key] );
		}
	 
		// try tags first
	 
		if ( ( $tag_key = array_search( 'post_tag', $taxs ) ) !== false ) {
	 
			$tax = 'post_tag';
			$tax_term_ids = wp_get_object_terms( $post->ID, $tax, array( 'fields' => 'ids' ) );
		}
	 
		// if no tags, then by cat or custom tax
	 
		if ( empty( $tax_term_ids ) ) {
			// remove post_tag to leave only the category or custom tax
			if ( $tag_key !== false ) {
				unset( $taxs[ $tag_key ] );
				$taxs = array_values($taxs);
			}
	 
			$tax = $taxs[0];
			$tax_term_ids = wp_get_object_terms( $post->ID, $tax, array('fields' => 'ids') );
	 
		}
	 
		if ( $tax_term_ids ) {
			$args = array(
				'post_type' => $post->post_type,
				'posts_per_page' => 5,
				'orderby' => 'rand',
				'tax_query' => array(
					array(
						'taxonomy' => $tax,
						'field' => 'id',
						'terms' => $tax_term_ids
					)
				),
				'post__not_in' => array ($post->ID),
			);
			$related = new WP_Query( $args );
	 
			if ($related->have_posts()) {
				$related_posts_list .= '<aside><h3>You May Also Like</h3><ul>'; 
				while ( $related->have_posts() ) : $related->the_post();
					$related_posts_list .= '<li><a href="'.amp_get_permalink( get_the_id() ).'" title="'.get_the_title().'">'.get_the_title().'</a></li>';	 
				endwhile; 
				wp_reset_postdata();
				$related_posts_list .= '</ul></aside>';
			}			 
		}

        $content .= $related_posts_list;

        return $content;
    }	
	
}