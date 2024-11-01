<?php
/*
*/


if ( ! function_exists( 'vloopsde_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function vloopsde_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/VloopsDiviExtension.php';
}
add_action( 'divi_extensions_init', 'vloopsde_initialize_extension' );
endif;
