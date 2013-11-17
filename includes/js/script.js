( function( $ ) {

	var content = '';

	$.each( buddyplug_vars, function( index, value ){
		is = value ? 'yes' : 'no';
		content += '<li>' + index + ' : <strong>' + is + '</strong></li>';

	});

	if( content.length > 1 ) {
		content = '<ul>' + content + '</ul>';
		$( '#buddyplug-content' ).html( content );
	}

} )( jQuery );