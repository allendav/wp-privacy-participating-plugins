<?php
/*
Plugin Name: WP Privacy Participating Plugins
Plugin URI: http://www.allendav.com/
Description: Makes it clearer which plugins are integrating with WordPress core privacy tools
Version: 1.0.0
Author: allendav
Author URI: http://www.allendav.com
License: GPL2
*/

class WP_Privacy_Participating_Plugins {
	private static $instance;

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __clone() {
	}

	private function __wakeup() {
	}

	protected function __construct() {
		add_action( 'admin_footer', array( $this, 'maybe_add_erasers_info' ) );
		add_action( 'admin_footer', array( $this, 'maybe_add_exporters_info' ) );
		add_action( 'admin_footer', array( $this, 'maybe_add_privacy_info' ) );
	}

	function maybe_add_erasers_info() {
		$prompt = __(
'Please note - this tool only erases the personal data stored by WordPress and
participating plugins. It does not delete registered users, nor does it erase
personal data stored by non-participating plugins. It is your responsibility
to delete registered users as well as personal data stored by non-participating
plugins. The following erasers are currently active:'
);

		$erasers = apply_filters( 'wp_privacy_personal_data_erasers', array() );
		?>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					var prompt = <?php echo json_encode( $prompt ); ?>;
					var erasers = <?php echo json_encode( $erasers ); ?>;
					var isRemovePage = $( 'body' ).hasClass( 'tools_page_remove_personal_data' );

					if ( isRemovePage ) {
						$( '.wp-header-end' ).after(
							"<div class='notice notice-info wp-privacy-eraser-notice'></div>"
						);
						$( '.wp-privacy-eraser-notice' ).html( '<p>' + prompt + '</p>' );
						if ( erasers ) {
							$( '.wp-privacy-eraser-notice' ).append( '<ul></ul>' );
								$.map( erasers, function( val ) {
									$( '.wp-privacy-eraser-notice ul' ).append( '<li>' + val.eraser_friendly_name + '</li>' );
								} );
						}
					}
				} );
			</script>
		<?php
	}

	function maybe_add_exporters_info() {
		$prompt = __(
'Please note - this tool only exports the personal data stored by WordPress and
participating plugins. It does not export personal data stored by
non-participating plugins. It is your responsibility to export personal data
stored by non-participating plugins separately. The following exporters are
currently active:'
);

		$exporters = apply_filters( 'wp_privacy_personal_data_exporters', array() );
		?>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					var prompt = <?php echo json_encode( $prompt ); ?>;
					var exporters = <?php echo json_encode( $exporters ); ?>;
					var isExportPage = $( 'body' ).hasClass( 'tools_page_export_personal_data' );

					if ( isExportPage ) {
						$( '.wp-header-end' ).after(
							"<div class='notice notice-info wp-privacy-exporter-notice'></div>"
						);
						$( '.wp-privacy-exporter-notice' ).html( '<p>' + prompt + '</p>' );
						if ( exporters ) {
							$( '.wp-privacy-exporter-notice' ).append( '<ul></ul>' );
								$.map( exporters, function( val ) {
									$( '.wp-privacy-exporter-notice ul' ).append( '<li>' + val.exporter_friendly_name + '</li>' );
								} );
						}
					}
				} );
			</script>
		<?php
	}

	function maybe_add_privacy_info() {
		$prompt = __(
'Please note - this tool only displays privacy policy information provided by
WordPress and participating plugins. It does not include privacy policy
information for non-participating plugins. It is your responsibility to
obtain privacy policy information for non-participating plugins separately.'
);
		?>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					var hasPrivacyDiv = 0 < $( 'div.wp-privacy-policy-guide' ).length;
					var prompt = <?php echo json_encode( $prompt ); ?>;
					if ( hasPrivacyDiv ) {
						$( 'h1' ).after(
							"<div class='notice notice-info wp-privacy-policy-notice'></div>"
						);
						$( '.wp-privacy-policy-notice' ).html( '<p>' + prompt + '</p>' );
					}
				} );
			</script>
		<?php
	}

}


WP_Privacy_Participating_Plugins::getInstance();
