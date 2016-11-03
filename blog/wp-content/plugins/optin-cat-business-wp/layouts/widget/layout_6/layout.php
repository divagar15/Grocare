<?php

/**
 * @package    Optin Cat
 */

$layout = array(

	'name' => __( 'Layout 6' ),

	'editables' => array(

		// Added to the fieldset "Form Background"
		'form' => array(
			'.fca_eoi_layout_6' => array(
				'background-color' => array( __( 'Form Background' ), '#2B2B2B' ),
				'border-color' => array( __( 'Border Color' ), '#727274' ),
			),
		),

		// Added to the fieldset "Headline"
		'headline' => array(
			'.fca_eoi_layout_6 .fca_eoi_layout_6_headline_copy_wrapper' => array(
				'font-size' => array( __('Font Size'), '28px'),
				'color' => array( __('Font Color'), '#CECECE'),
			),
		),
		'description' => array(
			'.fca_eoi_layout_6 .fca_eoi_layout_6_description_copy_wrapper div' => array(
				'color' => array( __('Font Color'), '#CECECE'),
			),
		),
		'name_field' => array(
			'.fca_eoi_layout_6 .fca_eoi_layout_6_name_field_wrapper, .fca_eoi_layout_6 .fca_eoi_layout_6_name_field_wrapper input' => array(
				'font-size' => array( __( 'Font Size' ), '18px' ),
				'color' => array( __( 'Font Color' ), '#7C7C7C' ),
				'background-color' => array( __( 'Background Color' ), '#CECECE' ),
			),
			'.fca_eoi_layout_6 .fca_eoi_layout_6_name_field_wrapper' => array(
				'border-color' => array( __('Border Color'), '#CECECE'),
			),
		),
		'email_field' => array(
			'.fca_eoi_layout_6 .fca_eoi_layout_6_email_field_wrapper, .fca_eoi_layout_6 .fca_eoi_layout_6_email_field_wrapper input' => array(
				'font-size' => array( __( 'Font Size' ), '18px' ),
				'color' => array( __( 'Font Color' ), '#7C7C7C' ),
				'background-color' => array( __( 'Background Color' ), '#CECECE'),
			),
			'.fca_eoi_layout_6 .fca_eoi_layout_6_email_field_wrapper' => array(
				'border-color' => array( __( 'Border Color' ), '#CECECE'),
			),
		),
		'button' => array(
			'.fca_eoi_layout_6 .fca_eoi_layout_6_submit_button_wrapper input' => array(
				'font-size' => array( __('Font Size'), '18px' ),
				'color' => array( __( 'Font Color' ), '#FFF' ),
				'background-color' => array( __( 'Button Color' ), '#FB4B00' ),
			),
		),
		'privacy' => array(
			'.fca_eoi_layout_6 .fca_eoi_layout_6_privacy_copy_wrapper' => array(
				'font-size' => array( __('Font Size'), '14px'),
				'color' => array( __('Font Color'), '#949494'),
			),
		),
		'fatcatapps' => array(
			'.fca_eoi_layout_6 .fca_eoi_layout_6_fatcatapps_link_wrapper a, .fca_eoi_layout_6 .fca_eoi_layout_6_fatcatapps_link_wrapper a:hover' => array(
				'color' => array( __('Font Color'), '#949494'),
			),
		),
	)

);
