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
		$('.copyShortcode').click(function(){
			var currBtnID = $(this).attr('id');
			var currID = currBtnID[currBtnID.length -1];

			var shortcode = $('#sample_shortcode'+currID).text();
			console.log(shortcode);
			navigator.clipboard.writeText(shortcode).then(
				function(){
					$('#copyMsg'+currID).html('');
					$('#copyShortcode'+currID).html('Copied !'); 
					setTimeout(function(){
						$('#copyShortcode'+currID).html('Copy Shortcode'); 
					}, 3000);
				},
				function() {
					$('#copyMsg'+currID).html('Unable to copy, try again ...');
				}
			);
		});

		/************* (END)COPY SAMPLE SHORTCODE **************/

	}); // doc ready

})( jQuery );
