<?php

function aweber_object( $settings, $id ) {
	static $object = null;

	if ( is_null( $object ) ) {
		$object = aweber_object_create( $settings, $id );
	}

	return $object;
}

function aweber_object_create( $settings, $id ) {

	if ( ! class_exists( 'AWeberAPI' ) ) {
		require_once FCA_EOI_PLUGIN_DIR . 'providers/aweber/aweber_api/aweber_api.php';
	}
	
	// Get the authorization code from $_REQUEST, if not found then from DB
	$api_credentials = get_option( 'fca_eoi_api_aweber_credentials_' . $id );

	if( 'fca_eoi_aweber_get_lists' === K::get_var( 'action', $_REQUEST ) ) {
		$authorization_code = trim( K::get_var( 'aweber_authorization_code', $_REQUEST ) );
	} else {
		$authorization_code = trim( K::get_var( 'authorization_code', $api_credentials ) );
	}
	// Exit function if missing authorization code
	if( ! $authorization_code ) {
		return FALSE;
	}
	// If we receive a different authorization_code, we calculate new keys
	if( K::get_var( 'authorization_code', $api_credentials ) !== $authorization_code ) {
		$api_credentials = array();
	}
	// Do we need to get credentials again, if so, set a marker and delete old credentials
	if ( empty( $api_credentials ) ) {
		$credentials_are_current = FALSE;
		delete_option( 'fca_eoi_api_aweber_credentials_' . $id );
	} else {
		$credentials_are_current = TRUE;
	}
	/**
	 * Check if credentials exists in options, 
	 * if they don't, create them,
	 * and if we fail, exit function with false 
	 */
	if( ! $credentials_are_current ) {
		try {
			list(
				$api_credentials[ 'consumerKey' ]
				, $api_credentials[ 'consumerSecret' ]
				, $api_credentials[ 'accessKey' ]
				, $api_credentials[ 'accessSecret' ]
			) = AWeberAPI::getDataFromAweberID( $authorization_code );
			$api_credentials[ 'authorization_code' ] = $authorization_code;
			delete_option( 'fca_eoi_api_aweber_credentials_' . $id );
			add_option( 'fca_eoi_api_aweber_credentials_' . $id, $api_credentials );
		} catch ( Exception $e ) {
			return FALSE;
		}
	}
	try {
		// Add credentials
		$obj[ 'credentials' ] = $api_credentials;
		// Initialize app
		$obj[ 'application' ] = new AWeberAPI(
			$api_credentials[ 'consumerKey' ]
			, $api_credentials[ 'consumerSecret' ]
		);
		// Prepare account
		$obj[ 'account' ] = $obj[ 'application' ]->getAccount(
			$api_credentials[ 'accessKey' ]
			, $api_credentials[ 'accessSecret' ]
		);
	} catch ( Exception $e ) {
		return FALSE;		
	}
	// Prepare lists
	foreach ( $obj[ 'account' ]->lists->data[ 'entries' ] as $list ) {
		$obj[ 'lists' ][] = array( 
			'id' => $list[ 'id' ]
			, 'name' => $list[ 'name' ]
		);
	}
	return $obj;
}

function aweber_get_lists( $settings, $id ) {
	$helper = aweber_object( $settings, $id );

	return empty( $helper ) || empty( $helper['lists'] ) ? array() : $helper['lists'];
}

function aweber_ajax_get_lists() {
	
	$id = $_POST['post_id'];

	$lists_formatted = array( '' => 'Not set' );

	global $dh_easy_opt_ins_plugin;
	$settings = $dh_easy_opt_ins_plugin->settings;

	$aweber_object = aweber_object( $settings, $id );
	if( $aweber_object ) {
		foreach ( $aweber_object[ 'lists' ] as $list ) {
			$lists_formatted[ $list[ 'id' ] ] = $list[ 'name' ];
		}
	}

	echo json_encode( $lists_formatted );
	exit;
}

function aweber_add_user( $settings, $user_data, $list_id ) {
	
	$helper = aweber_object( $settings, $user_data['form_id'] );

	if ( empty( $helper ) ) {
		return false;
	}

	$account = $helper[ 'account' ];
	$account_id = $account->data[ 'id' ];

	try {
		$listURL = "/accounts/{$account_id}/lists/{$list_id}";
		$list = $account->loadFromUrl( $listURL );
		$subscribers = $list->subscribers;
		$subscribers->create( array(
			'email' => K::get_var( 'email', $user_data ),
			'name' => K::get_var( 'name', $user_data ),
		) );
		
		return TRUE;
	} catch ( Exception $e ) {
		//check if already subscribed
		if ( stripos( $e->getMessage(), 'already subscribed') !== false ) {
			return true;
		}
		
	}
	return false;
}

function aweber_admin_notices( $errors ) {

	/* Provider errors can be added here */

	return $errors;
}

function aweber_string( $def_str ) {

	$strings = array(
		'Form integration' => __( 'AWeber Integration' ),
	);

	return K::get_var( $def_str, $strings, $def_str );
}

function aweber_integration( $settings ) {

	global $post;
	$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );
	$authorization_code = trim( K::get_var( 'aweber_authorization_code', $fca_eoi ) );
	$list_id = K::get_var( 'aweber_list_id', $fca_eoi );
	// Prepare the lists for K
	$lists = aweber_get_lists( $settings, $post->ID );
	$lists_formatted = array( '' => 'Not set' );
	if( ! empty( $lists ) ) {
		foreach ( $lists as $list ) {
			$lists_formatted[ $list[ 'id' ] ] = $list[ 'name' ];
		}
	}
	$suggested_auth = '';
	$suggested_api_credentials = '';
	
	//GRAB OLD DATA IF IT EXISTS
	$screen = get_current_screen();
	if ( 'add' === $screen->action ) {		
		$last_form_meta = get_option( 'fca_eoi_last_form_meta', '' );
		if ( !empty ($last_form_meta['aweber_authorization_code']) ) {
			$suggested_auth = $last_form_meta['aweber_authorization_code'];
			$id = $last_form_meta[ 'post_id' ];
			$suggested_api_credentials = get_option( 'fca_eoi_api_aweber_credentials_' . $id );
			update_option( 'fca_eoi_api_aweber_credentials_' . $post->ID, $suggested_api_credentials );
		}
	}

	empty ($authorization_code) ? $authorization_code = $suggested_auth : '';

	K::fieldset( aweber_string( 'Form integration' ) ,
		array(
			array( 'textarea', 'fca_eoi[aweber_authorization_code]',
				array( 
					'style' => 'width: 25em;',
					'rows' => '4',
					// 'readonly' => $lists ? 'readonly' : null,
				),
				array(
					'format' => '<p><label class="fca_eoi_aweber_label">Authorization Code<br />:textarea<br /></label><em> <a tabindex="-1" href="https://auth.aweber.com/1.0/oauth/authorize_app/30de7ab3" target="_blank">Get my AWeber App Authorization Code</a>.</em></p>',
					'value' => $authorization_code,
				)
			),
			array( 'select', 'fca_eoi[aweber_list_id]',
				array(
					'class' => 'select2',
					'style' => 'width: 27em;',
				),
				array(
					'format' => '<p id="aweber_list_id_wrapper"><label>List to subscribe to<br />:select</label></p>',
					'options' => $lists_formatted,
					'selected' => $list_id,
				),
			),
		),
		array(
			'id' => 'fca_eoi_fieldset_form_aweber_integration',
		)
	);
}
