/**
 * Undoes a redirect.
 *
 * @param {string} origin The redirect's origin.
 * @param {string} target The redirect's target.
 * @param {string} type The type of redirect.
 * @param {string} nonce The nonce being used to validate the current AJAX request.
 * @param {object} source The DOMElement containing the alerts.
 */
function wpseo_undo_redirect( origin, target, type, nonce, source ) {
	jQuery.post(
		ajaxurl,
		{
			action: 'wpseo_delete_redirect_plain',
			ajax_nonce: nonce,
			redirect: {
				origin: origin,
				target: target,
				type:   type
			}
		},
		function() {
			jQuery( source ).closest( '.yoast-alert' ).fadeOut( 'slow' );
		}
	);
}

/**
 * Creates a redirect
 *
 * @param {string} origin
 * @param {string} type
 * @param {string} nonce
 * @param {object} source
 */
function wpseo_create_redirect( origin, type, nonce, source ) {
	var target = '';

	if( parseInt( type, 10 ) !== 410 ) {
		/* eslint-disable no-alert */
		target = window.prompt( wpseo_premium_strings.enter_new_url.replace( '%s', origin ) );
		/* eslint-enable no-alert */

		if ( target === '' ) {
			/* eslint-disable no-alert */
			window.alert( wpseo_premium_strings.error_new_url );
			/* eslint-enable no-alert */
			return;
		}
	}

	jQuery.post(
		ajaxurl,
		{
			action: 'wpseo_add_redirect_plain',
			ajax_nonce: nonce,
			redirect: {
				origin: origin,
				target: target,
				type:   type
			}
		},
		function( response ) {
			var notice = jQuery( source ).closest( '.yoast-alert' );
			// Remove the classes first.
			jQuery( notice )
				.removeClass( 'updated' )
				.removeClass( 'error' );

			// Remove possibly added redirect errors
			jQuery( notice ).find( '.redirect_error' ).remove();

			if( response.error ) {
				// Add paragraph on top of the notice with actions and set class to error.
				jQuery( notice )
					.addClass( 'error' )
					.prepend( '<p class="redirect_error">' + response.error.message + '</p>' );

				return;
			}

			// Parse the success message
			var success_message = '';
			if( parseInt( type, 10 ) === 410 ) {
				success_message = wpseo_premium_strings.redirect_saved_no_target;
			} else {
				success_message = wpseo_premium_strings.redirect_saved.replace( '%2$s', '<code>' + response.target + '</code>' );
			}

			success_message = success_message.replace( '%1$s', '<code>' + response.origin + '</code>' );

			// Set class to updated and replace html with the success message
			jQuery( notice )
				.addClass( 'updated' )
				.html( '<p>' + success_message + '</p>' );
		},
		'json'
	);
}

module.exports = {
	wpseo_create_redirect: wpseo_create_redirect,
	wpseo_undo_redirect: wpseo_undo_redirect,
};
