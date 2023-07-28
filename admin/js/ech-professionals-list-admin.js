(function( $ ) {
	'use strict';
	$(function(){


		/************* GENERAL FORM **************/
		$('#ech_pl_settings_form').on('submit', function(e){
			e.preventDefault();
			$('.statusMsg').removeClass('error');
			$('.statusMsg').removeClass('updated');

			var statusMsg = '';
			
			$('#ech_pl_settings_form').attr('action', 'options.php');
			$('#ech_pl_settings_form')[0].submit();
			// output success msg
			statusMsg += 'Settings updated <br>';
			$('.statusMsg').html(statusMsg);
			$('.statusMsg').addClass('updated');
			
		});
		/************* (END) GENERAL FORM **************/





		/************* COPY SAMPLE SHORTCODE **************/
		$('#copyShortcode').click(function(){

			var shortcode = $('#sample_shortcode').text();

			navigator.clipboard.writeText(shortcode).then(
				function(){
					$('#copyMsg').html('');
					$('#copyShortcode').html('Copied !'); 
					setTimeout(function(){
						$('#copyShortcode').html('Copy Shortcode'); 
					}, 3000);
				},
				function() {
					$('#copyMsg').html('Unable to copy, try again ...');
				}
			);
		});
		/************* (END)COPY SAMPLE SHORTCODE **************/

	}); // doc ready

})( jQuery );
