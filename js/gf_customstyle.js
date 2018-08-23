(function($) {
	var Module = {
		body: $( 'body' )
	};

	Module.init = function() {
		this.addEventListener();
	};

	Module.addEventListener = function() {
		console.log('Start Custom Style');
	};

	Module.init();
})(jQuery);
