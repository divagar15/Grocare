<?php

/**
	* @package    Optin Cat
 */

$layout = array(

	'name' => __( 'Layout 7' ),

	'editables' => array(

		// Added to the fieldset "Form Background"
		'form' => array(
			'.fca_eoi_layout_7' => array(
				'background-color' => array( __( 'Form Background' ), '#FFF' ),
				'border-color' => array( __( 'Border Color' ), '#5DB308' ),
			),
		),

		// Added to the fieldset "Headline"
		'headline' => array(
			'.fca_eoi_layout_7 .fca_eoi_layout_7_headline_copy_wrapper' => array(
				'font-size' => array( __('Font Size'), '28px'),
				'color' => array( __('Font Color'), '#586B3D'),
			),
		),
		'description' => array(
			'.fca_eoi_layout_7 .fca_eoi_layout_7_description_copy_wrapper p' => array(
			),
		),
		'name_field' => array(
			'.fca_eoi_layout_7 .fca_eoi_layout_7_name_field_wrapper, .fca_eoi_layout_7 .fca_eoi_layout_7_name_field_wrapper input' => array(
				'font-size' => array( __( 'Font Size' ), '18px' ),
				'color' => array( __( 'Font Color' ), '#919B83' ),
				'background-color' => array( __( 'Background Color' ), '#FFF' ),
			),
			'.fca_eoi_layout_7 .fca_eoi_layout_7_name_field_wrapper' => array(
				'border-color' => array( __('Border Color'), '#E1E1E1'),
			),
		),
		'email_field' => array(
			'.fca_eoi_layout_7 .fca_eoi_layout_7_email_field_wrapper, .fca_eoi_layout_7 .fca_eoi_layout_7_email_field_wrapper input' => array(
				'font-size' => array( __( 'Font Size' ), '18px' ),
				'color' => array( __( 'Font Color' ), '#919B83' ),
				'background-color' => array( __( 'Background Color' ), '#FFF'),
			),
			'.fca_eoi_layout_7 .fca_eoi_layout_7_email_field_wrapper' => array(
				'border-color' => array( __( 'Border Color' ), '#E1E1E1'),
			),
		),
		'button' => array(
			'.fca_eoi_layout_7 .fca_eoi_layout_7_submit_button_wrapper input' => array(
				'font-size' => array( __('Font Size'), '18px' ),
				'color' => array( __( 'Font Color' ), '#D7F7B6' ),
				'background-color' => array( __( 'Button Color' ), '#57B101' ),
			),
		),
		'privacy' => array(
			'.fca_eoi_layout_7 .fca_eoi_layout_7_privacy_copy_wrapper' => array(
				'font-size' => array( __('Font Size'), '14px'),
				'color' => array( __('Font Color'), '#958878'),
			),
		),
		'fatcatapps' => array(
			'.fca_eoi_layout_7 .fca_eoi_layout_7_fatcatapps_link_wrapper a, .fca_eoi_layout_7 .fca_eoi_layout_7_fatcatapps_link_wrapper a:hover' => array(
				'color' => array( __('Font Color'), '#958878'),
			),
		),
	)
);
