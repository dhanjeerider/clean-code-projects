<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( is_multisite() ) {
	return;
}

$ccp_uploads = wp_upload_dir();
if ( ! empty( $ccp_uploads['basedir'] ) ) {
	$ccp_dir = trailingslashit( $ccp_uploads['basedir'] ) . 'clean-code-projects';
	foreach ( glob( $ccp_dir . '/*.html' ) ?: array() as $ccp_file ) {
		unlink( $ccp_file );
	}
	if ( is_dir( $ccp_dir ) ) {
		rmdir( $ccp_dir );
	}
}

delete_option( 'ccp_options' );
delete_option( 'ccp_projects_page_id' );
delete_transient( 'ccp_activation_notice' );

global $wpdb;
$wpdb->query(
	$wpdb->prepare(
		"DELETE m FROM {$wpdb->postmeta} AS m INNER JOIN {$wpdb->posts} AS p ON p.ID = m.post_id WHERE p.post_type = %s AND m.meta_key IN ( '_ccp_link', '_ccp_code', '_ccp_mode', '_ccp_file' )",
		'ccp_project'
	)
);
