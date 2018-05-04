(function($) {
	var Module = {
		body: $( 'body' )
	};

	Module.init = function() {
		this.addEventListener();
	};

	Module.addEventListener = function() {
		this.body.on( 'focus', '.gfield_customstyle', this._onFocusInput.bind( this ) );
		this.body.on( 'blur', '.gfield_customstyle', this._onBlurInput.bind( this ) );
		this.colorPicker();
	};

	Module.colorPicker = function() {
		if (!$().wpColorPicker) {
			return;
		}

		$( '#tab_gf-custom-style .colorpicker' ).wpColorPicker();
	};


	Module._onFocusInput = function(e) {
		$( e.currentTarget ).addClass( 'gfield_customstyle_focused' );
	};

	Module._onBlurInput = function(e) {
		setTimeout(function() {
			var target = $( e.target );

			$( e.currentTarget ).removeClass( 'gfield_customstyle_focused' );

			if ( ! target.val() ) {
				$( e.currentTarget ).removeClass( 'gfield_filled' );
				return;
			}

			$( e.currentTarget ).addClass( 'gfield_filled' );
		}, 0);
	};

	Module.init();
})(jQuery);
