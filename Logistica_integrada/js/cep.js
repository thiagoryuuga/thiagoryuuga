// funcao para retornar as cidades conforme o combo dos estados

$(function(){

	
	$("select[name=estado]").change(function(){

		estado = $(this).val();
		
		if ( estado === '')
			return false;
		
		resetaCombo('cidade');
			
		$.getJSON( path + '/inicio/getCidades/' + estado, function (data){
	
			//	console.log(data);
			var option = new Array();
			//alert(data[0]);
			$.each(data, function(i, obj){
				//alert(obj.ID);
		    	option[i] = document.createElement('option');
		    	$( option[i] ).attr( {value : obj.ID} );
		 		$( option[i] ).append( obj.NOME );
				//alert($(option[i]).val());
		    	$("select[name='cidade']").append( option[i] );
		
			});
	
		});
	
	});

});

function resetaCombo( el ) {
   $("select[name='"+el+"']").empty();
   var option = document.createElement('option');                                  
   $( option ).attr( {value : ''} );
   $( option ).append( 'Escolha' );
   $("select[name='"+el+"']").append( option );
}