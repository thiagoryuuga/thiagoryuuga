$(document).ready(function(){
      $("#carregando").hide();
         $("li.menu a").click(function(){
           		pagina = $("li.menu a").attr('href');
			   $("#carregando").ajaxStart(function(){
				   $("#carregando").show();
				   $("#pagina").hide();
				});
				$("#carregando").ajaxStop(function(){
				   $("#carregando").hide();
				   $("#pagina").show();
				});
				$("#pagina").load(pagina);
				return false;	
            
         })	;
         $("#main_menu ul a").click(function(){
				pagina = $(this).attr('href');
			   $("#carregando").ajaxStart(function(){
				   $("#carregando").show();
				   $("#pagina").hide();
				});
				$("#carregando").ajaxStop(function(){
				   $("#carregando").hide();
				   $("#pagina").show();
				});
				$("#pagina").html("");
				return false;	
            
         })	;
});

