$(function(){
      $("#carregando").hide();
         $("ul#menu a").click(function(){
            pagina = $(this).attr('href')
           $("#carregando").ajaxStart(function(){
               $(this).show()
               })
            $("#carregando").ajaxStop(function(){
               $(this).hide();
               
            })
            
			 $("#conteudo").attr('src', pagina);
            return false;	
            
         })
		 
         $("#link a").click(function(){
            pagina = $(this).attr('href')
           $("#carregando").ajaxStart(function(){
               $(this).show()
               })
            $("#carregando").ajaxStop(function(){
               $(this).hide();
               
            })
            
			 $("#conteudo").attr('src', pagina);
            return false;	
            
         })
		
		  $("#dt a").click(function(){
            pagina = $(this).attr('href')
           
            $("#carregando").ajaxStart(function(){
               $(this).show()
               })
            $("#carregando").ajaxStop(function(){
               $(this).hide();
               
            })
            
            $("#conteudo").attr('src', pagina);
            return false;
         })
		 
})

