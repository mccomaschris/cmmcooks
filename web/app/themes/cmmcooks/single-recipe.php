<?php
/**
 * Default template for individual recipe.
 *
 * @package cmmcooks
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	?>
	<div class="flex flex-wrap lg:-mx-12">
		<div class="w-full lg:w-1/3 lg:px-12"></div>
		<div class="w-full lg:w-2/3 lg:px-12">
			<h1><?php the_title(); ?></h1>
			<div class="">
				<?php
				echo '<div class="flex flex-wrap gap-x-2 gap-y-1">';

				$categories = get_the_terms( get_the_ID(), 'category' );

				foreach ( $categories as $recipe_category ) {
					echo '<a href="' . esc_url( get_term_link( $recipe_category->term_id ) ) . '" class="mb-2 no-underline py-1 px-3 rounded uppercase text-xs font-semibold tracking-wide inline-block cursor-pointer transition-colors duration-100 ease-out bg-green-700 text-white hover:text-white hover:bg-stone-900">' . $recipe_category->name . '</a>';
				}
				echo '</div>';
				?>
			</div>
		</div>
	</div>

	<div class="flex flex-wrap lg:-mx-12">
		<div class="w-full lg:w-1/3 lg:px-12">
			<?php
			if ( get_field( 'recipe_image' ) ) {
				echo '<img src="' . esc_url( get_field( 'recipe_image' ) ) . '" class="rounded shadow-sm w-full" />';
			}
			?>

			<h2>Ingredients</h2>
			<?php
			$ingredients = get_field( 'recipe_ingredients' );
			if ( $ingredients ) {
				echo '<ul class="list-none pl-0">';
				foreach ( $ingredients as $ingredient ) {
					echo '<li class="pl-0">' . esc_attr( $ingredient['amount'] ) . ' ' . esc_attr( $ingredient['measurement'] ) . ' ' . esc_attr( $ingredient['ingredient_description'] ) . '</li>';
				}
				echo '</ul>';
			} else {
				echo '<p>There are no ingredients for this recipe</p>';
			}
			?>
		</div>
		<div class="w-full lg:w-2/3 lg:px-12">
			<?php
			if ( get_field( 'recipe_description' ) ) {
				echo '<div>';
				echo wp_kses_post( get_field( 'recipe_description' ) );
				echo '</div>';
			}
			?>

			<h2>Instructions</h2>
			<?php
			$instructions = get_field( 'recipe_instructions' );
			if ( $instructions ) {
				$step = 0;
				echo '<ul class="list-none pl-0">';
				foreach ( $instructions as $instruction ) {
					$step++;
					echo '<li class="pl-0">';
					echo '<h3>Step ' . esc_attr( $step ) . '</h3>';
					echo '<span>' . esc_attr( $instruction['instruction'] ) . '</span>';
					echo '</li>';
				}
				echo '</ul>';
			} else {
				echo '<p>There are no instructions for this recipe</p>';
			}
			?>
		</div>
	</div>
<?php endwhile; ?>
<?php
get_footer();
