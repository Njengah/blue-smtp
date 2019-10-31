(function( $ ) {
	'use strict';
	
	$(document).ready(function ($) {


	// Password show/hide on hover
	
	$("#password-icon").hover(
		  function functionName() {
			//Change the attribute to text
			$("#passinput").attr("type", "text");
			//Change the icon of the password 
			$(".dashicons")
		  .removeClass("dashicons-hidden")
		  .addClass("dashicons-visibility");
			
			
		  },
		  function() {
			//Change the attribute back to password
			$("#passinput").attr("type", "password");
			$(".dashicons")
			  .removeClass("dashicons-visibility")
			  .addClass("dashicons-hidden");			
		  }
		);
		
   });
   
 	   
   
	
})( jQuery );
