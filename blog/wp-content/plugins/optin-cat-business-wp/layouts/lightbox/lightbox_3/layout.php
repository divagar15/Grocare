<?php

/**
 * @package    Optin Cat
 */

$layout = array(

	'name' => __( 'Popup 3' ),

	'editables' => array(

		// Added to the fieldset "Form Background"
		'form' => array(
			'.fca_eoi_lightbox_3' => array(
				'background-color' => array( __( 'Form Background Color' ), '#EEE' ),
				'border-color' => array( __( 'Border Color' ), '#D2D2D2' ),
			),
		),
		'headline' => array(
			'.fca_eoi_lightbox_3 .fca_eoi_lightbox_3_headline_copy_wrapper' => array(
				'font-size' => array( __( 'Font Size'), '27px'),
				'color' => array( __( 'Font Color'), '#FFF'),
				'background-color' => array( __( 'Background Color' ), '#344860' ),
			)
		),
		'description' => array(
			'.fca_eoi_lightbox_3 .fca_eoi_lightbox_3_description_copy_wrapper' => array(
				'font-size' => array( __( 'Font Size' ), '14px'),
				'color' => array( __( 'Font Color' ), '#6D6D6D'),
			)
		),
		'name_field' => array(
			'.fca_eoi_lightbox_3 .fca_eoi_lightbox_3_name_field_wrapper, .fca_eoi_lightbox_3 .fca_eoi_lightbox_3_name_field_wrapper input, .fca_eoi_lightbox_3 .fca_eoi_lightbox_3_name_field_wrapper .fa' => array(
				'font-size' => array( __( 'Font Size' ), '14px'),
				'color' => array( __( 'Font Color' ), '#444' ),
			),
			'.fca_eoi_lightbox_3 .fca_eoi_lightbox_3_name_field_wrapper, .fca_eoi_lightbox_3 .fca_eoi_lightbox_3_name_field_wrapper' => array(
				'border-color' => array( __( 'Border Color' ), '#D2D2D2' ),
			),
		),
		'email_field' => array(
			'.fca_eoi_lightbox_3 .fca_eoi_lightbox_3_email_field_wrapper, .fca_eoi_lightbox_3 .fca_eoi_lightbox_3_email_field_wrapper input, .fca_eoi_lightbox_3 .fca_eoi_lightbox_3_email_field_wrapper .fa' => array(
				'font-size' => array( __( 'Font Size' ), '14px'),
				'color' => array( __( 'Font Color' ), '#444' ),
			),
			'.fca_eoi_lightbox_3 .fca_eoi_lightbox_3_email_field_wrapper' => array(
				'border-color' => array( __( 'Border Color' ), '#D2D2D2' ),
			),
		),
		'button' => array(
			'.fca_eoi_lightbox_3 .fca_eoi_lightbox_3_submit_button_wrapper input' => array(
				'color' => array( __( 'Font Color' ), '#FFF' ),
				'font-size' => array( __( 'Font Size' ), '14px'),
				'background-color' => array( __( 'Background Color' ), '#D35500' ),
				'border-color' => array( __( 'Border Color' ), '#ac4500' ),
			),
		),
		'privacy' => array(
			'.fca_eoi_lightbox_3 .fca_eoi_lightbox_3_privacy_copy_wrapper' => array(
				'font-size' => array( __( 'Font Size' ), '13px' ),
				'color' => array( __( 'Font Color' ), '#B3B3B3' ),
			),
		),
		'fatcatapps' => array(
			'.fca_eoi_lightbox_3 .fca_eoi_lightbox_3_fatcatapps_link_wrapper a, .fca_eoi_lightbox_3 .fca_eoi_lightbox_3_fatcatapps_link_wrapper a:hover' => array(
				'color' => array( __( 'Font Color' ), '#D8722B'),
			),
		),
	)
);
