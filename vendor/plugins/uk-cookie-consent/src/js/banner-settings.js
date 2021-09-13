//
// Handle click of the copy button.
//
document.getElementById( 'termly-copy-preference-center-snippet' ).addEventListener( 'click', copy );
function copy() {
	document.getElementById( 'termly-preference-center-snippet' ).select();
	document.execCommand( 'copy' );
}

//
// Select all of the textarea on click.
//
document.getElementById( 'termly-preference-center-snippet' ).addEventListener( 'click', select );
function select() {
	document.getElementById( 'termly-preference-center-snippet' ).select();
}
