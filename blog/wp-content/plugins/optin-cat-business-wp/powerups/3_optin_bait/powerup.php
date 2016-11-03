<?php

define('FCA_EOI_EMAIL_TEST_KNOWLEDGE_BASE_LINK', 'https://fatcatapps.com/');

function powerup_optin_bait( $settings ) {

	paf_options ( array(
		'eoi_powerup_optin_bait' => array(
			'type' => 'checkbox',
			'options' => array(
				'on' => __( 'Enabled' ),
			),
			'page' => 'eoi_powerups',
			'title' => __( 'Optin Bait Delivery' ),
			'description' => sprintf( '<p class="description eoi_powerup_description">%s</p>', __( 'Lets you deliver emails containing optin baits right after the user opted in.' ) ),
		)
	) );

	if( ! paf( 'eoi_powerup_optin_bait' ) ) {
		return;
	}

	new EoiOptinBait( $settings );
}

class EoiOptinBait {

	var $settings;

	public function __construct( $settings ) {

		$this->settings = $settings;

		add_action( 'fca_eoi_powerups',         array( $this, 'show_optin_bait_fields' ) );
		add_action( 'admin_enqueue_scripts',    array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'fca_eoi_after_submission', array( $this, 'verify_and_send_optin_bait_email' ), 10, 2 );

		add_action( 'wp_ajax_fca_eoi_email_test_disable', array( $this, 'email_test_disable' ) );
		add_action( 'wp_ajax_fca_eoi_email_test_send',    array( $this, 'email_test_send' ) );
	}

	public function enqueue_admin_scripts() {
		wp_enqueue_style( 'powerups-css', FCA_EOI_PLUGIN_URL . '/assets/powerups/fca_eoi_powerups.css' );
	}

	public function verify_and_send_optin_bait_email( $fca_eoi , $_P ) {

		// Exit function if optin bait not enabled for this optin form
		if ( ! K::get_var( 'optin_bait', $fca_eoi, '' ) ) {
			return;
		}

		$this->send_optin_bait_email( $fca_eoi, $_P );
	}

	/**
	 * Sends the email
	 */
	private function send_optin_bait_email( $fca_eoi, $_P ) {
		$replacement_patterns = array(
			'recipient_name'   => K::get_var( 'name', $_P, '' ),
			'sender_name'      => K::get_var( 'sender_name', $fca_eoi[ 'optin_bait_fields' ], '' )
				? $fca_eoi[ 'optin_bait_fields' ][ 'sender_name' ]
				: get_option( 'blogname' )
			,
			'sender_email'     => K::get_var( 'sender_email', $fca_eoi[ 'optin_bait_fields' ], '' )
				? $fca_eoi[ 'optin_bait_fields' ][ 'sender_email' ]
				: get_option( 'admin_email' )
			,
			'site_url'         => get_site_url(),
			'site_name'        => get_option( 'blogname' ),
			'site_description' => get_option( 'blogdescription' ),
		);

		$from_name = $replacement_patterns[ 'sender_name' ];
		$from_email = $replacement_patterns[ 'sender_email' ];
		$from = sprintf( "%s <%s>"
			, $from_name
			, $from_email
		);

		$to = K::get_var( 'email', $_P, '' );

		$subject = K::get_var( 'subject', $fca_eoi[ 'optin_bait_fields' ], '' )
			? $fca_eoi[ 'optin_bait_fields' ][ 'subject' ]
			: ''
		;

		$message = K::get_var( 'message', $fca_eoi[ 'optin_bait_fields' ], '' )
			? $fca_eoi[ 'optin_bait_fields' ][ 'message' ]
			: ''
		;

		$headers = "From: $from\r\n";

		foreach ( $replacement_patterns as $k => $v ) {
			$message = str_replace( '{{' . $k . '}}', $v, $message );
			$subject = str_replace( '{{' . $k . '}}', $v, $subject );
		}

		// Send email if all important parameters are found
		if ( $message && $subject && $to ) {
			return wp_mail( $to, $subject, $message, $headers );
		}

		return false;
	}

