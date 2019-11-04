<?php

/**
 * The file that defines the custom widget
 *
 * A class definition that extends WP_Widget WordPress class and defines the custom
 * widget definition.
 *
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 */

/**
 * The core class for custom widget.
 *
 * This is used to define behavior of the custom widget and
 * renders HTML for admin and front-end side.
 *
 *
 * @since      1.0.0
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 * @author     Siddharth <siddharthantikekar@gmail.com>
 */
class Wp_Book_Widget extends WP_Widget {

	public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

	/**
	 * Register widget with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		parent::__construct( 'wp-book-widget', esc_html__( 'WP Book Widget', 'wp-book' ), array( 'description' => esc_html__( 'WP Book Widget' ) ) );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param Array $args     Widget arguments.
	 * @param Array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		if ( empty( $instance['category'] ) ) {
			return;
		}

		$category = get_term( $instance['category'] );

		if ( empty( $category ) ) {
			return;
		}

		$books = get_posts( array( 
			'post_type'   => 'wp-book',
			'post_status' => 'publish',
			'tax_query'   => array(
				array(
					'taxonomy' => 'wp-book-category',
					'field'    => 'slug',
					'terms'    => $category->slug
				)
			)
		) );

		if ( empty( $books ) ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . ' (' . esc_html( $category->name ) . ')' . $args['after_title'];
		}

		echo '<div class="textwidget"><ul>';

		foreach ( $books as $book ) {
			echo '<li><a href="' . esc_url( get_permalink( $book->ID ) ) . '">' . esc_html( $book->post_title ) . '</a></li>';
		}

		echo '</ul></div>' . $args['after_widget'];

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param Array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title    = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( '', 'wp-book' );
		$category = ( ! empty( $instance['category'] ) ) ? $instance['category'] : '';

		$terms = get_terms( array( 'taxonomy' => 'wp-book-category', 'hide_empty' => false ) );

		?>
		<p>
			<label for="<?= esc_attr( $this->get_field_id( 'title' ) ) ?>"><?= esc_html__( 'Title', 'wp-book' ) ?></label>
			<input class="widefat" id="<?= esc_attr( $this->get_field_id( 'title' ) ) ?>" name="<?= esc_attr( $this->get_field_name( 'title' ) ) ?>" type="text" value="<?= esc_attr( $title ) ?>">
		</p>
		<p>
			<label for="<?= esc_attr( $this->get_field_id( 'category' ) ) ?>"><?= esc_html__( 'Category', 'wp-book' ) ?></label>
			<select class="widefat" id="<?= esc_attr( $this->get_field_id( 'category' ) ) ?>" name="<?= esc_attr( $this->get_field_name( 'category' ) ) ?>">
				<?php
				foreach ( $terms as $term ) {
					?>
					<option value="<?= esc_attr( $term->term_id ) ?>" <?php selected( $term->term_id, $category ) ?>><?= esc_html( $term->name ) ?></option>
					<?php
				}
				?>
			</select>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param Array $new_instance Values just sent to be saved.
	 * @param Array $old_instance Previously saved values from database.
	 *
	 * @return Array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';

		return $instance;
	}

}
