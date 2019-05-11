<?php
/*
 * Template Name: Logy Template
 * Description: Logy Plugin Pages Template.
 */
get_header();
$shortcode = logy_get_page_shortcode( $post->ID );
echo apply_filters( 'the_content', $shortcode );
get_footer();