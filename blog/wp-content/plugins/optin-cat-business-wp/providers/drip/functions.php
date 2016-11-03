<?php
require_once FCA_EOI_PLUGIN_DIR . "providers/drip/Drip_Api.php";
function drip_object( $settings ) {
	static $object = null;

	if ( is_null( $object ) ) {
		$object = drip_object_create( $settings );
	}

	return $object;
}

function drip_object_create( $api_token ) {

	if ( $api_token ) {
		return new Drip_Api( $api_token );
	} else {
		return false;
	}
}

function drip_add_user( $settings, $user_data, $list_id ) {
	
	$form_meta = get_post_meta ( $user_data['form_id'], 'fca_eoi', true );
	$api_key =	$form_meta['drip_api_token'];
	$drip_api = drip_object( $api_key );
	$account_id = $form_meta['drip_account_id'];
	$action = $form_meta['drip_action'];
	$email = K::get_var( 'email', $user_data );
	$name = K::get_var( 'name', $user_data, '' );
	$name_field = apply_filters( 'fca_eoi_drip_name', 'name' );
	
	$params = array(
		'account_id' => $account_id,
		'email' => $email,
	);
	
	if ( !empty ( $name ) ) {
		if ( $action == 'subscribe' ) {
			$params['custom_fields'] = array( $name_field => $name );
		} else if  ( $action == 'event' ) {
			$params['properties'] = array( $name_field => $name );
		}
	}	

	$result = false;

	if ( $action == 'subscribe' ) {
		$params['campaign_id'] = $list_id;
		$response = $drip_api->subscribe_subscriber( $params );
		$result = ! empty( $response );
	} elseif ( $action == 'event' ) {
		$params['action'] = $form_meta['drip_event_name'];
		$result = $drip_api->record_event( $params );
	} elseif ( $action == 'tag' ) {
		$params['tag'] = $form_meta['drip_tag_name'];
		$result = $drip_api->tag_subscriber( $params );
	}

	return $result;
}

function drip_integration( $settings ) {

	global $post;
	$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );

	$last_form_meta = get_option( 'fca_eoi_last_form_meta', '' );
	$suggested_api = empty($last_form_meta['drip_api_token']) ? '' : $last_form_meta['drip_api_token'];
	$suggested_account_id = empty($last_form_meta['drip_account_id']) ? '' : $last_form_meta['drip_account_id'];
	$suggested_list = empty($last_form_meta['drip_list_id']) ? '' : $last_form_meta['drip_list_id'];

	
	K::fieldset( __( 'Drip Integration' ) ,
		array(
			array( 'input', 'fca_eoi[drip_api_token]',
				array(
					'class' => 'regular-text',
					'value' => K::get_var( 'drip_api_token', $fca_eoi, $suggested_api ),
					'id' => 'fca_eoi_drip_api_token'
				),
				array(
					'format' =>
						'<p>' .
							'<label>API Token<br>:input</label><br>' .
							'<em>The Drip API token can be found under Settings &rarr; My User Settings.</em>' .
						'</p>'
				),
			),
			array( 'input', 'fca_eoi[drip_account_id]',
				array(
					'class' => 'regular-text',
					'value' => K::get_var( 'drip_account_id', $fca_eoi, $suggested_account_id ),
					'id' => 'fca_eoi_drip_account_id'
				),
				array(
					'format' =>
						'<p>' .
							'<label>Account ID<br>:input</label><br>' .
							'<em>The Drip account ID can be found under Settings &rarr; Site Setup.</em>' .
						'</p>'
				),
			),
			array( 'select', 'fca_eoi[drip_action]',
				array(
					'class' => 'select2',
					'style' => 'width: 27em;',
					'id' => 'fca_eoi_drip_action'
				),
				array(
					'format' =>
						'<p id="fca_eoi_drip_action_wrapper" class="fca_eoi_drip_wrapper" style="display: none;">' .
							'<label>Action<br>:select</label>' .
						'</p>',
					'options' => array(
						'subscribe' => __( 'Subscribe to campaign' ),
						'event' => __( 'Record an event' ),
						'tag' => __( 'Apply a tag' )
					)
				),
			),
			array( 'select', 'fca_eoi[drip_list_id]',
				array(
					'class' => 'select2',
					'value' => K::get_var( 'drip_list_id', $fca_eoi, $suggested_list ),
					'style' => 'width: 27em;',
					'id' => 'fca_eoi_drip_campaign'
				),
				array(
					'format' =>
						'<p id="fca_eoi_drip_campaign_wrapper" class="fca_eoi_drip_wrapper fca_eoi_drip_option_wrapper" style="display: none;">' .
							'<label>Campaign<br>:select</label>' .
						'</p>'
				),
			),
			array( 'input', 'fca_eoi_drip_event_name',
				array(
					'class' => 'regular-text',
					'id' => 'fca_eoi_drip_event',
				),
				array(
					'format' =>
						'<p id="fca_eoi_drip_event_wrapper" class="fca_eoi_drip_wrapper fca_eoi_drip_option_wrapper" style="display: none">' .
							'<label>Event<br>:input</label><br>' .
						'</p>'
				),
			),
			array( 'input', 'fca_eoi_drip_tag_name',
				array(
					'class' => 'regular-text',
					'id' => 'fca_eoi_drip_tag',
				),
				array(
					'format' =>
						'<p id="fca_eoi_drip_tag_wrapper" class="fca_eoi_drip_wrapper fca_eoi_drip_option_wrapper" style="display: none">' .
							'<label>Tag<br>:input</label><br>' .
						'</p>'
				),
			)
		),
		array(
			'id' => 'fca_eoi_fieldset_form_drip_integration',
			'data-post-id' => $post->ID
		)
	);
}

function drip_ajax_get_lists() {

	$post       = get_post( K::get_var( 'post_id', $_POST ) );
	$fca_eoi    = get_post_meta( $post->ID, 'fca_eoi', true );
	$api_token  = K::get_var( 'drip_api_token', $_POST );
	$account_id = K::get_var( 'drip_account_id', $_POST );

	if ( ! class_exists( 'Drip_Api' ) ) {
		global $dh_easy_opt_ins_plugin;
		$settings = $dh_easy_opt_ins_plugin->settings;
		require_once FCA_EOI_PLUGIN_DIR . "providers/drip/Drip_Api.php";
	}

	$helper = new Drip_Api( $api_token );

	echo json_encode( array(
		'campaigns' => $helper->get_campaigns( array( 'account_id' => $account_id ) ),
		'error'     => $helper->get_error_message(),
		'current'   => array(
			'action'   => empty( $fca_eoi['drip_action'] ) ? 'subscribe' : $fca_eoi['drip_action'],
			'campaign' => empty( $fca_eoi['drip_list_id'] ) ? null : $fca_eoi['drip_list_id'],
			'event'    => get_post_meta( $post->ID, 'fca_eoi_drip_event_name', true ),
			'tag'      => get_post_meta( $post->ID, 'fca_eoi_drip_tag_name', true )
		)
	) );
	exit;
}

function drip_admin_notices( $errors ) {
	return $errors;
}
