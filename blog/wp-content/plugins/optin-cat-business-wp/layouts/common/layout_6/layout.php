<?php

function fca_eoi_layout_descriptor_6( $name, $layout_id, $texts ) {
	require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-layout.php';
	$layout = new EasyOptInsLayout( $layout_id );
	$class = $layout->layout_class;

	return array(

		'name' => __( $name ),

		'editables' => array(

			// Added to the fieldset "Form Background"
			'form' => array(
				'.fca_eoi_layout_6.' . $class => array(
					'background-color' => array( __( 'Form Background' ), '#2B2B2B' ),
					'border-color' => array( __( 'Border Color' ), '#727274' ),
				),
			),

			// Added to the fieldset "Headline"
			'headline' => array(
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_headline_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), '28px'),
					'color' => array( __('Font Color'), '#CECECE'),
				),
			),
			'description' => array(
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_description_copy_wrapper p, ' .
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_description_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), '14px'),
					'color' => array( __('Font Color'), '#CECECE'),
					
				),
			),
			'name_field' => array(
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_name_field_wrapper, ' .
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_name_field_wrapper input' => array(
					'font-size' => array( __( 'Font Size' ), '18px' ),
					'color' => array( __( 'Font Color' ), '#7C7C7C' ),
					'background-color' => array( __( 'Background Color' ), '#CECECE' ),
				),
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_name_field_wrapper' => array(
					'border-color' => array( __('Border Color'), '#CECECE'),
				),
			),
			'email_field' => array(
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_email_field_wrapper, ' .
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_email_field_wrapper input' => array(
					'font-size' => array( __( 'Font Size' ), '18px' ),
					'color' => array( __( 'Font Color' ), '#7C7C7C' ),
					'background-color' => array( __( 'Background Color' ), '#CECECE'),
				),
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_email_field_wrapper' => array(
					'border-color' => array( __( 'Border Color' ), '#CECECE'),
				),
			),
			'button' => array(
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_submit_button_wrapper input' => array(
					'font-size' => array( __('Font Size'), '18px' ),
					'color' => array( __( 'Font Color' ), '#FFF' ),
					'background-color' => array( __( 'Button Color' ), '#FB4B00' ),
				)
			),
			'privacy' => array(
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_privacy_copy_wrapper' => array(
					'font-size' => array( __('Font Size'), '14px'),
					'color' => array( __('Font Color'), '#949494'),
				),
			),
			'fatcatapps' => array(
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a, ' .
				'.fca_eoi_layout_6.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a:hover' => array(
					'color' => array( __('Font Color'), '#949494'),
				),
			),
		)
	);
}