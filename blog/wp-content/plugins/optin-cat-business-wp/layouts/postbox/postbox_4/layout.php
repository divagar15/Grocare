<?php

/**
 * @package    Optin Cat
 */

$layout = array(

	'name' => __( 'Postbox 4' ),

	'editables' => array(

		// Added to the fieldset "Form Background"
		'form' => array(
			'.fca_eoi_postbox_4' => array(
				'background-color' => array( __( 'Form Background Color' ), '' ),
				'border-color' => array( __( 'Border Color' ), '#E5E5E5' ),
			),
		),

		// Added to the fieldset "Headline"
		'headline' => array(
			'.fca_eoi_postbox_4 .fca_eoi_postbox_4_headline_copy_wrapper' => array(
				'font-size' => array( __('Font Size'), '25px'),
				'color' => array( __('Font Color'), '#000'),
			),
		),
		'description' => array(
			'.fca_eoi_postbox_4 .fca_eoi_postbox_4_description_copy_wrapper p' => array(
				'font-size' => array( __('Font Size'), '14px'),
				'color' => array( __('Font Color'), '#444'),
			),
		),
		'name_field' => array(
			'.fca_eoi_postbox_4 .fca_eoi_postbox_4_name_field_wrapper, .fca_eoi_postbox_4 .fca_eoi_postbox_4_name_field_wrapper input' => array(
				'color' => array( __( 'Font Color' ), '#999' ),
				'background-color' => array( __( 'Background Color' ), '' ),
			),
			'.fca_eoi_postbox_4 .fca_eoi_postbox_4_name_field_wrapper' => array(
				'border-color' => array( __('Border Color'), '#DDD'),
			),
		),
		'email_field' => array(
			'.fca_eoi_postbox_4 .fca_eoi_postbox_4_email_field_wrapper, .fca_eoi_postbox_4 .fca_eoi_postbox_4_email_field_wrapper input' => array(
				'color' => array( __( 'Font Color' ), '#999' ),
				'background-color' => array( __( 'Background Color' ), ''),
			),
			'.fca_eoi_postbox_4 .fca_eoi_postbox_4_email_field_wrapper' => array(
				'border-color' => array( __( 'Border Color' ), '#DDD'),
			),
		),
		'button' => array(
			'.fca_eoi_postbox_4 .fca_eoi_postbox_4_submit_button_wrapper input' => array(
				'font-size' => array( __('Font Size'), '14px'),
				'color' => array( __( 'Font Color' ), '#FFF' ),
				'background-color' => array( __( 'Background Color' ), '#39b0ff' ),
				'border-color' => array( __( 'Border Color' ), '#3498db' ),
			),
		),
		'privacy' => array(
			'.fca_eoi_postbox_4 .fca_eoi_postbox_4_privacy_copy_wrapper' => array(
				'font-size' => array( __('Font Size'), '14px'),
				'color' => array( __('Font Color'), '#999'),
			),
		),
		'fatcatapps' => array(
			'.fca_eoi_postbox_4 .fca_eoi_postbox_4_fatcatapps_link_wrapper a, .fca_eoi_postbox_4 .fca_eoi_postbox_4_fatcatapps_link_wrapper a:hover' => array(
				'color' => array( __('Font Color'), '#999'),
			),
		),
	)
);
