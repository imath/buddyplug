( function( $ ) {

	var errors = '';

	$( '#buddyplug-form-settings' ).on( 'submit', function(){
		$( '#buddyplug-form-settings :text' ).each( function(){
			if( $( this ).val().length < 1 )
				errors += '- ' + $(this).parents('tr').find('th').first().html() + "\n";
		});

		if( errors.length > 0 ) {
			alert( buddyplug_admin.message + "\n" + errors );
			errors = '';
			return false;
		}
	});

} )( jQuery );