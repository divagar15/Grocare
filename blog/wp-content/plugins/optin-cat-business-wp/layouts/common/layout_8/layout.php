<?php

function fca_eoi_layout_descriptor_8( $name, $layout_id, $texts ) {
	require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-layout.php';
	$layout = new EasyOptInsLayout( $layout_id );
	$class = $layout->layout_class;
	$fontSize = $class == 'fca_eoi_layout_widget' ? '24px' : '36px'; 
	$btnFontSize = $class == 'fca_eoi_layout_widget' ? '20px' : '24px'; 

	
	return array(

		'name' => __( $name ),

		'editables' => array(

			// Added to the fieldset "Form Background"
			'form' => array(
				'.fca_eoi_layout_8.' . $class => array(
					'background-color' => array( __( 'Form Background' ), '#FFF' ),
					'border-color' => array( __( 'Border Color' ), '#CDBCAA' ),
				),
			),

			// Added to the fieldset "Headline"
			'headline' => array(
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_headline_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), $fontSize),
					'color' => array( __('Font Color'), '#D19855'),
				),
			),
			'description' => array(
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_description_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), '14px'),
					'color' => array( __('Font Color'), '#B49E86'),
				),
			),
			'name_field' => array(
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_name_field_wrapper, ' .
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_name_field_wrapper input' => array(
					'font-size' => array( __( 'Font Size' ), '18px' ),
					'color' => array( __( 'Font Color' ), '#B49E86' ),
					'background-color' => array( __( 'Background Color' ), '#FFF' ),
				),
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_name_field_wrapper' => array(
					'border-color' => array( __('Border Color'), '#EAE3D3'),
				),
			),
			'email_field' => array(
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_email_field_wrapper, ' .
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_email_field_wrapper input' => array(
					'font-size' => array( __( 'Font Size' ), '18px' ),
					'color' => array( __( 'Font Color' ), '#B49E86' ),
					'background-color' => array( __( 'Background Color' ), '#FFF'),
				),
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_email_field_wrapper' => array(
					'border-color' => array( __( 'Border Color' ), '#EAE3D3'),
				),
			),
			'button' => array(
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_submit_button_wrapper input' => array(
					'font-size' => array( __('Font Size'), $btnFontSize ),
					'color' => array( __( 'Font Color' ), '#FFF' ),
					'background-color' => array( __( 'Button Color' ), '#FCBC74' ),
				),
			),
			'privacy' => array(
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_privacy_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), '14px'),
					'color' => array( __('Font Color'), '#B49E86'),
				),
			),
			'fatcatapps' => array(
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a, ' .
				'.fca_eoi_layout_8.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a:hover' => array(
					'color' => array( __('Font Color'), '#B49E86'),
				),
			),
		)
	);
}