	/**
	 * Adds Optin Bait fields
	 */
	public function show_optin_bait_fields( $fca_eoi ) {

		$optin_bait = K::get_var( 'optin_bait', $fca_eoi, '' );
		$optin_bait_fields = K::get_var( 'optin_bait_fields', $fca_eoi, array() );
		$replacement_patterns = array( 'recipient_name', 'sender_name', 'sender_email', 'site_url', 'site_name', 'site_description' );

		echo '<br />';
		echo '<div id="eoi-optin-bait">';

		// The switch
		K::input( 'fca_eoi[optin_bait]'
			, array(
				'type' => 'checkbox',
				'id' => 'eoi-optin-bait-switch',
				'checked' => $optin_bait ? 'checked' : null,
			)
			, array(
				'format' => '<label>:input Optin Bait Delivery</label>',
			)
		);

		// The fields
		echo '<div id="eoi-optin-bait-fields" class="hidden">';
		K::input( 'fca_eoi[optin_bait_fields][sender_name]'
			, array(
				'class' => 'regular-text',
				'value' => K::get_var( 'sender_name', $optin_bait_fields )
					? $optin_bait_fields[ 'sender_name' ]
					: get_option( 'blogname' )
				,
			)
			, array( 'format' => '<p><label>Sender name<br />:input</label></p>' )
		);
		K::input( 'fca_eoi[optin_bait_fields][sender_email]'
			, array(
				'class' => 'regular-text',
				'value' => K::get_var( 'sender_email', $optin_bait_fields )
					? $optin_bait_fields[ 'sender_email' ]
					: get_option( 'admin_email' )
				,
			)
			, array( 'format' => '<p><label>Sender email<br />:input</label></p>' )
		);
		K::input( 'fca_eoi[optin_bait_fields][subject]'
			, array(
				'class' => 'regular-text',
				'value' => K::get_var( 'subject', $optin_bait_fields, '' ),
			)
			, array( 'format' => '<p><label>Subject<br />:input</label><br /><span class="description">Field accepts replacement patterns</span></p>' )
		);
		K::textarea( 'fca_eoi[optin_bait_fields][message]'
			, array(
				'cols' => 62,
				'rows' => 10,
			)
			, array(
				'value' => K::get_var( 'message', $optin_bait_fields, '' ),
				'format' => '<p><label>Message<br />:textarea</label><br /><span class="description">Field accepts replacement patterns</span></p>',
			)
		);
		echo '<p><strong>Replacement Patterns</strong>:<br />';
		echo '<code>{{' . implode( '}}</code>, <code>{{', $replacement_patterns ) . '}}</code>';
		echo '</p>';

		$this->email_test_setup();

		echo '</div>';

		?><script>
			jQuery( document ).ready( function( $ ) {
				$( '#eoi-optin-bait-switch' ).change( function() {
					var $this = $( this );
					if ( $this.is( ':checked' ) ) {
						$( '#eoi-optin-bait-fields' ).show( 'fast' );
					} else {
						$( '#eoi-optin-bait-fields' ).hide( 'fast');
					}
				} ).change();
			} );
		</script><?php

		echo '</div>';
	}

	public function email_test_disable() {
		update_option( 'fca_eoi_email_test_disabled', true );
	}

	public function email_test_send() {
		wp_die( json_encode( $this->send_optin_bait_email( array(
			'optin_bait_fields' => stripslashes_deep( $_POST )
		), array(
			'name' => 'Test Recipient',
			'email' => $_POST[ 'email' ]
		) ) ) );
	}

	private function email_test_list_to_text( $items, $last_connection = 'or' ) {
		if ( empty( $items ) ) {
			return '';
		}

		$count = count( $items );

		if ( $count == 1 ) {
			return $items[0];
		}

		return
			implode( ', ', array_slice( $items, 0, $count - 1 ) ) .
			' ' . $last_connection . ' ' .
			$items[ $count - 1 ];
	}

