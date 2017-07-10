<?php
/**
 * The template for displaying the front page.
 * Template Name: API Template
 */

get_header(); ?>

<div class="row">

	<div id="primary" class="content-area">

		<div class="large-8 columns">

			<main id="main" class="site-main" role="main">

        <?php if(is_user_logged_in()) : ?>
  				<?php while ( have_posts() ) : the_post(); ?>

  					<?php get_template_part( 'template-parts/content', 'page-front' ); ?>

  					<?php
  						// If comments are open or we have at least one comment, load up the comment template
  						if ( comments_open() || get_comments_number() ) :
  							comments_template();
  						endif;
  					?>

  				<?php endwhile; // end of the loop. ?>
        <?php else: ?>

          <?php
            // Get Front Logged Out Page
            $my_query = new WP_Query( 'page_id=3851' );
            while ($my_query->have_posts()) : $my_query->the_post();
              $do_not_duplicate = $post->ID;
              get_template_part('template-parts/content', 'page-front');
            endwhile;
          ?>
        <?php endif ?>

			</main><!-- #main -->

		</div><!-- .large-8 -->

	</div><!-- #primary -->

	<div class="large-3 large-offset-1 columns">
		<?php get_sidebar(); ?>
	</div><!-- .large-3 -->

</div><!-- .row -->

<?php get_footer(); ?>
