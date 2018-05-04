<?php
/*
Plugin Name: Gravity Forms Custom Style
Plugin URI:
Description: Add Custom Style.
Version: 1.1.0
Author: Alefy Pimentel Ferreira
Author URI: https://github.com/alefypimentel
*/

define( 'GF_CUSTOMSTYLE_VERSION', '0.1.0' );

add_action( 'gform_loaded', array( 'GF_CustomStyle_Bootstrap', 'load' ), 5 );

class GF_CustomStyle_Bootstrap
{
	public static function load()
	{
		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		require_once( 'class-gf-custom-style.php' );
		GFAddOn::register( 'GFCustomStyle' );
	}
}

function gf_simple_addon()
{
	return GFCustomStyle::get_instance();
}
