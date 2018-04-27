<?php
function sa_options() {
	add_options_page( 'Flexible Content', 'Flexible Content', 'manage_options', 'flex-content', 'sa_options_page' );
}

function sa_options_page() {
	?>
	<div class="wrap">
	<h1>Flexible Content</h1>
	<p><?php __( 'By przegenerować wszystkie wpisy naciśnij poniższy guzik', 'sa_flex' ); ?></p>
	<form method="post" action="options.php"> 
		<?php settings_fields( 'sa_settings' ); ?>
		<?php submit_button(); ?>
	</form>
	<?php
}

function sa_settings() {
    register_setting( 'sa_settings', 'sa_settings', 'sa_rebuild_posts' );
}

function sa_rebuild_posts() {
    $post_types = apply_filters( 'sa_flex_content_post_type', ['post'] );
    $field_name = apply_filters( 'sa_flex_content_field_name', 'flexible_content' );

    $args = [
        'post_type' => $post_types,
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key'     => $field_name,
                'compare' => 'EXISTS',
            ],
        ],
    ];

    $query = get_posts( $args );
    
    foreach( $query as $post ) {
        setup_postdata( $post );
        $content = new FlexibleContent( $post->ID );
        $content->content_on_save();
    }
}

add_action( 'admin_menu', 'sa_options' );
add_action( 'admin_init', 'sa_settings' );