	private function email_test_learn_more_link() {
		return
			'<a href="' . htmlspecialchars(FCA_EOI_EMAIL_TEST_KNOWLEDGE_BASE_LINK) . '">' .
				'Learn more' .
			'</a>.';
	}

	private function email_test_recommendations_to_text( $recommendations ) {
		$text = 'For best deliverability, it is recommended that you ';

		if ( empty( $recommendations['activate'] ) ) {
			$text .= 'use a transactional email provider, such as ' .
			         $this->email_test_list_to_text( $recommendations['install'] );
		} else {
			$text .= 'activate the ' .
			         $this->email_test_list_to_text( $recommendations['activate'] ) . ' ' .
			         'plugin' . ( count( $recommendations['activate'] ) == 1 ? '' : 's' );
		}

		return $text . '. ' . $this->email_test_learn_more_link();
	}

	public function email_test_setup() {
		if ( get_option( 'fca_eoi_email_test_disabled' ) ) {
			return;
		}

		wp_enqueue_script( 'eoi-email-test-js', FCA_EOI_PLUGIN_URL . '/assets/admin/eoi-email-test.js' );
		wp_enqueue_style( 'eoi-email-test-css', FCA_EOI_PLUGIN_URL . '/assets/admin/eoi-email-test.css' );

		$recommendations = array(
			'activate' => array(),
			'install' => array()
		);

		$has_active = false;
		$all_provider_links = array();

		foreach ( $this->email_test_get_recommended_plugins() as $id => $plugin ) {
			$activate_plugin_link =
				'<a href="' . htmlspecialchars( admin_url( 'plugins.php' ) . '#' . $id ) . '">' .
					htmlspecialchars( $plugin['name'] ) .
				'</a>';

			$install_provider_link =
				'<a href="' . htmlspecialchars( $plugin['url'] ) . '">' .
					htmlspecialchars( $plugin['provider'] ) .
				'</a>';

			if ( $plugin['installed'] ) {
				$recommendations['activate'][] = $activate_plugin_link;
			} else {
				$recommendations['install'][] = $install_provider_link;
			}

			$all_provider_links[] = $install_provider_link;

			if ( $plugin['active'] ) {
				$has_active = true;
			}
		}

		$failure_message =
			'You might have entered a faulty email address, your server might not support sending emails ' .
			'or ' . $this->email_test_list_to_text( $all_provider_links ) . ' might not be configured correctly. ' .
			$this->email_test_learn_more_link();

		?>
		<script>
			var fca_eoi_email_test_recommendation = <?php echo json_encode( $this->email_test_recommendations_to_text( $recommendations ) ) ?>;
			var fca_eoi_email_test_can_activate = <?php echo json_encode( ! empty( $recommendations['activate'] ) ) ?>;
			var fca_eoi_email_test_has_active = <?php echo json_encode( $has_active ) ?>;
			var fca_eoi_email_test_failure_message = <?php echo json_encode( $failure_message ) ?>;
		</script>
		<div class="fca_eoi_email_test" id="fca_eoi_email_test"></div>
		<?php
	}

	private function email_test_get_recommended_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$known_plugins = array(
			'wpmandrill' => array(
				'name'      => 'wpMandrill',
				'provider'  => 'Mandrill',
				'url'       => 'https://wordpress.org/plugins/wpmandrill/',
				'installed' => false,
				'active'    => false
			),
			'mailgun' => array(
				'name'      => 'Mailgun',
				'provider'  => 'Mailgun',
				'url'       => 'https://wordpress.org/plugins/mailgun/',
				'installed' => false,
				'active'    => false
			)
		);

		foreach ( array_keys( get_plugins() ) as $file ) {
			foreach ( $known_plugins as $id => &$known_plugin ) {
				if ( strpos( $file, $id ) !== false ) {
					$known_plugin['installed'] = true;
					$known_plugin['active'] = is_plugin_active( $file );
				}
			}
		}

		return $known_plugins;
	}
}
