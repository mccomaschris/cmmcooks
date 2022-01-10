<?php
/**
 * Template Name: Homepage
 *
 * The template used for displaying the homepage.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cmmcooks
 */

get_header();
?>
<div class="lg:sticky lg:top-0 bg-stone-100 lg:pt-4 lg:pb-8 lg:border-b border-stone-200">
	<div class="flex items-center justify-center lg:justify-between">
		<h1 class="leading-none mb-0">Recipes</h1>
		<div class="hidden lg:block">
			<label for="nav" class="hidden">Navigation</label>
			<select onchange="document.getElementById(this.value).scrollIntoView();" id="nav" name="nav" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-stone-200 focus:outline-none focus:ring-green-700 focus:border-green-700 sm:text-sm rounded-md">
				<option value="">---</option>
				<option value="recent">Recently Added</option>
				<option value="favorites">Favorites</option>
				<option value="random">Random</option>
			</select>
		</div>
	</div>
</div>

<h2>Categories</h2>
<?php
$tags = get_terms(
	array(
		'taxonomy' => 'category',
	)
);

echo '<div class="flex flex-wrap gap-x-2 gap-y-1">';

foreach ( $tags as $recipe_tag ) {
	echo '<a href="' . esc_url( get_term_link( $recipe_tag->term_id ) ) . '" class="mb-2 no-underline py-1 px-3 rounded uppercase text-xs font-semibold tracking-wide inline-block cursor-pointer transition-colors duration-100 ease-out bg-green-700 text-white hover:text-white hover:bg-stone-900">' . $recipe_tag->name . '</a>';
}
echo '</div>';
?>

<h2 class="scroll-mt-24" id="recent">Recently Added Recipes</h2>
<?php
$recent_query = new WP_Query(
	array(
		'post_type'      => 'recipe',
		'posts_per_page' => 9,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

if ( $recent_query->have_posts() ) {
	echo '<div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-8">';

	while ( $recent_query->have_posts() ) {
		$recent_query->the_post();

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

	}
	echo '</div>';
} else {
	?>
	<p class="lg:px-4">No recipes found.</p>
	<?php
}
wp_reset_postdata();
?>

<h2 class="scroll-mt-24" id="favorites">Favorite Recipes</h2>
<?php

$favorites_query = new WP_Query(
	array(
		'post_type'      => 'recipe',
		'posts_per_page' => 9,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => array( // phpcs:ignore
			array(
				'key'     => 'recipe_favorite',
				'value'   => 1,
				'compare' => '=',
			),
		),
	)
);

if ( $favorites_query->have_posts() ) {
	echo '<div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-8">';

	while ( $favorites_query->have_posts() ) {
		$favorites_query->the_post();

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

	}
	echo '</div>';
} else {
	?>
	<p class="lg:px-4">No recipes found.</p>
	<?php
}
wp_reset_postdata();
?>

<h2 class="scroll-mt-24" id="random">Random Recipes</h2>
<?php
$random_query = new WP_Query(
	array(
		'post_type'      => 'recipe',
		'posts_per_page' => 9,
		'orderby'        => 'rand',
	)
);
if ( $random_query->have_posts() ) {
	echo '<div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-8">';

	while ( $random_query->have_posts() ) {
		$random_query->the_post();

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
	}
	echo '</div>';
} else {
	?>
	<p class="lg:px-4">No recipes found.</p>
	<?php
}
wp_reset_postdata();

get_footer();
