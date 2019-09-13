<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 * @author     Siddharth <siddharthantikekar@gmail.com>
 */
class Wp_Book_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-book-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-book-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function register_book_cpt_taxonomies() {
		$labels = array(
			'name' => _x( 'Books', 'Book general name', 'wp-book' ),
			'singular_name' => _x( 'Book', 'Book singular name', 'wp-book' ),
			'add_new' => _x( 'Add New', 'Add new book', 'wp-book' ),
			'add_new_item' => __( 'Add New Book', 'wp-book' ),
			'edit_item' => __( 'Edit Book', 'wp-book' ),
			'new_item' => __( 'New Book', 'wp-book' ),
			'view_item' => __( 'View Book', 'wp-book' ),
			'search_items' => __( 'Search Books', 'wp-book' ),
			'not_found' => __( 'No books found', 'wp-book' ),
			'all_items' => __( 'All Books', 'wp-book' ),
			'archives' => __( 'Book Archives', 'wp-book' ),
			'attributes' => __( 'Book Attributes', 'wp-book' ),
			'insert_into_item' => __( 'Insert into Book', 'wp-book' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Book', 'wp-book' ),
			'filter_items_list' => __( 'Filter Books list', 'wp-book' ),
			'items_list_navigation' => __( 'Books list navigation', 'wp-book' ),
			'items_list' => __( 'Books list', 'wp-book' ),
			'item_published' => __( 'Book published', 'wp-book' ),
			'item_published_privately' => __( 'Book published privately', 'wp-book' ),
			'item_reverted_to_draft' => __( 'Book reverted to draft', 'wp-book' ),
			'item_updated' => __( 'Book updated', 'wp-book' )
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-book-alt'
		);

		register_post_type( 'wp-book', $args );

		$labels = array(
			'name' => __( 'Book Categories', 'wp-book' ),
			'singular_name' => __( 'Book Category', 'wp-book' )
		);

		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'public' => true,
		);

		register_taxonomy( 'wp-book-category', array( 'wp-book' ), $args );


		$labels = array(
			'name' => __( 'Book Tags', 'wp-book' ),
			'singular_name' => __( 'Book Tag', 'wp-book' )
		);

		$args = array(
			'hierarchical' => false,
			'labels' => $labels,
			'public' => true,
		);

		register_taxonomy( 'wp-book-tag', array( 'wp-book' ), $args );
	}

	public function add_book_metabox() {
		add_meta_box( 'wp-book-metabox', __( 'Book Meta Information', 'wp-book' ), array( $this, 'add_book_metabox_html' ), 'wp-book' );
	}

	public function add_book_metabox_html( $post ) {
		$author = get_metadata( 'book', $post->ID, 'author-name', true );
		$price = get_metadata( 'book', $post->ID, 'price', true );
		$publisher = get_metadata( 'book', $post->ID, 'publisher', true );
		$year = get_metadata( 'book', $post->ID, 'year', true );
		$edition = get_metadata( 'book', $post->ID, 'edition', true );
		$url = get_metadata( 'book', $post->ID, 'url', true );
		?>
		<table border=1>
			<tr>
				<td>
					<label for="author-name">Author Name</label>
					<input form="post" type="text" id="author-name" name="author-name" value="<?= esc_attr( $author ) ?>">
				</td>
				<td>
					<label for="price">Price</label>
					<input form="post" type="text" id="price" name="price" value="<?= esc_attr( $price ) ?>">
				</td>
				<td>
					<label for="publisher">Publisher</label>
					<input form="post" type="text" id="publisher" name="publisher" value="<?= esc_attr( $publisher ) ?>">
				</td>
			</tr>
			<tr>
				<td>
					<label for="year">Year</label>
					<input form="post" type="text" id="year" name="year" value="<?= esc_attr( $year ) ?>">
				</td>
				<td>
					<label for="edition">Edition</label>
					<input form="post" type="text" id="edition" name="edition" value="<?= esc_attr( $edition ) ?>">
				</td>
				<td>
					<label for="url">URL</label>
					<input form="post" type="text" id="url" name="url" value="<?= esc_attr( $url ) ?>">
				</td>
			</tr>
		</table>
		<?php
	}

	public function register_book_metatable() {
		global $wpdb;

		$wpdb->bookmeta = $wpdb->prefix . 'bookmeta';
	}

	public function save_metadata( $post_ID, $post, $update ) {
		$author = filter_input( INPUT_POST, 'author-name', FILTER_SANITIZE_STRING );
		$price = filter_input( INPUT_POST, 'price', FILTER_SANITIZE_STRING );
		$publisher = filter_input( INPUT_POST, 'publisher', FILTER_SANITIZE_STRING );
		$year = filter_input( INPUT_POST, 'year', FILTER_SANITIZE_STRING );
		$edition = filter_input( INPUT_POST, 'edition', FILTER_SANITIZE_STRING );
		$url = filter_input( INPUT_POST, 'url', FILTER_SANITIZE_STRING );

		update_metadata( 'book', $post_ID, 'author-name', $author );
		update_metadata( 'book', $post_ID, 'price', $price );
		update_metadata( 'book', $post_ID, 'publisher', $publisher );
		update_metadata( 'book', $post_ID, 'year', $year );
		update_metadata( 'book', $post_ID, 'edition', $edition );
		update_metadata( 'book', $post_ID, 'url', $url );
	}

}
