<?php
/**
 * The template for displaying recipe lists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CMMCooks
 */

get_header();

if ( have_posts() ) :
	?>
	<h1>All Recipes</h1>
	<?php

	echo '<div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-8">';
	/* Start the Loop */
	while ( have_posts() ) :
		the_post();
		echo '<div class="h-full border border-stone-300 rounded flex flex-col bg-stone-50 shadow-sm transition-all duration-200 ease-out hover:shadow-lg">';

		if ( get_field( 'recipe_image' ) ) {
			echo '<div class="h-48 bg-cover bg-center" style="background: url(' . esc_url( get_field( 'recipe_image' ) ) . ')"></div>';
		} else {
			echo '<div class="bg-stone-700 h-48 flex items-center justify-center font-semibold text-stone-200">No image available.</div>';
		}

		echo '<div class="flex flex-col px-6 py-6">';
		echo '<a href="' . esc_url( get_the_permalink() ) . '" class="text-xl hover:text-green-700 hover:no-underline transition-all duration-100 ease-out">' . esc_attr( get_the_title() ) . '</a>';
		echo '<div class="my-2 flex-1">';
		echo wp_kses_post( get_field( 'recipe_description' ) );
		echo '</div>';
		echo '</div>';
		echo '</div>';
	endwhile;
	echo '</div>';
	the_posts_navigation();
else:
	echo '<p>No recipes found.</p>';
endif;

get_footer();
