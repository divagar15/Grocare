<?php

function getresponse_object( $settings ) {
	static $object = null;

	if ( is_null( $object ) ) {
		$object = getresponse_object_create( $settings );
	}

	return $object;
}

function getresponse_object_create( $settings ) {

	if( ! class_exists( 'GetResponseApiOptinCat' ) ) {
		require_once FCA_EOI_PLUGIN_DIR . "providers/getresponse/GetResponseAPI.class.php";
	}
	$last_form_meta = get_option( 'fca_eoi_last_form_meta', '' );
	$suggested_api = empty($last_form_meta['getresponse_api_key']) ? '' : $last_form_meta['getresponse_api_key'];
	
	$eoi_form_meta = K::get_var( 'eoi_form_meta', $settings, array() );
	$api_key = K::get_var('getresponse_api_key', $eoi_form_meta, $suggested_api );

	if( $api_key ) {
		return new GetResponseApiOptinCat( $api_key );
	} else {
		return false;
	}
}

function getresponse_get_lists( $settings ) {

	$helper = getresponse_object( $settings );

	// Return an empty array if the object is not a valid GetResponse Api instance
	if ( empty( $helper ) ) {
		return array();
	}

	$lists = $helper->getCampaigns();

	return empty( $lists ) ? array() : $lists;
}

function getresponse_ajax_get_lists() {


    // Validate the API key
	$api_key = K::get_var( 'getresponse_api_key', $_POST );
	$lists_formatted = array( '' => 'Not set' );

    // Make call and add lists if any
	if (32 ==  strlen($api_key)  ) {
		global $dh_easy_opt_ins_plugin;
		$settings = $dh_easy_opt_ins_plugin->settings;
		if( ! class_exists( 'GetResponseApiOptinCat' ) ) {
			require_once FCA_EOI_PLUGIN_DIR . "providers/getresponse/GetResponseAPI.class.php";
		}

		$api = new GetResponseApiOptinCat( $api_key );

		$campaigns = $api->getCampaigns();
		if ( empty( $campaigns ) ) {
			$campaigns = array();
		}

		foreach ( $campaigns as $id=>$list ) {
			$lists_formatted[$id] = $list->name;
		}
	}

	// Output response and exit
	echo json_encode( $lists_formatted );
	exit;
}

function getresponse_add_user( $settings, $user_data, $list_id ) {
	
	$form_meta = get_post_meta ( $user_data['form_id'], 'fca_eoi', true );
	$settings['eoi_form_meta']['getresponse_api_key'] =  $form_meta['getresponse_api_key'];
	
	$helper = getresponse_object( $settings );

	if ( empty( $helper ) ) {
		return false;
	}


	$result = $helper->addContact( $list_id, K::get_var( 'name', $user_data ), K::get_var( 'email', $user_data ) );


    // Return true if added, otherwise false
	if( $result!== Null && $result->queued == 1 ) {
		return true;
	} else {
		return false;
	}
}

function getresponse_admin_notices( $errors ) {

	/* Provider errors can be added here */

	return $errors;
}

function getresponse_string( $def_str ) {

	$strings = array(
		'Form integration' => __( 'GetResponse Integration' ),
	);

	return K::get_var( $def_str, $strings, $def_str );
}

function getresponse_integration( $settings ) {

	global $post;
	$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );

	// Remember old Getresponse settings if we are in a new form
	$last_form_meta = get_option( 'fca_eoi_last_form_meta', '' );
	$suggested_api = empty($last_form_meta['getresponse_api_key']) ? '' : $last_form_meta['getresponse_api_key'];
	$suggested_list = empty($last_form_meta['getresponse_list_id']) ? '' : $last_form_meta['getresponse_list_id'];

	// Prepare the lists for K
	$lists = getresponse_get_lists( $settings );

	$lists_formatted = array( '' => 'Not set' );

    foreach ( $lists as $id=>$list ) {
        $lists_formatted[$id] = $list->name;
    }

	K::fieldset( getresponse_string( 'Form integration' ) ,
		array(
			array( 'input', 'fca_eoi[getresponse_api_key]',
				array( 
					'class' => 'regular-text',
					'value' => K::get_var( 'getresponse_api_key', $fca_eoi, $suggested_api ),
				),
				array( 'format' => '<p><label>API Key<br />:input</label><br /><em><a tabindex="-1" href="https://app.getresponse.com/manage_api.html" target="_blank">[Get my GetResponse API Key]</a></em></p>' ),
			),
			array( 'select', 'fca_eoi[getresponse_list_id]',
				array(
					'class' => 'select2',
					'style' => 'width: 27em;',
				),
				array(
					'format' => '<p id="getresponse_list_id_wrapper"><label>List to subscribe to<br />:select</label></p>',
					'options' => $lists_formatted,
					'selected' => K::get_var( 'getresponse_list_id', $fca_eoi, $suggested_list ),
				),
			),
		),
		array(
			'id' => 'fca_eoi_fieldset_form_getresponse_integration',
		)
	);
}
