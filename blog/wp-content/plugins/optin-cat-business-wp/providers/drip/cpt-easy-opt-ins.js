jQuery( document ).ready( function( $ ) {

  var $parent = $( '#fca_eoi_fieldset_form_drip_integration' );
  var $api_token = $( '#fca_eoi_drip_api_token' );
  var $account_id = $( '#fca_eoi_drip_account_id' );
  var $action = $( '#fca_eoi_drip_action' );
  var $campaign = $( '#fca_eoi_drip_campaign' );
  var $event = $( '#fca_eoi_drip_event' );
  var $tag = $( '#fca_eoi_drip_tag' );
  var $wrappers = $( '.fca_eoi_drip_wrapper' );
  var $option_wrappers = $( '.fca_eoi_drip_option_wrapper' );
  var $action_wrapper = $( '#fca_eoi_drip_action_wrapper' );
  var $campaign_wrapper = $( '#fca_eoi_drip_campaign_wrapper' );
  var $event_wrapper = $( '#fca_eoi_drip_event_wrapper' );
  var $tag_wrapper = $( '#fca_eoi_drip_tag_wrapper' );
  var $error = $( '<p id="fca_eoi_drip_error" style="display: none;"></p>' );

  fca_eoi_provider_status_setup( 'drip', [ $api_token, $account_id ] );

  var obtain_data = ( function() {
    var is_running = false;
    var timeout_in_ms = 300;

    return function( callback ) {
      if ( is_running ) {
        return;
      }

      is_running = true;
      setTimeout( function() {
        $.post( ajaxurl, {
          'action': 'fca_eoi_drip_get_lists',
          'drip_api_token': $api_token.val(),
          'drip_account_id': $account_id.val(),
          'post_id': $parent.data('post-id')
        }, function( response ) {
          is_running = false;
          callback( JSON.parse( response ) );
        } );
      }, timeout_in_ms );
    }
  } )();

  var populate_data = function( data ) {
    var current = data['current'];

    $campaign.get( 0 ).innerHTML = data['campaigns'].map( function( item, index ) {
      var $option = $( '<option/>' );
      $option.attr( 'value', item['id'] );
      $option.append( item['name'] );

      var is_selected = false;

      if ( current.campaign ) {
        is_selected = item['id'] == current.campaign;
      } else {
        is_selected = index == 0;
      }

      if ( is_selected ) {
        $option.attr( 'selected', 'selected' );
      }

      return $option.get( 0 ).outerHTML;
    } ).join('');


    $event.val( current.event );
    $tag.val( current.tag );
    $action.val( current.action );

    $campaign.trigger( 'change' );
    $action.trigger( 'change' );

    $action_wrapper.show();
  };

  var toggle_options = function() {
    if ( ! fca_eoi_provider_is_value_changed( $( this ) ) ) {
      return;
    }

    if ( $api_token.val().length > 0 && $account_id.val().length > 0 ) {
      fca_eoi_provider_status_set( 'drip', fca_eoi_provider_status_codes.loading );

      $error.hide();
      $wrappers.hide();

      obtain_data( function( data ) {
        if ( data['error'] ) {
          fca_eoi_provider_status_set( 'drip', fca_eoi_provider_status_codes.error );
          $wrappers.hide();
          $error.text( data['error'] ).show();
        } else {
          fca_eoi_provider_status_set( 'drip', fca_eoi_provider_status_codes.ok );
          $error.hide();
          populate_data( data );
        }
      } );
    } else {
      $wrappers.hide();
    }
  };

  $parent.append( $error );

  $api_token.bind('input', toggle_options);
  $account_id.bind('input', toggle_options);

  $api_token.trigger( 'change' );

  $action.change( function () {
    $option_wrappers.hide();

    var selected_action = $action.val();
    if (selected_action == 'subscribe') {
      $campaign_wrapper.show();
    } else if (selected_action == 'event') {
      $event_wrapper.show();
    } else if (selected_action == 'tag') {
      $tag_wrapper.show();
    }
  }).change()
} );
