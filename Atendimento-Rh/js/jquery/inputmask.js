/**
*	@name						Input Mask
*	@descripton					This jQuery plugin makes labels useful and look more beautiful
*	@version					0.1 (28.01.2011)
*	@requires					jQuery 1.2.6+
*
*	@author						Sevil YILMAZ
*	@author-email				sevil@codersgrave.com
*	@author-website				http://www.codersgrave.com
*
*	@license					CC BY-NC 3.0 - http://creativecommons.org/licenses/by-nc/3.0/
*/
(function($){
	$.fn.inputmask = function() {
		return this.each(function() {
			var label = $(this).find('label');
			var input = $(this).find('input, textarea');
			var valTxt = label.text();
			
			$(input).focus(function() {
				if ($(this).val() > '')
					label.css({opacity: '0'});
				else if ($(this).val() == valTxt)
					$(this).val() == '';
				else
					label.animate({opacity: '0.4'}, 'fast');

			}).blur(function() {
				if ($(this).val() == '') {
					$(this).val() == valTxt;
					label.animate({opacity: '1'}, 'fast');
				}
			});
			
			$(input).keyup(function(){
				if ($(this).val() > '')
					label.css({opacity: '0'});
				else
					label.animate({opacity: '0.4'}, 'fast');
			});
		});
	}
})(jQuery);
