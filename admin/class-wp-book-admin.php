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

	/**
	 * This function registers wp-book CPT.
	 */
	private function register_post_type() {
		$labels = array(
			'name'                     => _x( 'Books', 'Book general name', 'wp-book' ),
			'singular_name'            => _x( 'Book', 'Book singular name', 'wp-book' ),
			'add_new'                  => _x( 'Add New', 'Add new book', 'wp-book' ),
			'add_new_item'             => __( 'Add New Book', 'wp-book' ),
			'edit_item'                => __( 'Edit Book', 'wp-book' ),
			'new_item'                 => __( 'New Book', 'wp-book' ),
			'view_item'                => __( 'View Book', 'wp-book' ),
			'search_items'             => __( 'Search Books', 'wp-book' ),
			'not_found'                => __( 'No books found', 'wp-book' ),
			'all_items'                => __( 'All Books', 'wp-book' ),
			'archives'                 => __( 'Book Archives', 'wp-book' ),
			'attributes'               => __( 'Book Attributes', 'wp-book' ),
			'insert_into_item'         => __( 'Insert into Book', 'wp-book' ),
			'uploaded_to_this_item'    => __( 'Uploaded to this Book', 'wp-book' ),
			'filter_items_list'        => __( 'Filter Books list', 'wp-book' ),
			'items_list_navigation'    => __( 'Books list navigation', 'wp-book' ),
			'items_list'               => __( 'Books list', 'wp-book' ),
			'item_published'           => __( 'Book published', 'wp-book' ),
			'item_published_privately' => __( 'Book published privately', 'wp-book' ),
			'item_reverted_to_draft'   => __( 'Book reverted to draft', 'wp-book' ),
			'item_updated'             => __( 'Book updated', 'wp-book' )
		);

		$args = array(
			'labels'      => $labels,
			'public'      => true,
			'has_archive' => true,
			'menu_icon'   => 'dashicons-book-alt'
		);

		register_post_type( 'wp-book', $args );
	}

	/**
	 * This function registers wp-book-category and wp-book-tag custom Taxonomies.
	 */
	private function register_taxonomies() {
		$labels = array(
			'name'          => __( 'Book Categories', 'wp-book' ),
			'singular_name' => __( 'Book Category', 'wp-book' )
		);

		$args = array(
			'hierarchical' => true,
			'labels'       => $labels,
			'public'       => true,
		);

		register_taxonomy( 'wp-book-category', array( 'wp-book' ), $args );


		$labels = array(
			'name'          => __( 'Book Tags', 'wp-book' ),
			'singular_name' => __( 'Book Tag', 'wp-book' )
		);

		$args = array(
			'hierarchical' => false,
			'labels'       => $labels,
			'public'       => true,
		);

		register_taxonomy( 'wp-book-tag', array( 'wp-book' ), $args );
	}

	/**
	 * This functions adds custom shortcode.
	 */
	private function add_shortcode() {
		
		/**
		 * This function is a callback for custom shortcode.
		 * It renders HTML instead of shortcode tag on front-end.
		 * 
		 * @param Array  $atts    Attributes of the shortcode.
		 * @param String $content Content between starting and closing shortcode tag.
		 * 
		 * @return String HTML showing content of the shortcode on front-end.
		 */
		function wp_book_shortcode( $atts = array(), $content = null ) {
			$atts = array_change_key_case( ( array ) $atts, CASE_LOWER );

			$atts = shortcode_atts( array(
				'id'          => false,
				'author_name' => false,
				'year'        => false,
				'category'    => false,
				'tag'         => false,
				'publisher'   => false,
			), $atts );

			$book_ids = array();

			if ( ! empty( $atts['id'] ) ) {
				$book_ids[] = $atts['id'];
			}

			global $wpdb;

			if ( ! empty( $atts['author_name'] ) ) {
				$result = $wpdb->get_col( 
					$wpdb->prepare( 'SELECT DISTINCT book_id FROM ' . $wpdb->bookmeta . ' WHERE meta_key="author-name" AND meta_value="%s"', array( $atts['author_name'] ) )
				);

				$book_ids = array_merge( $book_ids, $result );
			}

			if ( ! empty( $atts['year'] ) ) {
				$result = $wpdb->get_col( 
					$wpdb->prepare( 'SELECT DISTINCT book_id FROM ' . $wpdb->bookmeta . ' WHERE meta_key="year" AND meta_value="%s"', array( $atts['year'] ) )
				);

				$book_ids = array_merge( $book_ids, $result );
			}

			if ( ! empty( $atts['publisher'] ) ) {
				$result = $wpdb->get_col( 
					$wpdb->prepare( 'SELECT DISTINCT book_id FROM ' . $wpdb->bookmeta . ' WHERE meta_key="publisher" AND meta_value="%s"', array( $atts['publisher'] ) )
				);

				$book_ids = array_merge( $book_ids, $result );
			}

			$tax_query = array();
			
			if ( ! empty( $atts['category'] ) ) {
				$tax_query[] = array(
					'taxonomy' => 'wp-book-category',
					'field'    => 'name',
					'terms'    => $atts['category']
				);
			}

			if ( ! empty( $atts['tag'] ) ) {
				$tax_query[] = array(
					'taxonomy' => 'wp-book-tag',
					'field'    => 'name',
					'terms'    => $atts['tag']
				);
			}

			if ( count( $tax_query ) > 1 ) {
				$tax_query['relation'] = 'OR';
			}

			$books = array();
			if ( ! empty( $book_ids ) ) {
				$books = get_posts( array( 
					'post__in'    => $book_ids,
					'post_type'   => 'wp-book',
					'post_status' => 'publish',
					'order'       => 'ASC',
					'orderby'     => 'ID',
				) );
			}

			if ( ! empty( $tax_query ) ) {
				$tax_books = get_posts( array( 
					'post__not_in' => $book_ids,
					'post_type'    => 'wp-book',
					'post_status'  => 'publish',
					'order'        => 'ASC',
					'orderby'      => 'ID',
					'tax_query'    => $tax_query,
				) );

				$books = array_merge( $books, $tax_books );
			}

			if ( empty( $books ) ) {
				return $content;
			}

			$content .= '<h2>' . esc_html__( 'WP Books', 'wp-book' ) . '</h2>';

			foreach ( $books as $book ) {
				$author    = get_metadata( 'book', $book->ID, 'author-name', true );
				$price     = get_metadata( 'book', $book->ID, 'price', true );
				$publisher = get_metadata( 'book', $book->ID, 'publisher', true );
				$year      = get_metadata( 'book', $book->ID, 'year', true );
				$edition   = get_metadata( 'book', $book->ID, 'edition', true );
				$url       = get_metadata( 'book', $book->ID, 'url', true );

				$currency = get_option( 'wp-book-currency', 'INR' );

				$content .= '<div class="card">';
				
				$content .= '<div class="card-header">' . esc_html( $book->post_title ) . '</div><div class="card-body"><p class="card-text">' . $book->post_content . '</p></div>';

				$metainfo = '';

				if ( ! empty( $author ) ) {
					$metainfo .= '<li class="list-group-item">' . __( 'Author' ) . ' : ' . esc_html( $author ) . '</li>';
				}

				if ( ! empty( $price ) ) {
					$metainfo .= '<li class="list-group-item">' . __( 'Price' ) . ' : ' . esc_html( $price ) . ' ' . esc_html( $currency ) . '</li>';
				}

				if ( ! empty( $publisher ) ) {
					$metainfo .= '<li class="list-group-item">' . __( 'Publisher' ) . ' : ' . esc_html( $publisher ) . '</li>';
				}

				if ( ! empty( $year ) ) {
					$metainfo .= '<li class="list-group-item">' . __( 'Year' ) . ' : ' . esc_html( $year ) . '</li>';
				}

				if ( ! empty( $edition ) ) {
					$metainfo .= '<li class="list-group-item">' . __( 'Edition' ) . ' : ' . esc_html( $edition ) . '</li>';
				}

				if ( ! empty( $url ) ) {
					$metainfo .= '<li class="list-group-item">' . __( 'URL' ) . ' : ' . esc_html( $url ) . '</li>';
				}

				if ( ! empty( $metainfo ) ) {
					$content .= '<ul class="list-group list-group-flush">' . $metainfo . '</ul>';
				}

				$content .= '</div><br>';
			}

			return $content;
		}

		add_shortcode( 'wp-book', 'wp_book_shortcode' );
	}

	/**
	 * This function is hooked to 'init' action hook.
	 * It calls functions which registers cpt, ct and adds custom shortcode.
	 */
	public function init() {
		$this->register_post_type();
		$this->register_taxonomies();
		$this->add_shortcode();
	}

	/**
	 * This function is hooked to 'add_meta_boxes' action hook.
	 * It adds a metabox for additional book information.
	 */
	public function add_book_metabox() {
		add_meta_box( 'wp-book-metabox', __( 'Book Meta Information', 'wp-book' ), array( $this, 'add_book_metabox_html' ), 'wp-book' );
	}

	/**
	 * This function is a callback for wp-book-metabox.
	 * It renders the HTML code for the metabox.
	 * 
	 * @param Object $post Current post object.
	 */
	public function add_book_metabox_html( $post ) {
		$author    = get_metadata( 'book', $post->ID, 'author-name', true );
		$price     = get_metadata( 'book', $post->ID, 'price', true );
		$publisher = get_metadata( 'book', $post->ID, 'publisher', true );
		$year      = get_metadata( 'book', $post->ID, 'year', true );
		$edition   = get_metadata( 'book', $post->ID, 'edition', true );
		$url       = get_metadata( 'book', $post->ID, 'url', true );

		$currency = get_option( 'wp-book-currency', 'INR' );

		?>
		<table class="form-table">
			<tbody>
				<tr>
					<td>
						<label for="author-name">Author</label>
						<input form="post" type="text" id="author-name" name="author-name" value="<?= esc_attr( $author ) ?>">
					</td>
					<td>
						<label for="price">Price (<?= esc_html( $currency ) ?>)</label>
						<input form="post" type="number" id="price" name="price" value="<?= esc_attr( $price ) ?>">
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
			</tbody>
		</table>
		<?php
	}

	/**
	 * This function is hooked to 'plugins_loaded' action hook.
	 * It registers custom bookmeta table to global $wpdb;
	 */
	public function register_book_metatable() {
		global $wpdb;

		$wpdb->bookmeta = $wpdb->prefix . 'bookmeta';
	}

	/**
	 * This function is hooked to 'save_post_wp-book' action hook.
	 * It saves extra information of wp-book post.
	 * 
	 * @param INT     $post_ID Current post_ID which is being saved.
	 * @param Object  $post    Post object which is being saved.
	 * @param Boolean $update  Whether the post is being updated or not.
	 */
	public function save_metadata( $post_ID, $post, $update ) {
		$author    = filter_input( INPUT_POST, 'author-name', FILTER_SANITIZE_STRING );
		$price     = filter_input( INPUT_POST, 'price', FILTER_SANITIZE_STRING );
		$publisher = filter_input( INPUT_POST, 'publisher', FILTER_SANITIZE_STRING );
		$year      = filter_input( INPUT_POST, 'year', FILTER_SANITIZE_STRING );
		$edition   = filter_input( INPUT_POST, 'edition', FILTER_SANITIZE_STRING );
		$url       = filter_input( INPUT_POST, 'url', FILTER_SANITIZE_STRING );

		if ( ! empty( $author ) ) {
			update_metadata( 'book', $post_ID, 'author-name', $author );
		} else {
			delete_metadata( 'book', $post_ID, 'author-name' );
		}

		if ( ! empty( $price ) ) {
			update_metadata( 'book', $post_ID, 'price', $price );
		} else {
			delete_metadata( 'book', $post_ID, 'price' );
		}

		if ( ! empty( $publisher ) ) {
			update_metadata( 'book', $post_ID, 'publisher', $publisher );
		} else {
			delete_metadata( 'book', $post_ID, 'publisher' );
		}

		if ( ! empty( $year ) ) {
			update_metadata( 'book', $post_ID, 'year', $year );
		} else {
			delete_metadata( 'book', $post_ID, 'year' );
		}

		if ( ! empty( $edition ) ) {
			update_metadata( 'book', $post_ID, 'edition', $edition );
		} else {
			delete_metadata( 'book', $post_ID, 'edition' );
		}

		if ( ! empty( $url ) ) {
			update_metadata( 'book', $post_ID, 'url', $url );
		} else {
			delete_metadata( 'book', $post_ID, 'url' );
		}
	}

	/**
	 * This function is hooked to 'admin_init' action hook.
	 * It registers settings, Its section and fields.
	 */
	public function settings_init() {
		register_setting( 'wp-book', 'wp-book-currency' );
		register_setting( 'wp-book', 'wp-book-books-displayed-per-page' );

		add_settings_section( 'wp-book-settings-section', __( 'WP Book Settings', 'wp-book' ), array( $this, 'settings_section_callback' ), 'wp-book-settings' );

		add_settings_field( 'wp-book-settings-currency', __( 'Currency', 'wp-book' ), array( $this, 'currency_field_callback' ), 'wp-book-settings', 'wp-book-settings-section' );
		add_settings_field( 'wp-book-settings-books-displayed-page', __( 'Number of Books per Page', 'wp-book' ), array( $this, 'number_of_books_field_callback' ), 'wp-book-settings', 'wp-book-settings-section' );
	}

	/**
	 * This function is a callback for settings section added in settings_init() function.
	 */
	public function settings_section_callback() {
		// Description to display for section.
	}

	/**
	 * This function is a callback for currency field added in settings_init() function.
	 * It renders HTML for currency setting.
	 * 
	 * @param Array $args Arguments passed while adding settings field.
	 */
	public function currency_field_callback( $args ) {
		$option = get_option( 'wp-book-currency', 'INR' );

		?>
		<select name="wp-book-currency">
			<option value="USD" <?php selected( 'USD', $option ); ?>><?= esc_html__( 'United States Dollars', 'wp-book' ) ?></option>
			<option value="EUR" <?php selected( 'EUR', $option ); ?>><?= esc_html__( 'Euro', 'wp-book' ) ?></option>
			<option value="GBP" <?php selected( 'GBP', $option ); ?>><?= esc_html__( 'United Kingdom Pounds', 'wp-book' ) ?></option>
			<option value="DZD" <?php selected( 'DZD', $option ); ?>><?= esc_html__( 'Algeria Dinars', 'wp-book' ) ?></option>
			<option value="ARP" <?php selected( 'ARP', $option ); ?>><?= esc_html__( 'Argentina Pesos', 'wp-book' ) ?></option>
			<option value="AUD" <?php selected( 'AUD', $option ); ?>><?= esc_html__( 'Australia Dollars', 'wp-book' ) ?></option>
			<option value="ATS" <?php selected( 'ATS', $option ); ?>><?= esc_html__( 'Austria Schillings', 'wp-book' ) ?></option>
			<option value="BSD" <?php selected( 'BSD', $option ); ?>><?= esc_html__( 'Bahamas Dollars', 'wp-book' ) ?></option>
			<option value="BBD" <?php selected( 'BBD', $option ); ?>><?= esc_html__( 'Barbados Dollars', 'wp-book' ) ?></option>
			<option value="BEF" <?php selected( 'BEF', $option ); ?>><?= esc_html__( 'Belgium Francs', 'wp-book' ) ?></option>
			<option value="BMD" <?php selected( 'BMD', $option ); ?>><?= esc_html__( 'Bermuda Dollars', 'wp-book' ) ?></option>
			<option value="BRR" <?php selected( 'BRR', $option ); ?>><?= esc_html__( 'Brazil Real', 'wp-book' ) ?></option>
			<option value="BGL" <?php selected( 'BGL', $option ); ?>><?= esc_html__( 'Bulgaria Lev', 'wp-book' ) ?></option>
			<option value="CAD" <?php selected( 'CAD', $option ); ?>><?= esc_html__( 'Canada Dollars', 'wp-book' ) ?></option>
			<option value="CLP" <?php selected( 'CLP', $option ); ?>><?= esc_html__( 'Chile Pesos', 'wp-book' ) ?></option>
			<option value="CNY" <?php selected( 'CNY', $option ); ?>><?= esc_html__( 'China Yuan Renmimbi', 'wp-book' ) ?></option>
			<option value="CYP" <?php selected( 'CYP', $option ); ?>><?= esc_html__( 'Cyprus Pounds', 'wp-book' ) ?></option>
			<option value="CSK" <?php selected( 'CSK', $option ); ?>><?= esc_html__( 'Czech Republic Koruna', 'wp-book' ) ?></option>
			<option value="DKK" <?php selected( 'DKK', $option ); ?>><?= esc_html__( 'Denmark Kroner', 'wp-book' ) ?></option>
			<option value="NLG" <?php selected( 'NLG', $option ); ?>><?= esc_html__( 'Dutch Guilders', 'wp-book' ) ?></option>
			<option value="XCD" <?php selected( 'XCD', $option ); ?>><?= esc_html__( 'Eastern Caribbean Dollars', 'wp-book' ) ?></option>
			<option value="EGP" <?php selected( 'EGP', $option ); ?>><?= esc_html__( 'Egypt Pounds', 'wp-book' ) ?></option>
			<option value="FJD" <?php selected( 'FJD', $option ); ?>><?= esc_html__( 'Fiji Dollars', 'wp-book' ) ?></option>
			<option value="FIM" <?php selected( 'FIM', $option ); ?>><?= esc_html__( 'Finland Markka', 'wp-book' ) ?></option>
			<option value="FRF" <?php selected( 'FRF', $option ); ?>><?= esc_html__( 'France Francs', 'wp-book' ) ?></option>
			<option value="DEM" <?php selected( 'DEM', $option ); ?>><?= esc_html__( 'Germany Deutsche Marks', 'wp-book' ) ?></option>
			<option value="XAU" <?php selected( 'XAU', $option ); ?>><?= esc_html__( 'Gold Ounces', 'wp-book' ) ?></option>
			<option value="GRD" <?php selected( 'GRD', $option ); ?>><?= esc_html__( 'Greece Drachmas', 'wp-book' ) ?></option>
			<option value="HKD" <?php selected( 'HKD', $option ); ?>><?= esc_html__( 'Hong Kong Dollars', 'wp-book' ) ?></option>
			<option value="HUF" <?php selected( 'HUF', $option ); ?>><?= esc_html__( 'Hungary Forint', 'wp-book' ) ?></option>
			<option value="ISK" <?php selected( 'ISK', $option ); ?>><?= esc_html__( 'Iceland Krona', 'wp-book' ) ?></option>
			<option value="INR" <?php selected( 'INR', $option ); ?>><?= esc_html__( 'India Rupees', 'wp-book' ) ?></option>
			<option value="IDR" <?php selected( 'IDR', $option ); ?>><?= esc_html__( 'Indonesia Rupiah', 'wp-book' ) ?></option>
			<option value="IEP" <?php selected( 'IEP', $option ); ?>><?= esc_html__( 'Ireland Punt', 'wp-book' ) ?></option>
			<option value="ILS" <?php selected( 'ILS', $option ); ?>><?= esc_html__( 'Israel New Shekels', 'wp-book' ) ?></option>
			<option value="ITL" <?php selected( 'ITL', $option ); ?>><?= esc_html__( 'Italy Lira', 'wp-book' ) ?></option>
			<option value="JMD" <?php selected( 'JMD', $option ); ?>><?= esc_html__( 'Jamaica Dollars', 'wp-book' ) ?></option>
			<option value="JPY" <?php selected( 'JPY', $option ); ?>><?= esc_html__( 'Japan Yen', 'wp-book' ) ?></option>
		</select>
		<?php
	}

	/**
	 * This function is a callback for settings field added settings_init() function.
	 * It renders HTML for number_of_books setting field.
	 */
	public function number_of_books_field_callback() {
		$option = get_option( 'wp-book-books-displayed-per-page', 5 );
		
		?>
		<input type="number" name="wp-book-books-displayed-per-page" value="<?= esc_attr( $option ) ?>">
		<?php
	}

	/**
	 * This function is hooked to 'admin_menu' action hook.
	 * It adds a submenu for settings page under WP Books main menu.
	 */
	public function admin_menu() {
		add_submenu_page( 
			'edit.php?post_type=wp-book', 
			__( 'WP Book Settings', 'wp-book' ),
			__( 'Settings', 'wp-book' ),
			'manage_options',
			'wp-book-settings',
			array( $this, 'settings_page_callback' )
		);
	}

	/**
	 * This function is a callback for settings page.
	 * It renders the HTML for settings page.
	 */
	public function settings_page_callback() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings_updated = filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_STRING );
		if ( ! empty( $settings_updated ) ) {
			add_settings_error( 'wp-book-messages', 'wp-book-message', __( 'Settings Saved', 'wp-book' ), 'updated' );
		}

		settings_errors( 'wp-book-messages' );

		?>
		<div class="wrap">
			<form action="options.php" method="post">
			<?php

			settings_fields( 'wp-book' );

			do_settings_sections( 'wp-book-settings' );

			submit_button( 'Save Settings' );

			?>
			</form>
		</div>
		<?php
	}

	/**
	 * This function is hooked to 'wp_dashboard_setup'action hook.
	 * It adds a dashboard widget.
	 */
	public function wp_dashboard_setup() {
		wp_add_dashboard_widget( 'wp-book-dashboard-widget', esc_html__( 'Top 5 WP Book Categories (Based on Count)', 'wp-book' ), array( $this, 'dashboard_widget_html' ) );
	}

	/**
	 * This function is a callback for custom dashboard widget.
	 * It shows top 5 wp-book-category based on count.
	 */
	public function dashboard_widget_html() {
		$categories = get_terms( array(
			'hide_empty' => false,
			'taxonomy'   => 'wp-book-category',
			'orderby'    => 'count',
			'order'      => 'DESC'
		) );
		
		if ( empty( $categories ) ) {
			echo '<p>' . esc_html__( 'No Categories found.', 'wp-book' ) . '</p>';

			return;
		}

		echo '<ul>';
		foreach ( $categories as $category ) {
			echo '<li>' . esc_html( $category->name ) . ' (' . esc_html( $category->count ) . ')</li>';
		}
		echo '</ul>';
	}

}
