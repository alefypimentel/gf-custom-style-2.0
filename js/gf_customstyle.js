(function($) {
	var Module = {
		body: $( 'body' )
	};

	Module.init = function() {
		this.addEventListener();
	};

	Module.addEventListener = function() {
		this.colorPicker();
	};

 	Module.colorPicker = function() {
		if (!$().wpColorPicker) {
			return;
		}
 		$( '#tab_gf-custom-style .colorpicker' ).wpColorPicker();
	};

	console.log('Start Custom Style');

 	Module.init();
})(jQuery);
