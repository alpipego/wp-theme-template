<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 05/26/16
 * Time: 10:54 AM
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<h1><?= get_the_title(); ?></h1>
		<div class="single-content">
			<?php the_content(); ?>
		</div>
		<?php
	endwhile;
endif;


get_footer();
