/*
 * 
 */

function showContentTable( param ) {
	$( '.content-table' ).load( param );	
}

function selectUrkundeResult(num, lid) {
//	//var target = this;
//	var jqxhr = $.get( "setNumOfResults.php");
//	jqxhr.success(function( data ) {
//		var prefix = "";
//		if(num != 'ALL') { prefix = '&nbsp;&nbsp;&nbsp;&nbsp;'; }
//		$( '#num-of-results-' + lid ).html( num + prefix + '<span class="caret"></span>');
//	});
}

function selectVeranstaltung( id ) {
	var jqxhr = $.get( "ajaxRequest.php?func=selectVeranstaltung&id=" + id );
	jqxhr.success(function( data ) {
		$( '[class^=veranstaltung]' ).removeClass( 'bold' );
		$( '.veranstaltung-' + id ).addClass( 'bold' );
		$( '#page-header' ).html( data );
	});
}

function lockRace( id ) {
	var jqxhr = $.get( "ajaxRequest.php?func=lockRace&lid=" + id);
	jqxhr.success(function( data ) {
		console.log(data);
		if( data == 1 ) {
			$( '#lock-' + id ).removeClass( 'fa-unlock' );
			$( '#lock-' + id ).addClass( 'fa-lock' );
		} else {
			$( '#lock-' + id ).removeClass( 'fa-lock' );
			$( '#lock-' + id ).addClass( 'fa-unlock' );
		}
	});
}

function submitForm(form, redirect) {

	$.ajax({
		type: "POST",
		url: "ajaxRequest.php",
		data: $( form ).serialize(),
		success: function(msg) {
			if(msg != 'ok') {
				redirect = false;
				$('.alert').html(msg);
				$('.alert').removeClass('hidden');
			}
			if(redirect) { window.location.href = redirect; }
		}
	})

}


function addKlasseZeile( id ) {
	var jqxhr = $.get( 'ajaxRequest.php?func=addKlasse&id=' + id );
	jqxhr.success(function( data ) {
		if(data == 'ok') {
		window.location.href = 'index.php?func=klasse&id=' + id;
		}
	});
}

function deleteKlasse( id, kid ) {
	var jqxhr = $.get( 'ajaxRequest.php?func=deleteKlasse&id=' + id );
	jqxhr.success(function( data ) {
		if(data == 'ok') {
			window.location.href = 'index.php?func=klasse&id=' + kid;
		}
	});
}

function deleteFullKlasse( id ) {
	var jqxhr = $.get( 'ajaxRequest.php?func=deleteFullKlasse&id=' + id );
	jqxhr.success(function( data ) {
		if(data == 'ok') {
			window.location.href = 'index.php?func=klasse';
		} else {
			$('.alert').html(data);
			$('.alert').removeClass('hidden');
		}
	});
}
