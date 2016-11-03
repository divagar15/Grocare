<?php

/**
 * @package Optin Cat
 */

$layout = array(

	'name' => __( 'Postbox 8' ),

	'editables' => array(

		// Added to the fieldset "Form Background"
		'form' => array(
			'.fca_eoi_postbox_8' => array(
				'background-color' => array( __( 'Form Background' ), '#FFF' ),
				'border-color' => array( __( 'Border Color' ), '#CDBCAA' ),
			),
		),

		// Added to the fieldset "Headline"
		'headline' => array(
			'.fca_eoi_postbox_8 .fca_eoi_postbox_8_headline_copy_wrapper' => array(
				'font-size' => array( __('Font Size'), '28px'),
				'color' => array( __('Font Color'), '#D19855'),
			),
		),
		'description' => array(
			'.fca_eoi_postbox_8 .fca_eoi_postbox_8_description_copy_wrapper div' => array(
				'color' => array( __('Font Color'), '#B49E86'),
			),
		),
		'name_field' => array(
			'.fca_eoi_postbox_8 .fca_eoi_postbox_8_name_field_wrapper, .fca_eoi_postbox_8 .fca_eoi_postbox_8_name_field_wrapper input' => array(
				'font-size' => array( __( 'Font Size' ), '18px' ),
				'color' => array( __( 'Font Color' ), '#B49E86' ),
				'background-color' => array( __( 'Background Color' ), '#FFF' ),
			),
			'.fca_eoi_postbox_8 .fca_eoi_postbox_8_name_field_wrapper' => array(
				'border-color' => array( __('Border Color'), '#E1E1E1'),
			),
		),
		'email_field' => array(
			'.fca_eoi_postbox_8 .fca_eoi_postbox_8_email_field_wrapper, .fca_eoi_postbox_8 .fca_eoi_postbox_8_email_field_wrapper input' => array(
				'font-size' => array( __( 'Font Size' ), '18px' ),
				'color' => array( __( 'Font Color' ), '#B49E86' ),
				'background-color' => array( __( 'Background Color' ), '#FFF'),
			),
			'.fca_eoi_postbox_8 .fca_eoi_postbox_8_email_field_wrapper' => array(
				'border-color' => array( __( 'Border Color' ), '#EAE3D3'),
			),
		),
		'button' => array(
			'.fca_eoi_postbox_8 .fca_eoi_postbox_8_submit_button_wrapper input' => array(
				'font-size' => array( __('Font Size'), '18px' ),
				'color' => array( __( 'Font Color' ), '#FFF' ),
				'background-color' => array( __( 'Button Color' ), '#FCBC74' ),
			),
		),
		'privacy' => array(
			'.fca_eoi_postbox_8 .fca_eoi_postbox_8_privacy_copy_wrapper' => array(
				'font-size' => array( __('Font Size'), '14px'),
				'color' => array( __('Font Color'), '#B49E86'),
			),
		),
		'fatcatapps' => array(
			'.fca_eoi_postbox_8 .fca_eoi_postbox_8_fatcatapps_link_wrapper a, .fca_eoi_postbox_8 .fca_eoi_postbox_8_fatcatapps_link_wrapper a:hover' => array(
				'color' => array( __('Font Color'), '#B49E86'),
			),
		),
	)
);
