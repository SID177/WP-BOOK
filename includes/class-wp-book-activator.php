<?php
/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 * @author     Siddharth <siddharthantikekar@gmail.com>
 */
class Wp_Book_Activator {

	/**
	 * This function creates new custom table for book meta information.
	 * It also adds default values for setting options.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'bookmeta';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		meta_id bigint(20) NOT NULL AUTO_INCREMENT,
		book_id bigint(20) NOT NULL,
		meta_key varchar(255) DEFAULT NULL,
		meta_value longtext DEFAULT '',
		PRIMARY KEY  (meta_id),
		KEY book_id (book_id),
		KEY meta_key (meta_key)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		update_option( 'wp-book-currency', 'INR' );
		update_option( 'wp-book-books-displayed-per-page', '5' );
	}

}
