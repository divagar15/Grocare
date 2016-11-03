jQuery( document ).ready( function( $ ) {

	var $authorization_code = $( '[name="fca_eoi[aweber_authorization_code]"]' );
	var $post_id = $( '#post_ID' );
	var $lists = $( '[name="fca_eoi[aweber_list_id]"]' );
	var $lists_wrapper = $( '#aweber_list_id_wrapper' );

	aweber_toggle_fields();

	fca_eoi_provider_status_setup( 'aweber', $authorization_code );

	$authorization_code.bind( 'input', function() {
		if ( ! fca_eoi_provider_is_value_changed( $( this ) ) ) {
			return;
		}

		fca_eoi_provider_status_set( 'aweber', fca_eoi_provider_status_codes.loading );

		var data = {
			'action': 'fca_eoi_aweber_get_lists', /* API action name, do not change */
			'aweber_authorization_code' : $authorization_code.val(),
			'post_id' : $post_id.val()
		};

		$.post( ajaxurl, data, function( response ) {

			var lists = JSON.parse( response );

			fca_eoi_provider_status_set( 'aweber', Object.keys(lists).length > 1
				? fca_eoi_provider_status_codes.ok
				: fca_eoi_provider_status_codes.error );

			var $lists = $( '<select class="select2" style="width: 27em;" name="fca_eoi[aweber_list_id]" >' );

			for ( list_id in lists ) {
				$lists.append( '<option value="' + list_id + '">' + lists[ list_id ] + '</option>' );
			}

			// Replace dropdown with new list of lists, apply Select2 then show
			$( '[name="fca_eoi[aweber_list_id]"]' ).select2( 'destroy' );
			$( '[name="fca_eoi[aweber_list_id]"]' ).replaceWith( $lists );
			$( '[name="fca_eoi[aweber_list_id]"]' ).select2();
			aweber_toggle_fields();
		} );
	})

	// Confirm before unlocking the authorization_code field
	$authorization_code.filter( '[readonly=readonly]' ).click( function( e ) {
		var confirm = window.confirm( 'Due to limitations in the AWeber API, The plugin accepts only one AWeber app, thus, changing the authorization code in this form will change it in all other forms.\n\nDo you really want to change the authorization code?' );
		if ( confirm ) {
			$authorization_code.removeAttr( 'readonly' ).val( '' );
		}
	} );

	/**
	 * Show/hide some fields if there are/aren't list options
	 *
	 * Don't forget that there is always the option "Not Set", 
	 * so take it into consideration when cheking the number of options
	 */
	function aweber_toggle_fields() {

		var options = $( 'option', '[name="fca_eoi[aweber_list_id]"]' );

		if ( options.length > 1 ) {
			$lists_wrapper.show( 'fast' );
		} else {
			$lists_wrapper.hide();
		}
	}
} );
