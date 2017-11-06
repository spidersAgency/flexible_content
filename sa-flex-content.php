<?php
/**
 * Plugin Name:     Flexible Content
 * Plugin URI:      https://github.com/spidersAgency/flexible-content
 * Description:     Flexible Content Framework
 * Author:          Maciej Palmowski
 * Author URI:      https://spiders.agency
 * Text Domain:     flexible-content
 * Version:         1.0
 *
 * @package         flexible-content
 */

class FlexibleContent {
	function __construct( $id ) {
		//id wpisu
        $this->id = $id;
        $this->post_type = apply_filters( 'sa_flex_content_post_type', ['post'] );
        $this->custom_filters = apply_filters( 'sa_flex_content_custom_filters', true );
        $this->field_name = apply_filters( 'sa_flex_content_field_name', 'flexible_content' );
        $this->template_file = apply_filters( 'sa_flex_content_template_file', get_template_directory(). '/flex_content.php' );
	}

	/**
	 * generowanie treści na bazie flexible content
	 */
	function get_content() {
		if ( have_rows( $this->field_name, $this->id ) && file_exists( $this->template_file ) ) {
			// loop through the rows of data
			while ( have_rows( $this->field_name, $this->id ) ) {
				the_row();
				include ( $this->template_file );
			}
		}
	}
	/**
	 * zapisanie treści jako zmienna
	 * return string	kod html
	 */
	function convert_to_var() {
		ob_start();
		self::get_content();
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * podmiana the_content podczas zapisu
	 */
	function content_on_save() {
		if ( have_rows( $this->field_name, $this->id ) ) {
			if ( ! wp_is_post_revision( $this->id ) ) {
				// unhook this function so it doesn't loop infinitely
				remove_action( 'acf/save_post', [ $this, 'content_on_save' ], 99 );
			}

			if ( in_array( get_post_type( $this->id ), $this->post_type ) ) {
				$content = self::convert_to_var();
				$my_post['ID'] = $this->id;
				$my_post['post_content'] = $content;

				$test = wp_update_post( $my_post );
			}
		}
	}
}

function sa_flex_content_run() {
    if ( isset( $_POST['post_ID'] ) ) {        
        $content = new FlexibleContent( $_POST['post_ID'] );
        //przy każdym zapisie wpisu odpalamy funkcję
        add_action( 'acf/save_post', [ $content, 'content_on_save' ], 99 );
    }
}

add_action( 'admin_init', 'sa_flex_content_run' );